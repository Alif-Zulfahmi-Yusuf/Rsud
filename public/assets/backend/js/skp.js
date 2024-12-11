$(document).ready(function () {
    var groupColumn = 1; // Kolom kedua (indeks 1) untuk pengelompokan
    var table = $('#tableSkp').DataTable({
        columnDefs: [
            { visible: false, targets: groupColumn } // Sembunyikan kolom Tahun
        ],
        order: [[groupColumn, 'asc']], // Urutkan berdasarkan Tahun
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="9" class="fw-bold bg-light text-center">' + group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });
        },
        language: {
            emptyTable: "Tidak ada data yang tersedia",
            info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 hingga 0 dari 0 data",
            lengthMenu: "Tampilkan _MENU_ data",
            search: "Cari:",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya"
            }
        }
    });

    // Event klik pada grup untuk mengurutkan data
    $('#tableSkp tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
});


$(document).ready(function () {
    var groupColumn = 1; // Kolom kedua untuk pengelompokan (Rencana Hasil Kerja)
    var table = $('#tableRencana').DataTable({
        columnDefs: [
            { orderable: false, targets: [0, 1, 2, 3, 4, 5, 6] }, // Kolom No dan Action tidak dapat diurutkan
            { visible: false, targets: groupColumn }, // Kolom untuk grup disembunyikan
        ],
        order: [[groupColumn, 'asc']], // Urutkan berdasarkan grup
        paging: true, // Aktifkan pagination
        info: false, // Nonaktifkan informasi jumlah data
        searching: true, // Nonaktifkan fitur pencarian
        language: {
            emptyTable: "Tidak ada data yang tersedia",
            lengthMenu: "Tampilkan _MENU_ data",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Berikutnya",
                previous: "Sebelumnya",
            },
        },
        drawCallback: function (settings) {
            var api = this.api();
            var rows = api.rows({ page: 'current' }).nodes();
            var last = null;

            // Tambahkan baris grup sebelum baris detail
            api.column(groupColumn, { page: 'current' })
                .data()
                .each(function (group, i) {
                    if (last !== group) {
                        $(rows).eq(i).before(
                            '<tr class="group"><td colspan="7" class="fw-bold bg-light">' + group +
                            '</td></tr>'
                        );
                        last = group;
                    }
                });

            // Tambahkan styling khusus untuk baris grup
            $('.group').css({
                'background-color': '#f8f9fa',
                'color': '#495057',
                'font-weight': 'bold',
            });
        },
    });

    // Event klik pada grup untuk mengurutkan data
    $('#tableRencana tbody').on('click', 'tr.group', function () {
        var currentOrder = table.order()[0];
        if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            table.order([groupColumn, 'desc']).draw();
        } else {
            table.order([groupColumn, 'asc']).draw();
        }
    });
});


// Fungsi untuk menghapus data dengan konfirmasi SweetAlert
const deleteData = (e) => {
    let uuid = e.getAttribute('data-uuid'); // Mendapatkan data-uuid

    if (!uuid) {
        Swal.fire({
            title: "Error!",
            text: "Invalid UUID!",
            icon: "error"
        });
        return;
    }

    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to delete this item?",
        icon: "question",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Delete",
        cancelButtonText: "Cancel",
        allowOutsideClick: false,
        showCancelButton: true,
        showCloseButton: true
    }).then((result) => {
        if (result.isConfirmed) { // Jika pengguna mengonfirmasi penghapusan
            startLoading(); // Menampilkan loading indikator (pastikan fungsi ini sudah diimplementasi)

            $.ajax({
                type: "DELETE",
                url: `/skp/destroy/${uuid}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $('#tableSkp').DataTable();
                    table.rows().every(function () {
                        var row = this.node();
                        if ($(row).data('uuid') === uuid) {
                            table.row(row).remove();  // Hapus baris dari tabel
                        }
                    });
                    table.draw(); // Redraw tabel untuk memperbarui tampilan
                },
                error: function (xhr, status, error) {
                    stopLoading(); // Menghentikan loading indikator jika ada error
                    console.error("Error:", error); // Menampilkan pesan error di konsol
                    Swal.fire({
                        title: "Error!",
                        text: "Failed to delete the data. Please try again.",
                        icon: "error",
                        confirmButtonColor: "#3085d6"
                    });
                }
            });
        }
    });
}


// Fungsi untuk menghapus baris dari DataTable
const removeRowFromTable = (uuid) => {
    var table = $('#tableSkp').DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data('uuid') === uuid) {
            table.row(row).remove();  // Hapus baris dari tabel
        }
    });
    table.draw();  // Redraw DataTable untuk memperbarui tampilan
}

const openEditIndikatorModal = (uuid, rencanaPegawaiId, aspek, indikatorKinerja, tipeTarget, targetMinimum, targetMaximum, satuan, report) => {
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


// Menangani pengiriman form edit
$('#formEditIndikator').submit(function (e) {
    e.preventDefault();

    const uuid = $('#edit-uuid').val();
    const rencanaPegawaiId = $('#editRencanaPegawai').val();
    const aspek = $('#editAspek').val();
    const indikatorKinerja = $('#editIndikatorKinerja').val();
    const tipeTarget = $('#editTipeTarget').val();
    const targetMinimum = $('#editTargetMinimum').val();
    const targetMaximum = $('#editTargetMaximum').val();
    const satuan = $('#editSatuan').val();
    const report = $('#editReport').val();

    console.log(`UUID indikator: ${uuid}`);

    $.ajax({
        type: "PUT", // Ganti dengan PUT sesuai resource Laravel
        url: `/indikator-kinerja/${uuid}/update`, // Gunakan UUID di URL
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
        success: function (response) {
            toastSuccess(response.message);
            $('#modalEditIndikator').modal('hide');
            location.reload();
        },
        error: function (xhr) {
            toastError(xhr.responseJSON.message);
        }
    });

});


