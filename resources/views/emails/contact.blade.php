@component('mail::message')
    <div>
        <div class="message-info">
            <div><span>Name:</span> {{ $contactName }}</div>
            <div><span>Email:</span> {{ $contactEmail }}</div>
            <div><span>Company:</span> {{ $contactCompany }}</div>
        </div>
        <div class="message-body">{!! nl2br($contactMessage) !!}</div>
    </div>
@endcomponent
