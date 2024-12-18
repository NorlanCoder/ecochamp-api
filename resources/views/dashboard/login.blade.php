<!doctype html>
<html lang="en" data-bs-theme="light">


<!-- Mirrored from codervent.com/syndron/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 17 Oct 2024 11:57:07 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="{{asset('assets/images/favicon.png')}}" type="image">
	<!--plugins-->
	<link href="{asset('assets/plugins/simplebar/css/simplebar.css')}" rel="stylesheet">
	<link href="{{asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet">
	<link href="{{asset('assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet">
	<!-- loader-->
	<link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet">
	<script src="{{asset('assets/js/pace.min.js')}}"></script>
	<!-- Bootstrap CSS -->
	<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
	<link href="{{asset('assets/css/bootstrap-extended.css')}}" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{asset('assets/sass/app.css')}}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('assets/sass/dark-theme.css')}}">
	<link href="{{asset('assets/css/icons.css')}}" rel="stylesheet">
	<title>Ecochamp - DashBoard login</title>
</head>

<body class="">
	<!--wrapper-->
	<div class="wrapper">
		<div class="section-authentication-cover">
			<div class="">
				<div class="row g-0">

					<div class="col-12 col-xl-7 col-xxl-8 auth-cover-left align-items-center justify-content-center d-none d-xl-flex">

                        <div class="card shadow-none bg-transparent shadow-none rounded-0 mb-0">
							<div class="card-body">
                                 <img src="{{asset('assets/images/login-images/login-cover.svg')}}" class="img-fluid auth-img-cover-login" width="650" alt=""/>
							</div>
						</div>
						
					</div>

					<div class="col-12 col-xl-5 col-xxl-4 auth-cover-right align-items-center justify-content-center">
						<div class="card rounded-0 m-3 shadow-none bg-transparent mb-0">
							<div class="card-body p-sm-5">
								<div class="">
									<div class="mb-3 text-center">
										<img src="{{asset('assets/images/logo-icon.png')}}" width="60" alt="">
									</div>
									<div class="text-center mb-4">
										<h5 class="">Ecochamp Admin</h5>
										<p class="mb-0">Veuillez vous connecter à votre compte</p>
									</div>
									<div class="form-body">
										<form class="row g-3"action="{{route('web.auth.check')}}" method="post">
											@csrf
											<div class="col-12">
												<label for="inputEmailAddress" class="form-label">Email</label>
												<input type="email" class="form-control" required id="inputEmailAddress" placeholder="jhon@example.com" name="email">
												<span class="text-danger small">@error('email'){{ $message }} @enderror </span>
											</div>
											<div class="col-12">
												<label for="inputChoosePassword" class="form-label">Password</label>
												<div class="input-group" id="show_hide_password">
													<input type="password" class="form-control border-end-0" id="inputChoosePassword" required placeholder="Enter Password" name="password"> <a href="javascript:;" class="input-group-text bg-transparent"><i class="bx bx-hide"></i></a>
												</div>
												<span class="text-danger small">@error('password'){{ $message }} @enderror </span>
											</div>
											<div class="col-md-6">
												<div class="form-check form-switch">
													<input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
													<label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
												</div>
											</div>
											<div class="col-md-6 text-end">	<a href="auth-cover-forgot-password.html">Forgot Password ?</a>
											</div>
											<div class="col-12">
												<div class="d-grid">
													<button type="submit" class="btn btn-primary">Sign in</button>
												</div>
											</div>
											<!-- <div class="col-12">
												<div class="text-center">
													<p class="mb-0">Don't have an account yet? <a href="auth-cover-signup.html">Sign up here</a>
													</p>
												</div>
											</div> -->
										</form>
									</div>
									<!-- <div class="login-separater text-center mb-5"> <span>OR SIGN IN WITH</span>
										<hr>
									</div>
									<div class="list-inline contacts-social text-center">
										<a href="javascript:;" class="list-inline-item bg-facebook text-white border-0 rounded-3"><i class="bx bxl-facebook"></i></a>
										<a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a>
										<a href="javascript:;" class="list-inline-item bg-google text-white border-0 rounded-3"><i class="bx bxl-google"></i></a>
										<a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a>
									</div> -->

								</div>
							</div>
						</div>
					</div>

				</div>
				<!--end row-->
			</div>
		</div>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
	<!--plugins-->
	<script src="{{asset('assets/js/jquery.min.js')}}"></script>
	<script src="{{asset('assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
	<script src="{{asset('assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
	<script src="{{asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
	<!--Password show & hide js -->
	<script>
		$(document).ready(function () {
			$("#show_hide_password a").on('click', function (event) {
				event.preventDefault();
				if ($('#show_hide_password input').attr("type") == "text") {
					$('#show_hide_password input').attr('type', 'password');
					$('#show_hide_password i').addClass("bx-hide");
					$('#show_hide_password i').removeClass("bx-show");
				} else if ($('#show_hide_password input').attr("type") == "password") {
					$('#show_hide_password input').attr('type', 'text');
					$('#show_hide_password i').removeClass("bx-hide");
					$('#show_hide_password i').addClass("bx-show");
				}
			});
		});
	</script>
	<!--app JS-->
	<script src="{{asset('assets/js/app.js')}}"></script>
</body>


<!-- Mirrored from codervent.com/syndron/demo/vertical/auth-cover-signin.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 17 Oct 2024 11:57:07 GMT -->
</html>