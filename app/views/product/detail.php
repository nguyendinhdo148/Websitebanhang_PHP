<?php
// app/views/product/detail.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sản phẩm</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <style>
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .product-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 20px;
        }
        .product-info {
            margin-bottom: 20px;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            color: #000;
        }
        .text-danger {
            color: #dc3545;
        }
        .text-muted {
            color: #6c757d;
        }
        .font-weight-bold {
            font-weight: 700;
        }
        .rounded {
            border-radius: 0.25rem;
        }
        .shadow-sm {
            box-shadow: 0.2rem 0.2rem 0.5rem rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <?php include 'app/views/shares/header.php'; ?>

    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <h1 class="display-7 font-weight-bold text-primary">Chi tiết sản phẩm</h1>
            <a href="/webbanhang/Product/index" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Quay lại danh sách
            </a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <?php if (!empty($product->image)): ?>
                    <img src="/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                         class="img-fluid rounded shadow-sm"
                         alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                <?php else: ?>
                    <img src="https://via.placeholder.com/400x300?text=No+Image" 
                         class="img-fluid rounded shadow-sm"
                         alt="No image">
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h2 class="font-weight-bold"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="text-muted">
                    <i class="fas fa-tag mr-1"></i>
                    <?php echo htmlspecialchars($product->category_name ?? 'Không có danh mục', ENT_QUOTES, 'UTF-8'); ?>
                </p>
                <h4 class="text-danger font-weight-bold">
                    <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                </h4>
                <p class="mt-4"><?php echo nl2br(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8')); ?></p>

                <!-- Thêm vào giỏ hàng -->
                <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" 
                   class="btn btn-primary btn-lg mt-3">
                    <i class="fas fa-cart-plus mr-2"></i>Thêm vào giỏ hàng
                </a>

                <!-- Xem giỏ hàng -->
                <a href="/webbanhang/Product/cart" class="btn btn-info btn-lg mt-3">
                    <i class="fas fa-shopping-cart mr-2"></i>Xem giỏ hàng
                </a>
            </div>
        </div>
    </div>

    <?php include 'app/views/shares/footer.php'; ?>
</body>
</html>