<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kost;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;


class KostController extends Controller
{
    public function index(Request $request)
    {
        $query = Kost::query();

        if ($request->search) {
            $query->where('nomor_kamar', 'like', '%' . $request->search . '%');
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->price) {
            $query->orderBy('harga', $request->price);
        }

        $kosts = $query->get();
        return view('admin.kosT.index', compact('kosts'));
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
        $validated = $request->validate([
            'nomor_kamar' => 'required',
            'fasilitas' => 'required|array',
            'status' => 'required',
            'harga' => 'required|numeric',
            'foto.*' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $kost->fasilitas = json_encode($request->fasilitas);

        $kost->nomor_kamar = $request->nomor_kamar;
        $kost->status = $request->status;
        $kost->harga = $request->harga;

        if ($request->hasFile('foto')) {
            $photos = [];
            foreach ($request->file('foto') as $photo) {
                $path = $photo->store('kost-photos', 'public');
                $photos[] = $path;
            }
            $kost->foto = json_encode($photos);
        }

        $kost->save();

        return redirect()->route('admin.kost.index')
            ->with('success', 'Kamar berhasil diperbarui');
    }

    public function destroy($id)
    {
        try {
            $kost = Kost::findOrFail($id);

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
