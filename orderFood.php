<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سفارش غذا</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        form {
            width: 300px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
        }

        input,
        button,
        select {
            margin: 10px 0;
            padding: 8px;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            direction: rtl;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: center;
        }

        .message {
            text-align: center;
            color: green;
        }
    </style>
</head>

<body>
        <!-- Logout Button -->
<a href="logout.php" id="logoutBtn">
    <button type="button">Logout</button>
</a>
    <h1 style="text-align: center;">سفارش غذا</h1>
    <form id="orderForm" action="orderHandler.php" method="POST">
        <label for="food">انتخاب غذا:</label>
        <select id="food" name="food" required>
            <!-- لیست غذاها به صورت داینامیک بارگذاری می‌شود -->
        </select>
        <input type="number" id="quantity" name="quantity" placeholder="تعداد" required min="1">
        <input type="number" id="price" name="price" placeholder="قیمت کل" readonly>
        <button type="submit">ثبت سفارش</button>
    </form>

    <h2 style="text-align: center;">لیست سفارش‌ها</h2>
    <table id="orderTable">
        <thead>
            <tr>
                <th>ردیف</th>
                <th>نام غذا</th>
                <th>تعداد</th>
                <th>قیمت</th>
            </tr>
        </thead>
        <tbody>
            <!-- سفارش‌ها به صورت داینامیک بارگذاری می‌شود -->
        </tbody>
    </table>

    <script>
        // بارگذاری منو به لیست انتخاب غذا
        function loadMenu() {
            $.ajax({
                url: 'foodList.json',
                method: 'GET',
                dataType: 'json',
                success: function(menuItems) {
                    const foodDropdown = $('#food');
                    foodDropdown.empty();
                    menuItems.forEach(function(item) {
                        foodDropdown.append(`<option value="${item.name}" data-price="${item.price}">${item.name} - ${item.price} تومان</option>`);
                    });
                },
                error: function() {
                    alert('بارگذاری منو با خطا مواجه شد.');
                }
            });
        }

        function calculateTotal() {
            const selectedFood = $('#food').find(':selected');
            const pricePerUnit = selectedFood.data('price');
            const quantity = parseInt($('#quantity').val()) || 0;
            const totalPrice = pricePerUnit * quantity;
            $('#price').val(totalPrice || 0);
        }


   
        // بارگذاری سفارش‌ها به جدول
        function loadOrders() {
            $.ajax({
                url: 'orders.json', // مسیر فایل سفارش‌ها
                method: 'GET',
                dataType: 'json',
                success: function(orders) {
                    const orderTableBody = $('#orderTable tbody');
                    orderTableBody.empty();
                    orders.forEach(function(order, index) {
                        orderTableBody.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${order.food}</td>
                                <td>${order.quantity}</td>
                                <td>${order.price}</td>
                            </tr>
                        `);
                    });
                },
                error: function() {
                    alert('بارگذاری سفارش‌ها با خطا مواجه شد.');
                }
            });
        }

        //جمع سفارش ها
        function sumOrderPrice() {

        }


        function calculateTotal() {
            const selectedFood = $('#food').find(':selected');
            const pricePerUnit = selectedFood.data('price'); 
            const quantity = parseInt($('#quantity').val()) || 0; 
            const totalPrice = pricePerUnit * quantity;
            $('#price').val(totalPrice || 0); 
        }

        // نمایش پیام بعد از ثبت موفق سفارش
        $('#orderForm').on('submit', function(event) {
            event.preventDefault(); // جلوگیری از ارسال فرم
            const formData = $(this).serialize();
            $.ajax({
                url: 'orderHandler.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('سفارش شما با موفقیت ثبت شد.');
                    loadOrders(); // به‌روزرسانی لیست سفارش‌ها
                },
                error: function() {
                    alert('ثبت سفارش با خطا مواجه شد.');
                }
            });
        });


        // مقداردهی اولیه
        $(document).ready(function() {
            loadMenu();
            loadOrders();
        });

        $('#food, #quantity').on('change input', calculateTotal);


        $('#orderForm').on('submit', function(event) {
            event.preventDefault();
            const formData = $(this).serialize();
            $.ajax({
                url: 'orderHandler.php',
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert('سفارش شما با موفقیت ثبت شد.');
                    loadOrders();
                },
                error: function() {
                    alert('ثبت سفارش با خطا مواجه شد.');
                }
            });
        });


        $(document).ready(function() {
            loadMenu();
            loadOrders();
        });
    </script>
</body>

</html>