<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class SiswaController extends Controller
{
    /**
     * Menampilkan daftar semua siswa.
     */
    public function index()
    {
        try {
            return response()->json([
                'status' => 'success',
                'data' => Siswa::all()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengambil data siswa.'
            ], 500);
        }
    }

    /**
     * Menyimpan data siswa baru.
     */
    public function store(Request $request)
{
    Log::info('Memulai penyimpanan data siswa.');

    $validator = Validator::make($request->all(), [
        'nama' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
        'kelas' => ['required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
        'umur' => 'required|integer|min:6|max:18'
    ]);

    if ($validator->fails()) {
        Log::error('Validasi gagal', $validator->errors()->toArray());
        return response()->json([
            'status' => 'error',
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $siswa = Siswa::create($request->all());
        Log::info('Data siswa berhasil disimpan.', ['data' => $siswa]);
        return response()->json([
            'status' => 'success',
            'data' => $siswa
        ], 201);
    } catch (\Exception $e) {
        Log::error('Error menyimpan data siswa: ' . $e->getMessage());
        return response()->json([
            'status' => 'error',
            'message' => 'Gagal menyimpan data siswa.'
        ], 500);
    }
}


    /**
     * Menampilkan data siswa berdasarkan ID.
     */
    public function show($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Siswa tidak ditemukan.'
            ], 404);
        }
    }

    /**
     * Memperbarui data siswa berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => ['sometimes', 'required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
            'kelas' => ['sometimes', 'required', 'string', 'max:10', 'regex:/^(X|XI|XII)\s(IPA|IPS)\s[1-9]$/'],
            'umur' => 'sometimes|required|integer|min:6|max:18'
        ], [
            'nama.regex' => 'Nama hanya boleh mengandung huruf dan spasi',
            'kelas.regex' => 'Format kelas harus seperti "XII IPA 1"',
            'umur.min' => 'Umur minimal adalah 6 tahun',
            'umur.max' => 'Umur maksimal adalah 18 tahun'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $siswa
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memperbarui data siswa.'
            ], 500);
        }
    }

    /**
     * Menghapus data siswa berdasarkan ID.
     */
    public function destroy($id)
    {
        try {
            $siswa = Siswa::findOrFail($id);
            $siswa->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Data siswa berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting student: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data siswa.'
            ], 500);
        }
    }
}
