@extends('layout.layout')
@section('styles')
<link rel="stylesheet" href="{{ asset('CSS/reports.css') }}" />
@endsection
@section('content')

<div class="container">
        <div class="header">
            <div class="title-section">
                <h1>Report</h1>
                <div class="subtitle">Daftar Tanggapan</div>
            </div>
            <a href="{{route('input-reports')}}" class="create-btn">Buat Laporan</a>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Pengirim</th>
                        <th>Isi tanggapan</th>
                        <th>Tanggal</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> - </td>
                        <td> - </td>
                        <td> - </td>
                        <td><span class="no-photo">-</span></td>
                        <td>
                            <button class="action-btn" onclick="viewReport(1)">
                                -
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function viewReport(reportId) {
            // Redirect to report detail page
            window.location.href = `/reports/${reportId}`;
        }
    </script>
@endsection
