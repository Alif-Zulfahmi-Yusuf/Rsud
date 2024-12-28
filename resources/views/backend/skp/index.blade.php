@extends('backend.layouts.app')

@section('title', 'SKP')

@section('header')
{{ __('SKP') }}
@endsection

@push('css')
<!-- select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">
<link rel="stylesheet" href="https://cdn.datatables.net/rowgroup/1.3.1/css/rowGroup.dataTables.min.css">

@endpush


@section('content')
<div class="row gy-3 mb-6 justify-content-between">
    <div class="col-md-9 col-auto">
        <h2 class="mb-2 text-body-emphasis">Form Skp</h2>
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

<div class="card shadow rounded-lg mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <div class="d-flex justify-content-between align-items-center mb-3 mx-auto">
                <a class="btn btn-phoenix-success me-1 mb-1" href="#" data-bs-toggle="modal"
                    data-bs-target="#addSkpModal">
                    <i class="fa fa-plus me-1"></i> New Form Skp
                </a>
            </div>
            <table id="tableSkp" class="table table-hover table-sm fs-9 mb-0">
                <thead>
                    <tr>
                        <th class="sort border-top text-center">No</th>
                        <th class="sort border-top text-center">Tahun</th> <!-- Kolom untuk grouping -->
                        <th class="sort border-top text-center">Jabatan</th>
                        <th class="sort border-top text-center">Unit Kerja</th>
                        <th class="sort border-top text-center">Tanggal Skp</th>
                        <th class="sort border-top text-center">Tanggal Akhir</th>
                        <th class="sort border-top text-center">Posisi</th>
                        <th class="sort border-top text-center">Status</th>
                        <th class="sort border-top text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($skps as $skp)
                    <tr data-uuid="{{ $skp->uuid }}">
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $skp->tahun }}</td> <!-- Kolom Tahun -->
                        <td class="text-center">{{ $skp->user->jabatan ?? '-' }}</td>
                        <td class="text-center">{{ $skp->user->unit_kerja ?? '-' }}</td>
                        <td class="text-center">
                            {{ $skp->tanggal_skp ? \Carbon\Carbon::parse($skp->tanggal_skp)->format('Y M d') : '-' }}
                        </td>
                        <td class="text-center">
                            {{ $skp->tanggal_akhir ? \Carbon\Carbon::parse($skp->tanggal_akhir)->format('Y M d') : '-' }}
                        </td>
                        <td class="text-center">{{ $skp->user->atasan->name ?? '-' }}</td>
                        <td class="text-center">
                            @if ($skp->status === 'pending')
                            <span class="badge badge-phoenix badge-phoenix-warning">Pending</span>
                            @elseif ($skp->status === 'approve')
                            <span class="badge badge-phoenix badge-phoenix-success">Approve</span>
                            @elseif ($skp->status === 'revisi')
                            <span class="badge badge-phoenix badge-phoenix-danger">Revisi</span>
                            @else
                            <span class="badge bg-secondary">-</span>
                            @endif
                        </td>
                        <td class="text-center">
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
                                    <a class="dropdown-item" href="{{ route('skp.edit', $skp->uuid) }}">
                                        Edit
                                    </a>
                                    <hr class="dropdown-divider">
                                    <button type="button" class="dropdown-item text-danger delete-button"
                                        onclick="deleteData(this)" data-uuid="{{ $skp->uuid }}">Delete</button>
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
@include('backend.skp._modal')


@endsection

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
@if(session('success'))
toastSuccess("{{ session('success') }}");
@endif

@if(session('error'))
toastError("{{ session('error') }}");
@endif
</script>
@endpush