<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/CartModel.php');
require_once('app/helpers/SessionHelper.php');

class ProductController
{
    private $productModel;
    private $cartModel;
    private $db;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->cartModel = new CartModel($this->db);
    }
    
    private function checkAdminPermission()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập chức năng này';
            header('Location: /webbanhang/Product');
            exit();
        }
    }

    public function index()
    {
        $categoryModel = new CategoryModel($this->db);
        $categories = $categoryModel->getCategories();

        $categoryId = isset($_GET['category_id']) && is_numeric($_GET['category_id']) ? intval($_GET['category_id']) : null;
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        $minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? intval($_GET['min_price']) : null;
        $maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? intval($_GET['max_price']) : null;

        if ($categoryId) {
            $products = $this->productModel->getProductsByCategory($categoryId, $searchTerm, $minPrice, $maxPrice);
        } else {
            $products = $this->productModel->getProducts($searchTerm, $minPrice, $maxPrice);
        }

        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            $_SESSION['error_message'] = 'Không thấy sản phẩm.';
            header('Location: /webbanhang/Product');
        }
    }

    public function add()
    {
        $this->checkAdminPermission();
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        $this->checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            
            $result = $this->productModel->addProduct(
                $name,
                $description,
                $price,
                $category_id,
                $image
            );
            
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                $_SESSION['success_message'] = 'Thêm sản phẩm thành công';
                header('Location: /webbanhang/Product');
            }
        }
    }

    public function edit($id)
    {
        $this->checkAdminPermission();
        
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            $_SESSION['error_message'] = 'Không thấy sản phẩm.';
            header('Location: /webbanhang/Product');
        }
    }

    public function update()
    {
        $this->checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            
            $edit = $this->productModel->updateProduct(
                $id,
                $name,
                $description,
                $price,
                $category_id,
                $image
            );
            
            if ($edit) {
                $_SESSION['success_message'] = 'Cập nhật sản phẩm thành công';
                header('Location: /webbanhang/Product');
            } else {
                $_SESSION['error_message'] = 'Đã xảy ra lỗi khi lưu sản phẩm.';
                header("Location: /webbanhang/Product/edit/$id");
            }
        }
    }

    public function delete($id)
    {
        $this->checkAdminPermission();
        
        if ($this->productModel->deleteProduct($id)) {
            $_SESSION['success_message'] = 'Xóa sản phẩm thành công';
        } else {
            $_SESSION['error_message'] = 'Đã xảy ra lỗi khi xóa sản phẩm.';
        }
        header('Location: /webbanhang/Product');
    }
    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if (
            $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType !=
            "jpeg" && $imageFileType != "gif"
        ) {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        // Lưu file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }
    public function addToCart($id)
{
    if (!isset($_SESSION['user'])) {
        $_SESSION['error_message'] = 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng';
        header('Location: /webbanhang/account/login');
        exit();
    }

    $product = $this->productModel->getProductById($id);
    if (!$product) {
        $_SESSION['error_message'] = 'Sản phẩm không tồn tại';
        header('Location: /webbanhang/Product');
        exit();
    }

    $userId = $_SESSION['user']['id'];
    $cart = $this->cartModel->getOrCreateCart($userId);
    $result = $this->cartModel->addToCart($cart['id'], $id);

    if ($result) {
        $_SESSION['success_message'] = 'Đã thêm sản phẩm vào giỏ hàng';
    } else {
        $_SESSION['error_message'] = 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng';
    }
    
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}
    public function removeFromCart($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $cart = $this->cartModel->getOrCreateCart($userId);
        $this->cartModel->removeFromCart($cart['id'], $id);

        header('Location: /webbanhang/Product/cart');
        exit();
    }
    public function cart()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để xem giỏ hàng';
            header('Location: /webbanhang/account/login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $cart = $this->cartModel->getOrCreateCart($userId);
        $cartItems = $this->cartModel->getCartItems($cart['id']);

        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }
public function processCheckout()
{
    try {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để thanh toán';
            header('Location: /webbanhang/account/login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        
        // Kiểm tra user_id có tồn tại trong bảng users không
        $stmt = $this->db->prepare("SELECT id FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        if (!$stmt->fetch()) {
            throw new Exception("Người dùng không tồn tại");
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->db->beginTransaction();

            $cart = $this->cartModel->getOrCreateCart($userId);
            $cartItems = $this->cartModel->getCartItems($cart['id']);

            if (empty($cartItems)) {
                throw new Exception("Giỏ hàng trống");
            }

            // Lấy thông tin từ form
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            $payment_method = $_POST['payment_method'];

            // Tính tổng tiền
            $totalAmount = array_reduce($cartItems, function($sum, $item) {
                return $sum + ($item['price'] * $item['quantity']);
            }, 0);

            // Thêm đơn hàng với user_id
            $query = "INSERT INTO orders (user_id, name, phone, address, payment_method, total_amount) 
                     VALUES (:user_id, :name, :phone, :address, :payment_method, :total_amount)";
            $stmt = $this->db->prepare($query);
            
            $stmt->execute([
                ':user_id' => $userId,
                ':name' => $name,
                ':phone' => $phone,
                ':address' => $address,
                ':payment_method' => $payment_method,
                ':total_amount' => $totalAmount
            ]);
            
            $orderId = $this->db->lastInsertId();

            // Lưu chi tiết đơn hàng
            foreach ($cartItems as $item) {
                $query = "INSERT INTO order_details (order_id, product_id, quantity, price) 
                         VALUES (:order_id, :product_id, :quantity, :price)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $item['product_id'],
                    ':quantity' => $item['quantity'],
                    ':price' => $item['price']
                ]);
            }

            $this->cartModel->clearCart($cart['id']);
            $this->db->commit();

            $_SESSION['success_message'] = 'Đặt hàng thành công! Mã đơn hàng: #' . $orderId;
            header('Location: /webbanhang/Product/orderConfirmation');
            exit();
        }
    } catch (Exception $e) {
        if ($this->db->inTransaction()) {
            $this->db->rollBack();
        }
        
        $_SESSION['error_message'] = 'Lỗi đặt hàng: ' . $e->getMessage();
        header('Location: /webbanhang/Product/checkout');
        exit();
    }
}
    
    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }
    public function list()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }
    public function detail($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/detail.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }
    public function updateQuantity($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $quantity = $_POST['quantity'] ?? 1;
        $userId = $_SESSION['user']['id'];
        $cart = $this->cartModel->getOrCreateCart($userId);
        $this->cartModel->updateCartItem($cart['id'], $id, $quantity);

        header('Location: /webbanhang/Product/cart');
        exit();
    }
    public function increaseQuantity($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $cart = $this->cartModel->getOrCreateCart($userId);
        
        // Lấy số lượng hiện tại
        $currentItem = $this->cartModel->getCartItem($cart['id'], $id);
        $newQuantity = $currentItem ? $currentItem['quantity'] + 1 : 1;
        
        $this->cartModel->updateCartItem($cart['id'], $id, $newQuantity);

        header('Location: /webbanhang/Product/cart');
        exit();
    }
     public function decreaseQuantity($id)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /webbanhang/account/login');
            exit();
        }

        $userId = $_SESSION['user']['id'];
        $cart = $this->cartModel->getOrCreateCart($userId);
        
        // Lấy số lượng hiện tại
        $currentItem = $this->cartModel->getCartItem($cart['id'], $id);
        
        if ($currentItem) {
            $newQuantity = $currentItem['quantity'] - 1;
            if ($newQuantity <= 0) {
                $this->cartModel->removeFromCart($cart['id'], $id);
            } else {
                $this->cartModel->updateCartItem($cart['id'], $id, $newQuantity);
            }
        }

        header('Location: /webbanhang/Product/cart');
        exit();
    }
    public function clearCart()
{
    if (!isset($_SESSION['user'])) {
        header('Location: /webbanhang/account/login');
        exit();
    }

    $userId = $_SESSION['user']['id'];
    $cart = $this->cartModel->getOrCreateCart($userId);
    $this->cartModel->clearCart($cart['id']);

    $_SESSION['success_message'] = 'Đã xóa toàn bộ giỏ hàng';
    header('Location: /webbanhang/Product/cart');
    exit();
}
public function getCartCount() {
    if (!isset($_SESSION['user'])) {
        return 0;
    }
    
    $userId = $_SESSION['user']['id'];
    $cart = $this->cartModel->getOrCreateCart($userId);
    $cartItems = $this->cartModel->getCartItems($cart['id']);
    
    $total = 0;
    foreach ($cartItems as $item) {
        $total += $item['quantity'];
    }
    
    return $total;
}

}
