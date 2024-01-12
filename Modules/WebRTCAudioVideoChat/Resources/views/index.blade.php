@extends('webrtcaudiovideochat::layouts.master')
@section('css')
@parent
    <link href="{{ asset('modules/webrtcaudiovideochat/css/webrtcaudiovideochat.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('modules/webrtcaudiovideochat/js/app.js') }}"></script>
@endsection

@section('content')


    <div class="app">
        @include('webrtcaudiovideochat::chat-parts.header.index')
        <div class="wrapper">
            @include('webrtcaudiovideochat::chat-parts.left-bar.index')

            <div class="chat-area right">
                @include('webrtcaudiovideochat::chat-parts.right-content.header')
                @include('webrtcaudiovideochat::chat-parts.right-content.main')
                @include('webrtcaudiovideochat::chat-parts.right-content.footer')
                
            </div>
            <div class="detail-area">
                @include('webrtcaudiovideochat::chat-parts.right-content.settings')
               
            </div>
        </div>
    </div>

@endsection

@section('js')

    <script>
        const toggleButton = document.querySelector('.dark-light');
        const colors = document.querySelectorAll('.color');

        colors.forEach(color => {
        color.addEventListener('click', (e) => {
            colors.forEach(c => c.classList.remove('selected'));
            const theme = color.getAttribute('data-color');
            document.body.setAttribute('data-theme', theme);
            color.classList.add('selected');
        });
        });

        toggleButton.addEventListener('click', () => {
        document.body.classList.toggle('dark-mode');
        });
    </script>

@endsection
