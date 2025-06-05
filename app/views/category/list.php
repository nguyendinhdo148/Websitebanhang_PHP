<?php include __DIR__ . '/../shares/header.php'; ?>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="display-6 font-weight-bold text-primary">Quản lý Danh mục</h1>
        <a href="/webbanhang/Category/add" class="btn btn-success btn-lg">
            <i class="fas fa-plus-circle mr-2"></i>Thêm mới
        </a>
    </div>

    <?php if (!empty($categories)): ?>
    <div class="row">
        <?php foreach ($categories as $category): ?>
        <div class="col-md-4 mb-4">
            <div class="card category-card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h3 class="card-title font-weight-bold text-dark mb-3">
                            <!-- Link dẫn tới trang sản phẩm theo danh mục -->
                            <a href="/webbanhang/Category/productsByCategory/<?php echo $category->id; ?>" class="text-decoration-none text-dark">
                                <i class="fas fa-folder mr-2"></i>
                                <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>
                        <span class="badge badge-pill badge-primary">ID: <?php echo $category->id; ?></span>
                    </div>
                    
                    <div class="category-actions mt-3">
                        <a href="/webbanhang/Category/edit/<?php echo $category->id; ?>" 
                           class="btn btn-outline-warning btn-sm mr-2">
                           <i class="fas fa-edit mr-1"></i>Sửa
                        </a>
                        <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" 
                           class="btn btn-outline-danger btn-sm" 
                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                           <i class="fas fa-trash-alt mr-1"></i>Xóa
                        </a>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-top-0 text-right">
                    <small class="text-muted">Cập nhật lần cuối: <?php echo date('d/m/Y'); ?></small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center py-4">
        <i class="fas fa-info-circle fa-3x mb-3 text-info"></i>
        <h3 class="alert-heading">Chưa có danh mục nào</h3>
        <p>Hãy bắt đầu bằng cách thêm danh mục mới</p>
        <a href="/webbanhang/Category/add" class="btn btn-info mt-2">
            <i class="fas fa-plus mr-1"></i> Thêm danh mục đầu tiên
        </a>
    </div>
    <?php endif; ?>
</div>

<style>
    .category-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        overflow: hidden;
        background: #ffffff;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: rgba(0,123,255,0.2);
    }
    
    .category-card .card-title a:hover {
        color: #007bff !important;
        text-decoration: underline !important;
    }
    
    .category-actions .btn {
        transition: all 0.2s ease;
    }
</style>

<?php include __DIR__ . '/../shares/footer.php'; ?>
