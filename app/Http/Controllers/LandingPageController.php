<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan landing page dengan daftar kamar
     */
    public function index()
    {
        $rooms = Room::all();
        $totalRooms = Room::count();
        $availableRooms = Room::where('status', 'available')->count();

        return view('welcome', compact('rooms', 'totalRooms', 'availableRooms'));
    }
}
