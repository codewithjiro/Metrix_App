@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-5xl mx-auto px-6"
     x-data="{
        packageType: 'Document',
        customType: '',
        customPrice: '',
        showConfirm: false,

        rates: {
            'Document': 15.00,
            'Small Box': 45.00,
            'Medium Box': 85.00,
            'Large Cargo': 150.00,
            'Fragile': 110.00,
            'Electronics': 95.00,
            'Other': 0.00
        },

        get displayPrice() {
            if (this.packageType === 'Other') {
                return parseFloat(this.customPrice) || 0;
            }
            return this.rates[this.packageType];
        },

        get isValid() {
            if (this.packageType === 'Other') {
                return this.displayPrice >= 50 && this.customType.trim().length > 0;
            }
            return true;
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
            this.$root.querySelector('form').submit();
        }
     }"
>
    <div class="mb-12 text-center">
        <h1 class="text-5xl font-black text-white tracking-tighter mb-3">Initialize <span class="text-blue-500">Shipment</span></h1>
        <p class="text-slate-400 font-medium tracking-wide">Enter logistics parameters for secure dispatch.</p>
    </div>

    @if ($errors->any())
        <div class="mb-8 bg-red-500/10 border border-red-500/20 text-red-400 p-5 rounded-[24px] text-xs font-bold uppercase tracking-widest animate-pulse">
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('shipments.store') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="glass-card p-8 rounded-[40px] relative overflow-hidden border border-white/5 bg-slate-900/40">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 rounded-xl bg-blue-600/20 text-blue-400 flex items-center justify-center text-xs font-black">01</div>
                        <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Origin Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Sender Name</label>
                            <input type="text" name="sender_name" required
                                   class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50"
                                   placeholder="Full legal name">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Pickup Address</label>
                            <input type="text" name="sender_address" required
                                   class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-blue-600/50"
                                   placeholder="Street, City, Zip">
                        </div>
                    </div>
                </div>

                <div class="glass-card p-8 rounded-[40px] border border-white/5 bg-slate-900/40">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-8 h-8 rounded-xl bg-indigo-600/20 text-indigo-400 flex items-center justify-center text-xs font-black">02</div>
                        <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Destination Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Receiver Name</label>
                            <input type="text" name="receiver_name" required
                                   class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-indigo-600/50"
                                   placeholder="Recipient name">
                        </div>
                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Delivery Address</label>
                            <input type="text" name="receiver_address" required
                                   class="w-full bg-slate-800/40 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-indigo-600/50"
                                   placeholder="Full destination address">
                        </div>
                    </div>
                </div>

                {{-- ✅ ADMIN ONLY: ASSIGN TO CUSTOMER --}}
                @if(Auth::user()->role == 1)
                    <div class="glass-card p-8 rounded-[40px] border border-white/5 bg-slate-900/40">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-xl bg-purple-600/20 text-purple-400 flex items-center justify-center text-xs font-black">A</div>
                            <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Admin Assignment</h3>
                        </div>

                        <div class="group">
                            <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Assign Shipment To Customer</label>
                            <select name="user_id" required
                                    class="w-full bg-slate-800/60 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-purple-600/50 appearance-none cursor-pointer">
                                <option value="" disabled selected>Select customer</option>
                                @foreach(($users ?? []) as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }}</option>
                                @endforeach
                            </select>
                            <p class="mt-2 text-[10px] text-slate-500 uppercase font-bold tracking-widest">
                                Customer will see this shipment in their dashboard.
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            <div class="lg:col-span-1 space-y-8">
                <div class="glass-card p-8 rounded-[40px] border border-white/5 bg-slate-900/40">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-xl bg-emerald-600/20 text-emerald-400 flex items-center justify-center text-xs font-black">03</div>
                        <h3 class="font-bold text-white uppercase tracking-[0.2em] text-xs">Logistics</h3>
                    </div>

                    <div class="group">
                        <label class="block text-[10px] font-black uppercase text-slate-500 mb-2">Parcel Category</label>
                        <select name="package_type" x-model="packageType"
                                class="w-full bg-slate-800/60 border border-white/5 rounded-2xl p-4 text-white outline-none focus:ring-2 focus:ring-emerald-600/50 appearance-none cursor-pointer">
                            <option value="Document">Document ($15)</option>
                            <option value="Small Box">Small Box ($45)</option>
                            <option value="Medium Box">Medium Box ($85)</option>
                            <option value="Large Cargo">Large Cargo ($150)</option>
                            <option value="Fragile">Fragile ($110)</option>
                            <option value="Electronics">Electronics ($95)</option>
                            <option value="Other">Other (Custom)</option>
                        </select>
                    </div>

                    <template x-if="packageType === 'Other'">
                        <div class="space-y-4 pt-4 border-t border-white/5 animate-in slide-in-from-top-4">
                            <input type="text" name="custom_package_type" x-model="customType"
                                   class="w-full bg-blue-600/10 border border-blue-500/20 rounded-2xl p-4 text-white outline-none focus:ring-2"
                                   placeholder="Custom Category Name">
                            <div>
                                <input type="number" name="custom_price" x-model="customPrice" step="0.01"
                                       class="w-full bg-blue-600/10 border border-blue-500/20 rounded-2xl p-4 text-white outline-none focus:ring-2"
                                       placeholder="Your Offer (Min $50.00)">
                                <p x-show="customPrice > 0 && customPrice < 50"
                                   class="text-[9px] text-red-400 font-bold mt-2 uppercase tracking-tighter italic">
                                    ⚠️ Minimum price for custom cargo is $50.00
                                </p>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[40px] p-8 shadow-2xl flex flex-col justify-between min-h-[250px] relative overflow-hidden group">
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-150 transition-all duration-700"></div>
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.3em] text-white/60 mb-1">Final Estimate</p>
                        <div class="flex items-baseline gap-1">
                            <span class="text-2xl font-bold text-white/80">$</span>
                            <h2 class="text-6xl font-black text-white tracking-tighter" x-text="displayPrice.toFixed(2)"></h2>
                        </div>
                    </div>

                    <button type="submit"
                            @click="openConfirm($event)"
                            :disabled="!isValid"
                            :class="isValid ? 'bg-white text-blue-700 shadow-xl' : 'bg-white/20 text-white/50 cursor-not-allowed'"
                            class="w-full py-5 font-black rounded-[24px] transition-all active:scale-[0.98]">
                        Confirm Booking
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirmation Dialog / Modal -->
        <div x-cloak x-show="showConfirm"
             class="fixed inset-0 z-50 flex items-center justify-center px-4"
             @keydown.escape.window="closeConfirm()">
            <div class="absolute inset-0 bg-black/60" @click="closeConfirm()"></div>

            <div x-transition
                 class="relative w-full max-w-lg rounded-[28px] border border-white/10 bg-slate-950/95 p-6 shadow-2xl">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-500/15 text-amber-300 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-white font-black text-lg tracking-tight">Continue booking?</h3>
                        <p class="mt-2 text-slate-300 text-sm leading-relaxed">
                            Please double-check your <span class="font-bold text-white">Category</span> and <span class="font-bold text-white">Price</span>.
                            Once your shipment is created, the status becomes <span class="font-bold text-emerald-300">Pending</span> and you will no longer be able to edit the category/price later.
                        </p>

                        <div class="mt-4 rounded-2xl border border-white/10 bg-white/5 p-4 text-sm text-slate-200">
                            <div class="flex items-center justify-between">
                                <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Selected Category</span>
                                <span class="font-black text-white" x-text="packageType === 'Other' ? customType : packageType"></span>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-slate-400 font-bold text-xs uppercase tracking-widest">Estimated Price</span>
                                <span class="font-black text-white">$<span x-text="displayPrice.toFixed(2)"></span></span>
                            </div>
                        </div>

                        <div class="mt-6 flex gap-3">
                            <button type="button"
                                    @click="closeConfirm()"
                                    class="w-1/2 py-3 rounded-2xl border border-white/10 bg-white/5 text-slate-200 font-black hover:bg-white/10 transition">
                                Review
                            </button>

                            <button type="button"
                                    @click="confirmAndSubmit()"
                                    class="w-1/2 py-3 rounded-2xl bg-emerald-500 text-slate-950 font-black hover:bg-emerald-400 transition">
                                Yes, Continue
                            </button>
                        </div>

                        <p class="mt-3 text-[11px] text-slate-400">
                            Tip: If you’re unsure, click <span class="font-bold text-slate-200">Review</span> and adjust before submitting.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Confirmation Dialog -->

    </form>
</div>
@endsection
