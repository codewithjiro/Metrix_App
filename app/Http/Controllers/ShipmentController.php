<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = Shipment::query();

        // Admin global search
        if (Auth::user()->role == 1 && $request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('sender_name', 'like', "%{$search}%")
                  ->orWhere('receiver_name', 'like', "%{$search}%")
                  ->orWhere('receiver_address', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%");
            });
        }

        if (Auth::user()->role == 1) {
            $shipments = $query->latest()->get();
            return view('dashboard.admin', compact('shipments'));
        } else {
            $shipments = Auth::user()->shipments()
                ->when($request->filled('search'), function($q) use ($request) {
                    $q->where('receiver_name', 'like', "%{$request->search}%");
                })
                ->latest()
                ->get();

            return view('dashboard.customer', compact('shipments'));
        }
    }

    public function create()
    {
        // ✅ Admin can assign shipment to a customer
        $users = [];
        if (Auth::user()->role == 1) {
            $users = User::where('role', 0)->orderBy('name')->get();
        }

        return view('shipments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $isAdmin = Auth::user()->role == 1;

        $request->validate([
            'sender_name' => 'required|string|max:255',
            'sender_address' => 'required|string',
            'receiver_name' => 'required|string|max:255',
            'receiver_address' => 'required|string',
            'package_type' => 'required|string',
            'custom_package_type' => 'required_if:package_type,Other',
            'custom_price' => 'nullable|numeric|min:50',

            // ✅ only admin can set user_id
            'user_id' => $isAdmin ? 'required|exists:users,id' : 'nullable',
        ]);

        $rates = [
            'Document' => 15.00,
            'Small Box' => 45.00,
            'Medium Box' => 85.00,
            'Large Cargo' => 150.00,
            'Fragile' => 110.00,
            'Electronics' => 95.00
        ];

        $data = $request->all();

        if ($request->package_type === 'Other') {
            $data['package_type'] = $request->custom_package_type;
            $data['price'] = $request->custom_price;
        } else {
            $data['price'] = $rates[$request->package_type] ?? 0;
        }

        // ✅ assign owner
        if ($isAdmin) {
            $data['user_id'] = (int) $request->user_id;
            Shipment::create($data);
        } else {
            Auth::user()->shipments()->create($data);
        }

        return redirect()->route('dashboard')->with('success', 'Shipment booked successfully.');
    }

    public function edit(Shipment $shipment)
    {
        if (Auth::user()->role != 1) {
            if ($shipment->user_id !== Auth::id()) abort(403);
            if ($shipment->status !== 'Pending') {
                return redirect()->route('dashboard')->with('error', 'Modification locked.');
            }
        }

        $users = [];
        if (Auth::user()->role == 1) {
            $users = User::where('role', 0)->orderBy('name')->get(); // customers only
        }

        return view('shipments.edit', compact('shipment', 'users'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $isAdmin = Auth::user()->role == 1;

        if (!$isAdmin && ($shipment->user_id !== Auth::id() || $shipment->status !== 'Pending')) {
            abort(403);
        }

        $rates = [
            'Document' => 15.00,
            'Small Box' => 45.00,
            'Medium Box' => 85.00,
            'Large Cargo' => 150.00,
            'Fragile' => 110.00,
            'Electronics' => 95.00
        ];

        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_address' => 'required|string',
            'package_type' => 'required|string',
            'custom_package_type' => 'required_if:package_type,Other',

            // ✅ admin can re-assign
            'user_id' => $isAdmin ? 'required|exists:users,id' : 'nullable',

            'price' => [
                'required',
                'numeric',
                (!$isAdmin && $request->package_type === 'Other') ? 'min:50' : 'min:0',
            ],
        ]);

        $data = $request->only(['receiver_name', 'receiver_address', 'price', 'package_type']);

        // ✅ if admin, include user_id update
        if ($isAdmin) {
            $data['user_id'] = (int) $request->user_id;
        }

        if ($request->package_type === 'Other') {
            $data['package_type'] = $request->custom_package_type;
        } else {
            if (isset($rates[$request->package_type])) {
                $data['price'] = $rates[$request->package_type];
            }
        }

        $shipment->update($data);

        return redirect()->route('dashboard')->with('success', 'Shipment updated successfully.');
    }


    public function updateStatus(Request $request, Shipment $shipment)
    {
        if (Auth::user()->role != 1) abort(403);

        $request->validate([
            'status' => 'required|in:Pending,In Transit,Delivered,Cancelled'
        ]);

        $shipment->update(['status' => $request->status]);

        return back()->with('success', "Status updated.");
    }

    public function destroy(Shipment $shipment)
    {
        if (Auth::user()->role != 1) {
            if ($shipment->user_id !== Auth::id() || $shipment->status !== 'Pending') abort(403);
        }

        $shipment->delete();

        return redirect()->route('dashboard')->with('success', 'Shipment record removed.');
    }

    public function user() {
    return $this->belongsTo(\App\Models\User::class);
    }
}
