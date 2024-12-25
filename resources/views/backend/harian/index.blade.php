@extends('backend.layouts.app')

@section('title', 'Kegiatan Harian')

@push('css')
<!-- select 2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">

<style>
.select2-container--bootstrap-5 .select2-selection {
    font-size: 13px;
    /* Ubah ukuran font sesuai kebutuhan */
}

.select2-container--bootstrap-5 .select2-results__option {
    font-size: 13px;
    /* Ukuran font untuk opsi dalam dropdown */
}
</style>

@endpush

@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Kegiatan Harian</h2>
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

<div class="container mt-4">
    <div class="card">
        <div class="card-header  d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data List</h5>
            <a class="btn btn-phoenix-success" href="#" data-bs-toggle="modal" data-bs-target="#addHarianModal">
                <i class="fa fa-plus me-1"></i> Tambah Data
            </a>
        </div>
        <div class="table-responsive p-3">
            <table id="tableKegiatan" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th width="5%" class=" text-center">NO</th>
                        <th class="text-center">NIP/NAMA</th>
                        <th class="text-center">GOL</th>
                        <th class="text-center">TANGGAL</th>
                        <th class="text-center">URAIAN</th>
                        <th class="text-center">QTY</th>
                        <th class="text-center">BIAYA</th>
                        <th class="text-center">WAKTU</th>
                        <th class="text-center">STATUS</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data Kosong -->
                    @foreach ($kegiatanHarian as $kegiatan)
                    <tr data-uuid="{{ $kegiatan->uuid }}">
                        <th scope="row" class="text-center">{{ $loop->iteration }}</th>
                        <td class="text-center">
                            {{ $kegiatan->user->name }}
                            <br>
                            {{ $kegiatan->user->nip }}
                        </td>
                        <td class="text-center align-middle">{{ $kegiatan->user->pangkat->name }}</td>
                        <td class="text-center align-middle">
                            {{ $kegiatan->tanggal ? \Carbon\Carbon::parse($kegiatan->tanggal)->format('Y M d') : '-' }}
                        </td>
                        <td>{{ $kegiatan->uraian }}</td>
                        <td class="text-center align-middle">{{ $kegiatan->jumlah }}</td>
                        <td class="text-center align-middle">
                            Rp {{ number_format((float) ($kegiatan->biaya ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="text-center align-middle">
                            {{ \Carbon\Carbon::parse($kegiatan->waktu_mulai)->format('H:i') }} sd
                            {{ \Carbon\Carbon::parse($kegiatan->waktu_selesai)->format('H:i') }}
                        </td>
                        <td class="text-center align-middle">
                            @if ($kegiatan->status == 'pending')
                            <span class="badge badge-phoenix badge-phoenix-warning">Pending</span>
                            @elseif ($kegiatan->status == 'approve')
                            <span class="badge badge-phoenix badge-phoenix-success">Approve</span>
                            @elseif ($kegiatan->status == 'revisi')
                            <span class="badge badge-phoenix badge-phoenix-danger">Revisi</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-reveal-trigger position-static">
                                <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <svg class="fs-10" aria-hidden="true" focusable="false"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="16" height="16">
                                        <path fill="currentColor"
                                            d="M8 256a56 56 0 1 1 112 0A56 56 0 1 1 8 256zm160 0a56 56 0 1 1 112 0 56 56 0 1 1 -112 0zm216-56a56 56 0 1 1 0 112 56 56 0 1 1 0-112z">
                                        </path>
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                    <a class="dropdown-item {{ $kegiatan->status === 'approve' ? 'disabled' : '' }}"
                                        href="#" onclick="editData(
                                            '{{ $kegiatan->uuid }}', 
                                            '{{ $kegiatan->tanggal }}', 
                                            '{{ $kegiatan->jenis_kegiatan }}', 
                                            '{{ $kegiatan->uraian }}', 
                                            '{{ $kegiatan->rencana_pegawai_id }}', 
                                            '{{ $kegiatan->output }}', 
                                            '{{ $kegiatan->jumlah }}', 
                                            '{{ $kegiatan->waktu_mulai }}', 
                                            '{{ $kegiatan->waktu_selesai }}',
                                            '{{ $kegiatan->biaya }}',
                                            '{{ $kegiatan->evidence }}'
                                            )" data-bs-toggle="modal" data-bs-target="#editHarianModal"
                                        {{ $kegiatan->status === 'approve' ? 'aria-disabled=true' : '' }}>
                                        Edit
                                    </a>

                                    <hr class=" dropdown-divider">
                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteData(this)" data-uuid="{{ $kegiatan->uuid }}">Delete</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>


@endsection

<!-- Modal Edit -->

<form id="formEditHarian" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PATCH')
    <div class="modal fade" id="editHarianModal" tabindex="-1" aria-labelledby="editHarianModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editHarianModalLabel">Form Kegiatan Harian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form Kegiatan Harian -->
                    <input type="hidden" name="uuid" id="edit-uuid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal" class="form-label">Tanggal *</label>
                                <input type="date" name="tanggal" id="edit-tanggal" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan *</label>
                                <select name="jenis_kegiatan" id="edit-jenis_kegiatan" class="form-select" required>
                                    <option value="" disabled selected>-- Pilih --</option>
                                    <option value="tugas_pokok">Tugas Pokok</option>
                                    <option value="tugas_tambahan">Tugas Tambahan</option>
                                    <option value="dinas_luar">Dinas Luar</option>
                                    <option value="bebas_piket">Bebas Piket</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="uraian" class="form-label">Uraian *</label>
                        <textarea name="uraian" id="edit-uraian" class="form-control" style="height: 100px;"
                            required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="rencana_pegawai_id" class="form-label">Rencana Aksi *</label>
                        <select name="rencana_pegawai_id" id="edit-rencana_pegawai_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            @foreach ($rencanaKerjaPegawai as $rencana)
                            <option value="{{ $rencana->id }}">{{ $rencana->rencana }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="output" class="form-label">Output *</label>
                                <input type="text" name="output" id="edit-output" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="jumlah" class="form-label">Jumlah *</label>
                                <input type="text" name="jumlah" id="edit-jumlah" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="waktu_mulai" class="form-label">Waktu Mulai (Jam) *</label>
                                <input type="time" name="waktu_mulai" id="edit-waktu_mulai" class="form-control"
                                    required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="waktu_selesai" class="form-label">Waktu Selesai (Jam) *</label>
                                <input type="time" name="waktu_selesai" id="edit-waktu_selesai" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="biaya" class="form-label">Biaya (Jika Ada)</label>
                        <input type="text" name="biaya" id="edit-biaya" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="evidence" class="form-label">File Evidence</label>
                        <input type="file" name="evidence" id="edit-evidence" class="form-control">
                        <small id="evidence-label" class="form-text text-muted" style="display: none;">File saat
                            ini:</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="is_draft" value="0" class="btn btn-outline-danger">Save as
                        Draft</button>
                    <button type="submit" name="is_draft" value="1" class="btn btn-secondary">Save & Review</button>
                </div>
            </div>
        </div>
    </div>
</form>


@include('backend.harian._modal')



@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/kegiatan_harian.js') }}"></script>


<script>
$(document).ready(function() {
    @if(session('success'))
    toastSuccess("{{ session('success') }}");
    @endif

    @if(session('error'))
    toastError("{{ session('error') }}");
    @endif
});

const editData = (uuid, tanggal, jenisKegiatan, uraian, rencanaPegawaiId, output, jumlah, waktuMulai, waktuSelesai,
    biaya, fileEvidence) => {
    console.log(`Edit Data UUID: ${uuid}`);
    console.log(`Tanggal: ${tanggal}`);
    console.log(`Waktu mulai: ${waktuMulai}`);
    console.log(`Waktu selesai: ${waktuSelesai}`);
    console.log(`File Evidence: ${fileEvidence}`);

    // Format data jika diperlukan
    const formattedTanggal = new Date(tanggal).toISOString().split('T')[0]; // Format YYYY-MM-DD
    const formattedWaktuMulai = waktuMulai ? `${waktuMulai.slice(0, 2)} : ${waktuMulai.slice(3, 5)}` :
        ''; // Format ke H : i
    const formattedWaktuSelesai = waktuSelesai ? `${waktuSelesai.slice(0, 2)} : ${waktuSelesai.slice(3, 5)}` :
        ''; // Format ke H : i

    // Set nilai input modal
    $('#edit-uuid').val(uuid);
    $('#edit-tanggal').val(formattedTanggal);
    $('#edit-jenis_kegiatan').val(jenisKegiatan).trigger('change');
    $('#edit-uraian').val(uraian);
    $('#edit-rencana_pegawai_id').val(rencanaPegawaiId).trigger('change');
    $('#edit-output').val(output);
    $('#edit-jumlah').val(jumlah);
    $('#edit-waktu_mulai').val(formattedWaktuMulai); // Set waktu mulai
    $('#edit-waktu_selesai').val(formattedWaktuSelesai); // Set waktu selesai
    $('#edit-biaya').val(biaya);

    // Reset file input dan tampilkan nama file yang ada
    if (fileEvidence) {
        $('#edit-evidence').val(''); // Reset file input
        $('#evidence-label').text(`File saat ini: ${fileEvidence}`).show(); // Tampilkan nama file
    } else {
        $('#evidence-label').hide(); // Sembunyikan jika tidak ada file
    }

    // Aktifkan Select2
    $('#edit-jenis_kegiatan').select2({
        dropdownParent: $('#editHarianModal'),
        theme: "bootstrap-5",
        placeholder: "Pilih Jenis Kegiatan",
        allowClear: true
    });

    $('#edit-rencana_pegawai_id').select2({
        dropdownParent: $('#editHarianModal'),
        theme: 'bootstrap-5',
        placeholder: 'Pilih Rencana Kegiatan',
        allowClear: true
    });

    // Tampilkan modal
    $('#editHarianModal').modal('show');
};

$('#formEditHarian').submit(function(e) {
    e.preventDefault(); // Mencegah perilaku default submit form

    // Ambil data dari form
    const uuid = $('#edit-uuid').val(); // ID dari form untuk UUID
    const tanggal = $('#edit-tanggal').val(); // ID dari form untuk tanggal
    const jenisKegiatan = $('#edit-jenis_kegiatan').val(); // ID dari form untuk jenis kegiatan
    const uraian = $('#edit-uraian').val(); // ID dari form untuk uraian
    const rencanaPegawaiId = $('#edit-rencana_pegawai_id').val(); // ID dari form untuk rencana pegawai
    const output = $('#edit-output').val(); // ID dari form untuk output
    const jumlah = $('#edit-jumlah').val(); // ID dari form untuk jumlah
    const waktuMulai = $('#edit-waktu_mulai').val(); // ID dari form untuk waktu mulai
    const waktuSelesai = $('#edit-waktu_selesai').val(); // ID dari form untuk waktu selesai
    const biaya = $('#edit-biaya').val(); // ID dari form untuk biaya
    const evidenceFile = $('#edit-evidence')[0].files[0]; // File evidence baru dari form

    console.log(`Mengupdate data harian dengan UUID: ${uuid}`); // Debugging

    // Validasi sederhana untuk memastikan data tidak kosong
    if (!uuid || !tanggal || !jenisKegiatan || !uraian || !rencanaPegawaiId || !output) {
        toastError('Harap lengkapi semua data wajib sebelum mengupdate.');
        return;
    }
    // Proses AJAX untuk mengupdate data
    $.ajax({
        type: "PATCH", // Gunakan metode POST karena mengunggah file
        url: `/harian-pegawai/${uuid}/update`, // Endpoint dinamis dengan UUID
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
        },
        data: { // Data yang akan dikirimkan
            tanggal: tanggal,
            jenis_kegiatan: jenisKegiatan,
            uraian: uraian,
            rencana_pegawai_id: rencanaPegawaiId,
            output: output,
            jumlah: jumlah,
            waktu_mulai: waktuMulai,
            waktu_selesai: waktuSelesai,
            biaya: biaya,
            evidence: evidenceFile // File evidence baru
        },
        success: function(response) {
            console.log('Success:', response); // Debugging respons sukses
            toastSuccess(response.message ||
                'Data berhasil diperbarui.'); // Tampilkan notifikasi sukses
            $('#editHarianModal').modal('hide'); // Tutup modal
            location.reload(); // Reload halaman untuk memperbarui tampilan
        },
        error: function(xhr) {
            console.error('Error:', xhr); // Debugging error dari server
            // Ambil pesan error dari server
            let errorMessage = 'Terjadi kesalahan saat mengupdate data harian.';
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                // Gabungkan semua pesan error dari server
                errorMessage = Object.values(xhr.responseJSON.errors)
                    .flat()
                    .join('<br>');
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            toastError(errorMessage); // Tampilkan pesan error
        }
    });
});
</script>
@endpush