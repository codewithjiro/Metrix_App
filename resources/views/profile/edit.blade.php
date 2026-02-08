@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto relative">
    
    {{-- SUCCESS DIALOG (Toast Notification) --}}
    @if(session('success'))
        <div id="success-dialog" class="fixed top-24 right-8 z-[100] flex items-center gap-4 bg-slate-800 border border-emerald-500/30 p-4 rounded-2xl shadow-2xl shadow-emerald-900/20 transform transition-all duration-500 translate-y-0 opacity-100">
            <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h4 class="text-white font-bold text-sm">Success!</h4>
                <p class="text-slate-400 text-xs font-medium">{{ session('success') }}</p>
            </div>
            <button onclick="closeDialog()" class="ml-4 text-slate-500 hover:text-white transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    <div class="mb-10">
        <h1 class="text-4xl font-black text-white tracking-tight mb-2">Profile Settings</h1>
        <p class="text-slate-400 font-medium">Manage your account information and security.</p>
    </div>

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm font-bold">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @csrf
        @method('PATCH')

        {{-- Left Column: Photo --}}
        <div class="md:col-span-1">
            <div class="bg-slate-900/60 border border-white/5 rounded-[32px] p-6 text-center sticky top-24">
                <div class="relative w-32 h-32 mx-auto mb-4 group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-slate-800 bg-slate-800 relative">
                        {{-- Image Preview Logic --}}
                        <img id="preview-image" src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : '' }}" 
                             alt="Profile" 
                             class="w-full h-full object-cover {{ Auth::user()->profile_photo ? '' : 'hidden' }}">
                        
                        {{-- Default Initial Placeholder if no photo --}}
                        <div id="initial-placeholder" class="w-full h-full flex items-center justify-center bg-blue-600 text-white text-4xl font-bold {{ Auth::user()->profile_photo ? 'hidden' : '' }}">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    </div>
                    
                    {{-- Overlay for upload --}}
                    <label for="photo-upload" class="absolute inset-0 flex items-center justify-center bg-black/50 rounded-full opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity backdrop-blur-sm z-10">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                            <path stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <input type="file" id="photo-upload" name="photo" class="hidden" accept="image/*" onchange="previewFile(this)">
                    </label>
                </div>
                
                <p class="text-white font-bold text-lg mb-1">{{ Auth::user()->name }}</p>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-4">{{ Auth::user()->role == 1 ? 'Administrator' : 'Client Account' }}</p>
                <p id="photo-preview-text" class="text-xs text-blue-400 font-medium h-4"></p>
            </div>
        </div>

        {{-- Right Column: Form --}}
        <div class="md:col-span-2 space-y-6">
            
            {{-- Account Info --}}
            <div class="bg-slate-900/60 border border-white/5 rounded-[32px] p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </span>
                    Account Details
                </h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full bg-slate-950 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full bg-slate-950 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition">
                    </div>
                </div>
            </div>

            {{-- Security --}}
            <div class="bg-slate-900/60 border border-white/5 rounded-[32px] p-8">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                    <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </span>
                    Security Change
                </h3>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-950/50 border border-white/5 mb-4">
                        <p class="text-xs text-slate-400 leading-relaxed">
                            <strong class="text-white">Note:</strong> Only fill these fields if you want to change your password. You must provide your current password to authorize this change.
                        </p>
                    </div>

                    <div>
                        <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Current Password</label>
                        <input type="password" name="current_password" placeholder="Required for password changes" class="w-full bg-slate-950 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">New Password</label>
                            <input type="password" name="new_password" class="w-full bg-slate-950 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-slate-400 text-xs font-bold uppercase tracking-wider mb-2">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="w-full bg-slate-950 border border-white/10 rounded-xl p-3 text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 transition">
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition-all transform hover:-translate-y-1">
                    Save Changes
                </button>
            </div>

        </div>
    </form>
</div>

{{-- Scripts for Preview & Dialog --}}
<script>
    // 1. Image Preview Logic
    function previewFile(input) {
        var file = input.files[0];
        if(file){
            var reader = new FileReader();
            reader.onload = function(){
                // Hide the text/initial placeholder
                document.getElementById('initial-placeholder').classList.add('hidden');
                
                // Show the image and set src
                var img = document.getElementById('preview-image');
                img.classList.remove('hidden');
                img.src = reader.result;

                // Update text indicator
                document.getElementById('photo-preview-text').innerText = "New Image Selected";
            };
            reader.readAsDataURL(file);
        }
    }

    // 2. Success Dialog Logic (Auto Close)
    function closeDialog() {
        const dialog = document.getElementById('success-dialog');
        if(dialog) {
            dialog.style.opacity = '0';
            dialog.style.transform = 'translateY(-20px)';
            setTimeout(() => {
                dialog.remove();
            }, 500);
        }
    }

    // Auto-hide after 4 seconds
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(closeDialog, 4000);
    });
</script>
@endsection