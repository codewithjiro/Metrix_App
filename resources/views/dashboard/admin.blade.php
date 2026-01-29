@extends('layouts.app')

@section('content')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div class="max-w-7xl mx-auto px-6 py-8" 
     x-data="{ 
        activeTab: 'shipments', 
        showCreateUserModal: false 
     }">
    
    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-10">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 font-black text-[10px] uppercase tracking-widest flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500 animate-pulse"></span>
                    Admin Console
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight mb-2">
                System Overview
            </h1>
            <p class="text-slate-400 font-medium">
                Welcome back, Administrator. Global logistics status is <span class="text-emerald-400 font-bold">Optimal</span>.
            </p>
        </div>

        {{-- Tab Switcher --}}
        <div class="bg-slate-900/60 p-1 rounded-2xl border border-white/5 flex shadow-inner">
            <button @click="activeTab = 'shipments'" 
                    :class="activeTab === 'shipments' ? 'bg-blue-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                    class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                Shipments
            </button>
            <button @click="activeTab = 'users'" 
                    :class="activeTab === 'users' ? 'bg-purple-600 text-white shadow-lg' : 'text-slate-400 hover:text-white'"
                    class="px-6 py-3 rounded-xl text-xs font-bold uppercase tracking-widest transition-all">
                User Base
            </button>
        </div>
    </div>

    {{-- STATS GRID --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-yellow-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenue</p>
                    <h3 class="text-3xl font-black text-white tracking-tight">
                        <span class="text-lg text-slate-500 align-top mr-1">₱</span>{{ number_format($shipments->sum('price'), 2) }}
                    </h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-2xl">💰</div>
            </div>
        </div>

        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-blue-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Total Parcels</p>
                    <h3 class="text-3xl font-black text-blue-400 tracking-tight">{{ $shipments->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-2xl">📦</div>
            </div>
        </div>

        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-purple-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Users</p>
                    <h3 class="text-3xl font-black text-purple-400 tracking-tight">{{ $users->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-2xl">👥</div>
            </div>
        </div>

        <div class="group p-6 rounded-[28px] border border-white/5 bg-slate-900/60 backdrop-blur-md hover:border-orange-500/30 transition-all duration-300">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-widest mb-1">Pending Action</p>
                    <h3 class="text-3xl font-black text-orange-400 tracking-tight">{{ $shipments->where('status', 'Pending')->count() }}</h3>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-orange-500/10 border border-orange-500/20 flex items-center justify-center text-2xl">⏳</div>
            </div>
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- TAB 1: SHIPMENTS MANAGEMENT --}}
    <div x-show="activeTab === 'shipments'" x-transition>
        <div class="border border-white/5 bg-slate-900/60 backdrop-blur-xl rounded-[32px] overflow-hidden shadow-2xl">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <h3 class="font-bold text-lg text-white">Global Shipment Ledger</h3>
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">Fleet Active</span>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-500 text-[10px] font-black uppercase tracking-[0.15em] border-b border-white/5 bg-slate-950/30">
                            <th class="px-8 py-5 whitespace-nowrap">ID & Sender</th>
                            <th class="px-8 py-5 whitespace-nowrap">Recipient</th>
                            <th class="px-8 py-5 whitespace-nowrap">Value</th>
                            <th class="px-8 py-5 whitespace-nowrap">Status Update</th>
                            <th class="px-8 py-5 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-300">
                        @foreach($shipments as $shipment)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-5">
                                <span class="text-blue-400 font-mono text-xs">MX-{{ str_pad($shipment->id, 5, '0', STR_PAD_LEFT) }}</span>
                                <p class="text-white font-bold text-sm">{{ $shipment->sender_name }}</p>
                            </td>
                            <td class="px-8 py-5 text-sm">
                                <p class="font-bold text-white">{{ $shipment->receiver_name }}</p>
                                <p class="text-[10px] text-slate-500 truncate w-32">{{ $shipment->receiver_address }}</p>
                            </td>
                            <td class="px-8 py-5 text-emerald-400 font-bold">₱{{ number_format($shipment->price, 2) }}</td>
                            
                            {{-- RESTORED QUICK STATUS UPDATE --}}
                            <td class="px-8 py-5">
                                <form action="{{ route('shipments.updateStatus', $shipment->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <select name="status" onchange="this.form.submit()" 
                                            class="bg-slate-950 border border-white/10 text-[10px] font-black uppercase rounded-lg px-3 py-2 text-slate-300 outline-none focus:ring-1 focus:ring-blue-500 cursor-pointer hover:bg-slate-900 transition-all">
                                        <option value="Pending" {{ $shipment->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="In Transit" {{ $shipment->status == 'In Transit' ? 'selected' : '' }}>In Transit</option>
                                        <option value="Delivered" {{ $shipment->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="Cancelled" {{ $shipment->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </form>
                            </td>

                            <td class="px-8 py-5 text-right flex justify-end gap-2">
                                <a href="{{ route('shipments.edit', $shipment->id) }}" class="p-2 bg-slate-800 rounded-lg text-slate-400 hover:text-white transition" title="Manage Info">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('shipments.destroy', $shipment->id) }}" method="POST" onsubmit="return confirm('Admin: Force Delete Record?');">
                                    @csrf @method('DELETE')
                                    <button class="p-2 bg-red-500/10 text-red-500 rounded-lg hover:bg-red-500 hover:text-white transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- TAB 2: USER MANAGEMENT --}}
    <div x-show="activeTab === 'users'" x-cloak x-transition>
        <div class="border border-white/5 bg-slate-900/60 backdrop-blur-xl rounded-[32px] overflow-hidden shadow-2xl">
            <div class="px-8 py-6 border-b border-white/5 flex justify-between items-center bg-white/[0.01]">
                <h3 class="font-bold text-lg text-white">User Registry</h3>
                <button @click="showCreateUserModal = true" class="px-4 py-2 bg-purple-600 rounded-xl text-[10px] font-black uppercase text-white hover:bg-purple-500 transition shadow-lg shadow-purple-600/20">
                    + Create User
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-slate-500 text-[10px] font-black uppercase tracking-[0.15em] border-b border-white/5 bg-slate-950/30">
                            <th class="px-8 py-5">User Profile</th>
                            <th class="px-8 py-5">Email Identity</th>
                            <th class="px-8 py-5">Access Level</th>
                            <th class="px-8 py-5 text-right">Controls</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-slate-300">
                        @foreach($users as $user)
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <td class="px-8 py-5 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-800 border border-white/10 flex items-center justify-center font-bold text-slate-300">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white font-bold text-sm">{{ $user->name }}</p>
                                    <p class="text-[10px] text-slate-500 uppercase">ID: {{ $user->id }}</p>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm font-mono text-slate-400">{{ $user->email }}</td>
                            <td class="px-8 py-5">
                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase border {{ $user->role == 1 ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 'bg-slate-500/10 text-slate-400 border-slate-500/20' }}">
                                    {{ $user->role == 1 ? 'Admin' : 'Client' }}
                                </span>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($user->role != 1)
                                        <form action="{{ route('admin.promote', $user->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button class="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-lg text-[9px] font-black hover:bg-emerald-500 hover:text-white transition uppercase">Promote</button>
                                        </form>

                                        <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST" onsubmit="return confirm('WARNING: Permanently delete this user and all data?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-red-500/10 text-red-500 border border-red-500/20 rounded-lg hover:bg-red-500 hover:text-white transition">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-600 font-bold uppercase italic">Protected</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CREATE USER MODAL --}}
    <div x-cloak x-show="showCreateUserModal" class="fixed inset-0 z-50 flex items-center justify-center px-4">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" @click="showCreateUserModal = false"></div>
        <div x-transition class="relative w-full max-w-lg rounded-[32px] border border-white/10 bg-slate-900 p-8 shadow-2xl">
            <h3 class="text-2xl font-black text-white mb-6">Create New User</h3>
            <form action="{{ route('admin.createUser') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" required placeholder="Full Name" class="w-full bg-slate-800 border-none rounded-xl p-3 text-white text-sm outline-none focus:ring-2 focus:ring-purple-600">
                <input type="email" name="email" required placeholder="Email Address" class="w-full bg-slate-800 border-none rounded-xl p-3 text-white text-sm outline-none focus:ring-2 focus:ring-purple-600">
                <input type="password" name="password" required placeholder="Password" class="w-full bg-slate-800 border-none rounded-xl p-3 text-white text-sm outline-none focus:ring-2 focus:ring-purple-600">
                <input type="password" name="password_confirmation" required placeholder="Confirm Password" class="w-full bg-slate-800 border-none rounded-xl p-3 text-white text-sm outline-none focus:ring-2 focus:ring-purple-600">
                <select name="role" class="w-full bg-slate-800 border-none rounded-xl p-3 text-white text-sm">
                    <option value="0">Client</option>
                    <option value="1">Administrator</option>
                </select>
                <div class="flex gap-3 pt-4">
                    <button type="button" @click="showCreateUserModal = false" class="flex-1 py-3 bg-white/5 text-slate-300 font-bold rounded-xl">Cancel</button>
                    <button type="submit" class="flex-1 py-3 bg-purple-600 text-white font-bold rounded-xl shadow-lg shadow-purple-600/20">Create Account</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection