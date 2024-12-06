<?php
session_start(); 



if (isset($_SESSION['user'])) {
    echo " <a href='logout.php' class='btn btn-logout'>
            خروج
        </a>";
    $userId = $_SESSION['user']['user_id'];
} 



else {
    $userId = null; 
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سفارش غذا</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body
        {
            text-align: center;
        }
        #orderTable {
            width: 100%;
            border-collapse: collapse;
        }

        #orderTable th,
        #orderTable td {
            padding: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <h1 style="text-align: center;">سفارش غذا</h1>

    <!-- Add the USER_ID to a hidden input field to use on the client side -->
    <input type="hidden" id="userId" value="<?php echo $userId; ?>">

    <form id="orderForm">
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
                <th>عملیات</th>
            </tr>
        </thead>
        <tbody>
            <!-- سفارش‌ها به صورت داینامیک بارگذاری می‌شود -->
        </tbody>
    </table>

    <button id="finalizeOrderBtn">ثبت نهایی سفارش</button>

    <script>
        let orders = []; 
        const userId = $('#userId').val();

        //1 Load food menu from the server
        function loadMenu() {
            $.ajax({
                url: 'foodHandler.php',
                method: 'POST',
                data: {
                    action: 'loadFood'
                },
                dataType: 'json',
                success: function(menuItems) {
                    const foodDropdown = $('#food');
                    foodDropdown.empty(); // Clear existing options
                    menuItems.forEach(function(item) {
                        foodDropdown.append(`<option value="${item.NAME}" data-price="${item.PRICE}">${item.NAME} - ${item.PRICE} تومان</option>`);
                    });
                },
                error: function() {
                    alert('بارگذاری منو با خطا مواجه شد.');
                }
            });
        }

        // Load orders into the table
        function loadOrders() {
            const orderTableBody = $('#orderTable tbody');
            orderTableBody.empty(); // Clear existing rows

            orders.forEach(function(order, index) {
                orderTableBody.append(`
                    <tr>
                        <td>${index + 1}</td>
                        <td>${order.food}</td>
                        <td>${order.quantity}</td>
                        <td>${order.price}</td>
                        <td><button class="removeOrderBtn" data-index="${index}">حذف</button></td>
                    </tr>
                `);
            });
        }

  

        $('#orderForm').on('submit', function(event) {
            event.preventDefault();
            const food = $('#food').val();
            const foodName = $('#food option:selected').text();
            const quantity = $('#quantity').val();
            const price = $('#price').val();
            const userId = $('#userId').val(); // Ensure userId is retrieved properly

            // Validate fields before adding
            if (!food || quantity <= 0 || !price || !userId) {
                alert('لطفاً تمام فیلدها را پر کنید.');
                return;
            }

            // Create an order object to push to the orders array
            const order = {
                foodID: food, // Food ID
                foodName: foodName, // Food Name
                quantity: quantity, // Quantity
                price: price, // Price
                userId: userId // User ID
            };

            // Add the order to the orders array
            orders.push(order);
            loadOrders(); // Update the orders table view
        });



        // Finalize the order and send it to the server
        $('#finalizeOrderBtn').on('click', function() {
            if (orders.length === 0) {
                alert('هیچ سفارشی برای ارسال وجود ندارد.');
                return;
            }

            // Send orders to the server
            $.ajax({
                url: 'orderHandler.php',
                method: 'POST',
                data: {
                    action: 'addOrder',
                    orders: JSON.stringify(orders),
                    userId: userId // Send USER_ID to the server
                },
                success: function(response) {
                    alert('سفارش‌ها با موفقیت ثبت شد.');
                    orders = []; 
                    loadOrders(); 
                },
                error: function() {
                    alert('ثبت سفارش‌ها با خطا مواجه شد.');
                }
            });
        });




            // Calculate the total price based on the selected food and quantity
            function calculateTotal() {
            const selectedFood = $('#food').find(':selected');
            const pricePerUnit = selectedFood.data('price');
            const quantity = parseInt($('#quantity').val()) || 0;
            const totalPrice = pricePerUnit * quantity;
            $('#price').val(totalPrice || 0);
        }

      // Remove an order from the table
      $(document).on('click', '.removeOrderBtn', function() {
            const index = $(this).data('index');
            orders.splice(index, 1); // Remove from the orders array
            loadOrders(); // Update the table
        });

        // Initialize the page
        $(document).ready(function() {
            loadMenu(); // Load the food menu
            $('#food, #quantity').on('change input', calculateTotal); // Calculate total price
        });
    </script>
</body>

</html>