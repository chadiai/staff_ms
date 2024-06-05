@extends('errors::minimal2')

@section('title', __('Not Found'))
@section('code', '404')
@section('message', __('Not Found'))

@push('script')
    <script>
        // remove the last div inside the nav tag
        document.querySelector('nav').remove();
        document.querySelector('button').remove();
        document.querySelector('aside').style.display = 'none';

    </script>
@endpush

