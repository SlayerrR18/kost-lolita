<!-- resources/views/layouts/admin-sidebar.blade.php -->
<aside class="w-64 bg-gray-800 text-white h-screen">
    <div class="p-4">
        <h2 class="text-2xl font-semibold">Admin Dashboard</h2>
    </div>
    <ul class="space-y-4">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="block p-4 hover:bg-gray-700">
                Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.rooms.index') }}" class="block p-4 hover:bg-gray-700">
                Manajemen Kamar
            </a>
        </li>
        <li>
            <a href="#" class="block p-4 hover:bg-gray-700">
                Penghuni
            </a>
        </li>
        <li>
            <a href="#" class="block p-4 hover:bg-gray-700">
                Keuangan
            </a>
        </li>
        <li>
            <a href="#" class="block p-4 hover:bg-gray-700">
                Laporan
            </a>
        </li>

        {{-- logout --}}

        <li>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left block p-4 hover:bg-gray-700">
                    Logout
                </button>
            </form>
        </li>
    </ul>
</aside>
