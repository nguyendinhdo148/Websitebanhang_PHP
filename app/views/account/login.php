<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                    <div class="card-body p-5 text-center">

                        <form action="/webbanhang/account/checklogin" method="post">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <div class="mb-4">
                                    <i class="fas fa-user-circle fa-3x text-light mb-3"></i>
                                    <h2 class="fw-bold mb-2 text-uppercase">Đăng nhập</h2>
                                    <p class="text-white-50 mb-4">Vui lòng nhập tên đăng nhập và mật khẩu</p>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input type="text" name="username" id="username" class="form-control form-control-lg" required />
                                    <label class="form-label" for="username">Tên đăng nhập</label>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input type="password" name="password" id="password" class="form-control form-control-lg" required />
                                    <label class="form-label" for="password">Mật khẩu</label>
                                </div>

                                <div class="mb-4">
                                    <?php if(isset($_SESSION['login_error'])): ?>
                                        <div class="alert alert-danger alert-dismissible fade show">
                                            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember">
                                        <label class="form-check-label text-white-50" for="remember">
                                            Ghi nhớ đăng nhập
                                        </label>
                                    </div>
                                    <a href="#!" class="text-white-50 small">Quên mật khẩu?</a>
                                </div>

                                <button class="btn btn-outline-light btn-lg px-5 rounded-pill mb-3" type="submit" style="transition: all 0.3s;">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                                </button>

                                <div class="divider d-flex align-items-center my-4">
                                    <p class="text-center mx-3 mb-0 text-white-50">Hoặc đăng nhập bằng</p>
                                </div>

                                <div class="d-flex justify-content-center text-center">
                                    <a href="#!" class="text-white mx-2" style="font-size: 1.2rem;">
                                        <i class="fab fa-facebook-f"></i>
                                    </a>
                                    <a href="#!" class="text-white mx-2" style="font-size: 1.2rem;">
                                        <i class="fab fa-google"></i>
                                    </a>
                                    <a href="#!" class="text-white mx-2" style="font-size: 1.2rem;">
                                        <i class="fab fa-twitter"></i>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <p class="mb-0">Chưa có tài khoản? 
                                    <a href="/webbanhang/account/register" class="text-white-50 fw-bold" style="text-decoration: underline;">
                                        Đăng ký ngay
                                    </a>
                                </p>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'app/views/shares/footer.php'; ?>