<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index()
    {
        // Tampilkan laporan milik user yang sedang login saja
        $reports = Report::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.reports.index', compact('reports'));
    }

    public function create()
    {
        return view('user.reports.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'date'    => 'required|date',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB
        ]);

        $path = null;
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'message' => $request->message,
            'date'    => $request->date,
            'photo'   => $path,
            'status'  => 'dikirim',
        ]);

        return redirect()->route('user.reports.index')->with('success', 'Laporan berhasil dikirim.');
    }

    public function show(Report $report)
    {
        // Keamanan: Pastikan user hanya bisa melihat laporannya sendiri
        if ($report->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        return view('user.reports.show', compact('report'));
    }
}
