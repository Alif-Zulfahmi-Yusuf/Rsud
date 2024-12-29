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
                    location.reload(); // Reload halaman untuk memperbarui konten

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


const editData = (uuid, tanggal, jenisKegiatan, uraian, rencanaPegawaiId, output, jumlah, waktuMulai, waktuSelesai,
    biaya, fileEvidence) => {
    console.log(`Edit Data UUID: ${uuid}`);
    console.log(`Tanggal: ${tanggal}`);
    console.log("Debugging Waktu Mulai dan Selesai");
    console.log("Waktu Mulai Diterima:", waktuMulai); // Apakah muncul?
    console.log("Waktu Selesai Diterima:", waktuSelesai); // Apakah muncul?
    console.log(`File Evidence: ${fileEvidence}`);

    // Format data jika diperlukan
    const formattedTanggal = new Date(tanggal).toISOString().split('T')[0]; // Format YYYY-MM-DD

    // Format data waktu menjadi HH:mm
    const formatTime = (dateTime) => {
        if (!dateTime) return '';
        const time = new Date(dateTime).toTimeString().split(' ')[0]; // Ambil waktu saja
        return time.slice(0, 5); // HH:mm
    };

    const formattedWaktuMulai = formatTime(waktuMulai);
    const formattedWaktuSelesai = formatTime(waktuSelesai);

    console.log("Formatted Waktu Mulai:", formattedWaktuMulai);
    console.log("Formatted Waktu Selesai:", formattedWaktuSelesai);

    // Set nilai input modal
    $('#edit-uuid').val(uuid);
    $('#edit-tanggal').val(formattedTanggal);
    $('#edit-jenis_kegiatan').val(jenisKegiatan).trigger('change');
    $('#edit-uraian').val(uraian);
    $('#edit-rencana_pegawai_id').val(rencanaPegawaiId).trigger('change');
    $('#edit-output').val(output);
    $('#edit-jumlah').val(jumlah);
    $('#edit-waktu_mulai').val(formattedWaktuMulai);
    $('#edit-waktu_selesai').val(formattedWaktuSelesai);
    $('#edit-biaya').val(biaya);

    console.log("Nilai di Input Waktu Mulai:", $('#edit-waktu_mulai').val());
    console.log("Nilai di Input Waktu Selesai:", $('#edit-waktu_selesai').val());

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


$('#formEditHarian').submit(function (e) {
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
        success: function (response) {
            console.log('Success:', response); // Debugging respons sukses
            toastSuccess(response.message ||
                'Data berhasil diperbarui.'); // Tampilkan notifikasi sukses
            $('#editHarianModal').modal('hide'); // Tutup modal
            location.reload(); // Reload halaman untuk memperbarui tampilan
        },
        error: function (xhr) {
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




