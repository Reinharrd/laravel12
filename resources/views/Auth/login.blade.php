<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center" style="min-height: 100vh;">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header text-center">
                        <h4>Login</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="" id="formLogin">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password">
                            </div>
                            <button type="submit" class="btn btn-primary w-100" onclick="loginUser()">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById("formLogin").addEventListener("submit", function(e) {
            e.preventDefault();
            loginUser();
        });

        function loginUser() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;

            if (!email || !password) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: 'Silakan isi email dan password terlebih dahulu',
                    confirmButtonColor: '#0951BC'
                });
                return;
            }

            $.ajax({
                url: '/api/auth/login',
                method: 'POST',
                dataType: 'json',
                data: {
                    email: email,
                    password: password
                },
                success: function(response) {
                    // Simpan token di localStorage jika diperlukan
                    localStorage.setItem('token', response.token);

                    Swal.fire({
                        icon: 'success',
                        title: 'Login Berhasil',
                        text: 'Selamat datang!',
                        confirmButtonColor: '#0951BC'
                    }).then(() => {
                        window.location.href = '/beranda';
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Gagal',
                        text: xhr.responseJSON?.message || 'Email atau kata sandi salah',
                        confirmButtonColor: '#0951BC'
                    });
                }
            });
        }
    </script>
</body>

</html>