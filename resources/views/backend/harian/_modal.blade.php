<!-- Modal -->
<div class="modal fade" id="addHarianModal" tabindex="-1" aria-labelledby="addHarianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHarianModalLabel">Form Kegiatan Harian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Kegiatan Harian -->
                <form action="{{ route('harian-pegawai.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="tanggal" class="form-label">Tanggal *</label>
                                <input type="date" name="tanggal" id="tanggal" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="jenis_kegiatan" class="form-label">Jenis Kegiatan *</label>
                                <select name="jenis_kegiatan" id="jenis_kegiatan" class="form-select" required>
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
                        <textarea name="uraian" id="uraian" class="form-control" style="height: 100px;"
                            required></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="rencana_pegawai_id" class="form-label">Rencana Aksi *</label>
                        @if ($rencanaKerjaPegawai->isEmpty())
                        <span class="badge badge-phoenix badge-phoenix-danger">
                            Belum ada rencana kerja atau skp belum di approve dan belum aktif
                        </span>
                        @else
                        <select name="rencana_pegawai_id" id="rencana_pegawai_id" class="form-select" required>
                            <option value="" disabled selected>-- Pilih --</option>
                            @foreach ($rencanaKerjaPegawai as $rencana)
                            <option value="{{ $rencana->id }}">{{ $rencana->rencana }}</option>
                            @endforeach
                        </select>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="output" class="form-label">Output *</label>
                                <input type="text" name="output" id="output" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="jumlah" class="form-label">Jumlah *</label>
                                <input type="text" name="jumlah" id="jumlah" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="waktu_mulai" class="form-label">Waktu Mulai (Jam) *</label>
                                <input type="time" name="waktu_mulai" id="waktu_mulai" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="waktu_selesai" class="form-label">Waktu Selesai (Jam) *</label>
                                <input type="time" name="waktu_selesai" id="waktu_selesai" class="form-control"
                                    required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <label for="biaya" class="form-label">Biaya (Jika Ada)</label>
                        <input type="text" name="biaya" id="biaya" class="form-control">
                    </div>
                    <div class="form-group mb-3">
                        <label for="evidence" class="form-label">File Evidence</label>
                        <input type="file" name="evidence" id="evidence" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="is_draft" value="0" class="btn btn-outline-danger">Save as
                            Draft</button>
                        <button type="submit" name="is_draft" value="1" class="btn btn-secondary">Save &
                            Review</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>