<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $kosts = Kost::all();
        return view('welcome', compact('kosts'));
    }
}
