<?php
require_once 'db.php';

try {
    // Kiểm tra xem cột đã tồn tại chưa
    $result = $conn->query("SHOW COLUMNS FROM sales LIKE 'customer_name'");
    
    if ($result->num_rows == 0) {
        // Thêm cột customer_name
        $conn->query("ALTER TABLE sales ADD COLUMN customer_name VARCHAR(100) NULL");
        echo "✓ Thêm cột customer_name thành công<br>";
    } else {
        echo "• Cột customer_name đã tồn tại<br>";
    }
    
    $result = $conn->query("SHOW COLUMNS FROM sales LIKE 'customer_phone'");
    
    if ($result->num_rows == 0) {
        // Thêm cột customer_phone
        $conn->query("ALTER TABLE sales ADD COLUMN customer_phone VARCHAR(20) NULL");
        echo "✓ Thêm cột customer_phone thành công<br>";
    } else {
        echo "• Cột customer_phone đã tồn tại<br>";
    }
    
    echo "<br><strong>Migration hoàn tất!</strong><br>";
    echo "<a href='pos.php'>← Quay lại POS</a>";
    
    $conn->close();
} catch (Exception $e) {
    die("Lỗi: " . $e->getMessage());
}
?>
