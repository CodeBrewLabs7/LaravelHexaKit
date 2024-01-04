@extends('auth::layouts.app')

@php
    $pageTitle = 'Vendor Register';
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <h2>Vendor Register</h2>
            
            <form method="POST" action="{{ route('create.user') }}">
                @csrf
                <input type="hidden" name="type" value="vendor">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <label for="email">Email:</label>
                <input type="email" name="email" id="email">
                @error('email')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <label for="phone">Phone Number:</label>
                <input type="tel" name="phone_number" id="phone">
                @error('phone')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <label for="password">Password:</label>
                <input type="password" name="password" id="password">
                @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation">
                @error('password_confirmation')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror

                <button type="submit">Register</button>
            </form>
        </div>
    </div>
@endsection
