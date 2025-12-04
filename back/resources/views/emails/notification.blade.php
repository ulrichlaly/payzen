@component('mail::message')
    # {{ $notification->titre }}

    {{ $notification->message }}

    @if (isset($notification->data['action_url']))
        @component('mail::button', ['url' => url($notification->data['action_url'])])
            Voir les détails
        @endcomponent
    @endif

    Cordialement,<br>
    {{ config('app.name') }}

    ---
    <small>Vous recevez cet email car une action nécessite votre attention.</small>
@endcomponent
