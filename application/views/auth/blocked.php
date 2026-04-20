<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>403 - Access Forbidden</title>

    <!-- Font Awesome -->
    <link href="<?= base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:300,400,600,700,800" rel="stylesheet">

    <!-- SB Admin 2 -->
    <link href="<?= base_url('assets/css/sb-admin-2.min.css'); ?>" rel="stylesheet">

    <style>
        .error-403 {
            font-size: 7rem;
            font-weight: 800;
            color: #e74a3b;
            line-height: 1;
        }
        .icon-lock {
            font-size: 3rem;
            color: #858796;
        }
    </style>
</head>

<body class="bg-gradient-light">

<div class="container">
    <div class="row min-vh-100 align-items-center justify-content-center">
        <div class="col-lg-7 col-md-9">

            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">

                    <div class="mb-3">
                        <i class="fas fa-lock icon-lock"></i>
                    </div>

                    <div class="error-403 mb-3">403</div>

                    <h4 class="text-gray-800 font-weight-bold mb-2">
                        Access Forbidden
                    </h4>

                    <p class="text-gray-500 mb-4">
                        Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                        Silakan hubungi administrator jika ini adalah kesalahan.
                    </p>

                    <div class="d-flex justify-content-center gap-2">
                        <a href="<?= base_url('dashboard'); ?>" class="btn btn-primary px-4 mr-2">
                            <i class="fas fa-home mr-1"></i> Dashboard
                        </a>
                        <a href="<?= base_url('auth/logout'); ?>" class="btn btn-outline-danger px-4">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </a>
                    </div>

                </div>
            </div>

            <p class="text-center text-gray-400 mt-4 small">
                © <?= date('Y'); ?> Your Application
            </p>

        </div>
    </div>
</div>

<!-- JS -->
<script src="<?= base_url('assets/vendor/jquery/jquery.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?= base_url('assets/vendor/jquery-easing/jquery.easing.min.js'); ?>"></script>
<script src="<?= base_url('assets/js/sb-admin-2.min.js'); ?>"></script>

</body>
</html>
