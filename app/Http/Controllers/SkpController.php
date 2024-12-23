<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Perilaku;
use App\Models\Ekspetasi;
use App\Models\SkpAtasan;
use Illuminate\Http\Request;
use App\Models\CategoryPerilaku;
use App\Http\Requests\SkpRequest;
use App\Http\Services\SkpService;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkpController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $skpService;

    public function __construct(SkpService $skpService)
    {
        $this->skpService = $skpService;
    }
    public function index()
    {
        // Ambil data SKP berdasarkan ID pengguna yang sedang login
        $skps = Skp::with(['skpAtasan.user']) // Relasi ke SKP Atasan dan user dari SKP Atasan
            ->where('user_id', Auth::id()) // Filter berdasarkan pengguna yang login
            ->get();

        return view('backend.skp.index', compact('skps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkpRequest $request)
    {
        try {
            // Ambil data yang divalidasi
            $data = $request->validated();

            // Log data yang diterima dari request
            Log::info('Data yang diterima untuk SKP:', $data);

            // Panggil service untuk menyimpan SKP
            $skp = $this->skpService->store($data, Auth::user());

            return back()->with('success', 'Data SKP berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error yang terjadi
            Log::error('Gagal menyimpan data SKP', [
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        try {
            // Mendapatkan detail SKP menggunakan service
            $skpDetail = $this->skpService->getSkpDetail($uuid);

            // Mendapatkan semua kategori yang memiliki perilakus
            $categories = CategoryPerilaku::with('perilakus')
                ->whereHas('perilakus') // Hanya ambil kategori yang memiliki perilakus
                ->get();

            // Mendapatkan data ekspektasi berdasarkan skp_id
            $ekspektasis = Ekspetasi::where('skp_id', $skpDetail->id)->get();

            // Menampilkan view edit dengan data SKP, kategori perilaku, dan ekspektasi
            return view('backend.skp.edit', compact('skpDetail', 'categories', 'ekspektasis'));
        } catch (\RuntimeException $e) {
            // Log error jika data tidak ditemukan
            Log::error('Gagal menampilkan data SKP untuk edit', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);
            // Tangani jika data tidak ditemukan
            abort(404, $e->getMessage());
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        // Validasi data (jika ada)
        $request->validate([
            // Tambahkan aturan validasi jika diperlukan
            'keterangan' => 'nullable|string',
        ]);

        // Cari SKP berdasarkan UUID
        $skp = Skp::where('uuid', $uuid)->firstOrFail();

        // Ubah status pengajuan dan waktu pengajuan
        $skp->status = 'pending'; // Status awal setelah diajukan
        $skp->is_submitted = 1;   // Tandai sebagai telah diajukan
        $skp->submitted_at = now(); // Catat waktu pengajuan
        $skp->keterangan = $request->input('keterangan'); // Simpan keterangan
        $skp->save();

        // Berikan notifikasi sukses ke pengguna
        return redirect()->route('skp.index')->with('success', 'SKP berhasil diajukan!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->skpService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }

    public function getData($uuid)
    {
        // Ambil data utama dari RencanaHasilKinerja berdasarkan UUID
        $rencana = RencanaHasilKinerja::where('uuid', $uuid)
            ->with(['rencanaPegawai.indikatorKinerja'])
            ->first();

        if (!$rencana) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Proses data menjadi format tabel
        $data = [];
        foreach ($rencana->rencanaPegawai as $pegawai) {
            foreach ($pegawai->indikatorKinerja as $indikator) {
                $data[] = [
                    'nama_pegawai' => $pegawai->user->name ?? 'N/A',
                    'rencana' => $pegawai->rencana,
                    'indikator' => $indikator->indikator_kinerja,
                    'target_min' => $indikator->target_minimum,
                    'target_max' => $indikator->target_maksimum,
                    'satuan' => $indikator->satuan,
                ];
            }
        }

        return response()->json(['data' => $data]);
    }
}