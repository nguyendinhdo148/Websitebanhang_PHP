<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <h1 class="display-5 font-weight-bold text-primary mb-4">Quản lý người dùng</h1>

    <?php if (!empty($users)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên đăng nhập</th>
                        <th>Họ tên</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th>Cập nhật</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user->id; ?></td>
                            <td><?php echo htmlspecialchars($user->username); ?></td>
                            <td><?php echo htmlspecialchars($user->fullname); ?></td>
                            <td>
                                <span class="badge badge-<?php echo $user->role === 'admin' ? 'danger' : 'info'; ?>">
                                    <?php echo $user->role; ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($user->updated_at)); ?></td>
                            <td>
                                <div class="btn-group">
                                    <a href="/webbanhang/account/view/<?php echo $user->id; ?>" 
                                       class="btn btn-sm btn-outline-info" title="Chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/webbanhang/account/editUser/<?php echo $user->id; ?>" 
                                       class="btn btn-sm btn-outline-warning" title="Sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($user->role !== 'admin'): ?>
                                        <a href="/webbanhang/account/delete/<?php echo $user->id; ?>" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');"
                                           class="btn btn-sm btn-outline-danger" title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> Chưa có người dùng nào trong hệ thống.
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>
