@props(['action', 'method'])

<form {{ $attributes->merge(['method' => 'POST']) }}>
    @csrf
    @if (!in_array(strtoupper($attributes->get('method', 'GET')), ['GET', 'POST']))
        @method($attributes->get('method'))
    @endif

    {{ $slot }}
</form>