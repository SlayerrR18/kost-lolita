@extends('layouts.admin-layout')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-2xl font-semibold mb-4">Detail Kamar: {{ $room->room_number }}</h2>

            <div class="mb-6">
                <strong>Harga:</strong> Rp {{ number_format($room->price, 0, ',', '.') }}<br>
                <strong>Status:</strong> {{ ucfirst($room->status) }}<br>
                <strong>Fasilitas:</strong>
                <ul class="list-disc pl-6">
                    @foreach($room->facilities as $facility)
                        <li>{{ $facility }}</li>
                    @endforeach
                </ul>
            </div>

            @if($room->photos)
            <div class="mb-6">
                <strong>Foto Kamar:</strong>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($room->photos as $photo)
                    <div>
                        <img src="{{ Storage::url($photo) }}" alt="Foto Kamar" class="w-full h-48 object-cover rounded-lg">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <a href="{{ route('admin.rooms.edit', $room) }}" class="px-6 py-3 text-lg font-semibold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                Edit Kamar
            </a>
        </div>
    </div>
</div>
@endsection
