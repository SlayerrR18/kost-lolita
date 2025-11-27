<!-- resources/views/layouts/user-sidebar.blade.php -->
<aside class="w-64 bg-gray-800 text-white h-screen">
    <div class="p-4">
        <h2 class="text-2xl font-semibold">User Dashboard</h2>
    </div>
    <ul class="space-y-4">
        <li>
            <a href="{{ route('user.dashboard') }}" class="block p-4 hover:bg-gray-700">
                Dashboard
            </a>
        </li>
        <li>
            <a href="#" class="block p-4 hover:bg-gray-700">
                Kontrak
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
    </ul>
</aside>
