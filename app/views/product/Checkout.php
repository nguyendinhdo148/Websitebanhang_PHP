<?php include 'app/views/shares/header.php'; ?>

<div class="container py-5">
    <h1 class="mb-4">Thanh toán</h1>
    
    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
    <?php endif; ?>

    <form method="POST" action="/webbanhang/Product/processCheckout">
        <div class="form-group">
            <label for="name">Họ tên:</label>
            <input type="text" id="name" name="name" class="form-control" required 
                   value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">
        </div>
        <div class="form-group">
            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone" class="form-control" required
                   value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>">
        </div>
        <div class="form-group"> 
            <label for="address">Địa chỉ:</label>
            <textarea id="address" name="address" class="form-control" required><?= 
                isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '' 
            ?></textarea>
        </div>
        <div class="form-group">
            <label for="payment_method">Phương thức thanh toán:</label>
            <select id="payment_method" name="payment_method" class="form-control" required>
                <option value="cash" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] == 'cash') ? 'selected' : '' ?>>
                    Thanh toán khi nhận hàng
                </option>
                <option value="online" <?= (isset($_POST['payment_method']) && $_POST['payment_method'] == 'online') ? 'selected' : '' ?>>
                    Thanh toán trực tuyến
                </option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Thanh toán</button>
        <a href="/webbanhang/Product/cart" class="btn btn-secondary">Quay lại giỏ hàng</a>
    </form>
</div>

<?php include 'app/views/shares/footer.php'; ?>