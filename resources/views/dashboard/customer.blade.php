@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="text-blue-500 font-black text-xs uppercase tracking-[0.3em]">Client Portal</span>
                <div class="w-1 h-1 rounded-full bg-slate-700"></div>
                <span class="text-slate-500 font-bold text-xs uppercase tracking-widest">Live Status</span>
            </div>
            <h1 class="text-4xl font-black text-white tracking-tight mb-2">Hello, {{ Auth::user()->name }}</h1>
            <p class="text-slate-400">Welcome back. You have <span class="text-blue-400 font-bold">{{ $shipments->where('status', '!=', 'Delivered')->where('status', '!=', 'Cancelled')->count() }} active</span> deliveries.</p>
        </div>
        <a href="{{ route('shipments.create') }}" class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-500 text-white px-8 py-4 rounded-2xl font-bold transition-all shadow-xl shadow-blue-600/20 hover:-translate-y-1">
            <span>+</span> New Shipment
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="glass-card p-8 rounded-[32px] border border-white/5 bg-slate-900/40">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Total Spent</p>
            <h3 class="text-3xl font-black text-white">${{ number_format($shipments->sum('price'), 2) }}</h3>
        </div>
        <div class="glass-card p-8 rounded-[32px] border border-white/5 bg-slate-900/40">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">In Transit</p>
            <h3 class="text-3xl font-black text-blue-400">{{ $shipments->where('status', 'In Transit')->count() }}</h3>
        </div>
        <div class="glass-card p-8 rounded-[32px] border border-white/5 bg-slate-900/40">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2">Completed</p>
            <h3 class="text-3xl font-black text-emerald-400">{{ $shipments->where('status', 'Delivered')->count() }}</h3>
        </div>
        <div class="glass-card p-8 rounded-[32px] border border-red-500/10 bg-slate-900/40">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-2 text-red-500/80">Cancelled</p>
            <h3 class="text-3xl font-black text-red-400">{{ $shipments->where('status', 'Cancelled')->count() }}</h3>
        </div>
    </div>

    <div class="glass-card rounded-[32px] overflow-hidden border border-white/5 bg-slate-900/40">
        <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02] flex justify-between items-center flex-wrap gap-4">
            <div>
                <h3 class="font-bold text-lg text-white">Recent Deliveries</h3>
                <p class="text-[10px] text-slate-500 font-medium mt-0.5 uppercase tracking-wider">Only self-booked parcels are editable during pending phase.</p>
            </div>
            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest bg-slate-900 px-3 py-1.5 rounded-lg border border-white/5">Fleet Monitor Live</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] bg-slate-950/50">
                        <th class="px-8 py-5">Parcel & Origin</th>
                        <th class="px-8 py-5">Destination Info</th>
                        <th class="px-8 py-5">Logistics Status</th>
                        <th class="px-8 py-5 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($shipments as $shipment)
                    <tr class="hover:bg-blue-600/[0.03] transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-slate-800 rounded-2xl flex items-center justify-center text-xl border border-white/5 group-hover:border-blue-500/30 transition-colors">📦</div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-white font-bold text-sm">#MX-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</p>
                                        <span class="text-[9px] px-2 py-0.5 rounded-md bg-blue-500/10 text-blue-400 font-black border border-blue-500/20 uppercase tracking-tighter">{{ $shipment->package_type }}</span>
                                    </div>
                                    <p class="text-slate-500 text-[10px] font-medium uppercase tracking-tight">From: <span class="text-slate-300">{{ $shipment->sender_name }}</span></p>
                                </div>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-sm">
                            <p class="text-white font-bold text-sm tracking-tight">{{ $shipment->receiver_name }}</p>
                            <p class="text-slate-500 text-[11px] leading-tight line-clamp-2 max-w-[200px] italic">{{ $shipment->receiver_address }}</p>
                        </td>

                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest w-fit 
                                    {{ $shipment->status == 'Delivered' ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : '' }}
                                    {{ $shipment->status == 'Cancelled' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                                    {{ in_array($shipment->status, ['Pending', 'In Transit']) ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current {{ !in_array($shipment->status, ['Delivered', 'Cancelled']) ? 'animate-pulse' : '' }}"></span>
                                    {{ $shipment->status }}
                                </span>
                                <p class="text-emerald-500 text-xs font-black tracking-widest ml-1">${{ number_format($shipment->price, 2) }}</p>
                            </div>
                        </td>

                        <td class="px-8 py-6 text-right">
                            <div class="flex flex-col items-end gap-1">
                                @if($shipment->user_id === Auth::id() && $shipment->status == 'Pending')
                                    <div class="flex gap-2">
                                        <a href="{{ route('shipments.edit', $shipment->id) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition-all font-bold text-[10px] uppercase tracking-widest shadow-lg shadow-blue-600/20">Manage</a>
                                        
                                        <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('WARNING: Are you sure you want to cancel this shipment?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="px-4 py-2 bg-red-500/10 hover:bg-red-500 hover:text-white text-red-500 rounded-xl font-bold text-[10px] uppercase border border-red-500/20 transition-all">Cancel</button>
                                        </form>
                                    </div>
                                @else
                                    <div class="group relative inline-block">
                                        <div class="flex items-center gap-2 text-slate-600 bg-white/5 px-3 py-2 rounded-xl border border-white/5 cursor-help">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                            <span class="text-[10px] font-black uppercase tracking-widest">Locked</span>
                                        </div>
                                        <div class="absolute right-0 bottom-full mb-2 hidden group-hover:block w-48 p-2 bg-slate-900 border border-white/10 rounded-lg shadow-2xl z-50">
                                            <p class="text-[9px] text-slate-400 uppercase font-bold text-center">
                                                {{ $shipment->status != 'Pending' ? 'Editing disabled for parcels in transit/finalized' : 'Managed by System Administrator' }}
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-20 text-center opacity-20 uppercase font-black text-xs text-white">No Shipping Records Found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection