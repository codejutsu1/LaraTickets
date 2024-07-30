<x-mail::message>
# LaraTickets

Hello {{ ucwords($user->name) }},

{{ $message }}.

Thanks,<br>
{{ config('app.name') }} Team.
</x-mail::message>
