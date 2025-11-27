@extends('layouts.admin-layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Daftar Kamar Kost Lolita</h2>

            <a href="{{ route('admin.rooms.create') }}" class="px-6 py-3 text-lg font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 mb-4 inline-block">
                Tambah Kamar
            </a>

            <table class="min-w-full mt-6 border border-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left">Nomor Kamar</th>
                        <th class="px-6 py-3 text-left">Harga</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rooms as $room)
                    <tr>
                        <td class="px-6 py-4">{{ $room->room_number }}</td>
                        <td class="px-6 py-4">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">{{ ucfirst($room->status) }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.rooms.show', $room) }}" class="text-blue-600 hover:text-blue-800">Lihat</a> |
                            <a href="{{ route('admin.rooms.edit', $room) }}" class="text-yellow-600 hover:text-yellow-800">Edit</a> |
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
