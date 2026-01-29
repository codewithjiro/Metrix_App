@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    
    {{-- HEADER SECTION --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="px-3 py-1 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Client Portal
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">
                Hello, {{ Auth::user()->name }}
            </h1>
            <p class="text-slate-400 font-medium">
                Overview of your logistics operations. You have 
                <span class="text-white border-b border-blue-500 pb-0.5">{{ $shipments->where('status', '!=', 'Delivered')->where('status', '!=', 'Cancelled')->count() }} active</span> 
                deliveries in the network.
            </p>
        </div>

        <a href="{{ route('shipments.create') }}" class="group relative inline-flex items-center gap-3 bg-gradient-to-r from-blue-600 to-blue-500 hover:from-blue-500 hover:to-blue-400 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-xl shadow-blue-600/20 hover:-translate-y-1 overflow-hidden">
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            <span class="relative text-lg leading-none mb-0.5">+</span> 
            <span class="relative text-sm font-bold uppercase tracking-widest">New Booking</span>
        </a>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-yellow-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Spend</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">
                        <span class="text-lg text-slate-500 align-top mr-1">₱</span>{{ number_format($shipments->sum('price'), 2) }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-2xl shadow-[0_0_15px_rgba(234,179,8,0.1)]">💰</div>
            </div>
            <div class="w-full bg-slate-800 h-1 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-500 w-[70%]"></div>
            </div>
        </div>

        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-blue-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">In Transit</p>
                    <h3 class="text-3xl font-black text-blue-400 tracking-tight">{{ $shipments->where('status', 'In Transit')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-2xl shadow-[0_0_15px_rgba(59,130,246,0.1)]">🚚</div>
            </div>
            <div class="w-full bg-slate-800 h-1 rounded-full overflow-hidden">
                <div class="h-full bg-blue-500 w-[45%] animate-pulse"></div>
            </div>
        </div>

        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-emerald-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Completed</p>
                    <h3 class="text-3xl font-black text-emerald-400 tracking-tight">{{ $shipments->where('status', 'Delivered')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-2xl shadow-[0_0_15px_rgba(16,185,129,0.1)]">✅</div>
            </div>
            <div class="w-full bg-slate-800 h-1 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 w-full"></div>
            </div>
        </div>

        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-red-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Cancelled</p>
                    <h3 class="text-3xl font-black text-red-400 tracking-tight">{{ $shipments->where('status', 'Cancelled')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-red-500/10 border border-red-500/20 flex items-center justify-center text-2xl shadow-[0_0_15px_rgba(239,68,68,0.1)]">🛑</div>
            </div>
            <div class="w-full bg-slate-800 h-1 rounded-full overflow-hidden">
                <div class="h-full bg-red-500 w-[10%]"></div>
            </div>
        </div>
    </div>

    {{-- MAIN TABLE CARD --}}
    <div class="border border-white/5 bg-slate-900/60 backdrop-blur-xl rounded-[32px] overflow-hidden shadow-2xl relative">
        
        <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center flex-wrap gap-4 bg-white/[0.01]">
            <div>
                <h3 class="font-bold text-lg text-white">Recent Activity</h3>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-1">Detailed logistics ledger</p>
            </div>
            <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 rounded-full">System Live</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 text-[10px] font-black uppercase tracking-[0.15em] border-b border-white/5 bg-slate-950/30">
                        <th class="px-8 py-5">Item & Sender</th>
                        <th class="px-8 py-5">Recipient & Destination</th>
                        <th class="px-8 py-5">Logistics Status</th>
                        <th class="px-8 py-5 text-right">Controls</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-white/[0.02] transition-colors group">
                        
                        {{-- Item & Sender --}}
                        <td class="px-8 py-5">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 bg-slate-800 rounded-xl flex items-center justify-center text-lg border border-white/5 group-hover:border-blue-500/50 transition-all shrink-0">📦</div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-white font-bold text-sm tracking-tight">MX-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        <span class="text-[9px] px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-400 border border-blue-500/20 font-black uppercase">{{ $shipment->package_type }}</span>
                                    </div>
                                    <p class="text-slate-300 text-xs font-bold">{{ $shipment->sender_name }}</p>
                                    <p class="text-slate-500 text-[10px] leading-tight mt-1 max-w-[200px] italic">{{ $shipment->sender_address }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Recipient & Destination --}}
                        <td class="px-8 py-5">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-1.5">
                                    <svg class="w-3 h-3 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    <p class="text-white font-bold text-xs">{{ $shipment->receiver_name }}</p>
                                </div>
                                <div class="flex items-start gap-1.5">
                                    <svg class="w-3 h-3 text-slate-500 mt-0.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    <p class="text-slate-500 text-[10px] leading-tight max-w-[220px]">{{ $shipment->receiver_address }}</p>
                                </div>
                            </div>
                        </td>

                        {{-- Status & Cost --}}
                        <td class="px-8 py-5">
                            <div class="flex flex-col items-start gap-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[9px] font-black uppercase tracking-widest border
                                    {{ $shipment->status == 'Delivered' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : '' }}
                                    {{ $shipment->status == 'Cancelled' ? 'bg-red-500/10 text-red-400 border-red-500/20' : '' }}
                                    {{ in_array($shipment->status, ['Pending', 'In Transit']) ? 'bg-blue-500/10 text-blue-400 border-blue-500/20' : '' }}">
                                    <span class="w-1 h-1 rounded-full bg-current {{ !in_array($shipment->status, ['Delivered', 'Cancelled']) ? 'animate-ping' : '' }}"></span>
                                    {{ $shipment->status }}
                                </span>
                                <p class="text-slate-200 text-xs font-black tracking-tight">₱{{ number_format($shipment->price, 2) }}</p>
                            </div>
                        </td>

                        {{-- Controls --}}
                        <td class="px-8 py-5 text-right">
                            @if($shipment->user_id === Auth::id() && $shipment->status == 'Pending')
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('shipments.edit', $shipment->id) }}" class="p-2.5 bg-slate-800 hover:bg-blue-600 text-slate-400 hover:text-white rounded-xl transition-all shadow-lg" title="Edit">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to cancel this shipment?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2.5 bg-slate-800 hover:bg-red-500/20 text-slate-500 hover:text-red-400 rounded-xl transition-all border border-transparent hover:border-red-500/30" title="Delete">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex justify-end items-center gap-2 px-3 py-2 bg-white/5 rounded-xl border border-white/5 w-fit ml-auto opacity-40 grayscale cursor-not-allowed">
                                    <span class="text-[9px] font-black uppercase text-slate-500">Locked</span>
                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center justify-center opacity-20">
                                <svg class="w-16 h-16 text-slate-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                                <span class="font-black text-xs uppercase tracking-[0.3em] text-slate-400">No Shipping Records Found</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection