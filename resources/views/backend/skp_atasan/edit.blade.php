@extends('backend.layouts.app')

@section('title', 'Data Skp')

@section('header')
{{ __('Data Skp') }}
@endsection


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

<form action="{{ route('skp_atasan.update', $skpDetail->uuid) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="card shadow rounded-lg mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                    <div class="btn-group">
                        <button type="button" class="btn btn-phoenix-secondary me-1 mb-1 dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-plus me-1"></i> Add Action
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                    data-bs-target="#modalRencana">Rencana Hasil
                                    Kerja Atasan
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
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($skpDetail->rencanaHasilKinerja as $index => $rencana)
                        <tr data-uuid="{{ $rencana->uuid }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $rencana->rencana ?? 'Tidak Ada Nama' }}</td>
                            <td class="text-center">
                                <button
                                    class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal fs-10"
                                    type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="fas fa-ellipsis-h fs-10"></span>
                                </button>
                                <!-- Button Edit -->
                                <div class="dropdown-menu dropdown-menu-end py-2">
                                    <a href="#" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#modalEdit"
                                        onclick="openEditIndikatorModal('{{ $rencana->uuid }}', '{{ $rencana->rencana }}')">
                                        Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteDataRencana(this)"
                                        data-uuid="{{ $rencana->uuid }}">Delete</button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</form>
@endsection


<!-- Modal Edit -->
<form id="formEditRencana">
    @csrf
    @method('PUT')
    <!-- Laravel membutuhkan method PUT untuk update -->
    <div class="modal fade" id="modalEdit" tabindex="-1" data-bs-backdrop="static" aria-labelledby="editSkpModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSkpModalLabel">Form Edit Rencana Hasil Kerja Atasan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="uuid" id="edit_rencana_hasil_kerja_id">

                    <div class="form-group mb-3">
                        <label for="edit_rencana_hasil_kerja" class="form-label">Rencana Hasil Kerja</label>
                        <input type="text" name="rencana" id="edit_rencana_hasil_kerja" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>



@include('backend.skp_atasan._modalRencana')

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>
<script src="{{ asset('/assets/backend/js/skp_atasan.js') }}"></script>
<script src="https://cdn.datatables.net/rowgroup/1.3.1/js/dataTables.rowGroup.min.js"></script>

<script>
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError({
    message: "{{ session('error') }}", // Mengirim string error
});
@endif
</script>
@endpush