@component('mail::message')
    <div>
        <div>Name: {{ $contactName }}</div>
        <div>Email: {{ $contactEmail }}</div>
        <div>Company: {{ $contactCompany }}</div>
    </div>
@endcomponent
