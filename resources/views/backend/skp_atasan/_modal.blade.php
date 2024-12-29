<!-- Modal -->
<div class="modal fade" id="addSkpModal" tabindex="-1" aria-labelledby="addSkpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkpModalLabel">Add Form SKP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('skp_atasan.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="skp_year" class="form-label">Tahun SKP</label>
                        <select class="form-select" id="skp_year" name="year" required>
                            <option value="" disabled selected>Pilih Tahun</option>
                            @php
                            $currentYear = now()->year;
                            @endphp
                            @for ($year = $currentYear - 5; $year <= $currentYear + 10; $year++) <option
                                value="{{ $year }}">{{ $year }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="skp_module" class="form-label">Module SKP</label>
                        <select class="form-select" id="skp_module" name="module" required>
                            <option value="" disabled selected>Pilih Module</option>
                            <option value="kuantitatif">Kuantitatif</option>
                            <option value="kualitatif">Kualitatif</option>
                        </select>
                    </div>
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-phoenix-secondary me-1 mb-1">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>