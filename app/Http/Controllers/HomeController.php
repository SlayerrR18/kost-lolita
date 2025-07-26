<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $kosts = Kost::all();
        return view('home', compact('kosts'));
    }

    public function about()
    {
        return view('about');
    }

    public function kamarContact()
    {
        $kosts = Kost::all();
        return view('kamar_contact', compact('kosts'));
    }
}
