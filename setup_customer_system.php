<?php
require_once 'db.php';

echo "<h2>🔧 Thiết Lập Hệ Thống Danh Sách Khách Hàng</h2>";
echo "<hr>";

try {
    // 1. Thêm cột product_id nếu chưa có
    echo "<h3>Bước 1: Kiểm tra & Thêm cột product_id</h3>";
    $result = $conn->query("SHOW COLUMNS FROM sales LIKE 'product_id'");
    
    if ($result->num_rows == 0) {
        $conn->query("ALTER TABLE sales ADD COLUMN product_id INT NULL AFTER user_id");
        echo "✓ Đã thêm cột product_id<br>";
    } else {
        echo "✓ Cột product_id đã tồn tại<br>";
    }
    
    // 2. Xóa dữ liệu cũ (bán lẻ cũ không có product_id)
    echo "<h3>Bước 2: Cập Nhật Dữ Liệu</h3>";
    $old_count = $conn->query("SELECT COUNT(*) as cnt FROM sales WHERE product_id IS NULL")->fetch_assoc()['cnt'];
    
    if ($old_count > 0) {
        $conn->query("DELETE FROM sales WHERE product_id IS NULL");
        echo "✓ Xóa " . $old_count . " bản ghi cũ (không có product_id)<br>";
        echo "✓ Dữ liệu cũ đã được xóa để chuẩn bị cho dữ liệu mới<br>";
    } else {
        echo "✓ Dữ liệu đã sạch, sẵn sàng bán hàng mới<br>";
    }
    
    // 3. Kiểm tra kết cấu bảng
    echo "<h3>Bước 3: Kiểm Tra Cấu Trúc Bảng</h3>";
    $result = $conn->query("DESCRIBE sales");
    $columns = [];
    while($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    $required = ['customer_name', 'customer_phone', 'product_id'];
    $missing = [];
    foreach ($required as $col) {
        if (in_array($col, $columns)) {
            echo "✓ Cột '$col' tồn tại<br>";
        } else {
            echo "❌ Cột '$col' KHÔNG tồn tại<br>";
            $missing[] = $col;
        }
    }
    
    if (empty($missing)) {
        echo "<br><div style='background: #d4edda; padding: 15px; border-radius: 5px; border: 1px solid #c3e6cb;'>";
        echo "<h3 style='color: #155724; margin-top: 0;'>✓ SETUP HOÀN TẤT!</h3>";
        echo "<p style='color: #155724; margin: 10px 0;'>Hệ thống đã sẵn sàng. Bây giờ bạn có thể:</p>";
        echo "<ul style='color: #155724;'>";
        echo "<li>Bán hàng qua POS (<a href='pos.php'>pos.php</a>)</li>";
        echo "<li>Xem danh sách khách hàng (<a href='customer_list.php'>customer_list.php</a>)</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<br><div style='background: #f8d7da; padding: 15px; border-radius: 5px; border: 1px solid #f5c6cb;'>";
        echo "<p style='color: #721c24;'>⚠️ Thiếu cột: " . implode(", ", $missing) . "</p>";
        echo "</div>";
    }
    
    echo "<hr>";
    echo "<p style='margin-top: 30px;'>";
    echo "<a href='pos.php' style='margin-right: 10px; padding: 10px 20px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>→ Đến POS</a>";
    echo "<a href='customer_list.php' style='padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; font-weight: bold;'>→ Xem Khách Hàng</a>";
    echo "</p>";
    
    $conn->close();
} catch (Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; color: #721c24;'>";
    echo "❌ <strong>Lỗi:</strong> " . $e->getMessage();
    echo "</div>";
}
?>
