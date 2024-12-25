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
            // Ambil user yang sedang login
            $user = Auth::user();

            // Ambil SKP terkait dengan user
            $skp = SkpAtasan::where('user_id', $user->id)->first();

            if (!$skp) {
                throw new Exception("SKP tidak ditemukan.");
            }

            // Menyimpan data RencanaHasilKerja
            $rencanaHasilKerja = RencanaHasilKinerja::create([
                'rencana' => $data['rencana'], // Gunakan nama field dari form
                'user_id' => $user->id,
                'skp_atasan_id' => $skp->id,
            ]);

            return $rencanaHasilKerja;
        } catch (Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Rencana Hasil Kerja', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $data,
            ]);

            // Melemparkan error untuk ditangani oleh controller
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