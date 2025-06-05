<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <!-- Banner Section -->
    <div id="bannerCarousel" class="carousel slide mb-5" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="https://template.canva.com/EAFqHtD5eto/2/0/800w-ShkFwAq8YDk.jpg" class="d-block w-100 rounded shadow-sm" alt="Banner 1">
            </div>
            <div class="carousel-item">
                <img src="https://template.canva.com/EAGLd7U_4sg/8/0/800w-GBiKE03PQ44.jpg" class="d-block w-100 rounded shadow-sm" alt="Banner 2">
            </div>
            <div class="carousel-item">
                <img src="https://template.canva.com/EAGQmlnTUhg/1/0/800w-uwqoOAelBwc.jpg" class="d-block w-100 rounded shadow-sm" alt="Banner 3">
            </div>
            <div class="carousel-item">
                <img src="https://template.canva.com/EAFjuLtNtvc/2/0/800w-JClygUx_-Ow.jpg" class="d-block w-100 rounded shadow-sm" alt="Banner 4">
            </div>
        </div>
        <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-5 flex-wrap">
        <h1 class="display-5 font-weight-bold text-primary mb-3 mb-md-0">Quản lý Sản phẩm</h1>

        <form action="" method="GET" class="d-flex align-items-center mb-3 mb-md-0 mr-3">
            <select name="category_id" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category->id; ?>" 
                        <?php if (isset($_GET['category_id']) && $_GET['category_id'] == $category->id) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><button type="submit" class="btn btn-primary">Lọc</button></noscript>
        </form>

        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="/webbanhang/product/add" class="btn btn-success btn-lg">
                <i class="fas fa-plus-circle mr-2"></i>Thêm sản phẩm
            </a>
        <?php endif; ?>
    </div>

    <?php if (!empty($products)): ?>
    <div class="row">
        <?php foreach ($products as $product): ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card product-card h-100 shadow-sm border-0">
                <div class="card-img-top product-image-container p-3">
                    <?php if (!empty($product->image)): ?>
                        <img src="/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                             class="product-image img-fluid"
                             alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x200?text=No+Image" 
                             class="product-image img-fluid"
                             alt="No image">
                    <?php endif; ?>
                </div>
                
                <div class="card-body d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h3 class="card-title h5 font-weight-bold mb-0 flex-grow-1">
                            <a href="/webbanhang/Product/detail/<?php echo $product->id; ?>" 
                               class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </h3>
                        <span class="badge badge-pill badge-info ml-2">ID: <?php echo $product->id; ?></span>
                    </div>
                    
                    <div class="mb-2">
                        <span class="badge badge-secondary">
                            <i class="fas fa-tag mr-1"></i>
                            <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </div>
                    
                    <div class="product-description mb-3">
                        <?php 
                        $description = htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8');
                        if (strlen($description) > 120) {
                            echo substr($description, 0, 120) . '...';
                        } else {
                            echo $description;
                        }
                        ?>
                    </div>
                    
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-danger font-weight-bold mb-0">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                            </h5>
                            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                            <div class="product-actions">
                                <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" 
                                   class="btn btn-sm btn-outline-warning mr-1"
                                   title="Sửa sản phẩm">
                                   <i class="fas fa-edit"></i>
                                </a>
                                <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   title="Xóa sản phẩm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                   <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex">
                            <a href="/webbanhang/Product/detail/<?php echo $product->id; ?>" 
                               class="btn btn-info btn-sm flex-grow-1 mr-2">
                               <i class="fas fa-info-circle mr-1"></i> Chi tiết
                            </a>
                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" 
                               class="btn btn-primary btn-sm flex-grow-1">
                               <i class="fas fa-cart-plus mr-1"></i> Mua ngay
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="alert alert-info text-center py-5">
        <i class="fas fa-box-open fa-3x mb-3 text-info"></i>
        <h3 class="alert-heading">Không có sản phẩm nào</h3>
        <p>Hãy bắt đầu bằng cách thêm sản phẩm mới</p>
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a href="/webbanhang/product/add" class="btn btn-info mt-2">
                <i class="fas fa-plus mr-1"></i> Thêm sản phẩm đầu tiên
            </a>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<style>
    .carousel img {
        max-height: 300px;
        object-fit: cover;
    }

    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .product-image-container {
        height: 220px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .product-image {
        max-height: 100%;
        max-width: 100%;
        object-fit: contain;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-description {
        color: #6c757d;
        font-size: 0.9rem;
        line-height: 1.5;
        min-height: 60px;
    }

    .product-actions .btn {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        padding: 0;
    }
    
    .card-body {
        padding: 1.25rem;
    }
</style>

<?php include 'app/views/shares/footer.php'; ?>