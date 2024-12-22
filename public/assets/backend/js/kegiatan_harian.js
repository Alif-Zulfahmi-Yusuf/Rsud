$(document).ready(function () {
    // Inisialisasi DataTable dengan opsi bahasa
    window.tablePangkat = $('#tableKegiatan').DataTable({
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
                url: `/harian-pegawai/destroy/${uuid}`,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    stopLoading(); // Menghentikan loading indikator
                    toastSuccess(response.message); // Menampilkan notifikasi sukses

                    // Hapus baris dari DataTable
                    var table = $('#tableKegiatan').DataTable();
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
    var table = $('#tableKegiatan').DataTable();
    table.rows().every(function () {
        var row = this.node();
        if ($(row).data('uuid') === uuid) {
            table.row(row).remove();  // Hapus baris dari tabel
        }
    });
    table.draw();  // Redraw DataTable untuk memperbarui tampilan
}


const editData = (uuid, tanggal, jenisKegiatan, uraian, rencanaPegawaiId, output, jumlah, waktuMulai, waktuSelesai, biaya, fileEvidence) => {
    console.log(`Edit Data UUID: ${uuid}`);
    console.log(`Tanggal: ${tanggal}, Waktu Mulai: ${waktuMulai}, Waktu Selesai: ${waktuSelesai}`);
    console.log(`File Evidence: ${fileEvidence}`);

    // Format data jika diperlukan
    const formattedTanggal = new Date(tanggal).toISOString().split('T')[0]; // Format YYYY-MM-DD
    const formattedWaktuMulai = waktuMulai ? waktuMulai.slice(0, 5) : ''; // Ambil hanya HH:mm
    const formattedWaktuSelesai = waktuSelesai ? waktuSelesai.slice(0, 5) : ''; // Ambil hanya HH:mm

    // Set nilai input modal
    $('#tanggal').val(formattedTanggal);
    $('#jenis_kegiatan').val(jenisKegiatan).trigger('change');
    $('#uraian').val(uraian);
    $('#rencana_pegawai_id').val(rencanaPegawaiId).trigger('change');
    $('#output').val(output);
    $('#jumlah').val(jumlah);
    $('#waktu_mulai').val(formattedWaktuMulai); // Set waktu mulai
    $('#waktu_selesai').val(formattedWaktuSelesai); // Set waktu selesai
    $('#biaya').val(biaya);

    // Reset file input dan tampilkan nama file yang ada
    if (fileEvidence) {
        $('#evidence').val(''); // Reset file input
        $('#evidence-label').text(`File saat ini: ${fileEvidence}`).show(); // Tampilkan nama file
    } else {
        $('#evidence-label').hide(); // Sembunyikan jika tidak ada file
    }

    // Aktifkan Select2
    $('#jenis_kegiatan').select2({
        dropdownParent: $('#editHarianModal'),
        theme: "bootstrap-5",
        placeholder: "Pilih Jenis Kegiatan",
        allowClear: true
    });

    $('#rencana_pegawai_id').select2({
        dropdownParent: $('#editHarianModal'),
        theme: 'bootstrap-5',
        placeholder: 'Pilih Rencana Kegiatan',
        allowClear: true
    });

    // Tampilkan modal
    $('#editHarianModal').modal('show');
};


$(document).ready(function () {
    $('#tableHarian').DataTable({
        // Nonaktifkan fitur search dan length change (show entries)
        searching: false,
        lengthChange: false,
        language: {
            emptyTable: "No data available in table",
            info: "Show _START_ to _END_ of _TOTAL_ entries",
            infoEmpty: "Show 0 to 0 of 0 entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            }
        }
    });
});




