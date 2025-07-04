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
    public function updateKoleksi(Request $request, $id)
    {
        $koleksiModel = new KoleksiModel();
        $validate = $request->validate($koleksiModel->rulesUp(), $koleksiModel->messagesUp());
        $data = $koleksiModel->updateKoleksi($id, $validate);
        if ($data) {
            return response()->json([
                'message' => 'Koleksi berhasil diperbarui',
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'message' => 'Koleksi gagal diperbarui',
            ], 500);
        }
    }
    public function deleteKoleksi($id)
    {
        $koleksiModel = new KoleksiModel();
        $data = $koleksiModel->deleteKoleksi($id);
        if ($data) {
            return response()->json([
                'message' => 'Koleksi berhasil dihapus'
            ], 204);
        } else {
            return response()->json([
                'message' => 'Koleksi tidak ditemukan atau gagal dihapus'
            ], 404);
        }
    }
}
