<x-mail::message>
{{-- El cuerpo del correo se pasa desde el Mailable --}}
<x-mail::panel>
{{ $body }}
</x-mail::panel>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
