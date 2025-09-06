<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request)
    {
        $reports = Report::with(['user','handler'])
            ->when($request->q, function ($q) use ($request) {
                $q->where('message', 'like', '%'.$request->q.'%')
                  ->orWhereHas('user', fn($uq) => $uq->where('name','like','%'.$request->q.'%'));
            })
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from && $request->to, fn($q) => $q->whereBetween('date', [$request->from, $request->to]))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.report.index', compact('reports'));
    }


    public function show(Report $report)
    {
        $report->load(['user','handler']);
        return view('admin.report.show', compact('report'));
    }


    public function update(Request $request, Report $report)
    {
        $validated = $request->validate([
            'status'   => 'required|in:dikirim,sedang_dikerjakan,selesai',
            'response' => 'nullable|string|max:2000',
        ]);

        $report->status = $validated['status'];
        $report->response = $validated['response'] ?? null;
        $report->handled_by = Auth::id();
        if (!empty($validated['response'])) {
            $report->responded_at = now();
        }
        $report->save();

        return redirect()->route('admin.reports.index');
    }

    public function destroy(Report $report)
    {
        $report->delete();
        return back()->with('success', 'Report dihapus.');
    }
}
