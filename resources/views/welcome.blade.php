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
        
        .glass { 
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(16px); 
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .gradient-text { 
            background: linear-gradient(135deg, #60a5fa 0%, #2dd4bf 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }

        .bg-grid { 
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px; 
        }
        

        .map-filter {
            filter: grayscale(100%) invert(92%) contrast(83%);
            mix-blend-mode: luminosity;
        }

        .faq-content {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.3s ease-out;
        }
        .faq-content.open {
            grid-template-rows: 1fr;
        }
        .faq-inner {
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-slate-950 text-white antialiased bg-grid relative selection:bg-blue-500/30 selection:text-blue-200">

    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-teal-600/10 rounded-full blur-[120px]"></div>
    </div>

    <nav class="fixed w-full z-50 glass top-0 border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-cyan-500 rounded-xl flex items-center justify-center font-bold text-xl italic shadow-lg shadow-blue-500/20 text-white">M</div>
                <span class="text-2xl font-extrabold tracking-tight">METRIX</span>
            </div>
            <div class="hidden md:flex items-center gap-10 text-xs font-bold uppercase tracking-widest text-gray-400">
                <a href="#about" class="hover:text-blue-400 transition-colors">Home</a>
                <a href="#tracking" class="hover:text-blue-400 transition-colors">Fleet</a>
                <a href="#services" class="hover:text-blue-400 transition-colors">Services</a>
                <a href="#faq" class="hover:text-blue-400 transition-colors">FAQ</a>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="hidden md:block text-sm font-bold text-gray-400 hover:text-white transition-colors">Sign In</a>
                <a href="{{ route('register.show') }}" class="px-6 py-2 bg-white text-slate-950 hover:bg-blue-50 rounded-full font-bold transition text-sm shadow-[0_0_20px_rgba(255,255,255,0.3)]">
                    Register
                </a>
            </div>
        </div>
    </nav>

    <section id="about" class="relative pt-48 pb-32">
        <div class="max-w-7xl mx-auto px-6 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-blue-500/30 bg-blue-500/10 text-blue-300 text-xs font-bold uppercase tracking-widest mb-8 animate-pulse">
                <span class="w-2 h-2 rounded-full bg-blue-400"></span> Live System V 1.1 
            </div>
            <h1 class="text-6xl md:text-8xl font-black mb-8 tracking-tighter leading-[0.9]">
                Logistics for the <br> <span class="gradient-text">Digital Era.</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-400 max-w-2xl mx-auto mb-12 font-medium leading-relaxed">
                Hyper-fast delivery management. Track, manage, and scale your global logistics with our AI-integrated platform.
            </p>
            
            <div class="mt-24 grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
                <div class="glass p-6 rounded-[24px] hover:bg-white/[0.05] transition duration-300">
                    <div class="text-3xl font-black mb-1 text-white">99.9%</div>
                    <div class="text-[10px] uppercase font-bold text-blue-400 tracking-widest">Uptime Rate</div>
                </div>
                <div class="glass p-6 rounded-[24px] hover:bg-white/[0.05] transition duration-300">
                    <div class="text-3xl font-black mb-1 text-white">2M+</div>
                    <div class="text-[10px] uppercase font-bold text-teal-400 tracking-widest">Parcels Shipped</div>
                </div>
                <div class="glass p-6 rounded-[24px] hover:bg-white/[0.05] transition duration-300">
                    <div class="text-3xl font-black mb-1 text-white">150+</div>
                    <div class="text-[10px] uppercase font-bold text-purple-400 tracking-widest">Active Hubs</div>
                </div>
                <div class="glass p-6 rounded-[24px] hover:bg-white/[0.05] transition duration-300">
                    <div class="text-3xl font-black mb-1 text-white">0.02s</div>
                    <div class="text-[10px] uppercase font-bold text-orange-400 tracking-widest">Latency</div>
                </div>
            </div>
        </div>
    </section>

    <section id="tracking" class="py-20">
        <div class="max-w-7xl mx-auto px-6">
            <div class="glass p-8 md:p-12 rounded-[40px] flex flex-col lg:flex-row items-stretch gap-12 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-blue-500/10 blur-[80px] rounded-full pointer-events-none"></div>

                <div class="flex-1 flex flex-col justify-center">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="relative flex h-3 w-3">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs font-black uppercase text-emerald-500 tracking-widest">System Operational</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">Real-Time Fleet <br><span class="text-blue-500">Intelligence.</span></h2>
                    <p class="text-gray-400 mb-8 leading-relaxed text-lg">Our infrastructure runs on the Metrix-Core. Admins monitor global movement while customers receive minute-by-minute updates.</p>
                    
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 bg-white/[0.03] p-4 rounded-2xl border border-white/5 hover:border-blue-500/30 transition duration-300 cursor-default">
                            <div class="w-10 h-10 bg-blue-600/20 text-blue-400 rounded-xl flex items-center justify-center font-bold">1</div>
                            <div>
                                <h5 class="font-bold text-white">Encrypted Ledger</h5>
                                <p class="text-xs text-gray-500">AES-256 Shipment Data</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 bg-white/[0.03] p-4 rounded-2xl border border-white/5 hover:border-teal-500/30 transition duration-300 cursor-default">
                            <div class="w-10 h-10 bg-teal-600/20 text-teal-400 rounded-xl flex items-center justify-center font-bold">2</div>
                            <div>
                                <h5 class="font-bold text-white">Multi-Node Routing</h5>
                                <p class="text-xs text-gray-500">AI Optimized Pathways</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex-1 w-full bg-slate-900 rounded-[30px] aspect-square md:aspect-video border border-white/10 shadow-2xl relative overflow-hidden group">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15406.47167571026!2d120.596007!3d15.145007!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3396f24d77685601%3A0x62957778b4f17df6!2sClark%20Freeport%2C%20Mabalacat%2C%20Pampanga!5e0!3m2!1sen!2sph!4v1684305829105!5m2!1sen!2sph" 
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="map-filter w-full h-full opacity-60 group-hover:opacity-80 transition duration-700">
                    </iframe>
                    
                    <div class="absolute inset-0 pointer-events-none border-[1px] border-white/10 rounded-[30px] shadow-[inset_0_0_50px_rgba(0,0,0,0.8)]"></div>
                    <div class="absolute bottom-6 left-6 glass px-4 py-2 rounded-xl flex items-center gap-3 z-10">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-[10px] font-black uppercase tracking-widest text-white">Live Tracking: Pampanga Hub</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="py-32">
        <div class="max-w-7xl mx-auto px-6 text-center mb-20">
            <h2 class="text-5xl font-black mb-4 tracking-tighter">Precision <span class="text-blue-500">Logistics</span></h2>
            <p class="text-gray-400 font-medium text-lg">Enterprise-grade solutions for individuals and corporations.</p>
        </div>
        <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-3 gap-6">
            <div class="glass p-10 rounded-[32px] border border-white/5 hover:border-blue-500/50 hover:bg-white/[0.05] transition-all duration-500 group cursor-default">
                <div class="w-16 h-16 bg-blue-600/10 text-blue-400 rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">📦</div>
                <h3 class="text-2xl font-bold mb-3 text-white">Express Freight</h3>
                <p class="text-gray-400 text-sm leading-relaxed font-medium">Direct air and land routes optimized for time-sensitive materials with priority handling.</p>
            </div>
            <div class="glass p-10 rounded-[32px] border border-white/5 hover:border-teal-500/50 hover:bg-white/[0.05] transition-all duration-500 group cursor-default">
                <div class="w-16 h-16 bg-teal-600/10 text-teal-400 rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">🛡️</div>
                <h3 class="text-2xl font-bold mb-3 text-white">Secured Assets</h3>
                <p class="text-gray-400 text-sm leading-relaxed font-medium">Specialized handling for fragile, electronics, and high-value custom category items.</p>
            </div>
            <div class="glass p-10 rounded-[32px] border border-white/5 hover:border-purple-500/50 hover:bg-white/[0.05] transition-all duration-500 group cursor-default">
                <div class="w-16 h-16 bg-purple-600/10 text-purple-400 rounded-2xl flex items-center justify-center mb-8 text-3xl group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">⚡</div>
                <h3 class="text-2xl font-bold mb-3 text-white">Smart Tracking</h3>
                <p class="text-gray-400 text-sm leading-relaxed font-medium">Real-time GPS integration providing granular visibility into every single transit phase.</p>
            </div>
        </div>
    </section>

    <section id="faq" class="py-32 relative">
        <div class="max-w-3xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-black tracking-tight mb-4">Common Questions</h2>
                <div class="h-1 w-20 bg-gradient-to-r from-blue-600 to-teal-400 mx-auto rounded-full"></div>
            </div>
            
            <div class="grid gap-4">
                <div class="glass p-1 rounded-3xl transition-all duration-300 hover:border-white/20">
                    <div class="p-7 cursor-pointer" onclick="toggleFaq(this)">
                        <h4 class="font-bold text-lg flex justify-between items-center select-none">
                            How secure is my shipping data?
                            <span class="faq-icon text-blue-400 text-2xl transition-transform duration-300 font-light">+</span>
                        </h4>
                        <div class="faq-content">
                            <div class="faq-inner pt-4 text-gray-400 text-sm leading-relaxed">
                                We use end-to-end encryption (AES-256) for every shipment profile. Your origin and destination data remain private and are only accessible by authorized nodes in our network.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass p-1 rounded-3xl transition-all duration-300 hover:border-white/20">
                    <div class="p-7 cursor-pointer" onclick="toggleFaq(this)">
                        <h4 class="font-bold text-lg flex justify-between items-center select-none">
                            Can I cancel a booking?
                            <span class="faq-icon text-blue-400 text-2xl transition-transform duration-300 font-light">+</span>
                        </h4>
                        <div class="faq-content">
                            <div class="faq-inner pt-4 text-gray-400 text-sm leading-relaxed">
                                Yes, users can cancel their bookings instantly via the Command Center App as long as the status is still set to "Pending" or "Processing" before pickup.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass p-1 rounded-3xl transition-all duration-300 hover:border-white/20">
                    <div class="p-7 cursor-pointer" onclick="toggleFaq(this)">
                        <h4 class="font-bold text-lg flex justify-between items-center select-none">
                            Do you offer international shipping?
                            <span class="faq-icon text-blue-400 text-2xl transition-transform duration-300 font-light">+</span>
                        </h4>
                        <div class="faq-content">
                            <div class="faq-inner pt-4 text-gray-400 text-sm leading-relaxed">
                                Absolutely. Metrix supports global logistics with multi-node routing to ensure efficient international deliveries to over 150 countries.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-12 border-t border-white/5 bg-slate-950 text-center relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[300px] bg-blue-600/5 blur-[100px] pointer-events-none"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-8 h-8 bg-white text-slate-950 rounded-lg flex items-center justify-center font-bold italic">M</div>
                <span class="text-xl font-black tracking-tight">METRIX</span>
            </div>
            <p class="text-blue-500/60 text-[10px] font-bold uppercase tracking-[0.4em] mb-6">Intelligence in Motion</p>
            <div class="flex justify-center gap-6 text-xs text-gray-500 font-medium mb-8">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
                <a href="#" class="hover:text-white transition">Contact Support</a>
            </div>
            <p class="text-gray-700 text-[10px]">© 2026 METRIX SYSTEMS. ALL RIGHTS RESERVED.</p>
        </div>
    </footer>

    <script>
    function toggleFaq(element) {
        const content = element.querySelector('.faq-content');
        const icon = element.querySelector('.faq-icon');
        content.classList.toggle('open');
        if (content.classList.contains('open')) {
            icon.classList.add('rotate-45', 'text-teal-400');
            icon.classList.remove('text-blue-400');
        } else {
            icon.classList.remove('rotate-45', 'text-teal-400');
            icon.classList.add('text-blue-400');
        }
    }
    </script>
</body>
</html>