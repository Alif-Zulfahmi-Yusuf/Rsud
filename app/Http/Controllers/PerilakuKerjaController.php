<?php

namespace App\Http\Controllers;

use App\Models\Perilaku;
use Illuminate\Http\Request;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\PerilakuRequest;
use App\Http\Services\PerilakuService;


class PerilakuKerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $perilakuService;


    public function __construct(PerilakuService $perilakuService)
    {
        $this->perilakuService = $perilakuService;
    }


    public function index()
    {
        // Ambil semua kategori untuk modal input
        $categori = CategoryPerilaku::all();

        // Ambil kategori berdasarkan category_perilaku_id yang ada di tabel perilakus
        $categories = CategoryPerilaku::with(['perilakus'])
            ->whereIn('id', Perilaku::pluck('category_perilaku_id')->unique()) // Filter kategori berdasarkan id yang ada di perilakus
            ->get();

        return view('backend.perilaku.index', compact('categories', 'categori'));
    }


    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(PerilakuRequest $request)
    {
        try {

            $this->perilakuService->store($request->validated());

            return redirect()->back()->with('success', 'Perilaku berhasil ditambahkan.');
        } catch (\Exception $e) {

            Log::error('Gagal menyimpan Perilaku: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Perilaku gagal ditambahkan. Silakan coba lagi.');
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function edit(string $uuid)
    {
        // Cari perilaku berdasarkan UUID
        $perilaku = $this->perilakuService->selectFirstById('uuid', $uuid);

        $categories = CategoryPerilaku::all(); // Ambil semua kategori perilaku untuk dropdown

        return view('backend.perilaku.edit', compact('perilaku', 'categories'));
    }



    public function update(PerilakuRequest $request, string $uuid)
    {
        try {
            // Gabungkan data validasi dengan UUID dari parameter route
            $validated = array_merge($request->validated(), ['uuid' => $uuid]);

            // Log data yang divalidasi untuk debugging
            Log::info('Data yang divalidasi:', $validated);

            // Panggil service untuk memperbarui data
            $this->perilakuService->update($validated, $uuid);

            return redirect()->route('perilaku.index')->with('success', 'Perilaku berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat memperbarui perilaku: ' . $e->getMessage());
            return redirect()->route('perilaku.edit', $uuid)->with('error', 'Terjadi kesalahan saat memperbarui perilaku. Silakan coba lagi.');
        }
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->perilakuService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}