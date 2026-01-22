<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shipment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Admin sees ALL shipments
            $shipments = Shipment::with('user')->latest()->get();
            return view('dashboard.admin', compact('shipments'));
        } else {
            // Customer sees ONLY their shipments
            $shipments = $user->shipments()->latest()->get();
            return view('dashboard.customer', compact('shipments'));
        }
    }
}