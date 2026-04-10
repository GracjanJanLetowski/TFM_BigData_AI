@component('mail::message')
# Nuevo Pedido Realizado

Se ha generado un nuevo pedido en la tienda.

**ID del Pedido:** {{ $order->id }}

@component('mail::panel')
**Detalles del Pedido:**

- **Usuario:** {{ $order->user->name ?? 'N/A' }} ({{ $order->user->email ?? 'N/A' }})
- **Fecha:** {{ $order->created_at->format('d/m/Y H:i') }}
- **Total:** ${{ number_format($order->total, 2) }}
@endcomponent

Por favor, revisa el panel de administración para obtener más detalles.

Saludos,

{{ config('app.name') }}
@endcomponent
