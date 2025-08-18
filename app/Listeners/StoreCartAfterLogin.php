<?php

namespace App\Listeners;

use App\Events\UserLoggedInWithCart;
use App\Models\Cart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreCartAfterLogin
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\UserLoggedInWithCart  $event
     * @return void
     */
    public function handle(UserLoggedInWithCart $event)
    {
          
        foreach ($event->cartItems as $item) {
            Cart::create([
                'user_id' => $event->user->id,
                'book_id' => $item->id,
                'quantity' => $item->qty,
            ]);
        }
    }
}
