@extends('layouts.app')

@section('content')
<section class="home" id="home">
    <main class="content">
        <h1>Hello!</h1>
        <p>Selamat datang di Kost Lolita<br>Tersedia kost pria bersih dan aman dengan fasilitas lengkap</p>
        <a href="{{ route('kamar') }}" class="cta">Order Now!</a>
    </main>
</section>
@endsection
