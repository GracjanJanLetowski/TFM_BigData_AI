@component('mail::message')
# Confirmación de Pedido

Hola **{{ $order->user->name ?? 'Cliente' }}**,

Gracias por tu compra. Hemos recibido tu pedido con el número **#{{ $order->id }}** y ya estamos procesándolo.

@component('mail::panel')
**Detalles del Pedido:**

- **Fecha:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Total:** ${{ number_format($order->total, 2) }}
@endcomponent

Si tienes alguna pregunta, no dudes en contactarnos en [soporte@tuapp.com](mailto:soporte@tuapp.com).

¡Gracias por confiar en nosotros!

{{ config('app.name') }}
@endcomponent
