@extends('layouts.app')

@php
    $pageTitle = 'My Profile';
    $pageDescription = 'Update your account information and settings.';
@endphp

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">My Profile</h1>
        <p class="text-gray-600">Update your account information and settings.</p>
    </div>

    @if(session('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('message') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('dashboard.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Personal Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', Auth::user()->name) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               required>
                        @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">We'll use this email to notify you about your posts</p>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="+1 (555) 123-4567">
                        @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Optional: We'll use this for urgent notifications</p>
                    </div>

                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', Auth::user()->date_of_birth) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('date_of_birth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        <p class="text-xs text-gray-500 mt-1">Optional: Used for age verification</p>
                    </div>

                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <select id="gender" name="gender"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', Auth::user()->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', Auth::user()->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', Auth::user()->gender) == 'other' ? 'selected' : '' }}>Other</option>
                            <option value="prefer_not_to_say" {{ old('gender', Auth::user()->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="occupation" class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                        <input type="text" id="occupation" name="occupation" value="{{ old('occupation', Auth::user()->occupation) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="e.g. Software Developer, Student">
                        @error('occupation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Bio Section -->
                <div class="mt-6">
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-2">Bio / About Me</label>
                    <textarea id="bio" name="bio" rows="4"
                              class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                              placeholder="Tell us a bit about yourself...">{{ old('bio', Auth::user()->bio) }}</textarea>
                    @error('bio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Optional: Share a brief description about yourself</p>
                </div>
            </div>

            <!-- Address Information -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Address Information
                </h2>

                <div class="space-y-6">
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Street Address</label>
                        <input type="text" id="address" name="address" value="{{ old('address', Auth::user()->address) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="123 Main Street">
                        @error('address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" id="city" name="city" value="{{ old('city', Auth::user()->city) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="New York">
                            @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State/Province</label>
                            <input type="text" id="state" name="state" value="{{ old('state', Auth::user()->state) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="NY">
                            @error('state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-2">ZIP/Postal Code</label>
                            <input type="text" id="zip_code" name="zip_code" value="{{ old('zip_code', Auth::user()->zip_code) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   placeholder="10001">
                            @error('zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <input type="text" id="country" name="country" value="{{ old('country', Auth::user()->country ?? 'United States') }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="United States">
                        @error('country') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Verification Status -->
            <div class="mb-8 p-6 rounded-lg {{ Auth::user()->isVerified() ? 'bg-green-50 border border-green-200' : (Auth::user()->isPendingVerification() ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') }}">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium {{ Auth::user()->isVerified() ? 'text-green-800' : (Auth::user()->isPendingVerification() ? 'text-yellow-800' : 'text-red-800') }}">
                        Account Verification
                    </h3>
                    @if(!Auth::user()->isVerified())
                        <a href="{{ route('dashboard.verification') }}" class="text-sm font-medium {{ Auth::user()->isPendingVerification() ? 'text-yellow-600 hover:text-yellow-800' : 'text-red-600 hover:text-red-800' }}">
                            {{ Auth::user()->isPendingVerification() ? 'Check Status' : 'Get Verified' }} →
                        </a>
                    @endif
                </div>

                <div class="flex items-center">
                    @if(Auth::user()->isVerified())
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-green-800">Account Verified</p>
                            <p class="text-sm text-green-600">Verified on {{ Auth::user()->verified_at?->format('F j, Y') }}</p>
                        </div>
                    @elseif(Auth::user()->isPendingVerification())
                        <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-yellow-800">Verification Pending</p>
                            <p class="text-sm text-yellow-600">Review in progress (24-48 hours)</p>
                        </div>
                    @else
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-red-800">Account Not Verified</p>
                            <p class="text-sm text-red-600">Complete verification to unlock all features</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="mb-8 p-6 bg-gray-50 rounded-lg">
                <h3 class="text-lg font-medium text-gray-800 mb-4">Account Statistics</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600">{{ $postsCount }}</div>
                        <div class="text-sm text-gray-600">Total Posts</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-yellow-600">{{ $pendingPosts }}</div>
                        <div class="text-sm text-gray-600">Pending</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">{{ $approvedPosts }}</div>
                        <div class="text-sm text-gray-600">Approved</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-600">{{ $rejectedPosts }}</div>
                        <div class="text-sm text-gray-600">Rejected</div>
                    </div>
                </div>
            </div>

            <!-- Account Information (Read-only) -->
            <div class="mb-8 p-6 bg-blue-50 rounded-lg">
                <h3 class="text-lg font-medium text-blue-800 mb-4">Account Information</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700">Member Since:</span>
                        <span class="text-sm font-medium text-blue-800">{{ Auth::user()->created_at->format('F j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700">Last Updated:</span>
                        <span class="text-sm font-medium text-blue-800">{{ Auth::user()->updated_at->format('F j, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-blue-700">Account Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between items-center">
                <a href="{{ route('dashboard.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    ← Back to Dashboard
                </a>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition duration-200 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="bg-white rounded-lg shadow-md p-6 mt-6 border-l-4 border-red-500">
        <h3 class="text-lg font-medium text-red-800 mb-4">Danger Zone</h3>
        <p class="text-red-700 mb-4">
            Once you delete your account, there is no going back. Please be certain.
        </p>
        <button onclick="confirmAccountDeletion()"
                class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded font-medium transition duration-200">
            Delete Account
        </button>
    </div>
</div>

<script>
function confirmAccountDeletion() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone and all your posts will be permanently removed.')) {
        // In a real application, you would make an AJAX call to delete the account
        alert('Account deletion is not implemented in this demo. Please contact support if you need to delete your account.');
    }
}
</script>
@endsection
