<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Beranda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css" />
</head>

<body>
    <div class="container mx-0">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-8">
                <a class="btn btn-danger btn-sm" href="{{ route('beranda') }}">Kembali ke Beranda</a>
                <button class="btn btn-info btn-sm" onclick="openTambahModal()">tambah kategori</button>
                <h1 class="text-center" style="font-size: 50px">Kategori</h1>
                <table class="table" id="table-kategori">
                    <thead>
                        <tr>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    //modal edit
    <div class="modal fade" id="modaledit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Edit Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_kategori">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" class="form-control" id="nama_kategori" placeholder="Masukkan nama kategori">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="simpanKategori()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    //modal tambah
    <div class="modal fade" id="modaltambah" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Tambah Kategori</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" class="form-control" id="inputnama_kategori" placeholder="Masukkan nama kategori">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="tambahKategori()">Tambah</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>
        const apiURL = 'http://10.1.28.105:3000/api/';
        document.addEventListener('DOMContentLoaded', () => {
            getData();
        });

        function decrypt(encryptedData) {
            const key = CryptoJS.enc.Utf8.parse('3rl4ngg454l4m50l1dv1ct0ry1234567');

            const parts = encryptedData.split(':');
            const iv = CryptoJS.enc.Base64.parse(parts[0]);
            const ciphertext = CryptoJS.enc.Base64.parse(parts[1]);

            const decrypted = CryptoJS.AES.decrypt({
                    ciphertext: ciphertext
                },
                key, {
                    iv: iv,
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7
                }
            );

            return decrypted.toString(CryptoJS.enc.Utf8);
        }

        function openEditModal(id, nama_kategori) {
            $('#edit_id_kategori').val(id);
            $('#nama_kategori').val(nama_kategori);
            $('#modaledit').modal('show');
        }

        function openTambahModal() {
            $('#modaltambah').modal('show');
        }

        function getData() {
            $.ajax({
                url: apiURL + 'kategori',
                type: 'GET',
                dataType: 'json',
                // success: function(response) {
                //     const kategori = response.data;
                //     if (kategori && kategori.length) {
                //         $('#table-kategori').DataTable({
                //             data: kategori,
                //             destroy: true,
                //             columns: [{
                //                 data: 'nama_kategori',
                //                 title: 'Nama Kategori'
                //             }],
                //         })
                //     } else {
                //         Swal.fire('Gagal', 'Data pengguna tidak ditemukan.', 'error');
                //     }
                // },
                success: function(response) {
                    if (!response.encrypted) {
                        Swal.fire('Gagal', 'Data tidak terenkripsi.', 'error');
                        return;
                    }
                    const decryptedJson = decrypt(response.encrypted);
                    const result = JSON.parse(decryptedJson);
                    const kategori = result.data;
                    if (kategori && kategori.length) {
                        $('#table-kategori').DataTable({
                            data: kategori,
                            destroy: true,
                            columns: [{
                                    data: 'nama_kategori',
                                    title: 'Nama Kategori'
                                },
                                {
                                    data: null,
                                    title: 'Aksi',
                                    render: function(data, type, row) {
                                        return `<button type="button" class="btn btn-primary btn-sm" onclick="openEditModal(${row.id_kategori}, '${row.nama_kategori}')">Edit</button>
                                                <button class="btn btn-danger btn-sm" onclick="deleteKategori(${row.id})">Hapus</button>`;
                                    }
                                }
                            ],
                        })
                    } else {
                        Swal.fire('Gagal', 'Data kategori tidak ditemukan.', 'error');
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        // Token expired/invalid
                        localStorage.removeItem('token');
                        window.location.href = '/login';
                    } else {
                        Swal.fire('Gagal', 'Terjadi error lain.', 'error');
                    }
                }
            })
        }

        function simpanKategori() {
            const id = document.getElementById('edit_id_kategori').value;
            const nama_kategori = document.getElementById('nama_kategori').value;
            const token = localStorage.getItem('token');
            if (!token) {
                Swal.fire('Gagal', 'Token tidak ditemukan. Silakan login kembali.', 'error');
                window.location.href = '/login';
                return;
            }
            if (!nama_kategori) {
                Swal.fire('Gagal', 'Nama kategori tidak boleh kosong.', 'error');
                return;
            }
            $.ajax({
                url: apiURL + 'kategori/update/' + btoa(id ? id : ''),
                type: 'PUT',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    nama_kategori: nama_kategori
                }),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Kategori berhasil disimpan.',
                            confirmButtonColor: '#0951BC'
                        }).then(() => {
                            $('#modaledit').modal('hide');
                            getData();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menyimpan kategori.',
                            confirmButtonColor: '#0951BC'
                        });
                    }
                }
            })
        }

        function tambahKategori() {
            const nama_kategori = $('#inputnama_kategori').val();
            if (!nama_kategori) {
                Swal.fire('Gagal', 'Nama kategori tidak boleh kosong.', 'error');
                return;
            }
            $.ajax({
                url: apiURL + 'kategori/tambah',
                type: 'POST',
                dataType: 'json',
                contentType: 'application/json',
                data: JSON.stringify({
                    nama_kategori: nama_kategori
                }),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Kategori berhasil ditambahkan.',
                            confirmButtonColor: '#0951BC'
                        }).then(() => {
                            $('#modaltambah').modal('hide');
                            getData();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message || 'Terjadi kesalahan saat menambahkan kategori.',
                            confirmButtonColor: '#0951BC'
                        });
                    }
                }
            })
        }

        $(document).ready(function() {
            // $('#btnLogout').click(function() {
            //     Swal.fire({
            //         title: 'Konfirmasi',
            //         text: 'Apakah Anda yakin ingin keluar?',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#3085d6',
            //         cancelButtonColor: '#d33',
            //         confirmButtonText: 'Ya, keluar!'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             const token = localStorage.getItem('token');
            //             if (!token) {
            //                 window.location.href = '/login';
            //                 return;
            //             }
            //             $.ajax({
            //                 url: 'api/auth/logout',
            //                 type: 'POST',
            //                 dataType: 'json',
            //                 headers: {
            //                     'Authorization': 'Bearer ' + token
            //                 },
            //                 success: function(response) {
            //                     localStorage.removeItem('token');
            //                     window.location.href = '/login';
            //                 },
            //                 error: function(xhr) {
            //                     Swal.fire({
            //                         icon: 'error',
            //                         title: 'Logout Gagal',
            //                         text: xhr.responseJSON?.message || 'Terjadi kesalahan saat logout',
            //                         confirmButtonColor: '#0951BC'
            //                     });
            //                 }
            //             });
            //         }
            //     });
            // });
        })
    </script>
</body>

</html>