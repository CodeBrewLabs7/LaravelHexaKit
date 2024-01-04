@extends('auth::layouts.app')

@php
    $pageTitle = 'Change Password';
@endphp

@section('content')
    <div class="container">
        <div class="card change-password-form">
            <h2>Change Password</h2>

            <form method="POST" action="{{ route('user.setpassword') }}">
                @csrf

                <label for="current_password">Current Password:</label>
                <input type="password" name="current_password" id="current_password" required>

                <label for="new_password">New Password:</label>
                <input type="password" name="new_password" id="new_password" required>

                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>

                <button type="submit">Change Password</button>
            </form>
        </div>
    </div>
@endsection
