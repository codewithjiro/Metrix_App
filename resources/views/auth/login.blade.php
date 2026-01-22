@extends('layouts.app')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 px-6">
    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <div class="inline-flex w-12 h-12 bg-blue-600 rounded-xl items-center justify-center font-bold text-xl italic mb-4 shadow-lg shadow-blue-500/20">M</div>
        <h2 class="text-3xl font-extrabold tracking-tight text-white">Welcome Back</h2>
        <p class="mt-2 text-sm text-gray-400">
            Don't have an account? 
            <a href="{{ route('register.show') }}" class="font-semibold text-blue-400 hover:text-blue-300 transition">Create one for free</a>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/50 backdrop-blur-xl border border-white/10 py-10 px-8 shadow-2xl rounded-[32px]">
            <form class="space-y-6" action="{{ route('login') }}" method="POST">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 ml-1 mb-2">Email Address</label>
                    <input name="email" type="email" required 
                        class="w-full bg-slate-800 border-none rounded-2xl p-4 text-white focus:ring-2 focus:ring-blue-600 outline-none transition placeholder-gray-500" 
                        placeholder="name@company.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between ml-1 mb-2">
                        <label class="text-xs font-bold uppercase tracking-widest text-gray-500">Password</label>
                    </div>
                    <input name="password" type="password" required 
                        class="w-full bg-slate-800 border-none rounded-2xl p-4 text-white focus:ring-2 focus:ring-blue-600 outline-none transition placeholder-gray-500" 
                        placeholder="••••••••">
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                    Sign In to Dashboard
                </button>
            </form>
        </div>
    </div>
</div>
@endsection