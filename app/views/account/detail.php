<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h3 class="card-title mb-0">Chi tiết tài khoản</h3>
                </div>
                <div class="card-body">
                    <?php 
$avatarPath = '/webbanhang/public/uploads/avatars/' . $user->avatar;
if ($user->avatar && file_exists($_SERVER['DOCUMENT_ROOT'] . $avatarPath)): ?>
<div class="row mb-4 justify-content-center">
    <div class="col-md-6 text-center">
        <img src="<?php echo $avatarPath; ?>" 
             class="img-fluid rounded-circle" 
             style="max-width: 200px; max-height: 200px;"
             alt="Avatar">
    </div>
</div>
<?php endif; ?>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">ID:</div>
                        <div class="col-md-8"><?php echo $user->id; ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Tên đăng nhập:</div>
                        <div class="col-md-8"><?php echo htmlspecialchars($user->username); ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Họ và tên:</div>
                        <div class="col-md-8"><?php echo htmlspecialchars($user->fullname); ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Vai trò:</div>
                        <div class="col-md-8">
                            <span class="badge badge-<?php echo $user->role === 'admin' ? 'danger' : 'info'; ?>">
                                <?php echo $user->role === 'admin' ? 'Quản trị viên' : 'Người dùng'; ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Ngày tạo:</div>
                        <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Cập nhật lần cuối:</div>
                        <div class="col-md-8"><?php echo date('d/m/Y H:i', strtotime($user->updated_at)); ?></div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Email:</div>
                        <div class="col-md-8"><?php echo htmlspecialchars($user->email); ?></div>
                    </div>
                    
                    <div class="row mb-4">
                        <div class="col-md-4 text-muted">Số điện thoại:</div>
                        <div class="col-md-8"><?php echo htmlspecialchars($user->phone_number); ?></div>
                    </div>

                    <div class="mt-4">
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                            <a href="/webbanhang/account/editUser/<?php echo $user->id; ?>" class="btn btn-warning">
                                <i class="fas fa-edit mr-2"></i>Chỉnh sửa
                            </a>
                        <?php endif; ?>
                        <a href="/webbanhang/account/list" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
