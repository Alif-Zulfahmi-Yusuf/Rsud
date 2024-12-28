@extends('backend.layouts.app')

@section('title', 'Data Skp')

@section('header')
{{ __('Data Skp') }}
@endsection

@push('css')
<!-- data table -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">

<!-- select 2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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

<div class="container">
    <div class="row gy-4 mb-5">
        <!-- Card Pegawai yang Dinilai -->
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header text-white text-center rounded-top">
                    <h5 class="mb-0"><i class="fas fa-user-check me-2"></i>Pegawai yang Dinilai</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td><strong><i class="fas fa-user me-2 text-primary"></i>Nama</strong></td>
                                <td>: {{ Auth::user()->name }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-id-badge me-2 text-primary"></i>NIP</strong></td>
                                <td>: {{ Auth::user()->nip }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-building me-2 text-primary"></i>Unit Kerja</strong></td>
                                <td>: {{ Auth::user()->unit_kerja }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-layer-group me-2 text-primary"></i>Pangkat</strong></td>
                                <td>: {{ Auth::user()->pangkat->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Card Atasan Penilai -->
        <div class="col-md-6">
            <div class="card shadow border-0 rounded-lg">
                <div class="card-header  text-white text-center rounded-top">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Atasan Penilai</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                            <tr>
                                <td><strong><i class="fas fa-user me-2 text-success"></i>Nama</strong></td>
                                <td>: {{ Auth::user()->atasan->name }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-id-badge me-2 text-success"></i>NIP</strong></td>
                                <td>: {{ Auth::user()->atasan->nip }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-building me-2 text-success"></i>Unit Kerja</strong></td>
                                <td>: {{ Auth::user()->atasan->unit_kerja }}</td>
                            </tr>
                            <tr>
                                <td><strong><i class="fas fa-layer-group me-2 text-success"></i>Pangkat</strong></td>
                                <td>: {{ Auth::user()->atasan->pangkat->name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <form id="form-skp" action="{{ route('skp.update', $skpDetail->uuid) }}" method="POST"
        data-submit-url="{{ route('skp.update', $skpDetail->uuid) }}"
        data-toggle-url="{{ route('skp.toggle', $skpDetail->id) }}">>
        @csrf
        @method('PUT')
        <div class="card shadow rounded-lg mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-success dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-plus me-1"></i> Add Action
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalRencanaPegawai">Rencana Hasil Kerja
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                        data-bs-target="#modalIndikator">
                                        Indikator Individu
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <table id="tableRencana" class="table table-hover table-sm fs-9 mb-0">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th class="text-center">Rencana Hasil Kerja</th>
                                <th class="text-center">Aspek</th>
                                <th class="text-center">Indikator Kinerja</th>
                                <th class="text-center">Target</th>
                                <th class="text-center">Report</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($skpDetail->skpAtasan)
                            @foreach ($skpDetail->skpAtasan->rencanaHasilKinerja ?? [] as $rencana)
                            @foreach ($rencana->rencanaPegawai ?? [] as $pegawai)
                            @foreach ($pegawai->indikatorKinerja ?? [] as $indikator)
                            <tr data-uuid="{{ $indikator->uuid }}">
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    <div><strong>Rencana Hasil Kerja:</strong></div>
                                    <div>{{ $pegawai->rencana ?? 'Data Rencana Pegawai Tidak Tersedia' }}</div>
                                    <div class="text-muted"><strong>Rencana Hasil Kerja Pimpinan yang
                                            Diintervensi:</strong>
                                    </div>
                                    <div class="text-muted">{{ $rencana->rencana ?? 'Data Rencana Tidak Tersedia' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{ $indikator->aspek ?? '-' }}
                                </td>
                                <td>{{ $indikator->indikator_kinerja ?? '-' }}</td>
                                <td class="text-center">
                                    {{ $indikator->target_minimum ?? 0 }} - {{ $indikator->target_maksimum ?? 0 }}<br>
                                    {{ $indikator->satuan ?? '-' }}
                                </td>
                                <td class="text-center">{{ $indikator->report ?? '-' }}</td>
                                <td class="text-center">
                                    <button
                                        class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                        type="button" data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <span class="fas fa-ellipsis-h fs-10"></span>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end py-2">
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalEditIndikator" onclick="openEditIndikatorModal(
                                                '{{ $indikator->uuid }}',
                                                '{{ $indikator->rencana_kerja_pegawai_id }}',
                                                '{{ e($indikator->aspek) }}',
                                                '{{ e($indikator->indikator_kinerja) }}',
                                                '{{ e($indikator->tipe_target) }}',
                                                '{{ $indikator->target_minimum }}',
                                                '{{ $indikator->target_maksimum }}',
                                                '{{ e($indikator->satuan) }}',
                                                '{{ e($indikator->report) }}'
                                            )">
                                            Edit
                                        </a>

                                        <div class="dropdown-divider"></div>
                                        <button type="button" class="dropdown-item text-danger delete-button"
                                            onclick="deleteDataIndikator(this)"
                                            data-uuid="{{ $indikator->uuid }}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                            @endforeach
                            @else
                            <tr>
                                <td colspan="7" class="text-center">Data SKP Atasan Tidak Tersedia</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="card mb-3">
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
                                    <h6 class="fw-bold mb-2">Ukuran Keberhasilan/Indikator Kinerja dan Target:</h6>
                                    <ul class="mb-0">
                                        @foreach ($category->perilakus as $perilaku)
                                        <li>{{ $perilaku->name }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="align-middle" width="45%">
                                    <h6 class="fw-bold mb-2">Ekspektasi Khusus Pimpinan/Leader:</h6>
                                    @php
                                    // Cari ekspektasi berdasarkan category_id
                                    $ekspetasi = $ekspektasis->firstWhere('category_id', $category->id);
                                    @endphp
                                    <textarea class="form-control" rows="3" readonly>
                                    {{ $ekspetasi->ekspetasi ?? 'Ekspektasi belum diinputkan.' }}
                                    </textarea>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-5">Catatan</h5>
                <div class="form-group mb-3">
                    <textarea name="keterangan" id="catatan" class="form-control" style="height: 150px;"
                        placeholder="Masukkan Alasan revisi jika diperlukan..."></textarea>
                </div>

                <div>
                    @if ($skpDetail->is_active)
                    <button type="button" class="btn btn-phoenix-warning"
                        onclick="confirmToggle({{ $skpDetail->id }}, false)">Nonaktifkan</button>
                    @else
                    <button type="button" class="btn btn-phoenix-primary"
                        onclick="confirmToggle({{ $skpDetail->id }}, true)">Aktifkan</button>
                    @endif

                    @if ($skpDetail->status === 'revisi')
                    <button type="button" class="btn btn-phoenix-danger me-1 mb-1" onclick="confirmSubmit()">Ajukan
                        Revisi</button>
                    @elseif (!$skpDetail->is_submitted)
                    <button type="button" class="btn btn-phoenix-secondary me-1 mb-1" onclick="confirmSubmit()">Ajukan
                        SKP</button>
                    @else
                    <span class="badge badge-phoenix badge-phoenix-success">
                        SKP telah diajukan pada
                        {{ $skpDetail->submitted_at->format('d-m-Y H:i') }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        @if ($skpDetail->status === 'revisi')
        <div class="card border-danger">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0 text-danger">History Review</h5>
                    <span class="badge bg-danger text-white">Revisi</span>
                </div>
                <p class="mb-2">
                    {{ Auth::user()->atasan->name ?? 'Data Atasan Tidak Tersedia' }}
                </p>
                <p class="mb-0">
                    <strong>Keterangan Revisi:</strong><br>
                    {{ $skpDetail->keterangan_revisi ?? 'Tidak ada keterangan revisi tersedia.' }}
                </p>
            </div>
        </div>
        @endif
    </form>
</div>


@endsection

// Modal Edit Indikator
<form id="formEditIndikator">
    @csrf
    @method('PUT')
    <div class="modal fade" id="modalEditIndikator" tabindex="-1" data-bs-backdrop="static"
        aria-labelledby="modalEditIndikatorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditIndikatorLabel">Edit Indikator Kinerja</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="uuid" id="edit-uuid">
                    <!-- Ubah id menjadi sesuai dengan yang digunakan di JS -->

                    <div class="form-group mb-3">
                        <label for="editRencanaPegawai" class="form-label">Rencana Pegawai</label>
                        <select class="form-select" id="editRencanaPegawai" name="rencana_kerja_pegawai_id" required>
                            <option value="" selected>Pilih Rencana</option>
                            @foreach ($skpDetail->rencanaPegawai as $rencana)
                            <option value="{{ $rencana->id }}">
                                {{ $rencana->rencana ?? '-' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Aspek -->
                    <div class="mb-3">
                        <label for="editAspek" class="form-label">Aspek</label>
                        <select class="form-select" id="editAspek" name="aspek" required>
                            <option value="" disabled selected>-- pilih --</option>
                            <option value="kualitas">Kualitas</option>
                            <option value="kuantitas">Kuantitas</option>
                            <option value="waktu">Waktu</option>
                        </select>
                    </div>

                    <!-- Indikator Kinerja -->
                    <div class="mb-3">
                        <label for="editIndikatorKinerja" class="form-label">Indikator Kinerja</label>
                        <textarea name="indikator_kinerja" id="editIndikatorKinerja" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <!-- Tipe Target -->
                            <div class="mb-3">
                                <label for="editTipeTarget" class="form-label">Tipe Target</label>
                                <select class="form-select" id="editTipeTarget" name="tipe_target" required>
                                    <option value="" disabled selected>-- pilih --</option>
                                    <option value="satu_nilai">Satu Nilai</option>
                                    <option value="range_nilai">Range Nilai</option>
                                    <option value="kualitatif">Kualitatif</option>
                                </select>
                            </div>

                            <!-- Target Minimum -->
                            <div class="mb-3">
                                <label class="form-label">Target Minimum</label>
                                <input type="text" class="form-control" id="editTargetMinimum" name="target_minimum">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <!-- Target Maksimum -->
                            <div class="mb-3">
                                <label class="form-label">Target Maksimum</label>
                                <input type="text" class="form-control" id="editTargetMaximum" name="target_maksimum">
                            </div>

                            <!-- Satuan -->
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="editSatuan" name="satuan">
                            </div>
                        </div>
                    </div>

                    <!-- Report -->
                    <div class="mb-3">
                        <label for="editReport" class="form-label">Report</label>
                        <select class="form-select" id="editReport" name="report" required>
                            <option value="" disabled selected>-- pilih --</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="triwulan">Triwulan</option>
                            <option value="semesteran">Semesteran</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-outline-secondary">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>


@include('backend.skp._modalRencanaPegawai')
@include('backend.skp._modalIndikator')

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/skp.js') }}"></script>
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

function confirmSubmit() {
    Swal.fire({
        title: 'Ajukan SKP?',
        text: "Anda tidak dapat mengubah data setelah diajukan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, ajukan!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan form untuk pengajuan SKP
            const form = document.getElementById('form-skp');
            form.action = form.getAttribute('data-submit-url'); // URL untuk pengajuan
            form.submit();
        }
    });
}

function confirmToggle(skpId, status) {
    const action = status ? 'mengaktifkan' : 'menonaktifkan';
    Swal.fire({
        title: `Apakah Anda yakin ingin ${action} SKP ini?`,
        text: "Perubahan ini akan mempengaruhi status SKP.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: `Ya, ${action}!`,
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Arahkan form untuk aktivasi SKP
            const form = document.getElementById('form-skp');
            form.action = form.getAttribute('data-toggle-url'); // URL untuk aktivasi
            form.submit();
        }
    });
}


const openEditIndikatorModal = (uuid, rencanaPegawaiId, aspek, indikatorKinerja, tipeTarget, targetMinimum,
    targetMaximum, satuan, report) => {
    console.log(`UUID indikator: ${uuid}`); // Menampilkan UUID indikator di console untuk verifikasi
    // Isi data ke dalam form edit
    $('#edit-uuid').val(uuid);
    $('#editRencanaPegawai').val(rencanaPegawaiId);
    $('#editAspek').val(aspek);
    $('#editIndikatorKinerja').val(indikatorKinerja);
    $('#editTipeTarget').val(tipeTarget);
    $('#editTargetMinimum').val(targetMinimum);
    $('#editTargetMaximum').val(targetMaximum);
    $('#editSatuan').val(satuan);
    $('#editReport').val(report);

    // Tampilkan modal
    $('#modalEditIndikator').modal('show');
};


// Fungsi untuk menangani form submit edit indikator
$('#formEditIndikator').submit(function(e) {
    e.preventDefault(); // Mencegah perilaku default submit form

    // Ambil data dari form
    const uuid = $('#edit-uuid').val();
    const rencanaPegawaiId = $('#editRencanaPegawai').val();
    const aspek = $('#editAspek').val();
    const indikatorKinerja = $('#editIndikatorKinerja').val();
    const tipeTarget = $('#editTipeTarget').val();
    const targetMinimum = $('#editTargetMinimum').val();
    const targetMaximum = $('#editTargetMaximum').val();
    const satuan = $('#editSatuan').val();
    const report = $('#editReport').val();

    console.log(`Mengupdate indikator dengan UUID: ${uuid}`); // Debugging

    // Validasi sederhana untuk memastikan data tidak kosong
    if (!uuid || !rencanaPegawaiId || !aspek || !indikatorKinerja || !tipeTarget || !targetMinimum || !satuan) {
        toastError('Harap lengkapi semua data wajib sebelum mengupdate.');
        return;
    }

    // Proses AJAX untuk mengupdate data
    $.ajax({
        type: "PUT", // Gunakan metode HTTP PUT
        url: `/indikator-kinerja/${uuid}/update`, // Pastikan URL endpoint sesuai
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
        },
        data: {
            rencana_kerja_pegawai_id: rencanaPegawaiId,
            aspek: aspek,
            indikator_kinerja: indikatorKinerja,
            tipe_target: tipeTarget,
            target_minimum: targetMinimum,
            target_maksimum: targetMaximum,
            satuan: satuan,
            report: report
        },
        success: function(response) {
            console.log('Success:', response); // Debugging respons sukses
            toastSuccess(response.message ||
                'Indikator berhasil diperbarui.'); // Tampilkan notifikasi sukses
            $('#modalEditIndikator').modal('hide'); // Tutup modal
            location.reload(); // Reload halaman untuk memperbarui tampilan
        },
        error: function(xhr) {
            console.error('Error:', xhr); // Debugging error dari server
            const errorMessage = xhr.responseJSON?.message ||
                'Terjadi kesalahan saat mengupdate indikator.';
            toastError(errorMessage); // Tampilkan pesan error
        }
    });
});
</script>

@endpush