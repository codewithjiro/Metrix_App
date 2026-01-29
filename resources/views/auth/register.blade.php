@extends('layouts.app')

@section('content')
<div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
    <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px]"></div>
    <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-teal-600/10 rounded-full blur-[120px]"></div>
    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
</div>

<div class="h-screen w-full flex flex-col justify-center items-center px-4 relative overflow-hidden">

    <div class="text-center mb-4">
        <div class="inline-flex w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-2xl items-center justify-center font-bold text-xl italic mb-3 shadow-lg shadow-blue-500/30 text-white">
            M
        </div>
        <h2 class="text-3xl font-black tracking-tight text-white mb-1">Create Account</h2>
        <p class="text-xs text-gray-400 font-medium">
            Join the <span class="text-blue-400">Metrix Core</span> today.
        </p>
    </div>

    <div class="w-full max-w-2xl">
        
        <div class="bg-white/[0.03] backdrop-blur-xl border border-white/10 py-6 px-8 shadow-[0_0_40px_rgba(0,0,0,0.3)] rounded-[32px] relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-blue-500/50 to-transparent opacity-50"></div>

            <form action="{{ route('register.store') }}" method="POST" class="space-y-3" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1 mb-1">Full Name</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input name="name" type="text" required 
                                class="w-full bg-slate-900/50 border border-white/5 rounded-2xl py-3 pl-10 pr-4 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all placeholder-gray-600 font-medium text-xs" 
                                placeholder="Enter your full name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1 mb-1">Email Address</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                                </svg>
                            </div>
                            <input name="email" type="email" required 
                                class="w-full bg-slate-900/50 border border-white/5 rounded-2xl py-3 pl-10 pr-4 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all placeholder-gray-600 font-medium text-xs" 
                                placeholder="name@company.com" value="{{ old('email') }}">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1 mb-1">Avatar (Optional)</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500 group-hover:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="file" name="profile_photo" accept="image/*"
                            class="block w-full text-xs text-gray-400
                            file:mr-4 file:py-2 file:px-4 file:ml-10
                            file:rounded-xl file:border-0
                            file:text-[10px] file:font-black file:uppercase file:tracking-widest
                            file:bg-blue-600 file:text-white
                            file:cursor-pointer hover:file:bg-blue-500 file:transition-colors
                            bg-slate-900/50 border border-white/5 rounded-2xl py-1.5 pr-4 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1 mb-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input name="password" type="password" required 
                                class="w-full bg-slate-900/50 border border-white/5 rounded-2xl py-3 pl-10 pr-4 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all placeholder-gray-600 font-medium text-xs" 
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 ml-1 mb-1">Confirm</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-500 group-focus-within:text-blue-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <input name="password_confirmation" type="password" required 
                                class="w-full bg-slate-900/50 border border-white/5 rounded-2xl py-3 pl-10 pr-4 text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500 outline-none transition-all placeholder-gray-600 font-medium text-xs" 
                                placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 text-sm">
                        <span>Register Account</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 space-y-3">
            <p class="text-center text-xs text-gray-500">
                Already operational? 
                <a href="{{ route('login') }}" class="font-bold text-white hover:text-blue-400 transition">Sign in</a>
            </p>

            <div class="relative flex py-1 items-center">
                <div class="flex-grow border-t border-white/10"></div>
            </div>

            <a href="{{ url('/') }}" class="w-full py-3 bg-slate-800/50 hover:bg-slate-800 border border-white/5 text-gray-400 hover:text-white font-bold rounded-2xl transition-all flex items-center justify-center gap-2 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="text-[10px] uppercase tracking-widest">Return to Home</span>
            </a>
        </div>
        
    </div>
</div>
@endsection