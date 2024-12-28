<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\SkpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Services\RencanaKerjaPegawaiService;

class RencanaKerjaPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $rencanaKerjaPegawaiService;
    protected $skpService;

    public function __construct(RencanaKerjaPegawaiService $rencanaKerjaPegawaiService, SkpService $skpService)
    {
        $this->rencanaKerjaPegawaiService = $rencanaKerjaPegawaiService;
        $this->skpService = $skpService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi data dari form
            $validated = $request->validate([
                'rencana_atasan_id' => 'required|exists:rencana_hasil_kerja,id', // Validasi rencana atasan
                'rencana' => 'required|string|max:500', // Validasi rencana hasil kerja
                'skp_id' => 'required|exists:skps,id',
            ]);

            // Simpan data menggunakan service
            $this->rencanaKerjaPegawaiService->create($validated);

            // Redirect dengan pesan sukses
            return back()->with('success', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            Log::error('Gagal menyimpan Rencana Hasil Kerja Pegawai', [
                'error' => $e->getMessage(),
            ]);

            // Kembalikan response dengan pesan error
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}