<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $reports = Report::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('user.report.index', compact('reports'));
    }

    public function create()
    {
        return view('user.report.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
            'photo'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'date'    => 'nullable|date',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('reports', 'public');
        }

        Report::create([
            'user_id' => Auth::id(),
            'message' => $validated['message'],
            'photo'   => $photoPath,
            'date'    => $validated['date'] ?? now()->toDateString(),
            'status'  => 'dikirim' // Set default status
        ]);

        return redirect()
            ->route('user.reports.index')
            ->with('success', 'Laporan berhasil dikirim.');
    }
}
