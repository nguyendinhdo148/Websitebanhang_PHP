<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        .product-image {
            max-width: 100px;
            height: auto;
        }

        .user-greeting {
            margin-right: 15px;
            font-weight: 500;
        }

        .admin-badge {
            font-size: 0.7rem;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/webbanhang/Product/">Quản lý sản phẩm</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                </li>

                <?php if (isset($_SESSION['user'])): ?>
                    <!-- Hiển thị khi đã đăng nhập -->
                    <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Product/add">Thêm sản phẩm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/Category/">Quản lý danh mục</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/webbanhang/account/list">
                                <i class="fas fa-users"></i> Quản lý người dùng
                            </a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/webbanhang/Product/cart">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="badge badge-danger">
                                <?php
                                if (isset($_SESSION['user'])) {
                                    require_once('app/config/database.php');
                                    require_once('app/models/CartModel.php');

                                    $db = (new Database())->getConnection();
                                    $cartModel = new CartModel($db);
                                    $userId = $_SESSION['user']['id'];
                                    $cart = $cartModel->getOrCreateCart($userId);
                                    $cartItems = $cartModel->getCartItems($cart['id']);

                                    $totalQuantity = 0;
                                    foreach ($cartItems as $item) {
                                        $totalQuantity += $item['quantity'];
                                    }
                                    echo $totalQuantity;
                                } else {
                                    echo '0';
                                }
                                ?>
                            </span>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <div class="navbar-nav">
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="nav-item d-flex align-items-center">
                        <span class="user-greeting">
                            Xin chào, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>
                            <?php if ($_SESSION['user']['role'] === 'admin'): ?>
                                <span class="badge badge-primary admin-badge">ADMIN</span>
                            <?php endif; ?>
                        </span>
                        <a href="/webbanhang/account/logout" class="btn btn-outline-danger btn-sm">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                <?php else: ?>
                    <a class="nav-link" href="/webbanhang/account/login">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </a>
                    <a class="nav-link" href="/webbanhang/account/register">
                        <i class="fas fa-user-plus"></i> Đăng ký
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    <div class="container mt-4">