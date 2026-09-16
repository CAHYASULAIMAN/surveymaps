<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SUVEYS - Login</title>
    <!-- //icon logo di bar -->
   <link rel="icon" type="image/png" href="img/logo.avif">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">


</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-9 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                        <div class="col-lg-6 d-none d-lg-flex align-items-center justify-content-center">
                            <img src="img/logo.avif" class="img-fluid" alt="Gambar Utama" />
                        </div>

                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="mb-0" style="text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);">
                                            <span class="text-primary font-weight-bold">SURVEY</span> 
                                        </h4>
                                        <div class="text-center mb-3">
                                            <span class="text-gray-550">Routelink Mediatech</span>
                                        </div>
                                    </div>
                                    <form action="proses_login.php" 
                                          method="POST" class="user" 
                                          id="loginForm"
                                          onsubmit="return validateForm()"
                                          >
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                id="username" name="username" required 
                                                placeholder="Enter Email or Username . . . ">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" name="password" required 
                                                class="form-control form-control-user"
                                                id="password" placeholder="Password kamu . . . ">
                                        </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small ml-2">
                                            <input name="show_password" type="checkbox" class="custom-control-input" id="customCheck">
                                            <label class="custom-control-label" for="customCheck">Tampilkan sandi</label>
                                        </div>
                                    </div>
                                        <button type="submit" id="loginBtn" class="btn btn-primary btn-block btn-user font-weight-bold">
                                            <i class="fas fa-fingerprint fa-fw fingerprint-icon"></i> Login
                                        </button>
                                    
                                    </form>
                                    <hr>
                                    <!-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const passwordInput = document.getElementById("password");
        const showPasswordCheckbox = document.getElementById("customCheck");

        showPasswordCheckbox.addEventListener("change", function () {
            passwordInput.type = this.checked ? "text" : "password";
        });
    });

    VANTA.NET({
        el: "#bg-parallax",
        mouseControls: true,
        touchControls: true,
        gyroControls: false,
        minHeight: 200.00,
        minWidth: 200.00,
        scale: 1.00,
        scaleMobile: 1.00,
        color: 0xffffff,
        backgroundColor: 0x4e73df
        })

    document.addEventListener("DOMContentLoaded", function () {
         document.body.classList.add("loaded");
    });

    document.addEventListener("DOMContentLoaded", function () {
        const loginForm = document.getElementById("loginForm");
        const loginBtn = document.getElementById("loginBtn");

        loginForm.addEventListener("submit", function (event) {
            event.preventDefault();

            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Authenticating...';
            loginBtn.disabled = true; 

            requestAnimationFrame(() => {
                setTimeout(() => {
                    loginForm.submit();
                }, 100);
            });
        });
    });
    
</script>

</body>

</html>