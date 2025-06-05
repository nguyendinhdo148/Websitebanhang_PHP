<?php
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

// Lấy URL từ GET, ví dụ: Product/add/123
$url = $_GET['url'] ?? '';

$baseFolder = 'webbanhang'; // Tên folder dự án

// Nếu URL có chứa 'webbanhang' ở đầu, bỏ đi
if (strpos($url, $baseFolder) === 0) {
    $url = substr($url, strlen($baseFolder));
    $url = ltrim($url, '/');
}

// Làm sạch và tách URL
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Xác định tên controller
$controllerName = isset($url[0]) && $url[0] !== '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Xác định action
$action = isset($url[1]) && $url[1] !== '' ? $url[1] : 'index';

// Kiểm tra file controller tồn tại
$controllerFile = 'app/controllers/' . $controllerName . '.php';
if (!file_exists($controllerFile)) {
    die('Controller Not Found: ' . $controllerName);
}
require_once $controllerFile;

// Kiểm tra class tồn tại và method
$controller = new $controllerName();
if (!method_exists($controller, $action)) {
    die('Action "' . $action . '" not found in controller "' . $controllerName . '"');
}

// Gọi action với các tham số
call_user_func_array([$controller, $action], array_slice($url, 2));
