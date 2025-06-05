<?php
class CategoryModel
{
    private $db;
    private $table = 'category';  // sửa lại tên bảng đúng với database

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getCategories()
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getCategoryById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function addCategory($name)
    {
        if (empty($name)) {
            return ['Tên danh mục không được để trống.'];
        }
        $stmt = $this->db->prepare("INSERT INTO {$this->table} (name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    public function updateCategory($id, $name)
    {
        if (empty($name)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE {$this->table} SET name = ? WHERE id = ?");
        return $stmt->execute([$name, $id]);
    }

    public function deleteCategory($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
