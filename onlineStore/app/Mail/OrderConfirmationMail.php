<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order; 

    /**
     * Crea una nueva instancia del mensaje.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Construye el mensaje.
     */
    public function build()
    {
        return $this->subject('¡Gracias por tu compra!')
                    ->markdown('emails.orders.confirmation');
    }
}
