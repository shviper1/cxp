@extends('layouts.app')

@php
    $pageTitle = 'Register';
    $pageDescription = 'Create your account to manage posts and get better features.';
@endphp

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-500">
                    sign in to existing account
                </a>
            </p>
        </div>

        <form class="mt-8 space-y-6" action="{{ route('register') }}" method="POST">
            @csrf

            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name *</label>
                    <input id="name" name="name" type="text" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Your full name" value="{{ old('name') }}">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="your.email@example.com" value="{{ old('email') }}">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input id="phone" name="phone" type="tel"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="+1 (555) 123-4567" value="{{ old('phone') }}">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password *</label>
                    <input id="password" name="password" type="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Create a strong password">
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password *</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           placeholder="Confirm your password">
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </span>
                    Create Account
                </button>
            </div>

            <div class="text-center">
                <p class="text-sm text-gray-600">
                    By creating an account, you agree to our
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Terms of Service</a>
                    and
                    <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
