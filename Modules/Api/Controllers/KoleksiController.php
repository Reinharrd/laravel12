<?php

namespace Modules\Api\Controllers;

use Dflydev\DotAccessData\Data;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Api\Models\KoleksiModel;

class KoleksiController extends Controller
{
    public function getDataKoleksi(Request $request)
    {
        $koleksiModel = new KoleksiModel();
        $data = $koleksiModel->getDataKoleksi();
        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Data koleksi tidak ditemukan'
            ], 404);
        } else {
            return response()->json([
                'message' => 'Data koleksi berhasil ditemukan',
                'data' => $data
            ], 200);
        }
    }
    public function tambahKoleksi(Request $request)
    {
        $koleksiModel = new KoleksiModel();
        $validate = $request->validate($koleksiModel->rulesCreate(), $koleksiModel->messagesCreate());
        $data = $koleksiModel->tambahKoleksi($validate);
        if ($data) {
            return response()->json([
                'message' => 'Koleksi berhasil ditambahkan',
                'data' => $data
            ], 201);
        } else {
            return response()->json([
                'message' => 'Koleksi gagal ditambahkan',
            ], 500);
        }
    }
}
