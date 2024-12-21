<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use Illuminate\Http\Request;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ValidasiService;

class ValidasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $validasiService;

    public function __construct(ValidasiService $validasiService)
    {
        $this->validasiService = $validasiService;
    }

    public function index()
    {
        // Ambil data SKP yang statusnya 'pending' dan sudah diajukan (is_submitted = 1)
        // dan yang mempunyai relasi dengan skpAtasan yang user_id nya sama dengan user yang login
        $skps = Skp::with(['user', 'skpAtasan'])
            ->where('status', 'pending') // Filter status pending
            ->where('is_submitted', 1)  // Filter hanya yang sudah diajukan
            ->whereHas('skpAtasan', function ($query) {
                // Filter hanya yang memiliki relasi dengan skpAtasan yang user_id nya sama dengan user yang login
                $query->where('user_id', Auth::id());
            })
            ->get();

        return view('backend.validasi.index', compact('skps'));
    }



    public function edit($uuid)
    {
        try {
            // Mendapatkan detail SKP
            $skpDetail = Skp::where('uuid', $uuid)->firstOrFail();

            // Mendapatkan semua kategori perilaku yang memiliki perilakus
            $categories = CategoryPerilaku::with('perilakus')
                ->whereHas('perilakus')
                ->get();

            // Tampilkan view edit dengan data SKP dan kategori perilaku
            return view('backend.validasi.edit', compact('skpDetail', 'categories'));
        } catch (\RuntimeException $e) {
            // Log error untuk kasus runtime exception
            Log::error('Gagal menampilkan data SKP untuk edit', [
                'uuid' => $uuid,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            // Redirect dengan pesan error
            return redirect()->route('validasi.index')->with('error', $e->getMessage());
        } catch (\Exception $e) {
            // Log error untuk exception tak terduga
            Log::error('Kesalahan tidak terduga saat menampilkan data SKP untuk edit', [
                'uuid' => $uuid,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            // Redirect dengan pesan error
            return redirect()->route('validasi.index')->with('error', 'Terjadi kesalahan. Silakan coba lagi.');
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        try {

            // Validasi input
            $request->validate([
                'keterangan_revisi' => 'nullable|string|max:500',
            ]);

            // Temukan SKP berdasarkan UUID
            $skp = Skp::where('uuid', $uuid)->firstOrFail();

            // Periksa jenis tombol yang diklik
            if ($request->has('approve')) {
                $skp->status = 'approve';
                $skp->keterangan_revisi = null; // Bersihkan keterangan revisi jika approve
            } elseif ($request->has('revisi')) {
                $skp->status = 'revisi';
                $skp->is_submitted = 0; // Set is_submitted menjadi 0 untuk revisi
                $skp->keterangan_revisi = $request->input('keterangan_revisi');
            }

            $skp->save();

            return redirect()->route('validasi.index')->with('success', 'Review SKP berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal memperbarui status SKP', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}