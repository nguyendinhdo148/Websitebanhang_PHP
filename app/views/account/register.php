<?php include 'app/views/shares/header.php'; ?>

<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem; box-shadow: 0 8px 20px rgba(0,0,0,0.3);">
                    <div class="card-body p-5 text-center">
                        <form class="user" action="/webbanhang/account/save" method="post" enctype="multipart/form-data">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <div class="mb-4">
                                    <i class="fas fa-user-plus fa-3x text-light mb-3"></i>
                                    <h2 class="fw-bold mb-2 text-uppercase">Đăng ký tài khoản</h2>
                                    <p class="text-white-50 mb-4">Vui lòng điền đầy đủ thông tin</p>
                                </div>

                                <?php if(isset($errors) && !empty($errors)): ?>
                                    <div class="alert alert-danger alert-dismissible fade show mb-4">
                                        <ul class="mb-0 text-left">
                                            <?php foreach ($errors as $err): ?>
                                                <li><?php echo htmlspecialchars($err); ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <div class="form-outline form-white">
                                            <input type="text" class="form-control form-control-lg" 
                                                id="username" name="username" required
                                                placeholder=" " value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                                            <label class="form-label" for="username">Tên đăng nhập</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-outline form-white">
                                            <input type="text" class="form-control form-control-lg" 
                                                id="fullname" name="fullname" required
                                                placeholder=" " value="<?php echo isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : ''; ?>">
                                            <label class="form-label" for="fullname">Họ và tên</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3">
                                        <div class="form-outline form-white">
                                            <input type="email" class="form-control form-control-lg" 
                                                id="email" name="email" required
                                                placeholder=" " value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                                            <label class="form-label" for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-3">
                                        <div class="form-outline form-white">
                                            <input type="tel" class="form-control form-control-lg" 
                                                id="phoneNumber" name="phoneNumber" required
                                                placeholder=" " value="<?php echo isset($_POST['phoneNumber']) ? htmlspecialchars($_POST['phoneNumber']) : ''; ?>">
                                            <label class="form-label" for="phoneNumber">Số điện thoại</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-4">
                                    <div class="form-outline form-white">
                                        <input type="file" class="form-control form-control-lg" 
                                            id="avatar" name="avatar" accept="image/*">
                                        <label class="form-label" for="avatar">Ảnh đại diện</label>
                                    </div>
                                </div>

                                <div class="form-group row mt-4">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <div class="form-outline form-white">
                                            <input type="password" class="form-control form-control-lg" 
                                                id="password" name="password" required
                                                placeholder=" ">
                                            <label class="form-label" for="password">Mật khẩu</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-outline form-white">
                                            <input type="password" class="form-control form-control-lg" 
                                                id="confirmpassword" name="confirmpassword" required
                                                placeholder=" ">
                                            <label class="form-label" for="confirmpassword">Xác nhận mật khẩu</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-5">
                                    <button class="btn btn-outline-light btn-lg px-5 rounded-pill" type="submit" style="transition: all 0.3s;">
                                        <i class="fas fa-user-plus mr-2"></i>Đăng ký
                                    </button>
                                </div>
                            </div>

                            <div>
                                <p class="mb-0">Đã có tài khoản? 
                                    <a href="/webbanhang/account/login" class="text-white-50 fw-bold" style="text-decoration: underline;">
                                        Đăng nhập ngay
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