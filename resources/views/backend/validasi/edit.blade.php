@extends('backend.layouts.app')

@section('title', 'Review Form SKP')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">
@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Data SKP (Kinerja Utama)</h2>
    </div>
    <div class="col-md-3 col-auto">
        <div class="input-group flatpickr-input-container">
            <input class="form-control datetimepicker" id="datepicker" type="text"
                data-options='{"dateFormat":"M j, Y","disableMobile":true,"defaultDate":"{{ date('M j, Y') }}"}'
                placeholder="Select Date" />
            <span class="input-group-text"><i class="uil uil-calendar-alt"></i></span>
        </div>
    </div>
</div>
<form action="{{ route('validasi.update', $skpDetail->uuid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="container">
        <!-- Tabel Utama -->
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="tableReview" class="table table-bordered table-sm fs-8 mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th width="20%">Rencana Hasil Kerja Pimpinan</th>
                                <th width="20%">Rencana Hasil Kerja</th>
                                <th class="text-center" width="7%">Aspek</th>
                                <th width="30%">Indikator Kinerja Individu</th>
                                <th class="text-center" width="5%">Target</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Models\RencanaHasilKinerja::where('skp_atasan_id',
                            $skpDetail->skp_atasan_id)->get() ?? [] as $rencana)
                            @php
                            $pegawaiList = \App\Models\RencanaHasilKinerjaPegawai::where('skp_id', $skpDetail->id)
                            ->where('rencana_atasan_id', $rencana->id)
                            ->get();
                            @endphp

                            @foreach ($pegawaiList as $pegawai)
                            @php
                            $indikatorList = \App\Models\IndikatorKinerja::where('rencana_kerja_pegawai_id',
                            $pegawai->id)->get();
                            $rowspanIndikator = $indikatorList->count() ?: 1;
                            @endphp

                            @foreach ($indikatorList as $indikator)
                            <tr>
                                @if ($loop->parent->first && $loop->first)
                                <td class="align-middle text-center"
                                    rowspan="{{ $pegawaiList->count() * $rowspanIndikator }}">
                                    {{ $loop->parent->iteration }}
                                </td>
                                <td class="align-middle" rowspan="{{ $pegawaiList->count() * $rowspanIndikator }}">
                                    {{ $rencana->rencana ?? 'Data Tidak Tersedia' }}
                                </td>
                                @endif

                                @if ($loop->first)
                                <td class="align-middle" rowspan="{{ $rowspanIndikator }}">
                                    {{ $pegawai->rencana ?? 'Data Tidak Tersedia' }}
                                </td>
                                @endif

                                <td class="align-middle text-center">{{ $indikator->aspek ?? '-' }}</td>
                                <td>{{ $indikator->indikator_kinerja ?? '-' }}</td>
                                <td class="align-middle text-center">
                                    {{ $indikator->target_minimum ?? 0 }} - {{ $indikator->target_maksimum ?? 0 }}<br>
                                    {{ $indikator->satuan ?? '-' }}
                                </td>
                            </tr>
                            @endforeach

                            @if ($indikatorList->isEmpty())
                            <tr>
                                @if ($loop->first)
                                <td class="align-middle" rowspan="1">
                                    {{ $pegawai->rencana ?? 'Data Tidak Tersedia' }}
                                </td>
                                @endif
                                <td class="align-middle text-center">-</td>
                                <td>-</td>
                                <td class="align-middle text-center">-</td>
                            </tr>
                            @endif
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tabel Perilaku Kerja -->
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <h5 class="mb-5">Perilaku Kerja (BerAKHLAK)</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm fs-9 mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" colspan="3">PERILAKU KERJA / BEHAVIOUR</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr class="bg-light">
                                <td colspan="3" class="fw-bold text-start">{{ $category->name }}</td>
                            </tr>
                            <tr>
                                <td class="text-center align-middle" width="5%">{{ $loop->iteration }}</td>
                                <td class="align-middle" width="50%">
                                    <h6 class="fw-bold mb-2">Ukuran Keberhasilan/Indikator Kinerja:</h6>
                                    <ul class="mb-0">
                                        @foreach ($category->perilakus as $perilaku)
                                        <li>{{ $perilaku->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="align-middle" width="45%">
                                    <h6 class="fw-bold mb-2">Ekspektasi Khusus Pimpinan:</h6>
                                    <textarea class="form-control" name="ekspektasi[{{ $category->id }}]" rows="3"
                                        placeholder="Masukkan ekspektasi..."></textarea>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Catatan dan Aksi -->
        <div class="card">
            <div class="card-body">
                <h5 class="mb-5">Catatan</h5>
                <div class="form-group mb-3">
                    <textarea name="keterangan_revisi" class="form-control" style="height: 150px;"
                        placeholder="Masukkan keterangan jika diperlukan..."></textarea>
                </div>
                <div>
                    <button type="submit" name="approve" class="btn btn-secondary me-1 mb-1">Approve</button>
                    <button type="submit" name="revisi" class="btn btn-danger me-1 mb-1">Revisi</button>
                </div>
            </div>
        </div>
    </div>
</form>


@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/validasi.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script>
$(document).ready(function() {
    @if(session('success'))
    toastSuccess("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastError("{{ session('error') }}");
    @endif
});
</script>
@endpush