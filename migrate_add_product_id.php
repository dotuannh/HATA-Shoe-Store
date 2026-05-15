<?php
require_once 'db.php';

try {
    // Kiểm tra xem cột đã tồn tại chưa
    $result = $conn->query("SHOW COLUMNS FROM sales LIKE 'product_id'");
    
    if ($result->num_rows == 0) {
        // Thêm cột product_id
        $conn->query("ALTER TABLE sales ADD COLUMN product_id INT NULL AFTER user_id");
        echo "✓ Thêm cột product_id thành công<br>";
    } else {
        echo "• Cột product_id đã tồn tại<br>";
    }
    
    echo "<br><strong style='color: green;'>✓ Migration hoàn tất!</strong><br>";
    echo "<a href='pos.php' style='padding: 10px 20px; background: blue; color: white; text-decoration: none; border-radius: 5px;'>← Quay lại POS</a>";
    
    $conn->close();
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
}
?>
