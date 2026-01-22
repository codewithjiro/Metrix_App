@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $standardTypes = ['Document', 'Small Box', 'Medium Box', 'Large Cargo', 'Fragile', 'Electronics'];
    $currentType = old('package_type', $shipment->package_type);
    $isCustomExisting = !in_array($currentType, $standardTypes);

    // If custom existing, admin UI uses "Other" select, but we keep the real label here
    $displayTypeForReadonly = $isCustomExisting ? $currentType : $currentType;
@endphp

<div class="max-w-4xl mx-auto px-6"
     x-data="{
        isAdmin: {{ Auth::user()->role == 1 ? 'true' : 'false' }},
        standardTypes: {{ json_encode($standardTypes) }},

        // Admin UI:
        // If existing package_type is custom, show select as 'Other', and fill custom input with the real type
        packageType: '{{ $isCustomExisting ? 'Other' : $currentType }}',
        customPackageType: '{{ old('custom_package_type', $isCustomExisting ? $currentType : '') }}',

        // Non-admin needs the REAL package type (never 'Other')
        actualPackageType: '{{ $displayTypeForReadonly }}',

        price: {{ json_encode(old('price', $shipment->price)) }},

        showCatTip: false,
        showPriceTip: false,

        // Admin reassignment confirmation
        showAssignConfirm: false,
        originalUserId: {{ (int) $shipment->user_id }},
        selectedUserId: {{ (int) old('user_id', $shipment->user_id) }},

        rates: {
            'Document': 15.00,
            'Small Box': 45.00,
            'Medium Box': 85.00,
            'Large Cargo': 150.00,
            'Fragile': 110.00,
            'Electronics': 95.00
        },

        updatePrice() {
            if (this.isAdmin && this.isStandard() && this.rates[this.packageType]) {
                this.price = this.rates[this.packageType];
            }
        },

        isStandard() {
            return this.standardTypes.includes(this.packageType);
        },

        // used only for non-admin readonly styling
        isActualStandard() {
            return this.standardTypes.includes(this.actualPackageType);
        },

        // Submit handler: if admin changed assigned user, confirm first
        interceptSubmit(e) {
            if (!this.isAdmin) return;

            const changed = parseInt(this.selectedUserId) !== parseInt(this.originalUserId);
            if (changed) {
                e.preventDefault();
                this.showAssignConfirm = true;
            }
        },

        cancelAssignConfirm() {
            this.showAssignConfirm = false;
            // reset dropdown to original
            this.selectedUserId = this.originalUserId;
        },

        proceedAssignConfirm() {
            this.showAssignConfirm = false;
            this.$root.querySelector('form').submit();
        }
     }"
>
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <nav class="flex mb-4 text-[10px] font-black uppercase tracking-[0.3em] text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-400 transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-slate-300">Management Console</span>
            </nav>
            <h1 class="text-5xl font-extrabold text-white tracking-tighter">Edit <span class="text-indigo-500">Shipment</span></h1>
            <p class="text-slate-400 mt-2">
                Adjusting parameters for Parcel
                <span class="text-blue-400 font-mono">#MX-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</span>
            </p>

            {{-- Optional: show owner info for admin --}}
            @if(Auth::user()->role == 1)
                <p class="text-[10px] mt-3 text-slate-500 font-black uppercase tracking-widest">
                    Assigned to: <span class="text-slate-200 font-bold normal-case">{{ optional($shipment->user)->name ?? 'Unknown' }}</span>
                </p>
            @endif
        </div>

        <div class="glass-card px-6 py-4 rounded-2xl border-white/5 bg-slate-900/40">
            <p class="text-[10px] font-black uppercase text-slate-500 tracking-widest mb-1">Current Status</p>
            <span class="flex items-center gap-2 text-sm font-bold {{ $shipment->status == 'Delivered' ? 'text-emerald-400' : 'text-blue-400' }}">
                <span class="w-2 h-2 rounded-full bg-current {{ $shipment->status != 'Delivered' ? 'animate-pulse' : '' }}"></span>
                {{ $shipment->status }}
            </span>
        </div>
    </div>

    @if ($errors->any())
        <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-400 p-5 rounded-3xl text-xs font-bold uppercase tracking-widest">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <form action="{{ route('shipments.update', $shipment->id) }}" method="POST" class="space-y-8" @submit="interceptSubmit($event)">
            @csrf
            @method('PUT')

            {{-- ===================== ASSIGNMENT (ADMIN ONLY) ===================== --}}
            @if(Auth::user()->role == 1)
                <div class="glass-card p-8 rounded-[40px] space-y-6 bg-slate-900/40 border border-white/5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-xs font-black">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-white uppercase tracking-widest text-xs">Assignment</h3>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2">
                            Assigned Customer
                        </label>

                        <div class="relative">
                            <select name="user_id" required x-model="selectedUserId"
                                class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-purple-600/50 appearance-none cursor-pointer font-bold">
                                @foreach(($users ?? []) as $u)
                                    <option value="{{ $u->id }}">
                                        {{ $u->name }} — {{ $u->email }}
                                    </option>
                                @endforeach
                            </select>

                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-2">
                            Changing this will move the shipment to the selected customer’s dashboard.
                        </p>
                    </div>
                </div>
            @endif

            {{-- ===================== RECIPIENT INFO ===================== --}}
            <div class="glass-card p-8 rounded-[40px] space-y-6 bg-slate-900/40 border border-white/5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-xs font-black">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </div>
                    <h3 class="font-bold text-white uppercase tracking-widest text-xs">Recipient Info</h3>
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2">Receiver Name</label>
                    <input type="text" name="receiver_name" required
                           class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 transition-all"
                           value="{{ old('receiver_name', $shipment->receiver_name) }}">
                </div>

                <div class="group">
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2 mb-2">Delivery Address</label>
                    <textarea name="receiver_address" required rows="4"
                              class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50 transition-all resize-none">{{ old('receiver_address', $shipment->receiver_address) }}</textarea>
                </div>
            </div>

            {{-- ===================== FINANCIAL PARAMETERS ===================== --}}
            <div class="glass-card p-8 rounded-[40px] space-y-6 border-t-4 border-emerald-500/30 bg-slate-900/40">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xs font-black">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 8c-1.657 0-3 1.343-3 3s1.343 3 3 3 3 1.343 3 3-1.343 3-3 3m0-13a9 9 0 110 18 9 9 0 010-18zm0 0V3m0 18v-3"/></svg>
                    </div>
                    <h3 class="font-bold text-white uppercase tracking-widest text-xs">Financial Parameters</h3>
                </div>

                {{-- ===================== PACKAGE CATEGORY ===================== --}}
                <div class="group space-y-4">
                    <div class="flex items-center gap-2 ml-2 mb-2">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 group-focus-within:text-emerald-400 transition-colors">
                            Package Category
                        </label>

                        {{-- Tooltip for regular users --}}
                        <div x-show="!isAdmin" class="relative" @mouseenter="showCatTip=true" @mouseleave="showCatTip=false">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-800/70 border border-white/10 text-slate-300 text-[10px] font-black cursor-help">i</span>

                            <div x-cloak x-show="showCatTip" x-transition
                                 class="absolute z-50 left-0 top-7 w-72 p-3 rounded-2xl bg-slate-950/95 border border-white/10 text-slate-200 text-[11px] leading-relaxed shadow-xl">
                                <p class="font-black uppercase tracking-widest text-[9px] text-slate-400 mb-1">Locked</p>
                                Category is controlled by Admin to prevent price/category tampering.
                                If you need a change, contact support/admin.
                            </div>
                        </div>
                    </div>

                    {{-- ADMIN UI --}}
                    <template x-if="isAdmin">
                        <div class="space-y-4">
                            <div class="relative">
                                <select name="package_type"
                                        x-model="packageType"
                                        @change="
                                            if (packageType !== 'Other') customPackageType = '';
                                            updatePrice();
                                        "
                                        required
                                        class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-emerald-600/50 appearance-none cursor-pointer font-bold">
                                    <option value="Document">Document ($15)</option>
                                    <option value="Small Box">Small Box ($45)</option>
                                    <option value="Medium Box">Medium Box ($85)</option>
                                    <option value="Large Cargo">Large Cargo ($150)</option>
                                    <option value="Fragile">Fragile ($110)</option>
                                    <option value="Electronics">Electronics ($95)</option>
                                    <option value="Other">Other (Manual Entry)</option>
                                </select>
                                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </div>
                            </div>

                            <div x-show="packageType === 'Other'"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                                 x-transition:enter-end="opacity-100 transform translate-y-0"
                                 class="space-y-2">
                                <label class="block text-[10px] font-black uppercase text-blue-400 ml-2">Custom Category Name</label>
                                <input type="text"
                                       name="custom_package_type"
                                       x-model="customPackageType"
                                       class="w-full bg-blue-600/10 border border-blue-500/20 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600"
                                       placeholder="Enter new category name">
                            </div>
                        </div>
                    </template>

                    {{-- NON-ADMIN UI (IMPORTANT FIX) --}}
                    <template x-if="!isAdmin">
                        <div class="space-y-2">
                            {{-- Visible readonly --}}
                            <input type="text"
                                   :value="actualPackageType"
                                   readonly
                                   class="w-full bg-slate-950/60 border border-white/5 rounded-2xl p-4 text-slate-500 cursor-not-allowed font-bold outline-none">

                            {{-- Hidden real value submitted --}}
                            <input type="hidden" name="package_type" :value="actualPackageType">
                        </div>
                    </template>
                </div>

                {{-- ===================== PRICE ===================== --}}
                <div class="group">
                    <div class="flex items-center gap-2 ml-2 mb-2">
                        <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-500">
                            Service Price ($)
                        </label>

                        <div x-show="!isAdmin" class="relative" @mouseenter="showPriceTip=true" @mouseleave="showPriceTip=false">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-slate-800/70 border border-white/10 text-slate-300 text-[10px] font-black cursor-help">i</span>

                            <div x-cloak x-show="showPriceTip" x-transition
                                 class="absolute z-50 left-0 top-7 w-72 p-3 rounded-2xl bg-slate-950/95 border border-white/10 text-slate-200 text-[11px] leading-relaxed shadow-xl">
                                <p class="font-black uppercase tracking-widest text-[9px] text-slate-400 mb-1">Locked</p>
                                Standard prices are fixed for fairness and consistency.
                                Only Admin can adjust pricing.
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">$</span>

                        {{-- Admin can edit, users locked if standard --}}
                        <input type="number" name="price" step="0.01" required
                               x-model="price"
                               :readonly="!isAdmin && isActualStandard()"
                               :min="(!isAdmin && !isActualStandard()) ? 50 : 0"
                               :class="(!isAdmin && isActualStandard()) ? 'bg-slate-950/60 text-slate-500 cursor-not-allowed' : 'bg-emerald-500/5 text-white'"
                               class="w-full border border-emerald-500/20 rounded-2xl p-4 pl-8 outline-none focus:ring-2 focus:ring-emerald-600/50 transition-all">
                    </div>
                </div>
            </div>

            <button type="submit"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-500 text-white font-black rounded-2xl shadow-xl shadow-blue-600/20 transition-all hover:-translate-y-1 active:scale-[0.98]">
                Update Parameters
            </button>

            {{-- ===================== REASSIGN CONFIRM MODAL ===================== --}}
            <div x-cloak x-show="showAssignConfirm"
                 class="fixed inset-0 z-50 flex items-center justify-center px-4"
                 @keydown.escape.window="showAssignConfirm=false">
                <div class="absolute inset-0 bg-black/60" @click="showAssignConfirm=false"></div>

                <div x-transition class="relative w-full max-w-lg rounded-[28px] border border-white/10 bg-slate-950/95 p-6 shadow-2xl">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-purple-500/15 text-purple-300 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-white font-black text-lg tracking-tight">Reassign this shipment?</h3>
                            <p class="mt-2 text-slate-300 text-sm leading-relaxed">
                                You changed the <span class="font-bold text-white">Assigned Customer</span>.
                                This will move the shipment to the selected customer’s dashboard.
                            </p>

                            <div class="mt-6 flex gap-3">
                                <button type="button"
                                        @click="cancelAssignConfirm()"
                                        class="w-1/2 py-3 rounded-2xl border border-white/10 bg-white/5 text-slate-200 font-black hover:bg-white/10 transition">
                                    Cancel
                                </button>

                                <button type="button"
                                        @click="proceedAssignConfirm()"
                                        class="w-1/2 py-3 rounded-2xl bg-emerald-500 text-slate-950 font-black hover:bg-emerald-400 transition">
                                    Yes, Reassign
                                </button>
                            </div>

                            <p class="mt-3 text-[11px] text-slate-400">
                                Tip: Press <span class="font-bold text-slate-200">Esc</span> to close.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- /REASSIGN CONFIRM MODAL --}}
        </form>

        <div class="space-y-8">
            <div class="bg-slate-900/50 p-6 rounded-[32px] border border-white/5 space-y-4">
                <h3 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-500 ml-2">General Actions</h3>
                <a href="{{ route('dashboard') }}"
                   class="block w-full py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 text-center font-black rounded-2xl transition-all border border-white/5 text-xs uppercase tracking-widest">
                    Cancel Changes
                </a>
            </div>

            <div class="bg-red-500/5 p-8 rounded-[40px] border border-red-500/10 space-y-6">
                <div class="flex items-center gap-3 text-red-500">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <h3 class="font-bold uppercase tracking-widest text-xs">Danger Zone</h3>
                </div>
                <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('Confirm deletion?');">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-full py-4 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white border border-red-500/20 font-black rounded-2xl transition-all text-xs uppercase tracking-widest">
                        Delete Shipment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
