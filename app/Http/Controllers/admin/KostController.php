<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;


class KostController extends Controller
{
    public function index()
    {
        $kosts = Kost::all();

        return view('admin.kost.index', compact('kosts'));
    }

    public function create()
    {
        return view('admin.kost.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nomor_kamar' => 'required|string|unique:kosts,nomor_kamar',
            'fasilitas' => 'required|array',
            'foto' => 'required|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:51200',
            'status' => 'required|in:Kosong,Terisi',
            'harga' => 'required|numeric',
        ]);

        try {
            if ($request->hasFile('foto')) {
                $fotoArr = [];
                foreach ($request->file('foto') as $foto) {
                    $filename = time() . '_' . $foto->getClientOriginalName();
                    $fotoArr[] = $foto->storeAs('kost', $filename, 'public');
                }
                $validatedData['foto'] = $fotoArr;
            }

            Kost::create($validatedData);

            return redirect()->route('admin.kost.index')
                ->with('success', 'Kamar berhasil ditambahkan');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Kost $kost)
    {
        if (!$kost) {
            return redirect()->route('admin.kost.index')->with('error', 'Kost tidak ditemukan.');
        }
        return view('admin.kost.edit', compact('kost'));
    }

    public function update(Request $request, Kost $kost)
    {
        $validatedData = $request->validate([
            'nomor_kamar' => 'required|string|unique:kosts,nomor_kamar,'.$kost->id,
            'fasilitas' => 'required|array',
            'status' => 'required|in:Kosong,Terisi',
            'harga' => 'required|numeric',
            'foto' => 'nullable|array',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:51200',
        ]);

        try {
            if ($request->hasFile('foto')) {
                // Hapus foto lama
                if ($kost->foto) {
                    foreach ($kost->foto as $foto) {
                        Storage::disk('public')->delete($foto);
                    }
                }

                // Simpan foto baru
                $fotoArr = [];
                foreach ($request->file('foto') as $foto) {
                    $filename = time() . '_' . $foto->getClientOriginalName();
                    $fotoArr[] = $foto->storeAs('kost', $filename, 'public');
                }
                $validatedData['foto'] = $fotoArr;
            }

            $kost->update($validatedData);

            return redirect()->route('admin.kost.index')
                ->with('success', 'Kamar berhasil diupdate');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $kost = Kost::findOrFail($id);

            // Hapus foto jika ada
            if ($kost->foto) {
                foreach ($kost->foto as $foto) {
                    Storage::disk('public')->delete($foto);
                }
            }

            $kost->delete();

            return redirect()->route('admin.kost.index')
                ->with('success', 'Kamar berhasil dihapus');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function show(Kost $kost)
    {
        return view('admin.kost.show', compact('kost'));
    }
}
//
