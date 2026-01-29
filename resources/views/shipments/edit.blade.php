@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $standardTypes = ['Document', 'Small Box', 'Medium Box', 'Large Cargo', 'Fragile', 'Electronics'];
    
    // Get current value from DB
    $dbType = $shipment->package_type;
    
    // Check if it is a custom type
    $isCustom = !in_array($dbType, $standardTypes);

    // Set initial Alpine values based on DB state
    $initPackageType = $isCustom ? 'Other' : $dbType;
    $initCustomValue = $isCustom ? $dbType : '';
@endphp

<div class="max-w-7xl mx-auto px-6 py-8"
     x-data="{
        isAdmin: {{ Auth::user()->role == 1 ? 'true' : 'false' }},
        
        // Initialize with correct values from PHP
        packageType: '{{ $initPackageType }}',
        customPackageType: '{{ $initCustomValue }}',
        
        price: {{ $shipment->price ?? 0 }},
        
        originalUserId: {{ (int) $shipment->user_id }},
        selectedUserId: {{ (int) old('user_id', $shipment->user_id) }},
        showAssignConfirm: false,

        rates: {
            'Document': 150.00,
            'Small Box': 250.00,
            'Medium Box': 450.00,
            'Large Cargo': 850.00,
            'Fragile': 550.00,
            'Electronics': 600.00
        },

        updatePrice() {
            // Auto-update price only if Admin selects a Standard Type
            if (this.isAdmin && this.packageType !== 'Other' && this.rates[this.packageType]) {
                this.price = this.rates[this.packageType];
                this.customPackageType = ''; // Clear custom input when switching to standard
            }
        },

        interceptSubmit(e) {
            if (!this.isAdmin) return;
            if (parseInt(this.selectedUserId) !== parseInt(this.originalUserId)) {
                e.preventDefault();
                this.showAssignConfirm = true;
            }
        },

        proceedAssignConfirm() {
            this.showAssignConfirm = false;
            this.$root.querySelector('#editForm').submit();
        }
     }">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <nav class="flex mb-3 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-400 transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-slate-300">Management</span>
                <span class="mx-2">/</span>
                <span class="text-blue-500">Edit Shipment</span>
            </nav>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">Manage Shipment</h1>
            <p class="text-slate-400 font-medium">Tracking ID: <span class="text-blue-400 font-mono">#MX-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</span></p>
        </div>

        <div class="px-6 py-3 rounded-2xl border border-white/5 bg-slate-900/60 backdrop-blur-md flex items-center gap-3">
            <p class="text-[10px] font-black uppercase text-slate-500 tracking-widest">Live Status</p>
            <span class="flex items-center gap-2 text-sm font-bold uppercase tracking-wide
                {{ $shipment->status == 'Delivered' ? 'text-emerald-400' : '' }}
                {{ $shipment->status == 'Cancelled' ? 'text-red-400' : '' }}
                {{ in_array($shipment->status, ['Pending', 'In Transit']) ? 'text-blue-400' : '' }}">
                <span class="w-2 h-2 rounded-full bg-current {{ !in_array($shipment->status, ['Delivered', 'Cancelled']) ? 'animate-pulse' : '' }}"></span>
                {{ $shipment->status }}
            </span>
        </div>
    </div>

    {{-- ERROR DISPLAY --}}
    @if ($errors->any())
        <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-300 p-6 rounded-[24px] animate-pulse">
            <h3 class="font-black uppercase tracking-widest text-xs mb-2">Correction Required</h3>
            <ul class="space-y-1 ml-4 text-xs font-medium list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- LEFT COLUMN: FORM --}}
        <div class="lg:col-span-2">
            <form id="editForm" action="{{ route('shipments.update', $shipment->id) }}" method="POST" class="space-y-6" @submit="interceptSubmit($event)">
                @csrf
                @method('PUT')

                {{-- ADMIN CONTROLS --}}
                @if(Auth::user()->role == 1)
                <div class="glass-card p-8 rounded-[32px] space-y-6 border-l-4 border-blue-500 bg-slate-900/40 border border-white/5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-xs font-black">⚙</div>
                        <h3 class="font-bold text-white uppercase tracking-widest text-xs">Admin Controls</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2">Update Status</label>
                            <div class="relative">
                                <select name="status" class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-blue-600/50 appearance-none cursor-pointer">
                                    <option value="Pending" {{ $shipment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Transit" {{ $shipment->status == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="Delivered" {{ $shipment->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="Cancelled" {{ $shipment->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2">Assign To</label>
                            <div class="relative">
                                <select name="user_id" x-model="selectedUserId" class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-purple-600/50 appearance-none cursor-pointer">
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ✅ ORIGIN DATA (ADDED) --}}
                <div class="glass-card p-8 rounded-[32px] space-y-5 bg-slate-900/40 border border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-orange-600/20 text-orange-400 flex items-center justify-center text-xs font-black">📍</div>
                        <h3 class="font-bold text-white uppercase tracking-widest text-xs">Origin Details (Sender)</h3>
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Sender Name</label>
                        <input type="text" name="sender_name" required class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-orange-600/50 transition-all" value="{{ old('sender_name', $shipment->sender_name) }}">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Pickup Address</label>
                        <textarea name="sender_address" required rows="2" class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-medium outline-none focus:ring-2 focus:ring-orange-600/50 resize-none">{{ old('sender_address', $shipment->sender_address) }}</textarea>
                    </div>
                </div>

                {{-- RECIPIENT DATA --}}
                <div class="glass-card p-8 rounded-[32px] space-y-5 bg-slate-900/40 border border-white/5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xs font-black">📍</div>
                        <h3 class="font-bold text-white uppercase tracking-widest text-xs">Destination Details (Recipient)</h3>
                    </div>
                    
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Receiver Name</label>
                        <input type="text" name="receiver_name" required class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-indigo-600/50 transition-all" value="{{ old('receiver_name', $shipment->receiver_name) }}">
                    </div>
                    <div class="group">
                        <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Delivery Address</label>
                        <textarea name="receiver_address" required rows="3" class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-medium outline-none focus:ring-2 focus:ring-indigo-600/50 resize-none">{{ old('receiver_address', $shipment->receiver_address) }}</textarea>
                    </div>
                </div>

                {{-- PACKAGE & FINANCIALS --}}
                <div class="glass-card p-8 rounded-[32px] space-y-6 bg-slate-900/40 border border-white/5">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xs font-black">📦</div>
                        <h3 class="font-bold text-white uppercase tracking-widest text-xs">Valuation</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- CATEGORY SELECTOR --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Category</label>
                            @if(Auth::user()->role == 1)
                                <div class="space-y-3">
                                    <div class="relative">
                                        {{-- ✅ FIX: Using @foreach instead of x-for to render immediately --}}
                                        <select name="package_type" x-model="packageType" @change="updatePrice()" class="w-full bg-slate-800/50 border border-white/5 rounded-2xl p-4 text-white font-bold outline-none focus:ring-2 focus:ring-emerald-600/50 appearance-none cursor-pointer">
                                            @foreach($standardTypes as $type)
                                                <option value="{{ $type }}">{{ $type }}</option>
                                            @endforeach
                                            <option value="Other">Other (Custom)</option>
                                        </select>
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg></div>
                                    </div>
                                    <div x-show="packageType === 'Other'" x-transition>
                                        <input type="text" name="custom_package_type" x-model="customPackageType" class="w-full bg-emerald-500/10 border border-emerald-500/20 rounded-2xl p-4 text-white font-bold placeholder-emerald-500/50" placeholder="Enter custom category name...">
                                    </div>
                                </div>
                            @else
                                <input type="text" value="{{ $shipment->package_type }}" readonly class="w-full bg-slate-950 border border-white/5 rounded-2xl p-4 text-slate-500 font-bold outline-none cursor-not-allowed">
                                <input type="hidden" name="package_type" value="{{ $shipment->package_type }}">
                            @endif
                        </div>

                        {{-- PRICE INPUT --}}
                        <div>
                            <label class="block text-[10px] font-black uppercase text-slate-500 ml-2 mb-2">Price (₱)</label>
                            <div class="relative">
                                <span class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-500 font-bold">₱</span>
                                <input type="number" name="price" step="0.01" required x-model="price" 
                                    {{ Auth::user()->role != 1 ? 'readonly' : '' }}
                                    class="w-full border border-white/5 rounded-2xl p-4 pl-10 outline-none focus:ring-2 focus:ring-emerald-600/50 bg-slate-800/50 text-white font-bold text-lg {{ Auth::user()->role != 1 ? 'text-slate-500 cursor-not-allowed' : '' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-400 text-white font-black rounded-2xl shadow-xl hover:-translate-y-1 transition-all active:scale-95 uppercase tracking-widest">
                    Update Record
                </button>
            </form>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div class="space-y-6">
            {{-- META DATA --}}
            <div class="glass-card p-6 rounded-[32px] bg-slate-900/40 border border-white/5">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 mb-4">Meta Data</h3>
                <div class="flex justify-between items-center pb-4 border-b border-white/5 mb-4">
                    <span class="text-xs text-slate-400">Created</span>
                    <span class="text-xs font-bold text-white">{{ $shipment->created_at->format('M d, Y') }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-400">Owner</span>
                    <span class="text-xs font-bold text-blue-400">{{ optional($shipment->user)->name }}</span>
                </div>
            </div>

            {{-- BACK BUTTON --}}
            <div class="bg-slate-900/50 p-6 rounded-[32px] border border-white/5">
                <a href="{{ route('dashboard') }}" class="flex items-center justify-center w-full py-4 bg-slate-800 hover:bg-slate-700 text-white font-bold rounded-2xl border border-white/5 text-xs uppercase gap-2 transition-all">
                   <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                   Dashboard
                </a>
            </div>

            {{-- DANGER ZONE --}}
            <div class="bg-red-500/5 p-8 rounded-[32px] border border-red-500/10">
                <h3 class="font-bold text-red-500 uppercase tracking-widest text-[10px] mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Danger Zone
                </h3>
                <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('CRITICAL: Permanently delete this shipment?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full py-3 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 font-black rounded-xl transition-all text-[10px] uppercase tracking-widest">
                        Delete Shipment
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- REASSIGN MODAL --}}
    <div x-cloak x-show="showAssignConfirm" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showAssignConfirm = false"></div>
        <div x-transition class="relative w-full max-w-md rounded-[32px] border border-white/10 bg-slate-900 p-8 shadow-2xl">
            <h3 class="text-2xl font-black text-white mb-2 text-center">Confirm Transfer?</h3>
            <p class="text-slate-400 text-sm mb-8 text-center leading-relaxed">This will move the shipment to another user's dashboard.</p>
            <div class="flex gap-3">
                <button type="button" @click="showAssignConfirm = false" class="flex-1 py-3 rounded-xl bg-white/5 text-white font-bold">Cancel</button>
                <button type="button" @click="proceedAssignConfirm()" class="flex-1 py-3 rounded-xl bg-purple-600 text-white font-bold shadow-lg">Confirm</button>
            </div>
        </div>
    </div>
</div>
@endsection