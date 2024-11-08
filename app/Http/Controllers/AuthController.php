<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrasi pengguna baru
     */
    public function register(Request $request)
    {
        // Validasi data request
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8', // Menambahkan aturan konfirmasi password
        ]);

        try {
            // Buat pengguna baru
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => bcrypt($validatedData['password']),
            ]);

            // Buat token untuk pengguna
            $token = $user->createToken('auth_token')->plainTextToken;

            // Kirim respon dengan token
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201);

        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json(['error' => 'Registrasi gagal, silakan coba lagi.'], 500);
        }
    }

    /**
     * Login pengguna
     */
    public function login(Request $request)
    {
        // Validasi data request
        $validatedData = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
        ]);

        // Cek kredensial pengguna
        if (!Auth::attempt($validatedData)) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        try {
            // Ambil pengguna berdasarkan email
            $user = User::where('email', $request['email'])->firstOrFail();

            // Buat token untuk pengguna
            $token = $user->createToken('auth_token')->plainTextToken;

            // Kirim respon dengan token
            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json(['error' => 'Login gagal, silakan coba lagi.'], 500);
        }
    }

    /**
     * Logout pengguna
     */
    public function logout(Request $request)
    {
        try {
            // Hapus token pengguna saat ini
            $request->user()->currentAccessToken()->delete();

            // Kirim respon logout sukses
            return response()->json([
                'message' => 'Logout berhasil',
            ], 200);

        } catch (\Exception $e) {
            // Tangani jika terjadi kesalahan
            return response()->json(['error' => 'Logout gagal, silakan coba lagi.'], 500);
        }
    }

    /**
     * Mendapatkan data pengguna yang sedang login
     */
    public function userProfile(Request $request)
    {
        return response()->json($request->user());
    }
}
