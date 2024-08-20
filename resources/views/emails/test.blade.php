<x-mail::message>
# Ez egy teszt email

Ez a levél szövege.

<x-mail::button :url="''">
Itt lehet egy gomb
</x-mail::button>

Köszönjük,<br>
{{ config('app.name') }}
</x-mail::message>
