<?php
require_once 'db.php'
check_login();

// Lấy danh sách khách hàng đã mua
$sql = "SELECT 
    s.customer_name, 
    s.customer_phone, 
    COUNT(*) as total_orders,
    SUM(s.quantity) as total_items,
    SUM(s.total_price) as total_spent,
    MAX(s.sale_date) as last_purchase,
    GROUP_CONCAT(DISTINCT sh.sku SEPARATOR ', ') as product_skus
FROM sales s
LEFT JOIN shoes sh ON s.product_id = sh.id
WHERE s.customer_name IS NOT NULL AND s.customer_phone IS NOT NULL
GROUP BY s.customer_phone
ORDER BY last_purchase DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Customer List - HATA Shoe Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .customer-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .search-box {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .search-box input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        
        .search-box button {
            padding: 10px 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .search-box button:hover {
            background: var(--primary-dark);
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .customer-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .customer-table thead {
            background: #f8f9fa;
            font-weight: bold;
        }
        
        .customer-table th {
            padding: 15px;
            text-align: left;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .customer-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .customer-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 10px;
            background: #e3f2fd;
            color: #1976d2;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        
        .amount {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-vip {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-regular {
            background: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <?php require_once 'navigation.php'; ?>

    <div class="container">
        <div class="customer-header">
            <h1>Danh Sách Khách Hàng</h1>
        </div>
        
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Tìm kiếm theo tên hoặc số điện thoại..." onkeyup="searchCustomer()">
            <button onclick="location.reload();">Làm mới</button>
        </div>
        
        <?php 
        // Thống kê
        $total_customers = 0;
        $total_revenue = 0;
        $total_items = 0;
        
        if ($result->num_rows > 0) {
            $result->data_seek(0);
            while($row = $result->fetch_assoc()) {
                $total_customers++;
                $total_revenue += $row['total_spent'];
                $total_items += $row['total_items'];
            }
        }
        ?>
        
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_customers; ?></div>
                <div class="stat-label">Tổng Khách Hàng</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_items; ?></div>
                <div class="stat-label">Tổng Sản Phẩm Bán</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo number_format($total_revenue); ?></div>
                <div class="stat-label">Tổng Doanh Thu (VND)</div>
            </div>
        </div>
        
        <table class="customer-table" id="customerTable">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên Khách Hàng</th>
                    <th>Số Điện Thoại</th>
                    <th>Mã Giày Mua</th>
                    <th>Số Sản Phẩm</th>
                    <th>Tổng Chi Tiêu</th>
                    <th>Mua Lần Cuối</th>
                    <th>Loại Khách</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if ($result->num_rows > 0):
                    $result->data_seek(0);
                    $stt = 1;
                    while($row = $result->fetch_assoc()): 
                        $is_vip = $row['total_spent'] > 5000000; // VIP nếu chi tiêu > 5 triệu
                ?>
                    <tr>
                        <td><?php echo $stt++; ?></td>
                        <td><strong><?php echo html_safe($row['customer_name']); ?></strong></td>
                        <td><?php echo html_safe($row['customer_phone']); ?></td>
                        <td><?php echo html_safe($row['product_skus'] ?? 'N/A'); ?></td>
                        <td><?php echo $row['total_items']; ?> đôi</td>
                        <td class="amount"><?php echo number_format($row['total_spent']); ?> VND</td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['last_purchase'])); ?></td>
                        <td>
                            <?php if ($is_vip): ?>
                                <span class="badge status-vip">VIP</span>
                            <?php else: ?>
                                <span class="badge status-regular">Regular</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php 
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="8" style="text-align: center; color: #999;">Chưa có dữ liệu khách hàng</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <script>
    function searchCustomer() {
        let input = document.getElementById('searchInput').value.toLowerCase();
        let table = document.getElementById('customerTable');
        let rows = table.getElementsByTagName('tr');
        
        for (let i = 1; i < rows.length; i++) {
            let row = rows[i];
            let name = row.getElementsByTagName('td')[1].textContent.toLowerCase();
            let phone = row.getElementsByTagName('td')[2].textContent.toLowerCase();
            
            if (name.includes(input) || phone.includes(input)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    }
    </script>
</body>
</html>
