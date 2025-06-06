<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="card-title mb-0">Chỉnh sửa thông tin người dùng</h3>
                </div>
                <div class="card-body">
                    <form action="/webbanhang/account/updateUser" method="POST">
                        <input type="hidden" name="id" value="<?php echo $user->id; ?>">
                        
                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" name="username" class="form-control" 
                                   value="<?php echo htmlspecialchars($user->username); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Họ và tên</label>
                            <input type="text" name="fullname" class="form-control" 
                                   value="<?php echo htmlspecialchars($user->fullname); ?>" required>
                        </div>
                        
                        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <div class="form-group">
                            <label>Vai trò</label>
                            <select name="role" class="form-control" required>
                                <option value="user" <?php echo $user->role === 'user' ? 'selected' : ''; ?>>Người dùng</option>
                                <option value="admin" <?php echo $user->role === 'admin' ? 'selected' : ''; ?>>Quản trị viên</option>
                            </select>
                        </div>
                        <?php endif; ?>
                        
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i>Cập nhật
                            </button>
                            <a href="/webbanhang/account/list" class="btn btn-secondary">
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
