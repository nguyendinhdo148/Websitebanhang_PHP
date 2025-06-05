<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/models/ProductModel.php');
require_once('app/helpers/SessionHelper.php');

class CategoryController
{
    private $categoryModel;
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->productModel = new ProductModel($this->db);
    }

    private function checkAdminPermission()
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /webbanhang/Product'); // Chuyển hướng về trang sản phẩm
            exit();
        }
    }

    // Hiển thị danh sách danh mục - CHỈ ADMIN
    public function index()
    {
        $this->checkAdminPermission(); // Thêm dòng này
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    // Hiển thị chi tiết danh mục - CHỈ ADMIN
    public function show($id)
    {
        $this->checkAdminPermission(); // Thêm dòng này
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/show.php';
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy danh mục';
            header('Location: /webbanhang/Category');
        }
    }

    // Hiển thị sản phẩm theo danh mục - CHỈ ADMIN
    public function productsByCategory($id)
    {
        $this->checkAdminPermission(); // Thêm dòng này
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            $products = $this->productModel->getProductsByCategory($id);
            include 'app/views/category/listbyproduct.php';
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy danh mục';
            header('Location: /webbanhang/Category');
        }
    }

    // Form thêm mới danh mục - CHỈ ADMIN
    public function add()
    {
        $this->checkAdminPermission();
        include 'app/views/category/add.php';
    }

    // Lưu danh mục mới - CHỈ ADMIN
    public function save()
    {
        $this->checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';

            $result = $this->categoryModel->addCategory($name);

            if (is_array($result)) {
                $errors = $result;
                include 'app/views/category/add.php';
            } else {
                $_SESSION['success_message'] = 'Thêm danh mục thành công';
                header('Location: /webbanhang/Category');
                exit();
            }
        }
    }

    // Form chỉnh sửa danh mục - CHỈ ADMIN
    public function edit($id)
    {
        $this->checkAdminPermission();
        
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy danh mục';
            header('Location: /webbanhang/Category');
        }
    }

    // Cập nhật danh mục - CHỈ ADMIN
    public function update()
    {
        $this->checkAdminPermission();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $name = $_POST['name'] ?? '';

            $edit = $this->categoryModel->updateCategory($id, $name);

            if ($edit) {
                $_SESSION['success_message'] = 'Cập nhật danh mục thành công';
                header('Location: /webbanhang/Category');
                exit();
            } else {
                $_SESSION['error_message'] = 'Lỗi khi cập nhật danh mục';
                header("Location: /webbanhang/Category/edit/$id");
                exit();
            }
        }
    }

    // Xoá danh mục - CHỈ ADMIN
    public function delete($id)
    {
        $this->checkAdminPermission();
        
        // Kiểm tra xem danh mục có sản phẩm không
        $products = $this->productModel->getProductsByCategory($id);
        if (count($products) > 0) {
            $_SESSION['error_message'] = 'Không thể xóa danh mục vì có sản phẩm đang sử dụng';
            header('Location: /webbanhang/Category');
            exit();
        }

        if ($this->categoryModel->deleteCategory($id)) {
            $_SESSION['success_message'] = 'Xóa danh mục thành công';
        } else {
            $_SESSION['error_message'] = 'Lỗi khi xóa danh mục';
        }
        header('Location: /webbanhang/Category');
        exit();
    }
}