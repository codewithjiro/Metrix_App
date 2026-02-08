<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metrix | Intelligence in Motion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; color: #f8fafc; }
        .glass-sidebar { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.05); }
        .nav-link-active { background: linear-gradient(90deg, rgba(59, 130, 246, 0.2), transparent); border-left: 4px solid #3b82f6; color: white; }
        /* Modal Animation Classes */
        .modal-enter { opacity: 0; transform: scale(0.95); }
        .modal-enter-active { opacity: 1; transform: scale(1); transition: opacity 0.2s, transform 0.2s; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="antialiased flex min-h-screen">

    @auth
        @unless(request()->routeIs('login') || request()->routeIs('register.show'))
        <aside class="hidden lg:flex flex-col w-72 glass-sidebar sticky top-0 h-screen z-50">
            <div class="p-8">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-xl italic shadow-lg shadow-blue-500/20">M</div>
                    <span class="text-2xl font-black tracking-tighter text-white">METRIX</span>
                </div>

                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-4 p-4 rounded-xl text-slate-400 hover:text-white transition group {{ request()->routeIs('dashboard') ? 'nav-link-active text-white' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        <span class="font-bold text-sm tracking-wide">Overview</span>
                    </a>
                    
                    {{-- ✅ Hide New Booking for Admins --}}
                    @if(Auth::user()->role != 1)
                    <a href="{{ route('shipments.create') }}" class="flex items-center gap-4 p-4 rounded-xl text-slate-400 hover:text-white transition {{ request()->routeIs('shipments.create') ? 'nav-link-active' : '' }}">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span class="font-bold text-sm tracking-wide">New Booking</span>
                    </a>
                    @endif
                </nav>
            </div>

            <div class="mt-auto p-8 border-t border-white/5">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-blue-400 overflow-hidden relative">
                        @if(Auth::user()->profile_photo)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <p class="text-sm font-bold truncate w-32 text-white">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 font-black uppercase tracking-widest">
                            {{ Auth::user()->role == 1 ? 'Admin' : 'Client' }}
                        </p>
                    </div>
                </div>
                
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
                
                {{-- FIX: Added 'items-center' to align text and button perfectly --}}
                <div class="flex items-center gap-4">
                    <a href="{{ route('profile.edit') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition">
                        Settings
                    </a>
                    
                    <span class="text-slate-700 select-none">|</span>
                    
                    <button type="button" onclick="openLogoutModal()" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition">
                        Logout
                    </button>
                </div>
            </div>
        </aside>
        @endunless
    @endauth

    <div class="flex-1 flex flex-col min-w-0">
        @unless(request()->routeIs('login') || request()->routeIs('register.show'))
        <header class="h-20 flex items-center justify-between px-8 border-b border-white/5 sticky top-0 bg-slate-950/50 backdrop-blur-md z-40">
            <div class="flex items-center gap-4">
                <a href="/" class="lg:hidden flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold italic">M</div>
                </a>
                <h2 class="text-sm font-bold text-slate-400 uppercase tracking-[0.2em] hidden sm:block">Logistics System</h2>
                <div class="w-1 h-1 rounded-full bg-slate-700 hidden sm:block"></div>
                <p class="text-sm font-medium text-slate-500">{{ now()->format('D, M d Y') }}</p>
            </div>
            
            <div class="flex items-center gap-4">
                @guest
                    <a href="{{ route('login') }}" class="text-xs font-bold uppercase tracking-widest text-slate-400 hover:text-white transition">Login</a>
                    <a href="{{ route('register.show') }}" class="px-5 py-2 bg-blue-600 text-white text-xs font-bold rounded-full hover:bg-blue-500 transition">Join</a>
                @else
                @endguest
            </div>
        </header>
        @endunless

        <main class="{{ (request()->routeIs('login') || request()->routeIs('register.show')) ? 'p-0' : 'p-8' }}">
            @yield('content')
        </main>
    </div>

    <div id="logoutModal" class="hidden fixed inset-0 z-[100] items-center justify-center bg-black/70 backdrop-blur-sm transition-opacity duration-300">
        <div class="bg-slate-900 border border-white/10 p-6 rounded-2xl shadow-2xl w-full max-w-sm relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-600/20 blur-3xl rounded-full pointer-events-none"></div>
            
            <h3 class="text-lg font-bold text-white mb-2">Sign Out?</h3>
            <p class="text-sm text-slate-400 mb-6 leading-relaxed">
                Are you sure you want to end your session? You will need to login again to access your dashboard.
            </p>
            
            <div class="flex items-center gap-3">
                <button onclick="closeLogoutModal()" class="flex-1 py-2.5 rounded-lg border border-white/10 text-xs font-bold uppercase tracking-wider text-slate-300 hover:bg-white/5 transition">
                    Cancel
                </button>
                
                <button onclick="document.getElementById('logout-form').submit()" class="flex-1 py-2.5 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-xs font-bold uppercase tracking-wider hover:bg-red-500 hover:text-white transition">
                    Logout
                </button>
            </div>
        </div>
    </div>
    <script>
        function openLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.firstElementChild.classList.add('modal-enter-active');
                modal.firstElementChild.classList.remove('modal-enter');
            }, 10);
        }

        function closeLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        document.getElementById('logoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLogoutModal();
            }
        });
    </script>

</body>
</html>