<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show">
                            <?php 
                            echo $_SESSION['success_message'];
                            unset($_SESSION['success_message']);
                            ?>
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <?php if ($user->avatar && file_exists($user->avatar)): ?>
                            <img src="/<?php echo htmlspecialchars($user->avatar); ?>" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        <?php else: ?>
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-4x"></i>
                            </div>
                        <?php endif; ?>
                    </div>

                    <dl class="row">
                        <dt class="col-sm-4">Tên đăng nhập:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($user->username); ?></dd>

                        <dt class="col-sm-4">Họ và tên:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($user->fullname); ?></dd>

                        <dt class="col-sm-4">Email:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($user->email); ?></dd>

                        <dt class="col-sm-4">Số điện thoại:</dt>
                        <dd class="col-sm-8"><?php echo htmlspecialchars($user->phone_number); ?></dd>

                        <dt class="col-sm-4">Vai trò:</dt>
                        <dd class="col-sm-8">
                            <span class="badge badge-<?php echo $user->role === 'admin' ? 'danger' : 'info'; ?>">
                                <?php echo $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                            </span>
                        </dd>
                    </dl>

                    <div class="text-center mt-4">
                        <a href="/webbanhang/account/editProfile" class="btn btn-primary">
                            <i class="fas fa-edit mr-2"></i>Chỉnh sửa thông tin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
