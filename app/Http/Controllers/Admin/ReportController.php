<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function index(Request $request)
    {

        $query = Report::with('user')->latest();


        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function($q) use ($search) {
                $q->where('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }


        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }


        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }

        $reports = $query->paginate(10)->withQueryString();

        return view('admin.reports.index', compact('reports'));
    }

    public function show(Report $report)
    {
        return view('admin.reports.show', compact('report'));
    }

    public function update(Request $request, Report $report)
{
    $request->validate([
        'status' => 'required|in:dikirim,sedang_dikerjakan,selesai',
        'response' => 'nullable|string',
        'response_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $data = [
        'status' => $request->status,
        'response' => $request->response,
    ];


    if ($request->status == 'sedang_dikerjakan' && is_null($report->processing_at)) {
        $data['processing_at'] = now();
    } elseif ($request->status == 'selesai' && is_null($report->completed_at)) {

        if (is_null($report->processing_at)) {
            $data['processing_at'] = now();
        }
        $data['completed_at'] = now();
    }

    if ($request->hasFile('response_photo')) {
        // Hapus foto lama jika ada
        if ($report->response_photo) {
            Storage::disk('public')->delete($report->response_photo);
        }
        $data['response_photo'] = $request->file('response_photo')->store('reports/responses', 'public');
    }

    $report->update($data);

    return redirect()->back()->with('success', 'Laporan berhasil diperbarui.');
}
    public function destroy(Report $report)
    {
       
        if ($report->photo) {
            Storage::disk('public')->delete($report->photo);
        }

        $report->delete();

        return redirect()->route('admin.reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }
}
