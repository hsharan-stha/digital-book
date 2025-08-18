<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>パスワードリセット</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0; padding: 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background-color: #2d3748; padding: 20px; text-align: center;">
                            <h1 style="color: #ffffff; margin: 0; font-size: 24px;">専門教育出版 Digital Book</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">こんにちは {{ $user->name }} さん</p>
                            <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                                パスワードのリセットリクエストを受け付けました。下記のボタンをクリックして、新しいパスワードを設定してください。
                            </p>
                            <p style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}" 
                                   style="background-color: #2d3748; color: #ffffff; padding: 12px 24px; 
                                          text-decoration: none; font-size: 16px; border-radius: 4px; 
                                          display: inline-block;">
                                    パスワードをリセットする
                                </a>
                            </p>
                            <p style="font-size: 14px; color: #555555; margin-top: 20px;">
                                このリンクは60分で無効になります。もしこのメールに心当たりがない場合は無視してください。
                            </p>
                            <p style="font-size: 16px; color: #333333; margin-top: 30px;">
                                よろしくお願いいたします。<br>DigitalBook
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #f1f1f1; text-align: center; padding: 15px; font-size: 12px; color: #777777;">
                            © 2025 DigitalBook. 無断転載を禁じます。
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
