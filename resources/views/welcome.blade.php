<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metrix | Intelligence in Motion</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #020617; }
        .glass { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .gradient-text { background: linear-gradient(90deg, #3b82f6, #2dd4bf); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .bg-grid { background-image: radial-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px); background-size: 30px 30px; }
        
        /* Custom CSS to make Google Maps look like Dark Mode/Sci-Fi */
        .map-filter {
            filter: grayscale(100%) invert(92%) contrast(83%);
            mix-blend-mode: luminosity;
        }
    </style>
</head>
<body class="bg-slate-950 text-white antialiased bg-grid">

    <nav class="fixed w-full z-50 glass top-0">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center font-bold text-xl italic shadow-lg shadow-blue-500/20">M</div>
                <span class="text-2xl font-extrabold tracking-tight">METRIX</span>
            </div>
            <div class="hidden md:flex items-center gap-10 text-sm font-bold uppercase tracking-widest text-gray-400">
                <a href="#about" class="hover:text-blue-400 transition">About</a>
                <a href="#tracking" class="hover:text-blue-400 transition">Fleet</a>
                <a href="#services" class="hover:text-blue-400 transition">Services</a>
                <a href="#faq" class="hover:text-blue-400 transition">FAQ</a>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-full font-bold transition text-sm shadow-lg shadow-blue-600/20">
                        {{ Auth::user()->role == 1 ? 'Admin Control' : 'Client Dashboard' }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold hover:text-blue-400 px-4">Sign In</a>
                    <a href="{{ route('register.show') }}" class="px-6 py-2 bg-white text-black hover:bg-gray-200 rounded-full font-bold transition text-sm">Join Metrix</a>
                @endauth
            </div>
        </div>
    </nav>

    <section id="about" class="relative pt-52 pb-32 overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[600px] bg-blue-600/20 blur-[120px] rounded-full opacity-30"></div>
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <h1 class="text-6xl md:text-8xl font-black mb-8 tracking-tighter leading-none">
                Logistics for the <br> <span class="gradient-text">Digital Era.</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-12 font-medium">
                Hyper-fast delivery management. Track, manage, and scale your global logistics with our AI-integrated platform.
            </p>
            <div class="flex flex-col md:flex-row items-center justify-center gap-6">
                <a href="{{ route('register.show') }}" class="w-full md:w-auto px-10 py-5 bg-blue-600 rounded-2xl text-lg font-black hover:scale-105 transition-all shadow-[0_0_40px_rgba(37,99,235,0.3)] uppercase tracking-widest">Get Started</a>
                <button class="w-full md:w-auto px-10 py-5 glass rounded-2xl text-lg font-black hover:bg-white/10 transition uppercase tracking-widest">Track ID</button>
            </div>
            
            <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
                <div class="glass p-8 rounded-[32px]">
                    <div class="text-3xl font-black mb-1">99.9%</div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Uptime Rate</div>
                </div>
                <div class="glass p-8 rounded-[32px]">
                    <div class="text-3xl font-black mb-1">2M+</div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Parcels Shipped</div>
                </div>
                <div class="glass p-8 rounded-[32px]">
                    <div class="text-3xl font-black mb-1">150+</div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Active Hubs</div>
                </div>
                <div class="glass p-8 rounded-[32px]">
                    <div class="text-3xl font-black mb-1">98.2%</div>
                    <div class="text-[10px] uppercase font-bold text-gray-500 tracking-widest">Efficiency</div>
                </div>
            </div>
        </div>
    </section>

    <section id="tracking" class="py-32 bg-slate-900/20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="glass p-12 rounded-[50px] flex flex-col lg:flex-row items-center gap-12">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span class="text-xs font-black uppercase text-emerald-500 tracking-widest">System Operational</span>
                    </div>
                    <h2 class="text-4xl font-black mb-6 tracking-tight">Real-Time Fleet <br><span class="text-blue-500">Intelligence.</span></h2>
                    <p class="text-gray-400 mb-8 leading-relaxed">Our infrastructure runs on the Metrix-Core. Admins monitor global movement while customers receive minute-by-minute updates on their booking status.</p>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 bg-slate-800/40 p-4 rounded-2xl border border-white/5">
                            <div class="w-10 h-10 bg-blue-600/20 text-blue-400 rounded-xl flex items-center justify-center font-bold">1</div>
                            <span class="text-sm font-bold">Encrypted Parcel Ledger</span>
                        </div>
                        <div class="flex items-center gap-4 bg-slate-800/40 p-4 rounded-2xl border border-white/5">
                            <div class="w-10 h-10 bg-teal-600/20 text-teal-400 rounded-xl flex items-center justify-center font-bold">2</div>
                            <span class="text-sm font-bold">Global Multi-Node Routing</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex-1 w-full bg-slate-800/50 rounded-[40px] aspect-video border border-white/10 shadow-2xl relative overflow-hidden group">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d246947.7887304323!2d120.4468625902066!3d15.06822364024317!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f7093229b9cb%3A0x6769936934c9f00!2sPampanga!5e0!3m2!1sen!2sph!4v1708350000000!5m2!1sen!2sph" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="map-filter w-full h-full rounded-[40px]">
                    </iframe>
                    
                    <div class="absolute inset-0 pointer-events-none border-[6px] border-white/5 rounded-[40px] shadow-inner"></div>
                    
                    <div class="absolute bottom-6 left-6 bg-slate-900/80 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-white">Hub: Pampanga, PH</span>
                    </div>
                </div>
                </div>
        </div>
    </section>

    <section id="services" class="py-32">
        <div class="max-w-7xl mx-auto px-6 text-center mb-20">
            <h2 class="text-5xl font-black mb-4 tracking-tighter">Precision <span class="text-blue-500">Logistics</span></h2>
            <p class="text-gray-400 font-medium">Enterprise-grade solutions for individuals and corporations.</p>
        </div>
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-8">
            <div class="glass p-10 rounded-[40px] hover:border-blue-500/50 transition duration-500 group">
                <div class="w-16 h-16 bg-blue-600/10 text-blue-400 rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:scale-110 transition-transform">📦</div>
                <h3 class="text-2xl font-bold mb-4">Express Freight</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium">Direct air and land routes optimized for time-sensitive materials.</p>
            </div>
            <div class="glass p-10 rounded-[40px] hover:border-teal-500/50 transition duration-500 group">
                <div class="w-16 h-16 bg-teal-600/10 text-teal-400 rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:scale-110 transition-transform">🛡️</div>
                <h3 class="text-2xl font-bold mb-4">Secured Assets</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium">Specialized handling for fragile, electronics, and custom category items.</p>
            </div>
            <div class="glass p-10 rounded-[40px] hover:border-purple-500/50 transition duration-500 group">
                <div class="w-16 h-16 bg-purple-600/10 text-purple-400 rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:scale-110 transition-transform">⚡</div>
                <h3 class="text-2xl font-bold mb-4">Smart Tracking</h3>
                <p class="text-gray-500 text-sm leading-relaxed font-medium">Real-time GPS integration providing visibility into every transit phase.</p>
            </div>
        </div>
    </section>

    <section id="faq" class="py-32 relative">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-black tracking-tight mb-4">Common Questions</h2>
                <div class="h-1 w-20 bg-blue-600 mx-auto rounded-full"></div>
            </div>
            <div class="grid gap-4">
                <div class="glass p-8 rounded-3xl group cursor-pointer hover:bg-white/[0.05] transition">
                    <h4 class="font-bold text-lg mb-2 flex justify-between items-center">
                        How secure is my shipping data?
                        <span class="text-blue-500 text-xl group-hover:rotate-45 transition">+</span>
                    </h4>
                    <p class="text-gray-500 text-sm leading-relaxed hidden group-hover:block animate-in fade-in">We use end-to-end encryption for every shipment profile, ensuring origin and destination data remain private.</p>
                </div>
                <div class="glass p-8 rounded-3xl group cursor-pointer hover:bg-white/[0.05] transition">
                    <h4 class="font-bold text-lg mb-2 flex justify-between items-center">
                        Can I cancel a booking?
                        <span class="text-blue-500 text-xl group-hover:rotate-45 transition">+</span>
                    </h4>
                    <p class="text-gray-500 text-sm leading-relaxed hidden group-hover:block animate-in fade-in">Yes, users can cancel their bookings as long as the status is still set to "Pending" via the Command Center.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="py-32">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="glass p-16 rounded-[60px] border-blue-500/20 bg-blue-600/5">
                <h2 class="text-5xl font-black mb-8 tracking-tighter">Ready to <span class="text-blue-500">Scale?</span></h2>
                <p class="text-gray-400 mb-12 max-w-lg mx-auto font-medium leading-relaxed">Join the logistics revolution. Experience the cleanest system in the digital generation.</p>
                <div class="flex flex-col md:flex-row gap-4 justify-center">
                    <a href="{{ route('register.show') }}" class="px-12 py-5 bg-white text-black font-black rounded-2xl hover:bg-blue-600 hover:text-white transition shadow-2xl">Start Now</a>
                    <button class="px-12 py-5 glass font-black rounded-2xl hover:bg-white/10 transition uppercase tracking-widest text-xs">Contact Sales</button>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-16 border-t border-white/5 bg-slate-950 text-center">
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center font-bold italic">M</div>
            <span class="text-lg font-black tracking-tight">METRIX</span>
        </div>
        <p class="text-gray-500 text-[10px] font-bold uppercase tracking-[0.4em] mb-4">Intelligence in Motion</p>
        <p class="text-gray-600 text-[10px]">&copy; 2026 METRIX APP. ALL SYSTEMS OPERATIONAL.</p>
    </footer>

</body>
</html>