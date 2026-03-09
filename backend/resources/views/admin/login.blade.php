@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-[#1a56db] to-blue-900 flex items-center justify-center px-4">
        <div class="w-full max-w-md">
            {{-- Card --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8">
                {{-- Logo --}}
                <div class="text-center mb-8">
                    <div
                        class="w-16 h-16 bg-[#1a56db] rounded-2xl flex items-center justify-center text-white font-bold text-3xl mx-auto mb-4 shadow-lg">
                        O</div>
                    <h1 class="text-2xl font-bold text-gray-900">Admin Panel</h1>
                    <p class="text-gray-500 text-sm mt-1">Oromo Special Zone Administration</p>
                </div>

                {{-- Errors --}}
                @if($errors->any())
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                        {{ $errors->first() }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Form --}}
                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1a56db] focus:border-transparent transition text-sm @error('email') border-red-400 @enderror"
                            placeholder="admin@osza.gov.et">
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#1a56db] focus:border-transparent transition text-sm"
                            placeholder="••••••••">
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember"
                            class="w-4 h-4 text-[#1a56db] rounded border-gray-300 focus:ring-[#1a56db]">
                        <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
                    </div>
                    <button type="submit"
                        class="w-full bg-[#1a56db] hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-all duration-200 shadow-lg hover:shadow-blue-200 text-sm tracking-wide">
                        Sign In to Admin Panel
                    </button>
                </form>

                {{-- Back link --}}
                <p class="text-center mt-6 text-sm text-gray-400">
                    <a href="{{ route('home') }}" class="hover:text-[#1a56db] transition-colors">← Back to main website</a>
                </p>
            </div>
        </div>
    </div>
@endsection