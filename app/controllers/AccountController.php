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
            $role = 'user'; // Mặc định role là 'user' khi đăng ký

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

            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
                $result = $this->accountModel->save($username, $fullName, $password, $role);

                if ($result) {
                    header('Location: /webbanhang/account/login');
                }
            }
        }
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
}