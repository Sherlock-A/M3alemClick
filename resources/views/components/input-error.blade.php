@props(['messages' => null, 'field' => null])

@php
    $errors = [];
    if ($messages instanceof \Illuminate\Support\MessageBag) {
        $errors = $field ? $messages->get($field) : $messages->all();
    } elseif (is_array($messages)) {
        $errors = $messages;
    } elseif (is_string($messages)) {
        $errors = [$messages];
    }
@endphp

@if(count($errors))
    <ul class="mt-1 space-y-0.5">
        @foreach($errors as $error)
        <li class="text-red-500 text-xs flex items-center space-x-1">
            <span>•</span>
            <span>{{ $error }}</span>
        </li>
        @endforeach
    </ul>
@endif
