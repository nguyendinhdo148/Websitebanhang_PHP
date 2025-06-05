<?php
class CartModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getOrCreateCart($userId) {
        $stmt = $this->db->prepare("SELECT * FROM cart WHERE user_id = ?");
        $stmt->execute([$userId]);
        $cart = $stmt->fetch();

        if (!$cart) {
            $stmt = $this->db->prepare("INSERT INTO cart (user_id) VALUES (?)");
            $stmt->execute([$userId]);
            $cartId = $this->db->lastInsertId();
            return ['id' => $cartId, 'user_id' => $userId];
        }

        return $cart;
    }

    public function getCartItems($cartId) {
        $stmt = $this->db->prepare("
            SELECT ci.*, p.name, p.price, p.image 
            FROM cart_item ci
            JOIN product p ON ci.product_id = p.id
            WHERE ci.cart_id = ?
        ");
        $stmt->execute([$cartId]);
        return $stmt->fetchAll();
    }

    public function getCartItem($cartId, $productId) {
        $stmt = $this->db->prepare("
            SELECT * FROM cart_item 
            WHERE cart_id = ? AND product_id = ?
        ");
        $stmt->execute([$cartId, $productId]);
        return $stmt->fetch();
    }

    public function addToCart($cartId, $productId, $quantity = 1) {
        $existingItem = $this->getCartItem($cartId, $productId);

        if ($existingItem) {
            $newQuantity = $existingItem['quantity'] + $quantity;
            return $this->updateCartItem($cartId, $productId, $newQuantity);
        } else {
            $stmt = $this->db->prepare("INSERT INTO cart_item (cart_id, product_id, quantity) VALUES (?, ?, ?)");
            return $stmt->execute([$cartId, $productId, $quantity]);
        }
    }

    public function updateCartItem($cartId, $productId, $quantity) {
        $stmt = $this->db->prepare("UPDATE cart_item SET quantity = ? WHERE cart_id = ? AND product_id = ?");
        return $stmt->execute([$quantity, $cartId, $productId]);
    }

    public function removeFromCart($cartId, $productId) {
        $stmt = $this->db->prepare("DELETE FROM cart_item WHERE cart_id = ? AND product_id = ?");
        return $stmt->execute([$cartId, $productId]);
    }

    public function clearCart($cartId) {
        $stmt = $this->db->prepare("DELETE FROM cart_item WHERE cart_id = ?");
        return $stmt->execute([$cartId]);
    }
}