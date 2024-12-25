<!-- Modal -->
<div class="modal fade" id="modalRencana" tabindex="-1" data-bs-backdrop="static" aria-labelledby="addSkpModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkpModalLabel">Form Input Rencana Hasil Kerja Atasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('rencana-kerja.store') }}" method="POST">
                    @csrf
                    <!-- Rencana Hasil Kerja -->
                    <div class="form-group mb-3">
                        <label for="rencana_hasil_kerja" class="form-label">Rencana Hasil Kerja</label>
                        <input type="text" name="rencana" id="rencana_hasil_kerja" class="form-control">
                    </div>
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>