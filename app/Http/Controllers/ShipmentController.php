<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ShipmentController extends Controller
{
    /**
     * Display the Dashboard (Admin & Customer).
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $query = Shipment::query();

        // 1. ADMIN DASHBOARD LOGIC
        if (Auth::user()->role == 1) {
            
            if ($request->filled('search')) {
                $search = $request->get('search');
                $query->where(function($q) use ($search) {
                    $q->where('id', 'like', "%{$search}%")
                      ->orWhere('sender_name', 'like', "%{$search}%")
                      ->orWhere('receiver_name', 'like', "%{$search}%")
                      ->orWhere('receiver_address', 'like', "%{$search}%")
                      ->orWhere('status', 'like', "%{$search}%");
                });
            }

            $shipments = $query->latest()->get();
            $users = User::latest()->get(); // Needed for User Management Tab

            return view('dashboard.admin', compact('shipments', 'users'));
        } 
        
        // 2. CUSTOMER DASHBOARD LOGIC
        else {
            $shipments = Auth::user()->shipments()
                ->when($request->filled('search'), function($q) use ($request) {
                    $q->where('receiver_name', 'like', "%{$request->search}%")
                      ->orWhere('id', 'like', "%{$request->search}%");
                })
                ->latest()
                ->get();

            return view('dashboard.customer', compact('shipments'));
        }
    }

    /**
     * Show the create shipment form.
     */
    public function create()
    {
        $users = [];
        if (Auth::user()->role == 1) {
            $users = User::where('role', 0)->orderBy('name')->get();
        }
        return view('shipments.create', compact('users'));
    }

    /**
     * Store a newly created shipment.
     */
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
            'user_id' => $isAdmin ? 'required|exists:users,id' : 'nullable',
        ]);

        $rates = [
            'Document' => 150.00,
            'Small Box' => 250.00,
            'Medium Box' => 450.00,
            'Large Cargo' => 850.00,
            'Fragile' => 550.00,
            'Electronics' => 600.00
        ];

        $data = $request->all();

        // Price Calculation Logic
        if ($request->package_type === 'Other') {
            $data['package_type'] = $request->custom_package_type;
            $data['price'] = $request->custom_price; // Get from input
        } else {
            // Force standard rate from array (security)
            $data['price'] = $rates[$request->package_type] ?? 0;
        }

        // Ownership Assignment
        if ($isAdmin) {
            $data['user_id'] = (int) $request->user_id;
            Shipment::create($data);
        } else {
            Auth::user()->shipments()->create($data);
        }

        return redirect()->route('dashboard')->with('success', 'Shipment booked successfully.');
    }

    /**
     * Show the edit form.
     */
    public function edit(Shipment $shipment)
    {
        if (Auth::user()->role != 1) {
            if ($shipment->user_id !== Auth::id()) abort(403);
            if ($shipment->status !== 'Pending') {
                return redirect()->route('dashboard')->with('error', 'Modification locked for this shipment.');
            }
        }

        $users = [];
        if (Auth::user()->role == 1) {
            $users = User::where('role', 0)->orderBy('name')->get();
        }

        return view('shipments.edit', compact('shipment', 'users'));
    }

    /**
     * Update the shipment.
     */
    public function update(Request $request, Shipment $shipment)
    {
        $isAdmin = Auth::user()->role == 1;

        if (!$isAdmin && ($shipment->user_id !== Auth::id() || $shipment->status !== 'Pending')) {
            abort(403);
        }

        $request->validate([
            'sender_name' => 'required|string|max:255',    // ✅ ADDED
            'sender_address' => 'required|string',         // ✅ ADDED
            'receiver_name' => 'required|string|max:255',
            'receiver_address' => 'required|string',
            'package_type' => 'required|string',
            'custom_package_type' => 'required_if:package_type,Other',
            'price' => 'required|numeric|min:0',
            'status' => $isAdmin ? 'required|in:Pending,In Transit,Delivered,Cancelled' : 'nullable',
            'user_id' => $isAdmin ? 'required|exists:users,id' : 'nullable',
        ]);

        $rates = [
            'Document' => 150.00, 'Small Box' => 250.00, 'Medium Box' => 450.00,
            'Large Cargo' => 850.00, 'Fragile' => 550.00, 'Electronics' => 600.00
        ];

        // 1. Common Data
        $data = [
            'sender_name' => $request->sender_name,        // ✅ SAVING
            'sender_address' => $request->sender_address,  // ✅ SAVING
            'receiver_name' => $request->receiver_name,
            'receiver_address' => $request->receiver_address,
        ];

        // 2. Admin Specific Data
        if ($isAdmin) {
            $data['status'] = $request->status;
            $data['user_id'] = (int) $request->user_id;
            $data['price'] = $request->price;

            // ✅ FIX: Custom Type Logic
            if ($request->package_type === 'Other') {
                $data['package_type'] = $request->custom_package_type;
            } else {
                $data['package_type'] = $request->package_type;
                if (isset($rates[$request->package_type])) {
                    $data['price'] = $rates[$request->package_type];
                }
            }
        }

        $shipment->update($data);
        return redirect()->route('dashboard')->with('success', 'Shipment updated successfully.');
    }

    /**
     * Delete a shipment.
     */
    public function destroy(Shipment $shipment)
    {
        if (Auth::user()->role != 1) {
            if ($shipment->user_id !== Auth::id() || $shipment->status !== 'Pending') abort(403);
        }

        $shipment->delete();

        return redirect()->route('dashboard')->with('success', 'Shipment record deleted.');
    }

    /**
     * Update Status (Quick Action).
     */
    public function updateStatus(Request $request, Shipment $shipment)
    {
        if (Auth::user()->role != 1) abort(403);

        $request->validate([
            'status' => 'required|in:Pending,In Transit,Delivered,Cancelled'
        ]);

        $shipment->update(['status' => $request->status]);

        return back()->with('success', "Status updated to {$request->status}.");
    }

    // =========================================================================
    // ✅ USER MANAGEMENT METHODS (ADMIN ONLY)
    // =========================================================================

    public function promoteUser($id)
    {
        if(Auth::user()->role != 1) abort(403);
        
        $user = User::findOrFail($id);
        $user->role = 1;
        $user->save();

        return back()->with('success', "User {$user->name} promoted to Admin.");
    }

    public function storeUser(Request $request)
    {
        if(Auth::user()->role != 1) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:0,1',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => (int)$request->role,
        ]);

        return back()->with('success', 'New user account created successfully.');
    }

    public function destroyUser($id)
    {
        if(Auth::user()->role != 1) abort(403);
        
        $user = User::findOrFail($id);

        if ($user->id === Auth::id() || $user->role == 1) {
            return back()->with('error', 'Unauthorized: You cannot delete an administrator.');
        }

        $user->delete();

        return back()->with('success', "User {$user->name} has been removed from the system.");
    }
}