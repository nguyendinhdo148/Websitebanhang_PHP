<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 50vh;
        }
        footer {
            margin-top: auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <main class="flex-grow-1">
            <!-- Nội dung trang -->
        </main>

        <footer class="bg-dark text-white text-center text-lg-start py-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="text-uppercase">Về chúng tôi</h5>
                        <p>Web Bán Hàng cung cấp các sản phẩm chất lượng cao với giá cả hợp lý. Chúng tôi luôn đặt khách hàng lên hàng đầu.</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="text-uppercase">Liên kết nhanh</h5>
                        <ul class="list-unstyled">
                            <li><a href="/webbanhang/Product/index" class="text-white">Sản phẩm</a></li>
                            <li><a href="/webbanhang/Category/index" class="text-white">Danh mục</a></li>
                            <li><a href="/webbanhang/Product/cart" class="text-white">Giỏ hàng</a></li>
                            <li><a href="/webbanhang/Product/checkout" class="text-white">Thanh toán</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5 class="text-uppercase">Liên hệ</h5>
                        <p><i class="fas fa-map-marker-alt mr-2"></i>123 Đường ABC, TP.HCM</p>
                        <p><i class="fas fa-phone mr-2"></i>0123 456 789</p>
                        <p><i class="fas fa-envelope mr-2"></i>support@webbanhang.com</p>
                    </div>
                </div>
                <hr class="bg-light my-4">
                <div class="text-center">
                    <p class="mb-0">© 2025 Web Bán Hàng. All rights reserved.</p>
                    <div class="social-icons mt-2">
                        <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
