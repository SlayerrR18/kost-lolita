<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KostController extends Controller
{
    public function index(Request $request)
    {
        $query = Kost::query();

        if ($search = $request->input('search')) {
            $query->where('nomor_kamar', 'like', "%{$search}%");
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        // Normalisasi arah sort agar hanya asc|desc
        $direction = strtolower($request->input('price', 'asc'));
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = 'asc';
        }
        $query->orderBy('harga', $direction);

        $kosts = $query->paginate(12)->withQueryString();
        return view('admin.kost.index', compact('kosts'));
    }

    public function create()
    {
        return view('admin.kost.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nomor_kamar' => ['required','string','max:100','unique:kosts,nomor_kamar'],
            'fasilitas'   => ['required','array'],
            'fasilitas.*' => ['string','max:100'],
            'foto'        => ['required','array','min:1'],
            'foto.*'      => ['image','mimes:jpeg,png,jpg','max:5120'], // 5MB konsisten
            'status'      => ['required', Rule::in(['Kosong','Terisi'])],
            'harga'       => ['required','numeric','min:0'],
        ]);

        // Upload foto (konsisten ke folder 'kost')
        $paths = [];
        foreach ($request->file('foto', []) as $file) {
            // nama aman & unik
            $paths[] = $file->store('kost', 'public');
        }
        $validated['foto'] = $paths;

        // Pastikan fasilitas array sudah langsung tersimpan karena $casts
        $kost = Kost::create($validated);

        return redirect()->route('admin.kost.index')
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit(Kost $kost)
    {
        return view('admin.kost.edit', compact('kost'));
    }

    public function update(Request $request, Kost $kost)
    {
        $validated = $request->validate([
            'nomor_kamar' => ['required','string','max:100', Rule::unique('kosts','nomor_kamar')->ignore($kost->id)],
            'fasilitas'   => ['required','array'],
            'fasilitas.*' => ['string','max:100'],
            'status'      => ['required', Rule::in(['Kosong','Terisi'])],
            'harga'       => ['required','numeric','min:0'],
            'foto'        => ['sometimes','array'],
            'foto.*'      => ['image','mimes:jpeg,png,jpg','max:5120'],
        ]);

        // Pegang foto lama untuk dihapus jika ada upload baru
        $oldPhotos = $kost->foto ?? [];

        // Jika ada foto baru, upload & replace
        if ($request->hasFile('foto')) {
            $newPaths = [];
            foreach ($request->file('foto', []) as $file) {
                $newPaths[] = $file->store('kost', 'public');
            }
            $validated['foto'] = $newPaths;

            // Hapus fisik foto lama
            foreach ($oldPhotos as $p) {
                Storage::disk('public')->delete($p);
            }
        }

        $kost->update($validated);

        return redirect()->route('admin.kost.index')
            ->with('success', 'Kamar berhasil diperbarui');
    }

    public function destroy(Kost $kost)
    {
        // Hapus file-file foto
        foreach (($kost->foto ?? []) as $p) {
            Storage::disk('public')->delete($p);
        }

        $kost->delete();

        return redirect()->route('admin.kost.index')
            ->with('success', 'Kamar berhasil dihapus');
    }

    public function show(Kost $kost)
    {
        return view('admin.kost.show', compact('kost'));
    }
}
