<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food = $_POST['food'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    if (empty($food) || empty($quantity)) {
        http_response_code(400); // Bad Request
        echo "اطلاعات وارد شده ناقص است.";
        exit;
    }

    $orderData = [
        'food' => $food,
        'quantity' => (int)$quantity,
        'price' => (float)$price,
    ];

    $filePath = 'orders.json';

    // بررسی وجود فایل و خواندن داده‌ها
    if (file_exists($filePath)) {
        $existingOrders = json_decode(file_get_contents($filePath), true);
    } else {
        $existingOrders = [];
    }

    // افزودن سفارش جدید
    $existingOrders[] = $orderData;

    // ذخیره سفارش‌ها در فایل
    if (file_put_contents($filePath, json_encode($existingOrders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        http_response_code(200); // OK
        echo "سفارش با موفقیت ثبت شد.";
    } else {
        http_response_code(500); // Internal Server Error
        echo "ذخیره سفارش با خطا مواجه شد.";
    }
}
?>
