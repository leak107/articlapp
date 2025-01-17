<?php

namespace App\Listeners;

use App\Events\TransactionCreated;
use App\Mail\CustomerTransactionEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendTransactionEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransactionCreated $event): void
    {
        dispatch(function () use ($event) {
            Mail::to($event->customer->email)->send(new CustomerTransactionEmail(
                customer: $event->customer,
                product: $event->product,
                quantity: $event->quantity,
            ));
        })->delay(2);
    }
}
