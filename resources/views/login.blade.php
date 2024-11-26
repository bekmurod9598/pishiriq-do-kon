<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <!-- Title -->
	<title>Yasen boshqaruv tizimi</title>

	<!-- Meta -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="DexignZone">
	<meta name="robots" content="index, follow">
	<meta name="format-detection" content="telephone=no">

	<meta name="keywords" content="admin, admin dashboard, bootstrap, template, analytics, dark mode, modern, responsive admin dashboard, sass, ui kit">
	<meta name="description" content="Elevate your administrative efficiency and enhance productivity with the W3CRM Bootstrap Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">

	<meta property="og:title" content="W3CRM - Bootstrap Admin Dashboard Template">
	<meta property="og:description" content="Elevate your administrative efficiency and enhance productivity with the W3CRM Bootstrap Admin Dashboard Template. Designed to streamline your tasks, this powerful tool provides a user-friendly interface, robust features, and customizable options, making it the ideal choice for managing your data and operations with ease.">

	<!-- FAVICONS ICON -->
	<link rel="shortcut icon" type="image/png" href="images/favicon.png">

	<!-- MOBILE SPECIFIC -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="/vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
   <link href="/css/style.css" rel="stylesheet">

</head>

<body class="vh-100">
    <div class="authincation h-100">
        <div class="container-fluid h-100">
            <div class="row h-100">
				<div class="col-lg-6 col-md-12 col-sm-12 mx-auto align-self-center">
					<div class="login-form">
						<div class="text-center">
							<h3 class="title">KIRISH</h3>
							<p>Yasin boshqaruv tizimiga kirish</p>
						</div>
						<form method="POST" action="{{ route('login') }}">
                            @csrf
							<div class="mb-4">
								<label class="mb-1 text-dark">Email</label>
								<input type="email" class="form-control form-control" name="email">
							</div>
							<div class="mb-4 position-relative">
								<label class="mb-1 text-dark">Parol</label>
								<input type="password" id="dz-password" class="form-control" name="password">
								<span class="show-pass eye">

									<i class="fa fa-eye-slash"></i>
									<i class="fa fa-eye"></i>

								</span>
							</div>
							<div class="text-center mb-4">
								<button type="submit" class="btn btn-primary btn-block">Kirish</button>
							</div>
						</form>
					</div>
				</div>
                <div class="col-xl-6 col-sm-6">
					<div class="pages-left h-100">
						<div class="login-content">
						</div>
						<div class="login-media text-center">
							<img src="/images/login.png" alt="">
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>

<!--**********************************
	Scripts
***********************************-->
<!-- Required vendors -->
 <script src="/vendor/global/global.min.js"></script>
<script src="/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="/js/deznav-init.js"></script>

  <script src="/js/custom.js"></script>


</body>
</html>
