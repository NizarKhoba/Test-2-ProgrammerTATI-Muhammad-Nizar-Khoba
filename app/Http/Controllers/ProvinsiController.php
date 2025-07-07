<?php

namespace App\Http\Controllers;

use App\Models\Provinsi;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProvinsiController extends Controller
{
    public function index(): JsonResponse
    {
        $provinsis = Provinsi::all();
        return response()->json(['data' => $provinsis], 200);
    }

    public function show($id): JsonResponse
    {
        $provinsi = Provinsi::find($id);
        if (!$provinsi) {
            return response()->json(['message' => 'Provinsi tidak ditemukan'], 404);
        }
        return response()->json(['data' => $provinsi], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'ibukota' => 'required|string|max:255',
            'populasi' => 'nullable|integer',
            'luas_wilayah' => 'nullable|numeric'
        ]);

        $provinsi = Provinsi::create($request->all());
        return response()->json(['data' => $provinsi, 'message' => 'Provinsi berhasil ditambahkan'], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $provinsi = Provinsi::find($id);
        if (!$provinsi) {
            return response()->json(['message' => 'Provinsi tidak ditemukan'], 404);
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'ibukota' => 'required|string|max:255',
            'populasi' => 'nullable|integer',
            'luas_wilayah' => 'nullable|numeric'
        ]);

        $provinsi->update($request->all());
        return response()->json(['data' => $provinsi, 'message' => 'Provinsi berhasil diperbarui'], 200);
    }

    public function destroy($id): JsonResponse
    {
        $provinsi = Provinsi::find($id);
        if (!$provinsi) {
            return response()->json(['message' => 'Provinsi tidak ditemukan'], 404);
        }

        $provinsi->delete();
        return response()->json(['message' => 'Provinsi berhasil dihapus'], 200);
    }
}
