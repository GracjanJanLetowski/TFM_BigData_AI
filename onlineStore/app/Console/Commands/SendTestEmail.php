<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail; 
use App\Models\Order;

class SendTestEmail extends Command
{
    protected $signature = 'send:test-email';
    protected $description = 'Enviar un correo de prueba';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $order = Order::first() ?? new Order([ 
            'id' => 123,
            'total' => 150.00,
            'created_at' => now(),
            'user' => (object) ['name' => 'Gracjan', 'email' => 'gracjan@ejemplo.com'],
        ]);

        Mail::to('gracjan@ejemplo.com')->send(new OrderConfirmationMail($order));

        $this->info('Correo de prueba enviado.');
    }
}
