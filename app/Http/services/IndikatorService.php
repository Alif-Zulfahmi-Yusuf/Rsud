<?php

namespace App\Http\Services;

use Exception;
use App\Models\Skp;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\IndikatorKinerja; // Model yang digunakan untuk indikator kinerja

class IndikatorService
{
    /**
     * Menyimpan data Indikator Kinerja.
     *
     * @param array $data
     * @return IndikatorKinerja
     * @throws Exception
     */
    public function create(array $data)
    {
        try {
            $user = Auth::user();

            // Dapatkan `skp_id` secara otomatis untuk pengguna
            $skp = Skp::where('user_id', $user->id)->first();

            if (!$skp) {
                throw new Exception('Tidak ada SKP untuk pengguna ini.');
            }

            // Dapatkan rencana kerja atasan berdasarkan rencana kerja pegawai yang dipilih
            $rencanaKerjaPegawaiId = $data['rencana_kerja_pegawai_id'] ?? null;

            if (!$rencanaKerjaPegawaiId) {
                throw new Exception('Rencana kerja pegawai tidak dipilih.');
            }

            // Temukan rencana kerja atasan yang sesuai
            $rencanaKerjaAtasanId = RencanaHasilKinerja::whereHas('rencanaPegawai', function ($query) use ($rencanaKerjaPegawaiId) {
                $query->where('id', $rencanaKerjaPegawaiId);
            })->value('id');

            if (!$rencanaKerjaAtasanId) {
                throw new Exception('Tidak ditemukan rencana kerja atasan yang sesuai.');
            }

            // Simpan data indikator kinerja ke database
            $indikator = IndikatorKinerja::create([
                'rencana_atasan_id' => $rencanaKerjaAtasanId, // Otomatis diisi berdasarkan rencana kerja atasan
                'rencana_kerja_pegawai_id' => $rencanaKerjaPegawaiId,
                'user_id' => $user->id,
                'skp_id' => $skp->id, // Otomatis diisi berdasarkan SKP aktif
                'aspek' => $data['aspek'],
                'indikator_kinerja' => $data['indikator_kinerja'],
                'tipe_target' => $data['tipe_target'],
                'target_minimum' => $data['target_minimum'],
                'target_maksimum' => $data['target_maksimum'],
                'satuan' => $data['satuan'],
                'report' => $data['report'],
            ]);

            return $indikator;
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Indikator Kinerja', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            // Melemparkan error untuk ditangani oleh controller
            throw new Exception('Gagal menyimpan Indikator Kinerja: ' . $e->getMessage());
        }
    }

    public function update(string $uuid, array $data)
    {
        // Cari data indikator berdasarkan UUID
        $indikator = IndikatorKinerja::where('uuid', $uuid)->first();

        // Jika data tidak ditemukan, lempar exception
        if (!$indikator) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException('Indikator tidak ditemukan.');
        }

        // Update data indikator dengan data baru
        $indikator->update($data);

        // Return data indikator yang diperbarui
        return $indikator;
    }


    public function delete($uuid)
    {
        try {
            $indikator = IndikatorKinerja::where('uuid', $uuid)->firstOrFail();
            return $indikator->delete();
        } catch (\Exception $e) {
            Log::error("Error deleting indikator: " . $e->getMessage());
            return false; // Jika gagal, kembalikan false
        }
    }
}