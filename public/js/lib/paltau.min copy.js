(function ($) {
    "use strict";

    function invoke($root, api, args) {
        if (!args[0] || typeof args[0] === "object")
            return api.init.apply($root, args);
        if (api[args[0]])
            return api[args[0]].apply(
                $root,
                Array.prototype.slice.call(args, 1)
            );
        throw makeErr(args[0] + " is not a method or property");
    }

    function cssBox(top, left, z, overflow) {
        return {
            css: {
                position: "absolute",
                top: top,
                left: left,
                overflow: overflow || "hidden",
                zIndex: z || "auto",
            },
        };
    }

    function cubicBezierPoint(a, b, c, d, t) {
        var u = 1 - t,
            u2 = u * u,
            u3 = u2 * u,
            t2 = t * t,
            t3 = t2 * t;
        return pt(
            Math.round(
                u3 * a.x + 3 * t * u2 * b.x + 3 * t2 * u * c.x + t3 * d.x
            ),
            Math.round(
                u3 * a.y + 3 * t * u2 * b.y + 3 * t2 * u * c.y + t3 * d.y
            )
        );
    }

    function pt(x, y) {
        return { x: x, y: y };
    }

    function translate(x, y, accel) {
        return has3d && accel
            ? " translate3d(" + x + "px," + y + "px,0px) "
            : " translate(" + x + "px," + y + "px) ";
    }

    function rotate(deg) {
        return " rotate(" + deg + "deg) ";
    }

    function has(key, obj) {
        return Object.prototype.hasOwnProperty.call(obj, key);
    }

    function cssPrefix() {
        var vendors = ["Moz", "Webkit", "Khtml", "O", "ms"],
            i = vendors.length,
            pref = "";
        while (i--)
            if (vendors[i] + "Transform" in document.body.style)
                pref = "-" + vendors[i].toLowerCase() + "-";
        return pref;
    }

    // draws linear gradient on an element (webkit old/new + standards)
    function applyLinearGradient($el, start, end, stops, stopCount) {
        var i,
            parts = [];
        if (cssPref === "-webkit-") {
            for (i = 0; i < stopCount; i++)
                parts.push(
                    "color-stop(" + stops[i][0] + ", " + stops[i][1] + ")"
                );
            $el.css({
                "background-image":
                    "-webkit-gradient(linear, " +
                    start.x +
                    "% " +
                    start.y +
                    "%," +
                    end.x +
                    "% " +
                    end.y +
                    "%, " +
                    parts.join(",") +
                    " )",
            });
        } else {
            // compute physical start/end on element box, then convert stops -> percentages along projected length
            var s = {
                x: (start.x / 100) * $el.width(),
                y: (start.y / 100) * $el.height(),
            };
            var e = {
                x: (end.x / 100) * $el.width(),
                y: (end.y / 100) * $el.height(),
            };
            var dx = e.x - s.x,
                dy = e.y - s.y,
                theta = Math.atan2(dy, dx),
                wtheta = theta - Math.PI / 2;
            var projLen =
                Math.abs($el.width() * Math.sin(wtheta)) +
                Math.abs($el.height() * Math.cos(wtheta));
            var segLen = Math.sqrt(dx * dx + dy * dy);
            var corner = pt(
                e.x < s.x ? $el.width() : 0,
                e.y < s.y ? $el.height() : 0
            );
            var k = Math.tan(theta),
                kh = -1 / k;
            var ix = (kh * corner.x - corner.y - k * s.x + s.y) / (kh - k),
                iy = kh * ix - kh * corner.x + corner.y;
            var startOffset = Math.sqrt(
                Math.pow(ix - s.x, 2) + Math.pow(iy - s.y, 2)
            );

            for (i = 0; i < stopCount; i++)
                parts.push(
                    " " +
                        stops[i][1] +
                        " " +
                        (100 * (startOffset + segLen * stops[i][0])) / projLen +
                        "%"
                );
            $el.css({
                "background-image":
                    cssPref +
                    "linear-gradient(" +
                    -theta +
                    "rad," +
                    parts.join(",") +
                    ")",
            });
        }
    }

    function triggerEvent(name, $el, data) {
        var ev = $.Event(name);
        $el.trigger(ev, data);
        return ev.isDefaultPrevented()
            ? "prevented"
            : ev.isPropagationStopped()
            ? "stopped"
            : "";
    }

    function makeErr(msg) {
        function E(m) {
            this.name = "motionJsError";
            this.message = m;
        }
        E.prototype = Error();
        E.prototype.constructor = E;
        return new E(msg);
    }

    function findPos(el) {
        var o = { top: 0, left: 0 };
        do {
            o.left += el.offsetLeft;
            o.top += el.offsetTop;
        } while ((el = el.offsetParent));
        return o;
    }

    // ---------- env caps ----------
    var has3d,
        goodWebKit,
        cssPref = "",
        PI = Math.PI,
        HALF_PI = PI / 2;
    var touch = "ontouchstart" in window;
    var mouse = touch
        ? {
              down: "touchstart",
              move: "touchmove",
              up: "touchend",
              over: "touchstart",
              out: "touchend",
          }
        : {
              down: "mousedown",
              move: "mousemove",
              up: "mouseup",
              over: "mouseover",
              out: "mouseout",
          };

    var cornersMap = {
        backward: ["bl", "tl"],
        forward: ["br", "tr"],
        all: "tl bl tr br l r".split(" "),
    };
    var displays = ["single", "double"];
    var dirs = ["ltr", "rtl"];
    var defaults = {
        acceleration: true,
        display: "double",
        duration: 600,
        page: 1,
        gradients: true,
        turnCorners: "bl,br",
        when: null,
    };
    var motionDefaults = { cornerSize: 100 };

    // ---------- Paltau core (book-level) ----------
    var book = {
        init: function (opts) {
            has3d =
                "WebKitCSSMatrix" in window ||
                "MozPerspective" in document.body.style;
            var m,
                webkitMatch = /AppleWebkit\/([0-9\.]+)/i.exec(
                    navigator.userAgent
                );
            goodWebKit = webkitMatch
                ? parseFloat(webkitMatch[1]) > 534.3
                : true;
            cssPref = cssPrefix();

            var data = this.data();
            var $kids = this.children();
            opts = $.extend(
                {
                    width: this.width(),
                    height: this.height(),
                    direction:
                        this.attr("dir") || this.css("direction") || "ltr",
                },
                defaults,
                opts
            );

            data.opts = opts;
            data.pageObjs = {};
            data.pages = {};
            data.pageWrap = {};
            data.pageZoom = {};
            data.pagePlace = {};
            data.pageMv = [];
            data.zoom = 1;
            data.totalPages = opts.pages || 0;
            data.eventHandlers = {
                touchStart: $.proxy(book._touchStart, this),
                touchMove: $.proxy(book._touchMove, this),
                touchEnd: $.proxy(book._touchEnd, this),
                start: $.proxy(book._eventStart, this),
            };

            if (opts.when)
                for (m in opts.when)
                    if (has(m, opts.when)) this.bind(m, opts.when[m]);

            this.css({
                position: "relative",
                width: opts.width,
                height: opts.height,
            });
            this.paltau("display", opts.display);
            if (opts.direction) this.paltau("direction", opts.direction);
            if (has3d && !touch && opts.acceleration)
                this.transform(translate(0, 0, true));

            // add child pages (skip with attribute ignore="1")
            var pageCount = 0;
            for (m = 0; m < $kids.length; m++)
                if ($($kids[m]).attr("ignore") !== "1")
                    this.paltau("addPage", $kids[m], ++pageCount);

            $(this)
                .bind(mouse.down, data.eventHandlers.touchStart)
                .bind("end", book._eventEnd)
                .bind("pressed", book._eventPressed)
                .bind("released", book._eventReleased)
                .bind("motion", book._motion);

            $(this).parent().bind("start", data.eventHandlers.start);
            $(document)
                .bind(mouse.move, data.eventHandlers.touchMove)
                .bind(mouse.up, data.eventHandlers.touchEnd);

            this.paltau("page", opts.page);
            
            data.done = true;

            
            return this;
        },

        addPage: function (el, pageNo) {
            var data = this.data(),
                totalPlusOne = data.totalPages + 1,
                inferred;
            if (data.destroying) return false;

            if ((inferred = /\bp([0-9]+)\b/.exec($(el).attr("class"))))
                pageNo = parseInt(inferred[1], 10);
            var atEnd = false;
            if (pageNo) {
                if (pageNo === totalPlusOne) atEnd = true;
                else if (pageNo > totalPlusOne)
                    throw makeErr('Page "' + pageNo + '" cannot be inserted');
            } else {
                pageNo = totalPlusOne;
                atEnd = true;
            }

            if (pageNo >= 1 && pageNo <= totalPlusOne) {
                var parityClass =
                    data.display === "double"
                        ? pageNo % 2
                            ? " odd"
                            : " even"
                        : "";
                if (data.done) this.paltau("stop");
                if (pageNo in data.pageObjs)
                    book._movePages.call(this, pageNo, 1);
                if (atEnd) data.totalPages = totalPlusOne;

                data.pageObjs[pageNo] = $(el)
                    .css({ float: "left" })
                    .addClass("page p" + pageNo + parityClass);

                // IE9 quirk: hard class
                if (
                    navigator.userAgent.indexOf("MSIE 9.0") !== -1 &&
                    data.pageObjs[pageNo].hasClass("hard")
                )
                    data.pageObjs[pageNo].removeClass("hard");

                book._addPage.call(this, pageNo);
                book._removeFromDOM.call(this);
            }
            return this;
        },

        _addPage: function (n) {
            var data = this.data(),
                $page = data.pageObjs[n];
            if (!$page) return;

            if (book._necessPage.call(this, n)) {
                if (!data.pageWrap[n]) {
                    data.pageWrap[n] = $("<div/>", {
                        class: "page-wrapper",
                        page: n,
                        css: { position: "absolute", overflow: "hidden" },
                    });
                    this.append(data.pageWrap[n]);
                    if (!data.pagePlace[n]) {
                        data.pagePlace[n] = n;
                        data.pageObjs[n].appendTo(data.pageWrap[n]);
                    }

                    var box = book._pageSize.call(this, n, true);
                    $page.css({ width: box.width, height: box.height });
                    data.pageWrap[n].css(box);
                }
                if (data.pagePlace[n] == n) book._makemotion.call(this, n);
            } else {
                data.pagePlace[n] = 0;
                if (data.pageObjs[n]) data.pageObjs[n].remove();
            }
        },

        hasPage: function (n) {
            return has(n, this.data().pageObjs);
        },

        center: function (toPage) {
            var data = this.data(),
                size = $(this).paltau("size"),
                ml = 0;
            if (!data.noCenter) {
                if (data.display === "double") {
                    var view = this.paltau(
                        "view",
                        toPage || data.tpage || data.page
                    );
                    if (data.direction === "ltr")
                        ml += view[0]
                            ? view[1]
                                ? 0
                                : size.width / 4
                            : -size.width / 4;
                    else
                        ml += view[0]
                            ? view[1]
                                ? 0
                                : -size.width / 4
                            : size.width / 4;
                }
                $(this).css({ marginLeft: ml });
            }
            return this;
        },

        destroy: function () {
            var self = this,
                data = this.data(),
                evs =
                    "end first motion last pressed released start turning turned zooming missing".split(
                        " "
                    );
            if (triggerEvent("destroying", this) === "prevented") return;

            data.destroying = true;
            $.each(evs, function (_, e) {
                self.unbind(e);
            });
            this.parent().unbind("start", data.eventHandlers.start);

            $(document)
                .unbind(mouse.move, data.eventHandlers.touchMove)
                .unbind(mouse.up, data.eventHandlers.touchEnd);

            while (data.totalPages !== 0)
                this.paltau("removePage", data.totalPages);
            if (data.fparent) data.fparent.remove();
            if (data.shadow) data.shadow.remove();
            this.removeData();
            return this;
        },

        is: function () {
            return typeof this.data().pages === "object";
        },

        zoom: function (z) {
            var data = this.data();
            if (typeof z === "number") {
                if (z < 0.001 || z > 100)
                    throw makeErr(z + " is not a value for zoom");
                if (
                    triggerEvent("zooming", this, [z, data.zoom]) ===
                    "prevented"
                )
                    return this;

                var size = this.paltau("size"),
                    view = this.paltau("view"),
                    inv = 1 / data.zoom;
                var w = Math.round(size.width * inv * z),
                    h = Math.round(size.height * inv * z);
                data.zoom = z;
                $(this).paltau("stop").paltau("size", w, h);
                if (data.opts.autoCenter) this.paltau("center");
                book._updateShadow.call(this);

                for (var i = 0; i < view.length; i++)
                    if (view[i] && data.pageZoom[view[i]] !== data.zoom) {
                        this.trigger("zoomed", [
                            view[i],
                            view,
                            data.pageZoom[view[i]],
                            data.zoom,
                        ]);
                        data.pageZoom[view[i]] = data.zoom;
                    }
                return this;
            }
            return data.zoom;
        },

        _pageSize: function (n, withPos) {
            var data = this.data(),
                box = {};
            if (data.display === "single") {
                box.width = this.width();
                box.height = this.height();
                if (withPos) {
                    box.top = 0;
                    box.left = 0;
                    box.right = "auto";
                }
            } else {
                var half = this.width() / 2,
                    H = this.height();
                if (data.pageObjs[n].hasClass("own-size")) {
                    box.width = data.pageObjs[n].width();
                    box.height = data.pageObjs[n].height();
                } else {
                    box.width = half;
                    box.height = H;
                }
                if (withPos) {
                    var odd = n % 2;
                    box.top = (H - box.height) / 2;
                    if (data.direction === "ltr") {
                        box[odd ? "right" : "left"] = half - box.width;
                        box[odd ? "left" : "right"] = "auto";
                    } else {
                        box[odd ? "left" : "right"] = half - box.width;
                        box[odd ? "right" : "left"] = "auto";
                    }
                }
            }
            return box;
        },

        _makemotion: function (n) {
            var data = this.data();
            if (!data.pages[n] && data.pagePlace[n] == n) {
                var single = data.display === "single",
                    odd = n % 2;
                data.pages[n] = data.pageObjs[n]
                    .css(book._pageSize.call(this, n))
                    .motion({
                        page: n,
                        next: odd || single ? n + 1 : n - 1,
                        paltau: this,
                    })
                    .motion("disable", data.disabled);
                book._setPageLoc.call(this, n);
                data.pageZoom[n] = data.zoom;
            }
            return data.pages[n];
        },

        _makeRange: function () {
            if (this.data().totalPages < 1) return;
            var r = this.paltau("range");
            for (var i = r[0]; i <= r[1]; i++) book._addPage.call(this, i);
        },

        range: function (at) {
            var data = this.data(),
                p = at || data.tpage || data.page || 1,
                v = book._view.call(this, p);
            if (p < 1 || p > data.totalPages)
                throw makeErr('"' + p + '" is not a valid page');
            v[1] = v[1] || v[0];

            var back, fwd;
            if (v[0] >= 1 && v[1] <= data.totalPages) {
                var side = Math.floor(2);
                if (data.totalPages - v[1] > v[0]) {
                    back = Math.min(v[0] - 1, side);
                    fwd = 2 * side - back;
                } else {
                    fwd = Math.min(data.totalPages - v[1], side);
                    back = 2 * side - fwd;
                }
            } else back = fwd = 5;

            return [
                Math.max(1, v[0] - back),
                Math.min(data.totalPages, v[1] + fwd),
            ];
        },

        _necessPage: function (n) {
            if (n === 0) return true;
            var r = this.paltau("range");
            return (
                this.data().pageObjs[n].hasClass("fixed") ||
                (n >= r[0] && n <= r[1])
            );
        },

        _removeFromDOM: function () {
            var data = this.data(),
                k;
            for (k in data.pageWrap)
                if (has(k, data.pageWrap) && !book._necessPage.call(this, k))
                    book._removePageFromDOM.call(this, k);
        },

        _removePageFromDOM: function (n) {
            var data = this.data();
            if (data.pages[n]) {
                var fd = data.pages[n].data();
                motion._moveFoldingPage.call(data.pages[n], false);
                if (fd.f && fd.f.fwrapper) fd.f.fwrapper.remove();
                data.pages[n].removeData();
                data.pages[n].remove();
                delete data.pages[n];
            }
            if (data.pageObjs[n]) data.pageObjs[n].remove();
            if (data.pageWrap[n]) {
                data.pageWrap[n].remove();
                delete data.pageWrap[n];
            }
            book._removeMv.call(this, n);
            delete data.pagePlace[n];
            delete data.pageZoom[n];
        },

        removePage: function (n) {
            var data = this.data();
            if (n === "*") {
                while (data.totalPages !== 0)
                    this.paltau("removePage", data.totalPages);
            } else {
                if (n < 1 || n > data.totalPages)
                    throw makeErr("The page " + n + " doesn't exist");
                if (data.pageObjs[n]) {
                    this.paltau("stop");
                    book._removePageFromDOM.call(this, n);
                    delete data.pageObjs[n];
                }
                book._movePages.call(this, n, -1);
                data.totalPages -= 1;
                if (data.page > data.totalPages) {
                    data.page = null;
                    book._fitPage.call(this, data.totalPages);
                } else {
                    book._makeRange.call(this);
                    this.paltau("update");
                }
            }
            return this;
        },

        _movePages: function (from, delta) {
            var self = this,
                data = this.data(),
                single = data.display === "single";

            function shift(n) {
                var to = n + delta,
                    toOdd = to % 2,
                    cls = toOdd ? " odd " : " even ";
                if (data.pageObjs[n])
                    data.pageObjs[to] = data.pageObjs[n]
                        .removeClass("p" + n + " odd even")
                        .addClass("p" + to + cls);
                if (data.pagePlace[n] && data.pageWrap[n]) {
                    data.pagePlace[to] = to;
                    data.pageWrap[to] = data.pageObjs[to].hasClass("fixed")
                        ? data.pageWrap[n].attr("page", to)
                        : data.pageWrap[n]
                              .css(book._pageSize.call(self, to, true))
                              .attr("page", to);

                    if (data.pages[n])
                        data.pages[to] = data.pages[n].motion("options", {
                            page: to,
                            next: single || toOdd ? to + 1 : to - 1,
                        });

                    if (delta) {
                        delete data.pages[n];
                        delete data.pagePlace[n];
                        delete data.pageZoom[n];
                        delete data.pageObjs[n];
                        delete data.pageWrap[n];
                    }
                }
            }

            if (delta > 0)
                for (var i = data.totalPages; i >= from; i--) shift(i);
            else for (var j = from; j <= data.totalPages; j++) shift(j);
        },

        display: function (mode) {
            var data = this.data(),
                cur = data.display;
            if (mode === undefined) return cur;
            if ($.inArray(mode, displays) === -1)
                throw makeErr('"' + mode + '" is not a value for display');

            switch (mode) {
                case "single":
                    if (!data.pageObjs[0]) {
                        this.paltau("stop").css({ overflow: "hidden" });
                        data.pageObjs[0] = $("<div />", {
                            class: "page p-temporal",
                        })
                            .css({ width: this.width(), height: this.height() })
                            .appendTo(this);
                    }
                    this.addClass("shadow");
                    break;
                case "double":
                    if (data.pageObjs[0]) {
                        this.paltau("stop").css({ overflow: "" });
                        data.pageObjs[0].remove();
                        delete data.pageObjs[0];
                    }
                    this.removeClass("shadow");
            }
            data.display = mode;

            if (cur) {
                var s = this.paltau("size");
                book._movePages.call(this, 1, 0);
                this.paltau("size", s.width, s.height).paltau("update");
            }
            return this;
        },

        direction: function (dir) {
            var data = this.data();
            if (dir === undefined) return data.direction;
            dir = dir.toLowerCase();
            if ($.inArray(dir, dirs) === -1)
                throw makeErr('"' + dir + '" is not a value for direction');
            if (dir === "rtl")
                $(this).attr("dir", "ltr").css({ direction: "ltr" });
            data.direction = dir;
            if (data.done)
                this.paltau("size", $(this).width(), $(this).height());
            return this;
        },

        animating: function () {
            return this.data().pageMv.length > 0;
        },

        corner: function () {
            var data = this.data(),
                k,
                c;
            for (k in data.pages)
                if (has(k, data.pages) && (c = data.pages[k].motion("corner")))
                    return c;
            return false;
        },

        data: function () {
            return this.data();
        }, 

        disable: function (val) {
            var data = this.data(),
                view = this.paltau("view");
            data.disabled = val === undefined || val === true;
            for (var k in data.pages)
                if (has(k, data.pages))
                    data.pages[k].motion(
                        "disable",
                        data.disabled
                            ? true
                            : $.inArray(parseInt(k, 10), view) === -1
                    );
            return this;
        },

        disabled: function (v) {
            return v === undefined
                ? this.data().disabled === true
                : this.paltau("disable", v);
        },

        size: function (w, h) {
            if (w === undefined || h === undefined)
                return { width: this.width(), height: this.height() };
            this.paltau("stop");
            var data = this.data(),
                innerW = data.display === "double" ? w / 2 : w;

            this.css({ width: w, height: h });
            if (data.pageObjs[0])
                data.pageObjs[0].css({ width: innerW, height: h });

            for (var k in data.pageWrap)
                if (has(k, data.pageWrap)) {
                    var box = book._pageSize.call(this, k, true);
                    data.pageObjs[k].css({
                        width: box.width,
                        height: box.height,
                    });
                    data.pageWrap[k].css(box);
                    if (data.pages[k])
                        data.pages[k].css({
                            width: box.width,
                            height: box.height,
                        });
                }
            this.paltau("resize");
            return this;
        },

        resize: function () {
            var data = this.data();
            if (data.pages[0]) {
                data.pageWrap[0].css({ left: -this.width() });
                data.pages[0].motion("resize", true);
            }
            for (var i = 1; i <= data.totalPages; i++)
                if (data.pages[i]) data.pages[i].motion("resize", true);
            book._updateShadow.call(this);
            if (data.opts.autoCenter) this.paltau("center");
        },

        _removeMv: function (n) {
            var data = this.data();
            for (var i = 0; i < data.pageMv.length; i++)
                if (data.pageMv[i] == n) {
                    data.pageMv.splice(i, 1);
                    return true;
                }
            return false;
        },

        _addMv: function (n) {
            var data = this.data();
            book._removeMv.call(this, n);
            data.pageMv.push(n);
        },

        _view: function (p) {
            var data = this && this.data ? this.data() : null;
            if (!data) throw makeErr("_view called with invalid context");
            p = p || data.page;
            return data.display === "double"
                ? p % 2
                    ? [p - 1, p]
                    : [p, p + 1]
                : [p];
        },

        view: function (p) {
            var data = this.data(),
                v = book._view.call(this, p);
            return data?.display === "double"
                ? [v[0] > 0 ? v[0] : 0, v[1] <= data.totalPages ? v[1] : 0]
                : [v[0] > 0 && v[0] <= data.totalPages ? v[0] : 0];
        },

        stop: function (keep, noHide) {
            if (this.paltau("animating")) {
                var data = this.data();
                if (data.tpage) {
                    data.page = data.tpage;
                    delete data.tpage;
                }
                for (var i = 0; i < data.pageMv.length; i++)
                    if (data.pageMv[i] && data.pageMv[i] !== keep) {
                        var $p = data.pages[data.pageMv[i]],
                            fd = $p.data().f.opts;
                        $p.motion("hideFoldedPage", noHide);
                        if (!noHide) motion._moveFoldingPage.call($p, false);
                        if (fd.force) {
                            fd.next =
                                fd.page % 2 === 0 ? fd.page - 1 : fd.page + 1;
                            delete fd.force;
                        }
                    }
            }
            this.paltau("update");
            return this;
        },

        pages: function (n) {
            var data = this.data();
            if (n) {
                if (n < data.totalPages)
                    for (var i = data.totalPages; i > n; i--)
                        this.paltau("removePage", i);
                data.totalPages = n;
                book._fitPage.call(this, data.page);
                return this;
            }
            return data.totalPages;
        },

        _missing: function (at) {
            var data = this.data();
            if (data.totalPages < 1) return;
            var r = this.paltau("range", at),
                miss = [];
            for (var i = r[0]; i <= r[1]; i++)
                if (!data.pageObjs[i]) miss.push(i);
            if (miss.length > 0) this.trigger("missing", [miss]);
        },

        _fitPage: function (p) {
            var data = this.data(),
                v = this.paltau("view", p);
            book._missing.call(this, p);
            if (data.pageObjs[p]) {
                data.page = p;
                this.paltau("stop");
                for (var i = 0; i < v.length; i++)
                    if (v[i] && data.pageZoom[v[i]] !== data.zoom) {
                        this.trigger("zoomed", [
                            v[i],
                            v,
                            data.pageZoom[v[i]],
                            data.zoom,
                        ]);
                        data.pageZoom[v[i]] = data.zoom;
                    }
                book._removeFromDOM.call(this);
                book._makeRange.call(this);
                book._updateShadow.call(this);
                this.trigger("turned", [p, v]);
                this.paltau("update");
                if (data.opts.autoCenter) this.paltau("center");
            }
        },

        _turnPage: function (p) {
            var data = this.data(),
                place = data.pagePlace[p],
                v = this.paltau("view"),
                nv = this.paltau("view", p);
            if (data.page !== p) {
                var prev = data.page;
                if (triggerEvent("turning", this, [p, nv]) === "prevented") {
                    if (
                        prev == data.page &&
                        $.inArray(place, data.pageMv) !== -1
                    )
                        data.pages[place].motion("hideFoldedPage", true);
                    return;
                }
                if ($.inArray(1, nv) !== -1) this.trigger("first");
                if ($.inArray(data.totalPages, nv) !== -1) this.trigger("last");
            }

            var base, target;
            if (data.display === "single") {
                base = v[0];
                target = nv[0];
            } else if (v[1] && p > v[1]) {
                base = v[1];
                target = nv[0];
            } else if (v[0] && p < v[0]) {
                base = v[0];
                target = nv[1];
            }

            var corners = data.opts.turnCorners.split(",");
            var fdata = data.pages[base].data().f,
                opts = fdata.opts,
                keepPoint = fdata.point;

            book._missing.call(this, p);
            if (data.pageObjs[p]) {
                this.paltau("stop");
                data.page = p;
                book._makeRange.call(this);
                data.tpage = target;
                if (opts.next !== target) {
                    opts.next = target;
                    opts.force = true;
                }
                this.paltau("update");
                fdata.point = keepPoint;

                if (fdata.effect === "hard") {
                    if (data.direction === "ltr")
                        data.pages[base].motion("turnPage", p > base ? "r" : "l");
                    else
                        data.pages[base].motion("turnPage", p > base ? "l" : "r");
                } else {
                    if (data.direction === "ltr")
                        data.pages[base].motion(
                            "turnPage",
                            corners[p > base ? 1 : 0]
                        );
                    else
                        data.pages[base].motion(
                            "turnPage",
                            corners[p > base ? 0 : 1]
                        );
                }
            }
        },

        page: function (p) {
            var data = this.data();
            if (p === undefined) return data.page;
            if (data.disabled || data.destroying) return this;
            p = parseInt(p, 10);
            if (p > 0 && p <= data.totalPages) {
                if (p !== data.page) {
                    if (!data.done || $.inArray(p, this.paltau("view")) !== -1)
                        book._fitPage.call(this, p);
                    else book._turnPage.call(this, p);
                }
                return this;
            }
            throw makeErr("The page " + p + " does not exist");
        },

        next: function () {
            var v = book._view.call(this, this.data().page).pop() + 1;
            return this.paltau("page", Math.min(this.data().totalPages, v));
        },

        previous: function () {
            var v = book._view.call(this, this.data().page).shift() - 1;
            return this.paltau("page", Math.max(1, v));
        },

        peel: function (corner, auto) {
            var data = this.data(),
                v = this.paltau("view"),
                doAuto = auto === undefined ? true : auto === true;
            if (corner === false) this.paltau("stop", null, doAuto);
            else if (data.display === "single")
                data.pages[data.page].motion("peel", corner, doAuto);
            else {
                var side =
                    data.direction === "ltr"
                        ? corner.indexOf("l") !== -1
                            ? v[0]
                            : v[1]
                        : corner.indexOf("l") !== -1
                        ? v[1]
                        : v[0];
                if (data.pages[side])
                    data.pages[side].motion("peel", corner, doAuto);
            }
            return this;
        },

        _addMotionPage: function () {
            var opts = $(this).data().f.opts,
                $book = opts.paltau;
            book._addMv.call($book, opts.page);
        },

        _eventStart: function (e, opts, whichCorner) {
            var data = opts.paltau.data(),
                z = data.pageZoom[opts.page];
            if (e.isDefaultPrevented()) return;
            if (z && z !== data.zoom) {
                opts.paltau.trigger("zoomed", [
                    opts.page,
                    opts.paltau.paltau("view", opts.page),
                    z,
                    data.zoom,
                ]);
                data.pageZoom[opts.page] = data.zoom;
            }
            if (data.display === "single" && whichCorner) {
                if (
                    (whichCorner.charAt(1) === "l" &&
                        data.direction === "ltr") ||
                    (whichCorner.charAt(1) === "r" && data.direction === "rtl")
                ) {
                    opts.next =
                        opts.next < opts.page ? opts.next : opts.page - 1;
                    opts.force = true;
                } else {
                    opts.next =
                        opts.next > opts.page ? opts.next : opts.page + 1;
                }
            }
            book._addMotionPage.call(e.target);
            book._updateShadow.call(opts.paltau);
        },

        _eventEnd: function (e, opts, turned) {
            var $book = opts.paltau,
                data = $book.data();
            if (turned) {
                var t = data.tpage || data.page;
                if (t == opts.next || t == opts.page) {
                    delete data.tpage;
                    book._fitPage.call($book, t || opts.next, true);
                }
            } else {
                book._removeMv.call($book, opts.page);
                book._updateShadow.call($book);
                $book.paltau("update");
            }
        },

        _eventPressed: function (e) {
            var f = $(e.target).data().f,
                $book = f.opts.paltau;
            $book.data().mouseAction = true;
            $book.paltau("update");
            f.time = new Date().getTime();
        },

        _eventReleased: function (e, where) {
            var $el = $(e.target),
                fd = $el.data().f,
                $book = fd.opts.paltau,
                data = $book.data();
            var out =
                data.display === "single"
                    ? where.corner === "br" || where.corner === "tr"
                        ? where.x < $el.width() / 2
                        : where.x > $el.width() / 2
                    : where.x < 0 || where.x > $el.width();

            if (new Date().getTime() - fd.time < 200 || out) {
                e.preventDefault();
                book._turnPage.call($book, fd.opts.next);
            }
            data.mouseAction = false;
        },

        _motion: function (e) {
            e.stopPropagation();
            var opts = $(e.target).data().f.opts;
            opts.paltau.trigger("paltau", [opts.next]);
            if (opts.paltau.data().opts.autoCenter)
                opts.paltau.paltau("center", opts.next);
        },

        _touchStart: function () {
            var data = this.data(),
                k;
            for (k in data.pages)
                if (
                    has(k, data.pages) &&
                    motion._eventStart.apply(data.pages[k], arguments) === false
                )
                    return false;
        },

        _touchMove: function () {
            var data = this.data(),
                k;
            for (k in data.pages)
                if (has(k, data.pages))
                    motion._eventMove.apply(data.pages[k], arguments);
        },

        _touchEnd: function () {
            var data = this.data(),
                k;
            for (k in data.pages)
                if (has(k, data.pages))
                    motion._eventEnd.apply(data.pages[k], arguments);
        },

        calculateZ: function (moving) {
            var self = this;
            var data = self.data();
            var view = self.paltau("view");
            var base = view[0] || view[1];
            var out = { pageZ: {}, partZ: {}, pageV: {} };

            function mark(p) {
                var v = self.paltau("view", p); // ✅ public API, correct context
                if (v[0]) out.pageV[v[0]] = true;
                if (v[1]) out.pageV[v[1]] = true;
            }

            for (var i = 0, g = moving.length - 1; i <= g; i++) {
                var p = moving[i];
                var next = data.pages[p].data().f.opts.next;
                var place = data.pagePlace[p];
                mark(p);
                mark(next);
                var zPage = data.pagePlace[next] == next ? next : p;
                out.pageZ[zPage] = data.totalPages - Math.abs(base - zPage);
                out.partZ[place] = 2 * data.totalPages - g + i;
            }
            return out;
        },

        update: function () {
            var data = this.data();
            if (this.paltau("animating") && data.pageMv[0] !== 0) {
                var zmap = this.paltau("calculateZ", data.pageMv),
                    cor = this.paltau("corner"),
                    v = this.paltau("view"),
                    tv = this.paltau("view", data.tpage);
                for (var k in data.pageWrap)
                    if (has(k, data.pageWrap)) {
                        var fixed = data.pageObjs[k].hasClass("fixed");
                        data.pageWrap[k].css({
                            display: zmap.pageV[k] || fixed ? "" : "none",
                            zIndex:
                                (data.pageObjs[k].hasClass("hard")
                                    ? zmap.partZ[k]
                                    : zmap.pageZ[k]) || (fixed ? -1 : 0),
                        });
                        var $p = data.pages[k];
                        if ($p) {
                            $p.motion("z", zmap.partZ[k] || null);
                            if (zmap.pageV[k]) $p.motion("resize");
                            if (data.tpage) {
                                $p.motion("hover", false).motion(
                                    "disable",
                                    $.inArray(parseInt(k, 10), data.pageMv) ===
                                        -1 &&
                                        k != tv[0] &&
                                        k != tv[1]
                                );
                            } else {
                                $p.motion("hover", cor !== false).motion(
                                    "disable",
                                    k != v[0] && k != v[1]
                                );
                            }
                        }
                    }
            } else {
                for (var n in data.pageWrap)
                    if (has(n, data.pageWrap)) {
                        var loc = book._setPageLoc.call(this, n);
                        if (data.pages[n])
                            data.pages[n]
                                .motion("disable", data.disabled || loc !== 1)
                                .motion("hover", true)
                                .motion("z", null);
                    }
            }
            return this;
        },

        _updateShadow: function () {
            var data = this.data(),
                W = this.width(),
                H = this.height(),
                half = data.display === "single" ? W : W / 2;
            var v = this.paltau("view");
            if (!data.shadow)
                data.shadow = $("<div />", {
                    class: "shadow",
                    css: cssBox(0, 0, 0).css,
                }).appendTo(this);

            for (var i = 0; i < data.pageMv.length && v[0] && v[1]; i++) {
                var vNext = this.paltau(
                        "view",
                        data.pages[data.pageMv[i]].data().f.opts.next
                    ),
                    vCur = this.paltau("view", data.pageMv[i]);
                vNext[0] = vNext[0] && vCur[0];
                vNext[1] = vNext[1] && vCur[1];
            }

            var mode = v[0]
                ? v[1]
                    ? 3
                    : data.direction === "ltr"
                    ? 2
                    : 1
                : data.direction === "ltr"
                ? 1
                : 2;
            if (mode === 1)
                data.shadow.css({ width: half, height: H, top: 0, left: half });
            else if (mode === 2)
                data.shadow.css({ width: half, height: H, top: 0, left: 0 });
            else data.shadow.css({ width: W, height: H, top: 0, left: 0 });
        },

        _setPageLoc: function (n) {
            var data = this.data(),
                v = this.paltau("view"),
                state = 0;
            if (n == v[0] || n == v[1]) state = 1;
            else if (
                (data.display === "single" && n == v[0] + 1) ||
                (data.display === "double" && n == v[0] - 2) ||
                n == v[1] + 2
            )
                state = 2;

            if (!this.paltau("animating")) {
                if (state === 1)
                    data.pageWrap[n].css({
                        zIndex: data.totalPages,
                        display: "",
                    });
                else if (state === 2)
                    data.pageWrap[n].css({
                        zIndex: data.totalPages - 1,
                        display: "",
                    });
                else
                    data.pageWrap[n].css({
                        zIndex: 0,
                        display: data.pageObjs[n].hasClass("fixed")
                            ? ""
                            : "none",
                    });
            }
            return state;
        },

        options: function (o) {
            if (o === undefined) return this.data().opts;
            var data = this.data();
            $.extend(data.opts, o);
            if (o.pages) this.paltau("pages", o.pages);
            if (o.page) this.paltau("page", o.page);
            if (o.display) this.paltau("display", o.display);
            if (o.direction) this.paltau("direction", o.direction);
            if (o.width && o.height) this.paltau("size", o.width, o.height);
            if (o.when)
                for (var k in o.when)
                    if (has(k, o.when)) this.unbind(k).bind(k, o.when[k]);
            return this;
        },

        version: function () {
            return "4.1.0";
        },
    };

    // ---------- motion (page-level) ----------
    var motion = {
        init: function (opts) {
            this.data({
                f: {
                    disabled: false,
                    hover: false,
                    effect: this.hasClass("hard") ? "hard" : "sheet",
                },
            });
            this.motion("options", opts);
            motion._addPageWrapper.call(this);
            return this;
        },

        setData: function (o) {
            var d = this.data();
            d.f = $.extend(d.f, o);
            return this;
        },
        options: function (o) {
            var f = this.data().f;
            return o
                ? (motion.setData.call(this, {
                      opts: $.extend({}, f.opts || motionDefaults, o),
                  }),
                  this)
                : f.opts;
        },
        z: function (z) {
            var f = this.data().f;
            f.opts["z-index"] = z;
            if (f.fwrapper)
                f.fwrapper.css({
                    zIndex: z || parseInt(f.parent.css("z-index"), 10) || 0,
                });
            return this;
        },

        _cAllowed: function () {
            var f = this.data().f,
                p = f.opts.page,
                b = f.opts.paltau.data(),
                odd = p % 2;
            if (f.effect === "hard")
                return b.direction === "ltr"
                    ? [odd ? "r" : "l"]
                    : [odd ? "l" : "r"];
            if (b.display === "single") {
                if (p === 1)
                    return b.direction === "ltr"
                        ? cornersMap.forward
                        : cornersMap.backward;
                if (p === b.totalPages)
                    return b.direction === "ltr"
                        ? cornersMap.backward
                        : cornersMap.forward;
                return cornersMap.all;
            }
            return b.direction === "ltr"
                ? cornersMap[odd ? "forward" : "backward"]
                : cornersMap[odd ? "backward" : "forward"];
        },

        _cornerActivated: function (p) {
            var f = this.data().f,
                w = this.width(),
                h = this.height(),
                a = { x: p.x, y: p.y, corner: "" },
                cs = f.opts.cornerSize;
            if (a.x <= 0 || a.y <= 0 || a.x >= w || a.y >= h) return false;
            var allowed = motion._cAllowed.call(this);

            switch (f.effect) {
                case "hard":
                    if (a.x > w - cs) a.corner = "r";
                    else if (a.x < cs) a.corner = "l";
                    else return false;
                    break;
                case "sheet":
                    if (a.y < cs) a.corner += "t";
                    else if (a.y >= h - cs) a.corner += "b";
                    else return false;
                    if (a.x <= cs) a.corner += "l";
                    else if (a.x >= w - cs) a.corner += "r";
                    else return false;
            }
            return !a.corner || $.inArray(a.corner, allowed) === -1 ? false : a;
        },

        _isIArea: function (evt) {
            var off = this.data().f.parent.offset(),
                e =
                    touch && evt.originalEvent
                        ? evt.originalEvent.touches[0]
                        : evt;
            return motion._cornerActivated.call(this, {
                x: e.pageX - off.left,
                y: e.pageY - off.top,
            });
        },

        _c: function (corner, inset) {
            inset = inset || 0;
            var w = this.width(),
                h = this.height();
            switch (corner) {
                case "tl":
                    return pt(inset, inset);
                case "tr":
                    return pt(w - inset, inset);
                case "bl":
                    return pt(inset, h - inset);
                case "br":
                    return pt(w - inset, h - inset);
                case "l":
                    return pt(inset, 0);
                case "r":
                    return pt(w - inset, 0);
            }
        },

        _c2: function (corner) {
            var w = this.width(),
                h = this.height();
            switch (corner) {
                case "tl":
                    return pt(2 * w, 0);
                case "tr":
                    return pt(-w, 0);
                case "bl":
                    return pt(2 * w, h);
                case "br":
                    return pt(-w, h);
                case "l":
                    return pt(2 * w, 0);
                case "r":
                    return pt(-w, 0);
            }
        },

        _foldingPage: function () {
            var f = this.data().f;
            if (!f) return;
            var o = f.opts,
                b = o.paltau.data();
            if (b.display === "single")
                return o.next > 1 || o.page > 1 ? b.pageObjs[0] : null;
            return b.pageObjs[o.next];
        },

        _backGradient: function () {
            var f = this.data().f,
                b = f.opts.paltau.data();
            var needed =
                b.opts.gradients &&
                (b.display === "single" ||
                    (f.opts.page !== 2 && f.opts.page !== b.totalPages - 1));
            if (needed && !f.bshadow) {
                f.bshadow = $("<div/>", cssBox(0, 0, 1))
                    .css({
                        position: "",
                        width: this.width(),
                        height: this.height(),
                    })
                    .appendTo(f.parent);
            }
            return needed;
        },

        type: function () {
            return this.data().f.effect;
        },

        resize: function (doBoxes) {
            var f = this.data().f,
                b = f.opts.paltau.data(),
                w = this.width(),
                h = this.height();
            if (f.effect === "hard") {
                if (doBoxes) {
                    f.wrapper.css({ width: w, height: h });
                    f.fpage.css({ width: w, height: h });
                    if (b.opts.gradients) {
                        f.ashadow.css({ width: w, height: h });
                        f.bshadow.css({ width: w, height: h });
                    }
                }
            } else {
                if (doBoxes) {
                    var diag = Math.round(Math.sqrt(w * w + h * h));
                    f.wrapper.css({ width: diag, height: diag });
                    f.fwrapper
                        .css({ width: diag, height: diag })
                        .children(":first-child")
                        .css({ width: w, height: h });
                    f.fpage.css({ width: w, height: h });
                    if (b.opts.gradients)
                        f.ashadow.css({ width: w, height: h });
                    if (motion._backGradient.call(this))
                        f.bshadow.css({ width: w, height: h });
                }
                if (f.parent.is(":visible")) {
                    var po = findPos(f.parent[0]);
                    f.fwrapper.css({ top: po.top, left: po.left });
                    var bo = findPos(f.opts.paltau[0]);
                    f.fparent.css({ top: -bo.top, left: -bo.left });
                }
                this.motion("z", f.opts["z-index"]);
            }
        },

        _addPageWrapper: function () {
            var f = this.data().f,
                b = f.opts.paltau.data(),
                $parent = this.parent();
            f.parent = $parent;
            if (!f.wrapper) {
                if (f.effect === "hard") {
                    var css3 = {};
                    css3[cssPref + "transform-style"] = "preserve-3d";
                    css3[cssPref + "backface-visibility"] = "hidden";
                    f.wrapper = $("<div/>", cssBox(0, 0, 2))
                        .css(css3)
                        .appendTo($parent)
                        .prepend(this);
                    f.fpage = $("<div/>", cssBox(0, 0, 1))
                        .css(css3)
                        .appendTo($parent);
                    if (b.opts.gradients) {
                        f.ashadow = $("<div/>", cssBox(0, 0, 0))
                            .hide()
                            .appendTo($parent);
                        f.bshadow = $("<div/>", cssBox(0, 0, 0));
                    }
                } else {
                    var w = this.width(),
                        h = this.height(),
                        diag = Math.round(Math.sqrt(w * w + h * h));
                    f.fparent = f.opts.paltau.data().fparent;
                    if (!f.fparent) {
                        var fp = $("<div/>", {
                            css: { "pointer-events": "none" },
                        }).hide();
                        fp.data().motions = 0;
                        fp.css(cssBox(0, 0, "auto", "visible").css).appendTo(
                            f.opts.paltau
                        );
                        f.opts.paltau.data().fparent = fp;
                        f.fparent = fp;
                    }
                    this.css({
                        position: "absolute",
                        top: 0,
                        left: 0,
                        bottom: "auto",
                        right: "auto",
                    });
                    f.wrapper = $("<div/>", cssBox(0, 0, this.css("z-index")))
                        .appendTo($parent)
                        .prepend(this);
                    f.fwrapper = $(
                        "<div/>",
                        cssBox($parent.offset().top, $parent.offset().left)
                    )
                        .hide()
                        .appendTo(f.fparent);
                    f.fpage = $("<div/>", cssBox(0, 0, 0, "visible"))
                        .css({ cursor: "default" })
                        .appendTo(f.fwrapper);
                    if (b.opts.gradients)
                        f.ashadow = $("<div/>", cssBox(0, 0, 1)).appendTo(
                            f.fpage
                        );
                    motion.setData.call(this, f);
                }
            }
            motion.resize.call(this, true);
        },

        _fold: function (a) {
            var f = this.data().f,
                b = f.opts.paltau.data(),
                c = motion._c.call(this, a.corner),
                w = this.width(),
                h = this.height();

            if (f.effect === "hard") {
                a.x =
                    a.corner === "l"
                        ? Math.min(Math.max(a.x, 0), 2 * w)
                        : Math.max(Math.min(a.x, w), -w);
                var toX,
                    showA,
                    backIdx,
                    bgAnchor,
                    fgAnchor,
                    zBase = f.opts["z-index"] || b.totalPages,
                    ratio = c.x ? (c.x - a.x) / w : a.x / w,
                    deg = 90 * ratio,
                    isFront = deg < 90;

                if (a.corner === "l") {
                    fgAnchor = "0% 50%";
                    bgAnchor = "100% 50%";
                    showA = 0;
                    backIdx = f.opts.next - 1 > 0;
                    toX = 1;
                } else {
                    fgAnchor = "100% 50%";
                    bgAnchor = "0% 50%";
                    deg = -deg;
                    w = -w;
                    showA = 0;
                    backIdx = f.opts.next + 1 < b.totalPages;
                    toX = 0;
                }

                var parentCss = {};
                parentCss[cssPref + "perspective-origin"] = bgAnchor;

                f.wrapper.transform(
                    "rotateY(" +
                        deg +
                        "deg)translate3d(0px,0px," +
                        (this.attr("depth") || 0) +
                        "px)",
                    bgAnchor
                );
                f.fpage.transform(
                    "translateX(" + w + "px) rotateY(" + (180 + deg) + "deg)",
                    fgAnchor
                );
                f.parent.css(parentCss);

                var op = isFront ? -ratio + 1 : ratio - 1;
                if (isFront) {
                    f.wrapper.css({ zIndex: zBase + 1 });
                    f.fpage.css({ zIndex: zBase });
                } else {
                    f.wrapper.css({ zIndex: zBase });
                    f.fpage.css({ zIndex: zBase + 1 });
                }

                if (b.opts.gradients) {
                    if (backIdx)
                        f.ashadow
                            .css({
                                display: "",
                                left: showA ? "100%" : 0,
                                backgroundColor: "rgba(0,0,0," + 0.5 * op + ")",
                            })
                            .transform("rotateY(0deg)");
                    else f.ashadow.hide();
                    f.bshadow.css({ opacity: -op + 1 });
                    if (isFront) {
                        if (f.bshadow.parent()[0] !== f.wrapper[0])
                            f.bshadow.appendTo(f.wrapper);
                    } else {
                        if (f.bshadow.parent()[0] !== f.fpage[0])
                            f.bshadow.appendTo(f.fpage);
                    }
                    applyLinearGradient(
                        f.bshadow,
                        pt(100 * toX, 0),
                        pt(100 * (-toX + 1), 0),
                        [
                            [0, "rgba(0,0,0,0.3)"],
                            [1, "rgba(0,0,0,0)"],
                        ],
                        2
                    );
                }
            } else {
                // sheet effect (kept intact, just tightened a few locals)
                var self = this,
                    angleDeg = 0,
                    A,
                    Z,
                    Ash,
                    Lp,
                    dVec,
                    mVec,
                    u = pt(0, 0),
                    P = pt(0, 0),
                    M = pt(0, 0),
                    $folding = motion._foldingPage.call(this),
                    accel = b.opts.acceleration,
                    Qh = f.wrapper.height(),
                    topSide = a.corner[0] === "t",
                    leftSide = a.corner[1] === "l";

                var solve = function () {
                    var v = pt(0, 0),
                        mid = pt(0, 0);
                    v.x = c.x ? c.x - a.x : a.x;
                    v.y = goodWebKit ? (c.y ? c.y - a.y : a.y) : 0;
                    mid.x = leftSide ? w - v.x / 2 : a.x + v.x / 2;
                    mid.y = v.y / 2;

                    var ang = HALF_PI - Math.atan2(v.y, v.x),
                        k = ang - Math.atan2(mid.y, mid.x),
                        len = Math.max(
                            0,
                            Math.sin(k) *
                                Math.sqrt(mid.x * mid.x + mid.y * mid.y)
                        );

                    angleDeg = 180 * (ang / PI);
                    M = pt(len * Math.sin(ang), len * Math.cos(ang));

                    if (ang > HALF_PI) {
                        M.x += Math.abs((M.y * v.y) / v.x);
                        M.y = 0;
                        if (Math.round(M.x * Math.tan(PI - ang)) < h) {
                            a.y = Math.sqrt(h * h + 2 * mid.x * v.x);
                            if (topSide) a.y = h - a.y;
                            return solve();
                        }
                        var rem = PI - ang,
                            yy = Qh - h / Math.sin(rem);
                        u = pt(
                            Math.round(yy * Math.cos(rem)),
                            Math.round(yy * Math.sin(rem))
                        );
                        if (leftSide) u.x = -u.x;
                        if (topSide) u.y = -u.y;
                    }

                    var xoff = Math.round(M.y / Math.tan(ang) + M.x),
                        brem = w - xoff;
                    var cos2 = brem * Math.cos(2 * ang),
                        sin2 = brem * Math.sin(2 * ang);
                    P = pt(
                        Math.round(leftSide ? brem - cos2 : xoff + cos2),
                        Math.round(topSide ? sin2 : h - sin2)
                    );

                    if (b.opts.gradients) {
                        var xLen = brem * Math.sin(ang),
                            far = motion._c2.call(self, a.corner),
                            ratio =
                                Math.sqrt(
                                    Math.pow(far.x - a.x, 2) +
                                        Math.pow(far.y - a.y, 2)
                                ) / w;

                        dVec = Math.sin(
                            HALF_PI * (ratio > 1 ? 2 - ratio : ratio)
                        );
                        mVec = Math.min(ratio, 1);
                        var clip = xLen > 100 ? (xLen - 100) / xLen : 0;
                        Z = pt(
                            100 * ((xLen * Math.sin(ang)) / w),
                            100 * ((xLen * Math.cos(ang)) / h)
                        );
                        if (motion._backGradient.call(self)) {
                            Ash = pt(
                                100 * ((1.2 * xLen * Math.sin(ang)) / w),
                                100 * ((1.2 * xLen * Math.cos(ang)) / h)
                            );
                            if (!leftSide) Ash.x = 100 - Ash.x;
                            if (!topSide) Ash.y = 100 - Ash.y;
                        }
                        Lp = clip;
                    }
                    M.x = Math.round(M.x);
                    M.y = Math.round(M.y);
                    return true;
                };

                var place = function (p, anchor, origin, deg) {
                    var zeros = ["0", "auto"],
                        mx = ((w - Qh) * origin[0]) / 100,
                        my = ((h - Qh) * origin[1]) / 100;
                    var edges = {
                        left: zeros[anchor[0]],
                        top: zeros[anchor[1]],
                        right: zeros[anchor[2]],
                        bottom: zeros[anchor[3]],
                    };
                    var shadowCSS = {};
                    var sideOffset =
                        deg !== 90 && deg !== -90 ? (leftSide ? -1 : 1) : 0;
                    var originStr = origin[0] + "% " + origin[1] + "%";

                    self.css(edges).transform(
                        rotate(deg) + translate(p.x + sideOffset, p.y, accel),
                        originStr
                    );
                    f.fpage
                        .css(edges)
                        .transform(
                            rotate(deg) +
                                translate(
                                    p.x + P.x - u.x - (w * origin[0]) / 100,
                                    p.y + P.y - u.y - (h * origin[1]) / 100,
                                    accel
                                ) +
                                rotate((180 / deg - 2) * deg),
                            originStr
                        );
                    f.wrapper.transform(
                        translate(-p.x + mx - sideOffset, -p.y + my, accel) +
                            rotate(-deg),
                        originStr
                    );
                    f.fwrapper.transform(
                        translate(-p.x + u.x + mx, -p.y + u.y + my, accel) +
                            rotate(-deg),
                        originStr
                    );

                    if (b.opts.gradients) {
                        if (origin[0]) Z.x = 100 - Z.x;
                        if (origin[1]) Z.y = 100 - Z.y;

                        if ($folding) {
                            shadowCSS["box-shadow"] =
                                "0 0 20px rgba(0,0,0," + 0.5 * dVec + ")";
                            $folding.css(shadowCSS);
                        }
                        applyLinearGradient(
                            f.ashadow,
                            pt(leftSide ? 100 : 0, topSide ? 0 : 100),
                            pt(Z.x, Z.y),
                            [
                                [Lp, "rgba(0,0,0,0)"],
                                [
                                    0.8 * (1 - Lp) + Lp,
                                    "rgba(0,0,0," + 0.2 * mVec + ")",
                                ],
                                [1, "rgba(255,255,255," + 0.2 * mVec + ")"],
                            ],
                            3
                        );
                        if (motion._backGradient.call(self)) {
                            applyLinearGradient(
                                f.bshadow,
                                pt(leftSide ? 0 : 100, topSide ? 0 : 100),
                                pt(Ash.x, Ash.y),
                                [
                                    [0.6, "rgba(0,0,0,0)"],
                                    [0.8, "rgba(0,0,0," + 0.3 * mVec + ")"],
                                    [1, "rgba(0,0,0,0)"],
                                ],
                                3
                            );
                        }
                    }
                };

                switch (a.corner) {
                    case "tl":
                        a.x = Math.max(a.x, 1);
                        if (solve()) place(M, [1, 0, 0, 1], [100, 0], angleDeg);
                        break;
                    case "tr":
                        a.x = Math.min(a.x, w - 1);
                        if (solve())
                            place(
                                pt(-M.x, M.y),
                                [0, 0, 0, 1],
                                [0, 0],
                                -angleDeg
                            );
                        break;
                    case "bl":
                        a.x = Math.max(a.x, 1);
                        if (solve())
                            place(
                                pt(M.x, -M.y),
                                [1, 1, 0, 0],
                                [100, 100],
                                -angleDeg
                            );
                        break;
                    case "br":
                        a.x = Math.min(a.x, w - 1);
                        if (solve())
                            place(
                                pt(-M.x, -M.y),
                                [0, 1, 1, 0],
                                [0, 100],
                                angleDeg
                            );
                        break;
                }
            }
            f.point = a;
        },

        _moveFoldingPage: function (on) {
            var f = this.data().f;
            if (!f) return;
            var $book = f.opts.paltau,
                data = $book.data(),
                place = data.pagePlace;
            if (on) {
                var next = f.opts.next;
                if (place[next] != f.opts.page) {
                    if (f.folding) motion._moveFoldingPage.call(this, false);
                    motion._foldingPage.call(this).appendTo(f.fpage);
                    place[next] = f.opts.page;
                    f.folding = next;
                }
                $book.paltau("update");
            } else if (f.folding) {
                if (data.pages[f.folding]) {
                    var fd = data.pages[f.folding].data().f;
                    data.pageObjs[f.folding].appendTo(fd.wrapper);
                } else if (data.pageWrap[f.folding])
                    data.pageObjs[f.folding].appendTo(data.pageWrap[f.folding]);
                if (f.folding in place) place[f.folding] = f.folding;
                delete f.folding;
            }
        },

        _showFoldedPage: function (a, animate) {
            var $fold = motion._foldingPage.call(this),
                d = this.data(),
                f = d.f,
                visible = f.visible;
            if (!$fold) return false;

            if (!visible || !f.point || f.point.corner !== a.corner) {
                var hoverCorner =
                    f.status === "hover" ||
                    f.status === "peel" ||
                    f.opts.paltau.data().mouseAction
                        ? a.corner
                        : null;
                visible = false;
                if (
                    triggerEvent("start", this, [f.opts, hoverCorner]) ===
                    "prevented"
                )
                    return false;
            }

            if (animate) {
                var self = this,
                    from =
                        f.point && f.point.corner === a.corner
                            ? f.point
                            : motion._c.call(this, a.corner, 1);
                this.animatef({
                    from: [from.x, from.y],
                    to: [a.x, a.y],
                    duration: 500,
                    frame: function (xy) {
                        a.x = Math.round(xy[0]);
                        a.y = Math.round(xy[1]);
                        motion._fold.call(self, a);
                    },
                });
            } else {
                motion._fold.call(this, a);
                d.effect && !d.effect.turning && this.animatef(false);
            }

            if (!visible) {
                if (f.effect === "hard") {
                    f.visible = true;
                    motion._moveFoldingPage.call(this, true);
                    f.fpage.show();
                    if (f.opts.shadows) f.bshadow.show();
                } else {
                    f.visible = true;
                    f.fparent.show().data().motions++;
                    motion._moveFoldingPage.call(this, true);
                    f.fwrapper.show();
                    if (f.bshadow) f.bshadow.show();
                }
            }
            return true;
        },

        hide: function () {
            var f = this.data().f,
                b = f.opts.paltau.data(),
                $fold = motion._foldingPage.call(this);
            if (f.effect === "hard") {
                if (b.opts.gradients) {
                    f.bshadowLoc = 0;
                    f.bshadow.remove();
                    f.ashadow.hide();
                }
                f.wrapper.transform("");
                f.fpage.hide();
            } else {
                if (--f.fparent.data().motions === 0) f.fparent.hide();
                this.css({
                    left: 0,
                    top: 0,
                    right: "auto",
                    bottom: "auto",
                }).transform("");
                f.wrapper.transform("");
                f.fwrapper.hide();
                if (f.bshadow) f.bshadow.hide();
                $fold.transform("");
            }
            f.visible = false;
            return this;
        },
        hideFoldedPage: function (animate) {
            var f = this.data().f;
            if (!f.point) return;
            var self = this,
                p = f.point,
                done = function () {
                    f.point = null;
                    f.status = "";
                    self.motion("hide");
                    self.trigger("end", [f.opts, false]);
                };

            if (animate) {
                var c = motion._c.call(this, p.corner),
                    off =
                        p.corner[0] === "t"
                            ? Math.min(0, p.y - c.y) / 2
                            : Math.max(0, p.y - c.y) / 2,
                    b1 = pt(p.x, p.y + off),
                    b2 = pt(c.x, c.y - off);
                this.animatef({
                    from: 0,
                    to: 1,
                    duration: 800,
                    hiding: true,
                    frame: function (t) {
                        var r = cubicBezierPoint(p, b1, b2, c, t);
                        p.x = r.x;
                        p.y = r.y;
                        motion._fold.call(self, p);
                    },
                    complete: done,
                });
            } else {
                this.animatef(false);
                done();
            }
        },

        turnPage: function (corner) {
            var self = this,
                f = this.data().f,
                data = f.opts.paltau.data(),
                req = {
                    corner: f.corner
                        ? f.corner.corner
                        : corner || motion._cAllowed.call(this)[0],
                },
                from =
                    f.point ||
                    motion._c.call(
                        this,
                        req.corner,
                        f.opts.paltau ? data.opts.elevation : 0
                    ),
                far = motion._c2.call(this, req.corner);

            this.trigger("motion").animatef({
                from: 0,
                to: 1,
                duration: data.opts.duration,
                turning: true,
                frame: function (t) {
                    var r = cubicBezierPoint(from, from, far, far, t);
                    req.x = r.x;
                    req.y = r.y;
                    motion._showFoldedPage.call(self, req);
                },
                complete: function () {
                    self.trigger("end", [f.opts, true]);
                },
            });
            f.corner = null;
        },

        moving: function () {
            return "effect" in this.data();
        },
        isTurning: function () {
            return this.motion("moving") && this.data().effect.turning;
        },
        corner: function () {
            return this.data().f.corner;
        },

        _eventStart: function (e) {
            var f = this.data().f,
                $book = f.opts.paltau;
            if (
                f.corner ||
                f.disabled ||
                this.motion("isTurning") ||
                f.opts.page != $book.data().pagePlace[f.opts.page]
            )
                return;
            f.corner = motion._isIArea.call(this, e);
            if (f.corner && motion._foldingPage.call(this)) {
                this.trigger("pressed", [f.point]);
                motion._showFoldedPage.call(this, f.corner);
                return false;
            }
            f.corner = null;
        },

        _eventMove: function (e) {
            var f = this.data().f;
            
            if (f.disabled) return;
            var list = touch ? e.originalEvent.touches : [e];
            if (f.corner) {
                var off = f.parent.offset();
                f.corner.x = list[0].pageX - off.left;
                f.corner.y = list[0].pageY - off.top;
                motion._showFoldedPage.call(this, f.corner);
            } else if (f.hover && !this.data().effect && this.is(":visible")) {
                
                var a = motion._isIArea.call(this, list[0]);
                if (a) {
                    if (
                        (f.effect === "sheet" && a.corner.length === 2) ||
                        f.effect === "hard"
                    ) {
                        f.status = "hover";
                        var c = motion._c.call(
                            this,
                            a.corner,
                            f.opts.cornerSize / 2
                        );
                        a.x = c.x;
                        a.y = c.y;
                        motion._showFoldedPage.call(this, a, true);
                    }
                } else {
                    
                    // ✅ also handle peel state
                    if (f.status === "hover" || f.status === "peel") {
                        f.status = "";
                        motion.hideFoldedPage.call(this, true);
                    }
                }
            }
        },

        _eventEnd: function () {
            var f = this.data().f,
                c = f.corner;
            if (
                !f.disabled &&
                c &&
                triggerEvent("released", this, [f.point || c]) !== "prevented"
            ) {
                motion.hideFoldedPage.call(this, true);
            } else if (
                f.visible &&
                (f.status === "hover" || f.status === "peel")
            ) {
                f.status = "";
                motion.hideFoldedPage.call(this, true);
            }
            f.corner = null;
        },
        disable: function (v) {
            motion.setData.call(this, { disabled: v });
            return this;
        },
        hover: function (v) {
            motion.setData.call(this, { hover: v });
            return this;
        },

        peel: function (corner, animate) {
            var f = this.data().f;
            if (!corner) {
                f.status = "";
                motion.hideFoldedPage.call(this, animate);
                return this;
            }
            if ($.inArray(corner, cornersMap.all) === -1)
                throw makeErr("Corner " + corner + " is not permitted");
            if ($.inArray(corner, motion._cAllowed.call(this)) !== -1) {
                var cpt = motion._c.call(this, corner, f.opts.cornerSize / 2);
                f.status = "peel";
                motion._showFoldedPage.call(
                    this,
                    { corner: corner, x: cpt.x, y: cpt.y },
                    animate
                );
            }
            return this;
        },
    };

    // requestAnim polyfill
    window.requestAnim =
        window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function (cb) {
            window.setTimeout(cb, 1000 / 60);
        };

    // ---------- jQuery extensions ----------
    $.extend($.fn, {
        motion: function () {
            return invoke($(this[0]), motion, arguments);
        },
        paltau: function () {
            return invoke($(this[0]), book, arguments);
        },
        transform: function (val, origin) {
            var css = {};
            if (origin) css[cssPref + "transform-origin"] = origin;
            css[cssPref + "transform"] = val;
            return this.css(css);
        },
        animatef: function (opt) {
            var d = this.data();
            if (d.effect) d.effect.stop();
            if (opt) {
                if (!opt.to.length) opt.to = [opt.to];
                if (!opt.from.length) opt.from = [opt.from];

                var delta = [],
                    len = opt.to.length,
                    running = true,
                    self = this,
                    start = new Date().getTime();
                var tick = function () {
                    if (d.effect && running) {
                        var frameVals = [],
                            t = Math.min(
                                opt.duration,
                                new Date().getTime() - start
                            );
                        for (var i = 0; i < len; i++)
                            frameVals.push(
                                d.effect.easing(
                                    1,
                                    t,
                                    opt.from[i],
                                    delta[i],
                                    opt.duration
                                )
                            );
                        opt.frame(len === 1 ? frameVals[0] : frameVals);
                        if (t === opt.duration) {
                            delete d.effect;
                            self.data(d);
                            if (opt.complete) opt.complete();
                        } else window.requestAnim(tick);
                    }
                };
                for (var k = 0; k < len; k++)
                    delta.push(opt.to[k] - opt.from[k]);

                d.effect = $.extend(
                    {
                        stop: function () {
                            running = false;
                        },
                        easing: function (_a, t, b, c, dur) {
                            t = t / dur - 1;
                            return c * Math.sqrt(1 - t * t) + b;
                        },
                    },
                    opt
                );
                this.data(d);
                tick();
            } else delete d.effect;
        },
    });

    // public helpers
    $.isTouch = touch;
    $.mouseEvents = mouse;
    $.cssPrefix = cssPrefix;
    $.cssTransitionEnd = function () {
        var el = document.createElement("fakeelement"),
            map = {
                transition: "transitionend",
                OTransition: "oTransitionEnd",
                MSTransition: "transitionend",
                MozTransition: "transitionend",
                WebkitTransition: "webkitTransitionEnd",
            };
        for (var k in map) if (el.style[k] !== undefined) return map[k];
    };
    $.findPos = findPos;
})(jQuery);
