<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <title>Kost Lolita</title>
</head>
<body class="bg-gray-100">

<div class="container mx-auto p-6">
    <h1 class="text-3xl font-bold text-center mb-6">Kost Lolita</h1>

    <div class="flex">
        <!-- Panel Kamar -->
        <div class="bg-white rounded-lg shadow-lg p-6 w-1/2">
            <img src="img/kamar-1.jpg" alt="Kamar A" class="rounded-lg mb-4" />
            <h2 class="text-xl font-semibold">Kamar A</h2>
            <p class="text-gray-600">Fasilitas:</p>
            <ul class="list-disc ml-5">
                <li>Kamar mandi dalam</li>
                <li>Lemari</li>
                <li>Tempat Tidur</li>
            </ul>
            <p class="text-lg font-semibold">Rp 650.000 / Bulan</p>
        </div>

        <!-- Form Pendaftaran -->
        <div class="bg-white rounded-lg shadow-lg p-6 ml-6 w-1/2">
            <h2 class="text-xl font-semibold mb-4">Masukan Data Diri</h2>
            <form enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="full_name" class="block mb-1">Nama Lengkap</label>
                    <input type="text" id="full_name" name="full_name" class="border rounded w-full p-2" required>
                </div>

                <div class="mb-4">
                    <label for="room_number" class="block mb-1">Kamar Nomor</label>
                    <select id="room_number" name="room_number" class="border rounded w-full p-2" required>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <!-- Tambahkan opsi lainnya di sini -->
                    </select>
                </div>

                <div class="mb-4">
                    <label for="start_date" class="block mb-1">Tanggal Mulai Kost</label>
                    <input type="date" id="start_date" name="start_date" class="border rounded w-full p-2" required>
                </div>

                <div class="mb-4">
                    <label for="rent" class="block mb-1">Sewa</label>
                    <select id="rent" name="rent" class="border rounded w-full p-2">
                        <option value="bulanan">Per Bulan</option>
                        <!-- Tambahkan opsi lainnya di sini -->
                    </select>
                </div>

                <div class="mb-4">
                    <label for="transfer_evidence" class="block mb-1">Upload Bukti Transfer</label>
                    <input type="file" id="transfer_evidence" name="transfer_evidence" class="border rounded w-full p-2" accept="image/*" required>
                </div>

                <div class="mb-4">
                    <label for="status" class="block mb-1">Status</label>
                    <select id="status" name="status" class="border rounded w-full p-2">
                        <option value="menikah">Sudah Menikah</option>
                        <option value="belum_menikah">Belum Menikah</option>
                        <!-- Tambahkan opsi lainnya di sini -->
                    </select>
                </div>

                <div class="mb-4">
                    <label for="phone_number" class="block mb-1">Nomor Telepon</label>
                    <input type="tel" id="phone_number" name="phone_number" class="border rounded w-full p-2" required>
                </div>

                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Pesan Sekarang</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
