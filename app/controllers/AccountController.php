<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
class AccountController
{
    private $accountModel;
    private $db;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    function register()
    {
        include_once 'app/views/account/register.php';
    }
    
    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $email = $_POST['email'] ?? '';
            $phoneNumber = $_POST['phoneNumber'] ?? '';
            $avatar = null;

            $errors = [];
            if (empty($username)) {
                $errors['username'] = "Vui lòng nhập userName!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui lòng nhập fullName!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui lòng nhập password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp";
            }
            
            // Kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);

            if ($account) {
                $errors['account'] = "Tài khoản này đã có người đăng ký!";
            }

            // Validate email
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email không hợp lệ!";
            }

            // Validate phone number
            if (empty($phoneNumber) || !preg_match("/^[0-9]{10}$/", $phoneNumber)) {
                $errors['phoneNumber'] = "Số điện thoại không hợp lệ!";
            }

            // Handle avatar upload
            $avatar = null;
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'public/uploads/avatars/';
                
                // Create directory if it doesn't exist
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $fileExtension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                
                if (!in_array($fileExtension, $allowedTypes)) {
                    $errors['avatar'] = "Chỉ chấp nhận file ảnh dạng JPG, JPEG, PNG & GIF.";
                } elseif ($_FILES['avatar']['size'] > 5000000) { // 5MB limit
                    $errors['avatar'] = "File ảnh không được vượt quá 5MB.";
                } else {
                    $newFileName = uniqid() . '.' . $fileExtension;
                    $uploadPath = $uploadDir . $newFileName;
                    
                    if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                        $avatar = $uploadPath; // Store the path relative to the project root
                    } else {
                        $errors['avatar'] = "Không thể tải file lên. Vui lòng thử lại.";
                    }
                }
            }

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                try {
                    $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                    $result = $this->accountModel->save($username, $fullName, $password, 'user', $email, $phoneNumber, $avatar);
                    
                    if ($result) {
                        header('Location: /webbanhang/account/login');
                        exit();
                    }
                } catch (PDOException $e) {
                    if (strpos($e->getMessage(), 'Email đã được sử dụng') !== false) {
                        $errors['email'] = "Email này đã được sử dụng";
                    } else {
                        $errors['general'] = "Có lỗi xảy ra, vui lòng thử lại sau";
                    }
                    include_once 'app/views/account/register.php';
                }
            }
        }
    }

    private function uploadAvatar($file)
    {
        $target_dir = "uploads/avatars/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is actual image
        if (!getimagesize($file["tmp_name"])) {
            return "Error: File không phải là hình ảnh.";
        }

        // Check file size (5MB max)
        if ($file["size"] > 5000000) {
            return "Error: File quá lớn.";
        }

        // Allow certain file formats
        if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
            return "Error: Chỉ chấp nhận file JPG, JPEG, PNG & GIF.";
        }

        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file;
        }

        return "Error: Có lỗi khi tải file lên.";
    }
    
    function logout()
    {
        // Hủy toàn bộ session
        session_unset();
        session_destroy();
        
        header('Location: /webbanhang/product');
        exit;
    }
    
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $account = $this->accountModel->getAccountByUserName($username);
            
            if ($account) {
                $pwd_hashed = $account->password;
                
                if (password_verify($password, $pwd_hashed)) {
                    // Bắt đầu session nếu chưa
                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    // Lưu thông tin user vào session
                    $_SESSION['user'] = [
                        'id' => $account->id,
                        'username' => $account->username,
                        'fullname' => $account->fullname,
                        'role' => $account->role // Lưu role vào session
                    ];

                    header('Location: /webbanhang/product');
                    exit;
                } else {
                    // Xử lý khi mật khẩu sai
                    $_SESSION['login_error'] = "Mật khẩu không chính xác";
                    header('Location: /webbanhang/account/login');
                    exit;
                }
            } else {
                // Xử lý khi không tìm thấy tài khoản
                $_SESSION['login_error'] = "Tài khoản không tồn tại";
                header('Location: /webbanhang/account/login');
                exit;
            }
        }
    }
    
    public function list() {
        // Check if user is admin
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /webbanhang/Product');
            exit();
        }

        $users = $this->accountModel->getAllUsers();
        include 'app/views/account/list.php';
    }

    public function editUser($id) 
    {
        // Check admin permission
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /webbanhang/Product');
            exit();
        }

        $user = $this->accountModel->getUserById($id);
        if ($user) {
            include 'app/views/account/editUser.php';
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng';
            header('Location: /webbanhang/account/list');
            exit();
        }
    }

    public function updateUser() 
    {
        // Check admin permission
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: /webbanhang/Product');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $username = $_POST['username'];
            $fullname = $_POST['fullname'];
            $role = $_POST['role'];

            if ($this->accountModel->updateUser($id, $username, $fullname, $role)) {
                $_SESSION['success_message'] = 'Cập nhật người dùng thành công';
            } else {
                $_SESSION['error_message'] = 'Cập nhật người dùng thất bại';
            }
            header('Location: /webbanhang/account/list');
            exit();
        }
    }

    public function view($id = null) 
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error_message'] = 'Vui lòng đăng nhập để xem thông tin';
            header('Location: /webbanhang/account/login');
            exit();
        }

        // If no ID provided, show current user's profile
        if ($id === null) {
            $id = $_SESSION['user']['id'];
        }

        // Only allow users to view their own profile unless they're admin
        if ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['id'] != $id) {
            $_SESSION['error_message'] = 'Bạn không có quyền xem thông tin này';
            header('Location: /webbanhang/Product');
            exit();
        }

        $user = $this->accountModel->getUserById($id);
        if ($user) {
            include 'app/views/account/detail.php';
        } else {
            $_SESSION['error_message'] = 'Không tìm thấy thông tin người dùng';
            header('Location: /webbanhang/Product');
            exit();
        }
    }

    public function delete($id) {
        // Check admin permission
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện thao tác này';
            header('Location: /webbanhang/Product');
            exit();
        }

        // Get user info before delete
        $user = $this->accountModel->getUserById($id);
        if (!$user) {
            $_SESSION['error_message'] = 'Không tìm thấy người dùng';
            header('Location: /webbanhang/account/list');
            exit();
        }

        // Prevent deleting admin account
        if ($user->role === 'admin') {
            $_SESSION['error_message'] = 'Không thể xóa tài khoản admin';
            header('Location: /webbanhang/account/list');
            exit();
        }

        try {
            if ($this->accountModel->deleteUser($id)) {
                $_SESSION['success_message'] = 'Xóa người dùng thành công';
            } else {
                $_SESSION['error_message'] = 'Xóa người dùng thất bại';
            }
        } catch (PDOException $e) {
            $_SESSION['error_message'] = 'Không thể xóa người dùng này. Vui lòng thử lại sau.';
        }
        
        header('Location: /webbanhang/account/list');
        exit();
    }
}