<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Casts\Json;
use App\Http\Services\RencanaKerjaAtasanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RencanaKerjaController extends Controller
{
    protected $rencanaKerjaatasanService;

    public function __construct(RencanaKerjaAtasanService $rencanaKerjaatasanService)
    {
        $this->rencanaKerjaatasanService = $rencanaKerjaatasanService;
    }

    public function store(Request $request)
    {
        try {
            // Validasi data dari form
            $validated = $request->validate([
                'rencana' => 'required|string|max:255|unique:rencana_hasil_kerja',
                'skp_atasan_id' => 'required|exists:skp_atasan,id', // Validasi SKP atasan ID
            ]);

            // Simpan data menggunakan service
            $this->rencanaKerjaatasanService->store($validated);

            // Log sukses menyimpan data
            Log::info('Rencana Hasil Kerja berhasil disimpan', [
                'data' => $validated,
            ]);

            // Redirect ke halaman index atau halaman sukses lainnya
            return back()->with('success', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            Log::error('Gagal menyimpan Rencana Hasil Kerja', [
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', $e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        $data = $request->validate([
            'rencana' => 'required|string|max:255|unique:rencana_hasil_kerja,rencana,' . $uuid,
        ]);

        try {
            $this->rencanaKerjaatasanService->update($uuid, $data);

            return response()->json(['message' => 'Rencana berhasil diperbarui.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Rencana tidak ditemukan.'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->rencanaKerjaatasanService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}