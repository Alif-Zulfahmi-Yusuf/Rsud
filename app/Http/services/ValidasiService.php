<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ValidasiService
{

    //

    public function selectFirstById($column, $value)
    {
        return Skp::where($column, $value)->firstOrFail();
    }


    public function getSkpDetail(string $uuid)
    {
        try {
            // Mendapatkan detail SKP beserta relasi yang terkait dengan filter berdasarkan user_id
            $skpDetail = Skp::where('uuid', $uuid)
                ->firstOrFail(); // Ambil data pertama atau gagal jika tidak ditemukan

            return $skpDetail;
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal mendapatkan detail SKP untuk atasan', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Data SKP tidak ditemukan. Pastikan relasi dan data sudah sesuai.');
        }
    }
}