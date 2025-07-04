<?php

namespace Modules\Api\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KoleksiModel extends Model
{
    protected $table = 'koleksis';

    protected $fillable = [
        'nama_koleksi',
        'jenis_koleksi',
        'kategori',
        'penerbit',
        'penulis',
        'tahun_terbit',
        'deskripsi'
    ];

    public function rulesCreate()
    {
        return [
            'nama_koleksi' => 'required|string|max:255',
            'jenis_koleksi' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer',
            'deskripsi' => 'nullable|string|max:1000'
        ];
    }

    public function messagesCreate()
    {
        return [
            'nama_koleksi.required' => 'Nama koleksi wajib diisi',
            'nama_koleksi.max' => 'Nama koleksi tidak boleh lebih dari 255 karakter',
            'jenis_koleksi.required' => 'Jenis koleksi wajib diisi',
            'jenis_koleksi.max' => 'Jenis koleksi tidak boleh lebih dari 255 karakter',
            'kategori.required' => 'Kategori wajib diisi',
            'kategori.max' => 'Kategori tidak boleh lebih dari 255 karakter',
            'penerbit.required' => 'Penerbit wajib diisi',
            'penerbit.max' => 'Penerbit tidak boleh lebih dari 255 karakter',
            'penulis.required' => 'Penulis wajib diisi',
            'penulis.max' => 'Penulis tidak boleh lebih dari 255 karakter',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka',
            'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 1000 karakter'
        ];
    }

    public function getDataKoleksi()
    {
        // return $this->select('*')->get();
        $users = DB::table('koleksis')
            ->select('id', 'nama_koleksi', 'jenis_koleksi', 'kategori', 'penerbit', 'penulis', 'tahun_terbit', 'deskripsi')
            ->get();
        return $users->map(function ($item) {
            return [
                'id' => $item->id,
                'nama_koleksi' => $item->nama_koleksi,
                'jenis_koleksi' => $item->jenis_koleksi,
                'kategori' => $item->kategori,
                'penerbit' => $item->penerbit,
                'penulis' => $item->penulis,
                'tahun_terbit' => $item->tahun_terbit,
                'deskripsi' => $item->deskripsi
            ];
        });
    }

    public function tambahKoleksi()
    {
        return DB::table('koleksis')->insert([
            'nama_koleksi' => request('nama_koleksi'),
            'jenis_koleksi' => request('jenis_koleksi'),
            'kategori' => request('kategori'),
            'penerbit' => request('penerbit'),
            'penulis' => request('penulis'),
            'tahun_terbit' => request('tahun_terbit'),
            'deskripsi' => request('deskripsi')
        ]);
    }
}
