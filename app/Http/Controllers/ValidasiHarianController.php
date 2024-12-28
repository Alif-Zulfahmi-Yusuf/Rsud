<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ValidasiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info('Memulai proses index validasi harian.', [
            'user_id' => Auth::id(),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        DB::enableQueryLog();

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $kegiatanHarianList = KegiatanHarian::with(['user', 'user.pangkat'])
            ->where('status', 'pending')
            ->where('is_draft', 1)
            ->whereHas('rencanaPegawai', function ($query) use ($bulan, $tahun) {
                $query->whereHas('rencanaAtasan', function ($queryAtasan) {
                    $queryAtasan->where('user_id', Auth::id());
                });

                if ($bulan) {
                    $query->whereMonth('tanggal', $bulan);
                }

                if ($tahun) {
                    $query->whereYear('tanggal', $tahun);
                }
            })
            ->get()
            ->unique('user_id'); // Pastikan hanya satu data per user

        Log::info('Query SQL:', DB::getQueryLog());
        DB::disableQueryLog();

        if ($kegiatanHarianList->isEmpty()) {
            Log::warning('Tidak ada data ditemukan untuk validasi harian.', [
                'user_id' => Auth::id(),
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);
        }

        return view('backend.validasi_harian.index', compact('kegiatanHarianList'));
    }


    public function getByUser($userId)
    {
        try {
            $kegiatanHarians = KegiatanHarian::where('user_id', $userId)
                ->where('status', 'pending')
                ->where('is_draft', 1)
                ->get();

            if ($kegiatanHarians->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data ditemukan');
            }

            return response()->json($kegiatanHarians);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data kegiatan harian untuk user_id ' . $userId, ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $userId)
    {
        // Validasi input
        $request->validate([
            'penilaian' => 'required|array', // Penilaian harus berupa array
            'penilaian.*' => 'in:logis,kurang_logis,tidak_logis', // Validasi nilai penilaian
        ]);

        // Ambil semua data kegiatan harian untuk user_id tertentu
        $kegiatanHarianList = KegiatanHarian::where('user_id', $userId)->get();

        // Jika tidak ada data, berikan respons error
        if ($kegiatanHarianList->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk user ini.');
        }

        // Update setiap kegiatan harian berdasarkan input penilaian
        foreach ($kegiatanHarianList as $kegiatanHarian) {
            if (isset($request->penilaian[$kegiatanHarian->id])) {
                $kegiatanHarian->penilaian = $request->penilaian[$kegiatanHarian->id];
                $kegiatanHarian->status = 'approve'; // Set status menjadi "approve"
                $kegiatanHarian->save();
            }
        }

        return redirect()->route('validasi-harian.index')->with(['success' => 'Data berhasil diperbarui']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}