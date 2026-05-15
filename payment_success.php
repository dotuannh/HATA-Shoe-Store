<?php
require_once 'db.php';
check_login();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Success - HATA Shoe Store</title>
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
    <style>
        .success-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 20px;
        }
        
        .success-card {
            background: white;
            border-radius: 12px;
            padding: 50px 40px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
        }
        
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
        
        .success-title {
            font-size: 2rem;
            color: #28a745;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .success-message {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }
        
        .success-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .detail-row:last-child {
            border-bottom: none;
        }
        
        .detail-label {
            font-weight: bold;
            color: #333;
        }
        
        .detail-value {
            color: #666;
        }
        
        .total-amount {
            font-size: 1.5rem;
            color: var(--primary-color);
            font-weight: bold;
            margin: 20px 0;
        }
        
        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-continue {
            flex: 1;
            padding: 15px 30px;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-continue:hover {
            background: var(--primary-dark);
        }
        
        .btn-print {
            flex: 1;
            padding: 15px 30px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        
        .btn-print:hover {
            background: #5a6268;
        }
        
        @media print {
            .btn-group { display: none; }
            nav.navbar { display: none; }
        }
    </style>
</head>
<body>
    <?php require_once 'navigation.php'; ?>

    <div class="success-container">
        <div class="success-card">
            <div class="success-icon">✓</div>
            <div class="success-title">Thanh Toán Thành Công!</div>
            <div class="success-message">
                Đơn hàng của bạn đã được xử lý thành công.<br>
                Cảm ơn bạn đã mua sắm tại HATA Shoe Store!
            </div>
            
            <?php if (isset($_SESSION['last_order_info'])): ?>
                <div class="success-details">
                    <div class="detail-row">
                        <span class="detail-label">Khách Hàng:</span>
                        <span class="detail-value"><?php echo html_safe($_SESSION['last_order_info']['customer_name']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Số Điện Thoại:</span>
                        <span class="detail-value"><?php echo html_safe($_SESSION['last_order_info']['customer_phone']); ?></span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Số Mặt Hàng:</span>
                        <span class="detail-value"><?php echo $_SESSION['last_order_info']['items_count']; ?> sản phẩm</span>
                    </div>
                </div>
                
                <div class="total-amount">
                    Tổng Tiền: <?php echo number_format($_SESSION['last_order_info']['total_amount']); ?> VND
                </div>
                
                <?php unset($_SESSION['last_order_info']); ?>
            <?php endif; ?>
            
            <div class="btn-group">
                <button class="btn-print" onclick="window.print();">🖨 In Hóa Đơn</button>
                <a href="pos.php" class="btn-continue">Tiếp Tục Bán Hàng</a>
            </div>
        </div>
    </div>
</body>
</html>
