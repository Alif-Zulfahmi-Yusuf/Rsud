<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
use App\Models\KegiatanHarian;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\RencanaHasilKinerjaPegawai;


class KegiatanService
{
    //

    public function saveKegiatanHarian(array $data, bool $isDraft = false): KegiatanHarian
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        Log::info('Menyimpan kegiatan harian untuk user', [
            'user_id' => $user->id
        ]);

        // Tambahkan data otomatis ke array
        $data['user_id'] = $user->id; // ID user yang sedang login
        $data['atasan_id'] = $user->atasan_id; // Tambahkan atasan_id dari relasi user
        $data['is_draft'] = $isDraft ? 1 : 0; // Set is_draft berdasarkan parameter
        $data['status'] = 'pending'; // Default status

        // Log tambahan untuk atasan_id
        Log::info('Atasan ID ditambahkan ke data kegiatan harian.', [
            'atasan_id' => $user->atasan_id
        ]);

        // Pastikan user memiliki atasan_id yang valid
        if (!$user->atasan_id) {
            throw new \Exception('User belum memilih atasan.');
        }

        // Tangani file evidence jika ada
        if (isset($data['evidence']) && $data['evidence'] instanceof UploadedFile) {
            $data['evidence'] = $this->uploadEvidence($data['evidence']);
            Log::info('Evidence berhasil diunggah.', [
                'file_path' => $data['evidence']
            ]);
        }

        // Simpan kegiatan harian ke database
        $kegiatanHarian = KegiatanHarian::create($data);

        Log::info('Kegiatan Harian berhasil disimpan.', [
            'kegiatan_harian_id' => $kegiatanHarian->id,
            'user_id' => $user->id,
            'atasan_id' => $user->atasan_id,
        ]);

        return $kegiatanHarian;
    }

    public function updateKegiatanHarian(array $data, $uuid)
    {
        $kegiatanHarian = KegiatanHarian::where('uuid', $uuid)->firstOrFail();

        return $kegiatanHarian->update($data);
    }

    private function uploadEvidence(UploadedFile $file): string
    {
        $path = $file->store('evidence', 'public'); // Simpan file di storage/public/evidence
        Log::info('Evidence berhasil diunggah.', [
            'path' => $path
        ]);
        return $path;
    }


    public function delete($uuid)
    {
        $kegiatan = KegiatanHarian::where('uuid', $uuid)->firstOrFail();

        return $kegiatan->delete();
    }
}