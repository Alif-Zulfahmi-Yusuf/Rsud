<?php

namespace App\Http\Services;

use App\Models\RencanaHasilKinerjaPegawai;
use App\Models\Skp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;

class RencanaKerjaPegawaiService
{
    /**
     * Menyimpan data Rencana Hasil Kerja Pegawai.
     *
     * @param array $data
     * @return RencanaHasilKinerjaPegawai
     * @throws Exception
     */
    public function create(array $data)
    {
        try {
            DB::beginTransaction(); // Mulai transaksi

            $user = Auth::user();
            // Validasi keberadaan SKP Atasan (opsional, jika belum tervalidasi di controller)
            $skp = Skp::findOrFail($data['skp_id']);
            if ($skp->user_id !== $user->id) {
                throw new Exception('Anda tidak memiliki akses ke SKP ini.');
            }

            // Menyimpan data RencanaHasilKinerjaPegawai
            $rencanaPegawai = RencanaHasilKinerjaPegawai::create([
                'rencana_atasan_id' => $data['rencana_atasan_id'],
                'rencana' => $data['rencana'],
                'user_id' => $user->id,
                'skp_id' => $data['skp_id'],
            ]);

            DB::commit(); // Commit transaksi jika semuanya berhasil
            return $rencanaPegawai;
        } catch (Exception $e) {
            DB::rollBack(); // Rollback jika terjadi kesalahan
            Log::error('Gagal menyimpan Rencana Hasil Kerja Pegawai', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $data,
            ]);
            // Lempar exception lagi untuk ditangani controller
            throw new Exception('Gagal menyimpan Rencana Hasil Kerja Pegawai: ' . $e->getMessage());
        }
    }



    /**
     * Menghapus data berdasarkan UUID.
     *
     * @param string $uuid
     * @return bool
     * @throws Exception
     */
    public function delete($uuid)
    {
        try {
            $rencanaPegawai = RencanaHasilKinerjaPegawai::where('uuid', $uuid)->firstOrFail();

            return $rencanaPegawai->delete();
        } catch (Exception $e) {
            Log::error('Gagal menghapus Rencana Hasil Kerja Pegawai', [
                'error' => $e->getMessage(),
                'uuid' => $uuid,
            ]);

            throw new Exception('Gagal menghapus Rencana Hasil Kerja Pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan detail data Rencana Hasil Kerja Pegawai.
     *
     * @param string $uuid
     * @return RencanaHasilKinerjaPegawai
     * @throws Exception
     */
}