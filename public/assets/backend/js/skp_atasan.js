$(document).ready(function () {
    var groupColumn = 1; // Kolom kedua (indeks 1) untuk pengelompokan
    var table = $('#tableSkpAtasan').DataTable({
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
                            '<tr class="group"><td colspan="8" class="fw-bold bg-light text-center">' + group +
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
    var table = $('#tableRencana').DataTable({

        paging: true, // Aktifkan pagination
        info: false, // Nonaktifkan informasi jumlah data
        searching: false, // Nonaktifkan fitur pencarian
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
                url: `/skp_atasan/destroy/${uuid}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $('#tableSkpAtasan').DataTable();
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
    var table = $('#tableSkpAtasan').DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data('uuid') === uuid) {
            table.row(row).remove();  // Hapus baris dari tabel
        }
    });
    table.draw();  // Redraw DataTable untuk memperbarui tampilan
}





const deleteDataRencana = (e) => {
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
                url: `/rencana-kerja/destroy/${uuid}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $('#tableRencana').DataTable();
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
const removeRowFromTableRencana = (uuid) => {
    var table = $('#tableSkpAtasan').DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data('uuid') === uuid) {
            table.row(row).remove();  // Hapus baris dari tabel
        }
    });
    table.draw();  // Redraw DataTable untuk memperbarui tampilan
}


const openEditIndikatorModal = (uuid, rencana) => {
    $('#edit_rencana_hasil_kerja_id').val(uuid);
    $('#edit_rencana_hasil_kerja').val(rencana);



    $('#modalEdit').modal('show');
};


$('#formEditRencana').submit(function (e) {
    e.preventDefault(); // Mencegah form submit default

    // Ambil nilai input dari form
    const uuid = $('#edit_rencana_hasil_kerja_id').val();
    const rencana = $('#edit_rencana_hasil_kerja').val();

    console.log(`UUID: ${uuid}, Rencana: ${rencana}`); // Debugging

    // Validasi sederhana sebelum AJAX
    if (!uuid || !rencana) {
        toastError('Harap isi semua data sebelum mengupdate.');
        return;
    }

    // Proses AJAX untuk update data
    $.ajax({
        type: "PUT",
        url: `/rencana-kerja/${uuid}`, // Pastikan URL sesuai dengan route Laravel Anda
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Tambahkan CSRF token
        },
        data: {
            rencana: rencana
        },
        success: function (response) {
            console.log('Success Response:', response); // Debugging respons server
            toastSuccess(response.message ||
                'Rencana berhasil diperbarui.'); // Tampilkan pesan sukses
            $('#modalEdit').modal('hide'); // Tutup modal
            location.reload(); // Reload halaman untuk memperbarui tampilan
        },
        error: function (xhr) {
            console.error('Error Response:', xhr); // Debugging error dari server
            const errorMessage = xhr.responseJSON?.message ||
                'Terjadi kesalahan saat mengupdate rencana.';
            toastError(errorMessage); // Tampilkan pesan error
        }
    });
});
