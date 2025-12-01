@extends('layouts.app')

@php
    $pageTitle = 'Account Verification';
    $pageDescription = 'Verify your account to unlock all features.';
@endphp

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Account Verification</h1>
        <p class="text-gray-600">Verify your identity to unlock premium features and build trust with other users.</p>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    <!-- Verification Status -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Verification Status</h2>
            @if($user->isVerified())
                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">✓ Verified</span>
            @elseif($user->isPendingVerification())
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">⏳ Pending Review</span>
            @elseif($user->isRejected())
                <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">✗ Rejected</span>
            @else
                <span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-medium">Unverified</span>
            @endif
        </div>

        <div class="space-y-3">
            @if($user->isVerified())
                <div class="flex items-center text-green-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Your account has been verified successfully!</span>
                </div>
                <p class="text-sm text-gray-600 ml-7">Verified on {{ $user->verified_at?->format('F j, Y') }}</p>
            @elseif($user->isPendingVerification())
                <div class="flex items-center text-yellow-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>Your verification documents are being reviewed.</span>
                </div>
                <p class="text-sm text-gray-600 ml-7">We'll notify you once the review is complete (usually within 24-48 hours).</p>
            @elseif($user->isRejected())
                <div class="flex items-center text-red-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Verification was rejected.</span>
                </div>
                @if($user->verification_notes)
                    <p class="text-sm text-red-600 ml-7">Reason: {{ $user->verification_notes }}</p>
                @endif
                <p class="text-sm text-gray-600 ml-7">You can resubmit your documents for review.</p>
            @else
                <div class="flex items-center text-gray-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <span>Your account is not verified yet.</span>
                </div>
                <p class="text-sm text-gray-600 ml-7">Complete the verification process below to get verified.</p>
            @endif
        </div>
    </div>

    <!-- Verification Benefits -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Why Verify Your Account?</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <span class="text-blue-600 font-bold">✓</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800">Build Trust</h4>
                    <p class="text-sm text-gray-600">Verified badge shows other users you are legitimate</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <span class="text-green-600 font-bold">★</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800">Premium Features</h4>
                    <p class="text-sm text-gray-600">Access to advanced posting features and analytics</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <span class="text-purple-600 font-bold">🛡️</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800">Account Protection</h4>
                    <p class="text-sm text-gray-600">Enhanced security and recovery options</p>
                </div>
            </div>
            <div class="flex items-start">
                <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3 flex-shrink-0">
                    <span class="text-orange-600 font-bold">📈</span>
                </div>
                <div>
                    <h4 class="font-medium text-gray-800">Better Visibility</h4>
                    <p class="text-sm text-gray-600">Verified posts appear higher in search results</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Form (only show if not verified or rejected) -->
    @if(!$user->isVerified() || $user->isRejected())
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Submit Verification Documents</h3>

            @if($user->verification_status === 'pending')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="font-medium text-yellow-800">Verification In Progress</h4>
                            <p class="text-yellow-700 text-sm">Your documents are currently being reviewed. Please wait for our team to complete the verification process.</p>
                        </div>
                    </div>
                </div>
            @else
                <form action="{{ route('dashboard.verification.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="space-y-6">
                        <!-- ID Document Upload -->
                        <div>
                            <label for="id_document" class="block text-sm font-medium text-gray-700 mb-2">
                                Government ID Document *
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="id_document" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload ID document</span>
                                            <input id="id_document" name="id_document" type="file" accept="image/*,.pdf" required class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF up to 5MB</p>
                                </div>
                            </div>
                            @error('id_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selfie Upload -->
                        <div>
                            <label for="selfie" class="block text-sm font-medium text-gray-700 mb-2">
                                Selfie with ID *
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="selfie" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload selfie</span>
                                            <input id="selfie" name="selfie" type="file" accept="image/*" required class="sr-only">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 5MB</p>
                                </div>
                            </div>
                            @error('selfie')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Instructions -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h4 class="text-sm font-medium text-blue-800">Verification Requirements</h4>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>ID document must be clear and readable</li>
                                            <li>Selfie must show your face clearly holding the ID</li>
                                            <li>Both documents are kept secure and private</li>
                                            <li>Review typically takes 24-48 hours</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Submit for Verification
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    @endif
</div>
@endsection
