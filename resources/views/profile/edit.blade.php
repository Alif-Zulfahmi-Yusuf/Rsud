@extends('backend.layouts.app')

@section('title', 'Profile')

@section('header')
{{ __('Profile') }}
@endsection

@push('css')
<!-- datatables -->
<link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.css">

<!-- select2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link href="{{ asset('assets/backend/vendors/choices/choices.min.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="container">
    <div class="row gy-3 mb-6 justify-content-between">
        <div class="col-md-9 col-auto">
            <h2 class="mb-2 text-body-emphasis">Profile</h2>
        </div>
    </div>

    <ul class="nav nav-underline mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                type="button" role="tab" aria-controls="pills-profile" aria-selected="true">
                <i class="fa-solid fa-user"></i> Update Profile
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-password-tab" data-bs-toggle="pill" data-bs-target="#pills-password"
                type="button" role="tab" aria-controls="pills-password" aria-selected="false">
                <i class="fa-solid fa-lock"></i>
                Change Password
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pills-delete-tab" data-bs-toggle="pill" data-bs-target="#pills-delete"
                type="button" role="tab" aria-controls="pills-delete" aria-selected="false">
                <i class="fa-solid fa-trash"></i>
                Delete Account
            </button>
        </li>
    </ul>

    <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab"
            tabindex="0">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="tab-pane fade" id="pills-password" role="tabpanel" aria-labelledby="pills-password-tab"
            tabindex="0">
            <div class="card mt-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Update Password') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-delete" role="tabpanel" aria-labelledby="pills-delete-tab" tabindex="0">
            <div class="card mt-4">
                <div class="card-header pb-0">
                    <h6>{{ __('Delete Account') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('profile._modal')

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- Pastikan ini setelah jQuery -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('/assets/backend/js/helper.js') }}"></script>

<script>
@if(session('status'))
statusMessage = @json(session('status'));
if (typeof statusMessage === 'object') {
    statusMessage = statusMessage.message || 'Unknown status';
}
toastSuccess(statusMessage);
@endif

$(document).ready(function() {
    // Inisialisasi DataTable pada modal
    window.tablePangkat = $('#atasanTable').DataTable({
        language: {
            emptyTable: "No data available in table",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Showing 0 to 0 of 0 entries",
            lengthMenu: "Show _MENU_ entries",
            search: "Search:",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });

    // Ketika tombol Pilih ditekan
    $('.select-atasan').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var pangkat = $(this).data('pangkat');
        var unitKerja = $(this).data('unit-kerja');
        var jabatan = $(this).data('jabatan');

        // Set nilai pada form input yang terkait
        $('#atasan_id_input').val(id);
        $('#atasan_name').val(name);
        $('#atasan_pangkat').val(pangkat);
        $('#atasan_unit_kerja').val(unitKerja);
        $('#atasan_jabatan').val(jabatan);

        // Tutup modal setelah memilih
        $('#atasanModal').modal('hide');
    });
});
</script>
@endpush