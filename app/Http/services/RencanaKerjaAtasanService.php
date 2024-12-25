<?php

namespace App\Http\Services;

use App\Models\RencanaHasilKinerja;
use App\Models\SkpAtasan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class RencanaKerjaAtasanService
{
    public function store(array $data)
    {
        try {
            $user = Auth::user();

            // Validasi keberadaan SKP Atasan (opsional, jika belum tervalidasi di controller)
            $skpAtasan = SkpAtasan::findOrFail($data['skp_atasan_id']);
            if ($skpAtasan->user_id !== $user->id) {
                throw new Exception('Anda tidak memiliki akses ke SKP ini.');
            }

            // Menyimpan data RencanaHasilKerja
            $rencanaHasilKerja = RencanaHasilKinerja::create([
                'rencana' => $data['rencana'],
                'user_id' => $user->id,
                'skp_atasan_id' => $data['skp_atasan_id'], // Gunakan SKP Atasan ID dari input
            ]);

            return $rencanaHasilKerja;
        } catch (Exception $e) {
            Log::error('Gagal menyimpan Rencana Hasil Kerja', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $data,
            ]);

            throw new Exception('Gagal menyimpan Rencana Hasil Kerja: ' . $e->getMessage());
        }
    }




    public function update($uuid, array $data)
    {
        $rencana = RencanaHasilKinerja::where('uuid', $uuid)->firstOrFail();

        return $rencana->update($data);
    }


    public function delete($uuid)
    {
        $rencana = RencanaHasilKinerja::where('uuid', $uuid)->firstOrFail();

        return $rencana->delete();
    }

    // Tambahkan fungsi untuk mendapatkan data detail

}