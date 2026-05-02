<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessCode;

class AcccessCodeController extends Controller
{
    public function generateCode(Request $request)
    {
        $validation = $request->validate([
            'count' => 'required|integer|min:1|max:100', // Jumlah kode yang ingin dibuat
        ]);

        $codes = [];
        for ($i = 0; $i < $validation['count']; $i++) {
            $code = random_int(1000000000, 9999999999); // Generate kode acak 10 digit
            // Simpan kode ke database
            AccessCode::create(['code' => $code]);
            $codes[] = $code;
        }

        return response()->json(['codes' => $codes], 201);
    }

    public function listCodes()
    {
        $codes = AccessCode::all();
        return response()->json(['codes' => $codes], 200);
    }

    public function deleteCode($id)
    {
        $code = AccessCode::find($id);
        if (!$code) {
            return response()->json(['message' => 'Kode tidak ditemukan'], 404);
        }

        $code->delete();
        return response()->json(['message' => 'Kode berhasil dihapus'], 200);
    }
}
