<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Chỉnh sửa thông tin cá nhân</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($_SESSION['errors'])): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($_SESSION['errors'] as $error): ?>
                                    <li><?php echo htmlspecialchars($error); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php unset($_SESSION['errors']); ?>
                    <?php endif; ?>

                    <form action="/account/updateProfile" method="POST" enctype="multipart/form-data">
                        <div class="form-group text-center">
                            <div class="mb-3">
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
                            <input type="hidden" name="existing_avatar" value="<?php echo htmlspecialchars($user->avatar ?? ''); ?>">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="avatar" name="avatar" accept="image/*">
                                <label class="custom-file-label" for="avatar">Chọn ảnh đại diện mới</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($user->username); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" 
                                   value="<?php echo htmlspecialchars($user->fullname); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" 
                                   value="<?php echo htmlspecialchars($user->email); ?>" required>
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" name="phoneNumber" class="form-control" 
                                   value="<?php echo htmlspecialchars($user->phone_number); ?>" required>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Lưu thay đổi
                            </button>
                            <a href="/webbanhang/account/profile" class="btn btn-secondary ml-2">
                                <i class="fas fa-arrow-left mr-2"></i>Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
