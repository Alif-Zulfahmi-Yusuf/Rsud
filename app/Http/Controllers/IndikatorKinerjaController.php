<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IndikatorKinerja;
use Illuminate\Support\Facades\Log;
use App\Http\Services\IndikatorService;
use App\Http\Requests\IndikatorKinerjaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class IndikatorKinerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $indikatorService;

    // Konstruktor untuk inject service
    public function __construct(IndikatorService $indikatorService)
    {
        $this->indikatorService = $indikatorService;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input dari form
            $validated = $request->validate([
                'rencana_kerja_pegawai_id' => 'nullable|exists:rencana_hasil_kerja_pegawai,id',
                'rencana_kerja_atasan_id' => 'nullable|exists:rencana_hasil_kerja,id', // Validasi rencana_kerja_atasan_id
                'aspek' => 'required|string|in:kualitas,kuantitas,waktu',
                'indikator_kinerja' => 'required|string|max:255',
                'tipe_target' => 'required|string|in:satu_nilai,range_nilai,kualitatif',
                'target_minimum' => 'required|numeric|min:0',
                'target_maksimum' => 'nullable|numeric|min:0',
                'satuan' => 'required|string|max:50',
                'report' => 'required|string|in:bulanan,triwulan,semesteran,tahunan',
            ]);

            // Pastikan key tetap ada meskipun nullable
            $validated['rencana_kerja_atasan_id'] = $validated['rencana_kerja_atasan_id'] ?? null;
            $validated['rencana_kerja_pegawai_id'] = $validated['rencana_kerja_pegawai_id'] ?? null;

            // Simpan data menggunakan service
            $this->indikatorService->create($validated);

            return back()->with('success', 'Indikator Kinerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Indikator Kinerja', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }


    public function update(Request $request, $uuid)
    {
        Log::info('Data diterima untuk update:', $request->all());
        // Validasi data dari form
        try {
            $data = $request->validate([
                'rencana_kerja_pegawai_id' => 'required|exists:rencana_hasil_kerja_pegawai,id',
                'aspek' => 'required|string|max:255',
                'indikator_kinerja' => 'required|string',
                'tipe_target' => 'required|string',
                'target_minimum' => 'required|numeric',
                'target_maksimum' => 'nullable|numeric',
                'satuan' => 'required|string|max:255',
                'report' => 'nullable|string',
            ]);

            Log::info('Data valid:', $data);

            $this->indikatorService->update($uuid, $data);

            return response()->json(['message' => 'Indikator berhasil diperbarui.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validasi gagal:', $e->errors());
            return response()->json(['message' => 'Validasi gagal.'], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Indikator tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            Log::error('Kesalahan:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->indikatorService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Data not found or failed to delete.'], 404);
    }
}