@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-6 py-8"
     x-data="{
        packageType: 'Document',
        customType: '',
        customPrice: '',
        showConfirm: false,

        // RATES in PHP
        rates: {
            'Document': 150.00,
            'Small Box': 250.00,
            'Medium Box': 450.00,
            'Large Cargo': 850.00,
            'Fragile': 550.00,
            'Electronics': 600.00,
            'Other': 0.00
        },

        get displayPrice() {
            if (this.packageType === 'Other') {
                return parseFloat(this.customPrice) || 0;
            }
            return this.rates[this.packageType];
        },

        // VALIDATION LOGIC
        get isValid() {
            if (this.packageType === 'Other') {
                const price = parseFloat(this.customPrice);
                // Rule 1: Must be at least 50 pesos
                // Rule 2: Must be less than 1,000,000 (prevent crazy numbers)
                // Rule 3: Must have a custom type name
                return price >= 50 && price <= 999999 && this.customType.trim().length > 0;
            }
            return true;
        },

        // Warning for unrealistically high price
        get isHighPrice() {
            return this.packageType === 'Other' && this.displayPrice > 50000;
        },

        openConfirm(e) {
            e.preventDefault();
            if (!this.isValid) return;
            this.showConfirm = true;
        },

        closeConfirm() {
            this.showConfirm = false;
        },

        confirmAndSubmit() {
            this.$root.querySelector('#createForm').submit();
        }
     }">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <nav class="flex mb-3 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-400 transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-500">New Booking</span>
            </nav>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">
                Initialize Shipment
            </h1>
            <p class="text-slate-400 font-medium">
                Enter logistics parameters for secure dispatch.
            </p>
        </div>
        
        {{-- Back Button --}}
        <a href="{{ route('dashboard') }}" class="group flex items-center gap-2 px-5 py-3 rounded-2xl border border-white/5 bg-slate-900/60 hover:bg-slate-800 transition-all text-slate-400 hover:text-white font-bold text-xs uppercase tracking-widest">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Dashboard
        </a>
    </div>

    @if ($errors->any())
        <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-300 p-6 rounded-[24px] animate-pulse">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <h3 class="font-black uppercase tracking-widest text-xs">Correction Required</h3>
            </div>
            <ul class="space-y-1 ml-7 text-xs font-medium">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="createForm" action="{{ route('shipments.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- LEFT COLUMN: ADDRESS DETAILS --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- ORIGIN --}}
                <div class="glass-card p-8 rounded-[32px] border border-white/5 bg-slate-900/40 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-blue-600"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-xs font-black">01</div>
                        <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Origin Details</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-5">
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2 group-focus-within:text-blue-400 transition-colors">Sender Name</label>
                            <input type="text" name="sender_name" required
                                class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 transition-all font-bold placeholder-slate-600"
                                placeholder="Full legal name">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2 group-focus-within:text-blue-400 transition-colors">Pickup Address</label>
                            <input type="text" name="sender_address" required
                                class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 transition-all font-medium placeholder-slate-600"
                                placeholder="Street, City, Zip">
                        </div>
                    </div>
                </div>

                {{-- DESTINATION --}}
                <div class="glass-card p-8 rounded-[32px] border border-white/5 bg-slate-900/40 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-indigo-500"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xs font-black">02</div>
                        <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Destination Details</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-5">
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2 group-focus-within:text-indigo-400 transition-colors">Receiver Name</label>
                            <input type="text" name="receiver_name" required
                                class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-indigo-600/50 transition-all font-bold placeholder-slate-600"
                                placeholder="Recipient name">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2 group-focus-within:text-indigo-400 transition-colors">Delivery Address</label>
                            <input type="text" name="receiver_address" required
                                class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-indigo-600/50 transition-all font-medium placeholder-slate-600"
                                placeholder="Full destination address">
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: LOGISTICS & PRICE --}}
            <div class="lg:col-span-1 space-y-8">
                
                {{-- LOGISTICS --}}
                <div class="glass-card p-8 rounded-[32px] border border-white/5 bg-slate-900/40 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xs font-black">03</div>
                        <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Logistics</h3>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2 group-focus-within:text-emerald-400 transition-colors">Parcel Category</label>
                        <div class="relative">
                            <select name="package_type" x-model="packageType"
                                class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-emerald-600/50 appearance-none cursor-pointer font-bold transition-all hover:bg-slate-800">
                                <option value="Document">Document (₱150)</option>
                                <option value="Small Box">Small Box (₱250)</option>
                                <option value="Medium Box">Medium Box (₱450)</option>
                                <option value="Large Cargo">Large Cargo (₱850)</option>
                                <option value="Fragile">Fragile (₱550)</option>
                                <option value="Electronics">Electronics (₱600)</option>
                                <option value="Other">Other (Custom)</option>
                            </select>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- CUSTOM FIELDS (Animated) --}}
                    <div x-show="packageType === 'Other'" x-collapse class="space-y-4 pt-4 border-t border-white/5 mt-4">
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Custom Name</label>
                            <input type="text" name="custom_package_type" x-model="customType"
                                class="w-full bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-emerald-500 font-bold placeholder-emerald-500/30"
                                placeholder="E.g. Pallet, Machinery">
                        </div>
                        
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Offer Price (₱)</label>
                            <input type="number" name="custom_price" x-model="customPrice" step="0.01"
                                class="w-full bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-emerald-500 font-bold placeholder-emerald-500/30"
                                placeholder="Min ₱50.00">
                            
                            {{-- Validation Messages --}}
                            <p x-show="customPrice > 0 && customPrice < 50" class="text-[10px] text-red-400 font-bold mt-2 ml-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Minimum price is ₱50.00
                            </p>
                            <p x-show="isHighPrice" class="text-[10px] text-yellow-400 font-bold mt-2 ml-2 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                High Value Cargo: Admin verification required.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- TOTAL & SUBMIT --}}
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[40px] p-8 shadow-2xl flex flex-col justify-between min-h-[250px] relative overflow-hidden group">
                    {{-- Animated Blob --}}
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-all duration-700"></div>
                    
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/60 mb-1">Final Estimate</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-white/80">₱</span>
                            <h2 class="text-6xl font-black text-white tracking-tighter" x-text="displayPrice.toFixed(2)"></h2>
                        </div>
                    </div>

                    <button type="submit"
                            @click="openConfirm($event)"
                            :disabled="!isValid"
                            :class="isValid ? 'bg-white text-blue-700 shadow-xl hover:scale-[1.02]' : 'bg-white/20 text-white/50 cursor-not-allowed'"
                            class="w-full py-5 font-black rounded-[24px] transition-all active:scale-[0.98] uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                        <span>Confirm Booking</span>
                        <svg x-show="isValid" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- CONFIRMATION MODAL --}}
        <div x-cloak x-show="showConfirm"
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             @keydown.escape.window="closeConfirm()">
            
            <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="closeConfirm()"></div>

            <div x-transition class="relative w-full max-w-md rounded-[32px] border border-white/10 bg-slate-900 p-8 shadow-2xl transform transition-all">
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center mb-4">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                
                <h3 class="text-2xl font-black text-white tracking-tight mb-2">Finalize Booking?</h3>
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    You are about to book a shipment for <span class="text-white font-bold" x-text="packageType === 'Other' ? customType : packageType"></span> at <span class="text-emerald-400 font-bold">₱<span x-text="displayPrice.toFixed(2)"></span></span>.
                    <br><br>
                    Once created, this cannot be edited by the client.
                </p>

                <div class="flex gap-3">
                    <button type="button" @click="closeConfirm()" class="flex-1 py-3 rounded-xl border border-white/10 bg-white/5 text-slate-300 font-bold hover:bg-white/10 transition">
                        Review
                    </button>
                    <button type="button" @click="confirmAndSubmit()" class="flex-1 py-3 rounded-xl bg-blue-600 text-white font-bold hover:bg-blue-500 transition shadow-lg shadow-blue-600/20">
                        Yes, Proceed
                    </button>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection