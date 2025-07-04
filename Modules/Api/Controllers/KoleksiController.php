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
        $per_halaman = 3;
        $halaman = (int) $request->query('halaman', 1);
        $offset = ($halaman - 1) * $per_halaman;
        $koleksiModel = new KoleksiModel();
        $data = $koleksiModel->getDataKoleksi($per_halaman, $offset);
        if ($data['total_data'] == 0) {
            return response()->json([
                'message' => 'Data koleksi tidak ditemukan'
            ], 404);
        }
        $total_halaman = ceil($data['total_data'] / $per_halaman);
        return response()->json([
            'message' => 'Data koleksi berhasil ditemukan',
            'data' => $data['data'],
            'pagination' => [
                'halaman' => $halaman,
                'per_halaman' => $per_halaman,
                'total_data' => $data['total_data'],
                'total_halaman' => $total_halaman,
            ]
        ], 200);
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
