@extends('layouts.app')

@section('content')
<div class="min-h-[85vh] flex items-center justify-center px-6 relative">
    <div class="absolute w-64 h-64 bg-blue-600/20 blur-[100px] rounded-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2"></div>

    <div class="w-full max-w-lg z-10">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-black tracking-tighter text-white mb-2">Create Account</h2>
            <p class="text-slate-400 font-medium">Join Metrix and start shipping today.</p>
        </div>

        <div class="bg-slate-900/40 backdrop-blur-2xl border border-white/10 p-8 md:p-10 rounded-[40px] shadow-2xl">

            {{-- ✅ GLOBAL ERROR HANDLER --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-300 p-4 rounded-2xl">
                    <p class="text-xs font-black uppercase tracking-widest text-red-300 mb-2">Please fix the following:</p>
                    <ul class="text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <div class="group">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 ml-4 mb-2 group-focus-within:text-blue-400 transition-colors">
                        Full Name
                    </label>
                    <input name="name" type="text" required
                           class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50 transition-all placeholder-slate-600"
                           placeholder="John Doe" value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-2 text-xs font-bold text-red-400 ml-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="group">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 ml-4 mb-2 group-focus-within:text-blue-400 transition-colors">
                        Email Address
                    </label>
                    <input name="email" type="email" required
                           class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50 transition-all placeholder-slate-600"
                           placeholder="john@example.com" value="{{ old('email') }}">
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-400 ml-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ✅ PROFILE PICTURE + ERROR --}}
                <div class="group">
                    <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 ml-4 mb-2">
                        Profile Picture (Optional)
                    </label>

                    <input type="file" name="profile_photo" accept="image/*"
                           class="block w-full text-sm text-slate-400
                           file:mr-4 file:py-3 file:px-6
                           file:rounded-2xl file:border-0
                           file:text-xs file:font-black file:uppercase file:tracking-widest
                           file:bg-blue-600 file:text-white
                           hover:file:bg-blue-500
                           cursor-pointer bg-slate-800/50 border border-white/5 rounded-2xl">

                    <p class="mt-2 text-[11px] text-slate-500 ml-2">
                        Allowed: JPG/PNG/GIF — Max 2MB
                    </p>

                    @error('profile_photo')
                        <p class="mt-2 text-xs font-bold text-red-400 ml-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="group">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 ml-4 mb-2 group-focus-within:text-blue-400 transition-colors">
                            Password
                        </label>
                        <input name="password" type="password" required
                               class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50 transition-all placeholder-slate-600"
                               placeholder="••••••••">
                        @error('password')
                            <p class="mt-2 text-xs font-bold text-red-400 ml-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="group">
                        <label class="block text-xs font-bold uppercase tracking-widest text-slate-500 ml-4 mb-2 group-focus-within:text-blue-400 transition-colors">
                            Confirm
                        </label>
                        <input name="password_confirmation" type="password" required
                               class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600/50 transition-all placeholder-slate-600"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                            class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl shadow-lg shadow-blue-600/30 transition-all hover:-translate-y-1 active:scale-[0.98]">
                        Create My Account
                    </button>
                </div>

                <p class="text-center text-sm text-slate-500 mt-6">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-400 font-bold hover:text-blue-300 transition-colors">Sign in here</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection
