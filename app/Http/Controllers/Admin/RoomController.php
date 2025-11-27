<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    // LIST KAMAR
    public function index()
    {
        $rooms = Room::all();

        return view('admin.rooms.index', compact('rooms'));
    }

    // FORM TAMBAH KAMAR
    public function create()
    {
        return view('admin.rooms.create');
    }

    // SIMPAN KAMAR BARU
   public function store(Request $request)
    {
        $request->validate([
            'room_number'  => 'required|unique:rooms',
            'price'        => 'required|integer',
            'status'       => 'required',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'nullable|string',
            'additional_facilities'   => 'nullable|array',
            'additional_facilities.*' => 'nullable|string',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gabung fasilitas default + tambahan
        $defaultFacilities    = $request->input('facilities', []);
        $additionalFacilities = $request->input('additional_facilities', []);

        $additionalFacilities = array_filter(array_map('trim', $additionalFacilities));
        $allFacilities        = array_merge($defaultFacilities, $additionalFacilities);

        // Simpan foto
        $photoUrls = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoUrls[] = $photo->store('rooms', 'public');
            }
        }

        Room::create([
            'room_number' => $request->room_number,
            'price'       => $request->price,
            'facilities'  => $allFacilities,
            'photos'      => $photoUrls,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil ditambahkan.');
    }


    // ✅ DETAIL KAMAR (INI YANG BIKIN ERROR KALAU GA ADA)
    public function show(Room $room)
    {
        return view('admin.rooms.show', compact('room'));
    }

    // FORM EDIT KAMAR
    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    // UPDATE KAMAR
        public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number'  => 'required',
            'price'        => 'required|integer',
            'status'       => 'required',
            'facilities'   => 'nullable|array',
            'facilities.*' => 'nullable|string',
            'additional_facilities'   => 'nullable|array',
            'additional_facilities.*' => 'nullable|string',
            'photos'       => 'nullable|array',
            'photos.*'     => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gabung fasilitas default + tambahan
        $defaultFacilities    = $request->input('facilities', []);
        $additionalFacilities = $request->input('additional_facilities', []);

        $additionalFacilities = array_filter(array_map('trim', $additionalFacilities));
        $allFacilities        = array_merge($defaultFacilities, $additionalFacilities);

        // Foto lama
        $photoUrls = $room->photos ?? [];

        // Tambah foto baru jika ada
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $photoUrls[] = $photo->store('rooms', 'public');
            }
        }

        $room->update([
            'room_number' => $request->room_number,
            'price'       => $request->price,
            'facilities'  => $allFacilities,
            'photos'      => $photoUrls,
            'status'      => $request->status,
        ]);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil diperbarui.');
    }


    // HAPUS KAMAR
    public function destroy(Room $room)
    {
        if (is_array($room->photos)) {
            foreach ($room->photos as $photo) {
                Storage::disk('public')->delete($photo);
            }
        }

        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }
}
