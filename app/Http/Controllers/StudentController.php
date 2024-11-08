<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar semua siswa
     */
    public function index()
    {
        try {
            return response()->json(Siswa::all(), 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal mengambil data siswa.'], 500);
        }
    }

    /**
     * Menyimpan data siswa baru
     */
    public function store(Request $request)
    {
        // Validasi data request
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'kelas' => 'required|string|max:10',
            'umur' => 'required|integer',
        ]);

        try {
            // Menyimpan data siswa baru
            $siswa = Siswa::create($validatedData);

            // Mengembalikan respon data siswa yang baru dibuat
            return response()->json($siswa, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menyimpan data siswa.'], 500);
        }
    }

    /**
     * Menampilkan data siswa berdasarkan ID
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json($siswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Siswa tidak ditemukan.'], 404);
        }
    }

    /**
     * Memperbarui data siswa berdasarkan ID
     */
    public function update(Request $request, $id)
    {
        try {
            // Mencari siswa berdasarkan ID
            $siswa = Siswa::findOrFail($id);

            // Validasi data request
            $validatedData = $request->validate([
                'nama' => 'sometimes|required|string|max:255',
                'kelas' => 'sometimes|required|string|max:10',
                'umur' => 'sometimes|required|integer',
            ]);

            // Memperbarui data siswa
            $siswa->update($validatedData);

            // Mengembalikan respon dengan data siswa yang telah diperbarui
            return response()->json($siswa, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memperbarui data siswa.'], 500);
        }
    }

    /**
     * Menghapus data siswa berdasarkan ID
     */
    public function destroy($id)
    {
        try {
            // Mencari siswa berdasarkan ID dan menghapusnya
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();

            // Mengembalikan respon dengan status 204 (No Content) jika berhasil dihapus
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal menghapus data siswa.'], 500);
        }
    }
}
