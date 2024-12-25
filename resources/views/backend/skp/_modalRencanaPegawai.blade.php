<!-- Modal -->
<div class="modal fade" id="modalRencanaPegawai" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalRencanaPegawai" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalRencanaPegawai">Form Input Rencana Hasil Kerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('rencana-pegawai.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="skp_id" value="{{ $skpDetail->id }}">
                    <!-- Rencana Atasan -->
                    <div class="form-group mb-3">
                        <label for="rencana_atasan" class="form-label">Rencana Atasan</label>
                        <select class="form-select" id="rencana_atasan" name="rencana_atasan_id" required>
                            <option value="" selected>Pilih Rencana Atasan</option>
                            @if($skpDetail->skpAtasan && $skpDetail->skpAtasan->rencanaHasilKinerja->isNotEmpty())
                            @foreach ($skpDetail->skpAtasan->rencanaHasilKinerja as $rencana)
                            <option value="{{ $rencana->id }}">
                                {{ $rencana->rencana ?? '-' }}
                            </option>
                            @endforeach
                            @else
                            <option value="" disabled>
                                Tidak ada rencana hasil kinerja tersedia
                            </option>
                            @endif
                        </select>
                    </div>
                    <!-- Rencana Hasil Kerja -->
                    <div class="form-group mb-3">
                        <label for="rencana_hasil_kerja" class="form-label">Rencana Hasil Kerja</label>
                        <input type="text" name="rencana" id="rencana_hasil_kerja" class="form-control" required>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>