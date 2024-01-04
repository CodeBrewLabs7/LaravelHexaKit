@extends('auth::layouts.app')

@php
    $pageTitle = 'Login';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <h2>Login</h2>

            <form method="POST" action="{{ route('login.user') }}">
                @csrf

                <label for="email">Email:</label>
                <input type="email" name="email" id="email">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <label for="password">Password:</label>
                <input type="password" name="password" id="password">

                <button type="submit">Login</button>
            </form>
        </div>
    </div>
@endsection
