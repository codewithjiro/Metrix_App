@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-6 text-white">
    <div class="mb-10 flex flex-col md:flex-row justify-between items-end gap-6">
        <div>
            <nav class="flex mb-4 text-xs font-bold uppercase tracking-widest text-slate-500">
                <span class="text-blue-400">Admin Console</span>
                <span class="mx-2">/</span>
                <span>System Overview</span>
            </nav>
            <h1 class="text-5xl font-extrabold text-white tracking-tighter mb-1">Hello, {{ Auth::user()->name }}</h1>
            <p class="text-slate-400 font-medium italic">Overseeing all global shipments and logistics pipelines.</p>
        </div>

        <form action="{{ route('dashboard') }}" method="GET" class="flex gap-2 w-full md:w-auto">
            <div class="relative flex-1 md:w-80">
                <input type="text" name="search" placeholder="Search ID, Name, or Address..." value="{{ request('search') }}"
                    class="w-full bg-slate-800/50 border border-white/10 rounded-2xl px-6 py-3 text-sm outline-none focus:ring-2 focus:ring-blue-600 transition-all text-white">
            </div>
            <button type="submit" class="bg-blue-600 p-3 rounded-2xl hover:bg-blue-500 transition shadow-lg shadow-blue-600/20 text-white">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="glass-card p-6 rounded-[32px] border-l-4 border-emerald-500 bg-slate-900/40 shadow-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Total Revenue</p>
            <h3 class="text-3xl font-black text-emerald-400">${{ number_format($shipments->sum('price'), 2) }}</h3>
        </div>
        <div class="glass-card p-6 rounded-[32px] border-l-4 border-blue-500 bg-slate-900/40 shadow-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Active Pipeline</p>
            <h3 class="text-3xl font-black text-white">{{ $shipments->count() }}</h3>
        </div>
        <div class="glass-card p-6 rounded-[32px] border-l-4 border-yellow-500 bg-slate-900/40 shadow-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Pending Pickups</p>
            <h3 class="text-3xl font-black text-white">{{ $shipments->where('status', 'Pending')->count() }}</h3>
        </div>
        <div class="glass-card p-6 rounded-[32px] border-l-4 border-indigo-500 bg-slate-900/40 shadow-xl">
            <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1">Registered Users</p>
            <h3 class="text-3xl font-black text-white">{{ \App\Models\User::count() }}</h3>
        </div>
    </div>

    <div class="glass-card rounded-[40px] overflow-hidden border border-white/5 bg-slate-900/40 shadow-2xl">
        <div class="px-8 py-6 border-b border-white/5 bg-white/[0.02]">
            <h3 class="font-bold text-xl text-white">Active Shipments Fleet</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-white">
                <thead>
                    <tr class="bg-slate-900/30 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                        <th class="px-8 py-4">Sender / Tracking</th>
                        <th class="px-8 py-4">Destination</th>
                        <th class="px-8 py-4">Live Status</th>
                        <th class="px-8 py-4">Quick Update</th>
                        <th class="px-8 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5 text-slate-300">
                    @forelse($shipments as $shipment)
                    <tr class="group hover:bg-white/[0.03] transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-slate-700 to-slate-800 flex items-center justify-center font-bold text-blue-400">
                                    {{ strtoupper(substr($shipment->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $shipment->sender_name }}</p>
                                    <p class="text-blue-400 text-[10px] font-black tracking-widest uppercase">ID: MX-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <span class="text-[9px] px-2 py-0.5 rounded bg-slate-800 text-slate-400 font-black uppercase border border-white/5 inline-block mb-1">{{ $shipment->package_type }}</span>
                            <p class="text-slate-200 text-xs font-bold">To: {{ $shipment->receiver_name }}</p>
                            <p class="text-slate-500 text-[10px] truncate w-48">{{ $shipment->receiver_address }}</p>
                        </td>
                        <td class="px-8 py-6">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest 
                                {{ $shipment->status == 'Delivered' ? 'bg-emerald-500/10 text-emerald-400' : '' }}
                                {{ $shipment->status == 'Cancelled' ? 'bg-red-500/10 text-red-400' : '' }}
                                {{ in_array($shipment->status, ['Pending', 'In Transit']) ? 'bg-blue-500/10 text-blue-400' : '' }}">
                                {{ $shipment->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6">
                            <form action="{{ route('shipments.updateStatus', $shipment->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <select name="status" onchange="this.form.submit()" class="bg-slate-950 border border-white/5 text-[10px] font-bold uppercase rounded-lg px-2 py-1 text-slate-300 outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer">
                                    <option value="Pending" {{ $shipment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Transit" {{ $shipment->status == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                                    <option value="Delivered" {{ $shipment->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="Cancelled" {{ $shipment->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('shipments.edit', $shipment->id) }}" class="p-2 bg-slate-800 rounded-xl text-slate-400 hover:text-white transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('WARNING: Permanent deletion of data. Proceed?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 bg-slate-800 rounded-xl text-slate-500 hover:bg-red-500/20 hover:text-red-400 transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-8 py-20 text-center opacity-30 text-white font-black text-xs">No Records Matching Search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection