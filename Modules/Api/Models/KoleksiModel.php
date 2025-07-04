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

    // rules & messages
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

    public function rulesUp()
    {
        return [
            'nama_koleksi' => 'required',
            'jenis_koleksi' => 'required',
            'kategori' => 'required',
            'penerbit' => 'required',
            'penulis' => 'required',
            'tahun_terbit' => 'required|integer',
            'deskripsi' => 'nullable'
        ];
    }

    public function messagesUp()
    {
        return [
            'nama_koleksi.required' => 'Nama koleksi wajib diisi',
            'jenis_koleksi.required' => 'Jenis koleksi wajib diisi',
            'kategori.required' => 'Kategori wajib diisi',
            'penerbit.required' => 'Penerbit wajib diisi',
            'penulis.required' => 'Penulis wajib diisi',
            'tahun_terbit.required' => 'Tahun terbit wajib diisi',
            'tahun_terbit.integer' => 'Tahun terbit harus berupa angka'
        ];
    }

    // query builder
    public function getDataKoleksi($per_halaman, $offset)
    {
        // return $this->select('*')->get();
        $users = DB::table('koleksis')
            ->select('id', 'nama_koleksi', 'jenis_koleksi', 'kategori', 'penerbit', 'penulis', 'tahun_terbit', 'deskripsi');

        $total_data = $users->clone()->count();
        $data = $users->offset($offset)
            ->limit($per_halaman)
            ->get();

        return [
            'total_data' => $total_data,
            'data' => $data
        ];
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

    public function updateKoleksi($id, $data)
    {
        return DB::table('koleksis')
            ->where('id', $id)
            ->update([
                'nama_koleksi' => $data['nama_koleksi'],
                'jenis_koleksi' => $data['jenis_koleksi'],
                'kategori' => $data['kategori'],
                'penerbit' => $data['penerbit'],
                'penulis' => $data['penulis'],
                'tahun_terbit' => $data['tahun_terbit'],
                'deskripsi' => $data['deskripsi']
            ]);
    }

    public function deleteKoleksi($id)
    {
        return DB::table('koleksis')
            ->where('id', $id)
            ->delete();
    }
}
