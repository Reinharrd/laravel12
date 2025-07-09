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
                <button class="btn btn-danger" id="btnLogout">Log Out</button>
                <a class="btn btn-info" href="{{ route('kategori') }}">Kategori</a>
                <h1 class="text-center" style="font-size: 50px">Beranda</h1>
                <table class="table" id="table-user">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.2.0/crypto-js.min.js"></script>
    <script>
        const apiURL = 'http://10.1.28.105:8000/api/auth/';
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

        function getData() {
            const token = localStorage.getItem('token');
            if (!token) {
                // Token tidak ada
                window.location.href = '/login';
                return;
            }
            $.ajax({
                url: apiURL + 'me',
                type: 'GET',
                dataType: 'json',
                headers: {
                    'Authorization': 'Bearer ' + token
                },
                success: function(response) {
                    // console.log(response);
                    // const user = response.data;
                    if (!response.encrypted) {
                        Swal.fire('Gagal', 'Data tidak terenkripsi.', 'error');
                        return;
                    }
                    const decryptedJson = decrypt(response.encrypted);
                    const user = JSON.parse(decryptedJson);
                    if (user) {
                        $('#table-user').DataTable({
                            data: [user],
                            destroy: true,
                            columns: [{
                                    data: 'nama',
                                    title: 'Nama'
                                },
                                {
                                    data: 'email',
                                    title: 'Email'
                                }
                            ],
                        })
                    } else {
                        Swal.fire('Gagal', 'Data pengguna tidak ditemukan.', 'error');
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

        $(document).ready(function() {
            $('#btnLogout').click(function() {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, keluar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const token = localStorage.getItem('token');
                        if (!token) {
                            window.location.href = '/login';
                            return;
                        }
                        $.ajax({
                            url: 'api/auth/logout',
                            type: 'POST',
                            dataType: 'json',
                            headers: {
                                'Authorization': 'Bearer ' + token
                            },
                            success: function(response) {
                                localStorage.removeItem('token');
                                window.location.href = '/login';
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Logout Gagal',
                                    text: xhr.responseJSON?.message || 'Terjadi kesalahan saat logout',
                                    confirmButtonColor: '#0951BC'
                                });
                            }
                        });
                    }
                });
            });
        })
    </script>
</body>

</html>