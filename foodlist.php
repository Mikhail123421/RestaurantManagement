<?php
session_start(); // شروع سشن
?>

<?php if (isset($_SESSION['user'])): ?>
    <!-- نمایش دکمه خروج اگر کاربر وارد شده باشد -->
    <a href="logout.php">
        <button type="button" class="btn btn-logout">خروج</button>
    </a>
<?php endif; ?>


<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./lib/jquery-3.6.0.min.js"></script>
    <style>
        form {
            width: 300px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
        }

        input,
        button {
            margin: 10px 0;
            padding: 8px;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
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

        .btn-logout {
            background-color: #f44336;
            color: white;
            display: flex;
          
        }

        a{
            text-decoration: none;
        }

        .btn-logout:hover {
            background-color: #e53935;
        }

        form
        {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        form input
        {
            width: 400px;
            height: 30px;
            border-radius: 8px;
            box-shadow: none;
        }

        form select
        {
            width: 400px;
            height: 30px;
            border-radius: 8px;
            box-shadow: none;
        }
    </style>
</head>

<body>

    <form id="foodForm">
        <input type="text" id="name" name="name" placeholder="نام" required>
        <input type="number" id="price" name="price" placeholder="قیمت" required>
        <button type="submit">افزودن</button>
    </form>
    <h2 style="text-align: center;">منو غذا</h2>
    <table id="menuTable">
        <thead>
            <tr>
                <th>کد</th>
                <th>نام</th>
                <th>قیمت</th>
                <th>حذف</th>
            </tr>
        </thead>
        <tbody>
            <!-- آیتم‌های غذا در اینجا بارگذاری خواهند شد -->
        </tbody>
    </table>

    <form id="editFoodForm" style="display: none;">
        <h3>ویرایش غذا</h3>
        <input type="hidden" id="editId" name="id">
        <input type="text" id="editName" name="name" placeholder="نام" required>
        <input type="number" id="editPrice" name="price" placeholder="قیمت" required>
        <button type="submit">ویرایش</button>
    </form>

    <script>
        // بارگذاری منو در ابتدا
        loadMenu();

        function loadMenu() {
            $.ajax({
                url: 'foodHandler.php',
                method: 'POST',
                dataType: 'json', 
                data: {
                    action: 'loadFood' 
                },
                success: function(menuItems) {
                    const tableBody = $('#menuTable tbody'); 
                    tableBody.empty(); 

                    // عبور از روی آیتم‌های منو و اضافه کردن سطرها
                    menuItems.forEach(function(item) {
                        tableBody.append(`
                            <tr>
                                <td id="food-id-${item.id}">${item.ID}</td> <!-- شناسه غذا -->
                                <td id="food-name-${item.id}" class="food-name">${item.NAME}</td> <!-- نام غذا -->
                                <td id="food-price-${item.id}" class="food-price">${item.PRICE}</td> <!-- قیمت غذا -->
                                <td>
                                    <button onclick="deleteFood(${item.ID})">حذف</button>
                                    <button onclick="editFood(${item.ID})">ویرایش</button>
                                </td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    console.error('خطا در بارگذاری منو:', xhr.responseJSON?.message || xhr.statusText);
                    alert(xhr.responseJSON?.message || 'خطا در بارگذاری منو. لطفا دوباره تلاش کنید.');
                }
            });
        }

        function deleteFood(id) {
            if (confirm("آیا از حذف این غذا اطمینان دارید؟")) {
                $.ajax({
                    url: 'foodHandler.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        console.log('حذف موفقیت‌آمیز:', response); // لاگ برای اشکال‌زدایی
                        alert(response.message); // پیام موفقیت
                        loadMenu(); // بارگذاری مجدد منو
                    },
                    error: function(xhr) {
                        console.error('حذف ناموفق:', xhr.responseJSON?.message || xhr.statusText);
                        alert(xhr.responseJSON?.message || 'خطا در حذف غذا.');
                    }
                });
            }
        }

        function addFood(name, price) {
            $.ajax({
                url: 'foodHandler.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'add',
                    name: name,
                    price: price
                },
                success: function(response) {
                    alert(response.message); // نمایش پیام موفقیت
                    loadMenu(); // بارگذاری مجدد منو
                    $('#foodForm')[0].reset(); // بازنشانی فرم
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'خطا در افزودن غذا.');
                }
            });
        }

        function editFood(id) {
            const name = $(`#food-name-${id}`).text();  // دریافت نام غذا با استفاده از شناسه
            const price = $(`#food-price-${id}`).text(); // دریافت قیمت غذا با استفاده از شناسه

            $('#editName').val(name);
            $('#editPrice').val(price);
            $('#editId').val(id); // تنظیم شناسه غذا در فیلد مخفی

            $('#editFoodForm').show(); // نمایش فرم ویرایش

            $('#foodForm').hide();
        }

        function hideEditForm() {
            $('#editFoodForm').hide(); // مخفی کردن فرم
            $('#editFoodForm')[0].reset(); // بازنشانی فیلدهای فرم
        }

        $('#foodForm').on('submit', function(e) {
            e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش‌فرض
            const name = $('#name').val();
            const price = $('#price').val();
            addFood(name, price); // فراخوانی تابع افزودن غذا
        });

        $('#editFoodForm').on('submit', function(e) {
            e.preventDefault(); // جلوگیری از ارسال فرم به صورت پیش‌فرض

            const id = $('#editId').val();
            const name = $('#editName').val();
            const price = $('#editPrice').val();

            $.ajax({
                url: 'foodHandler.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'edit',
                    id: id,
                    name: name,
                    price: price
                },
                success: function(response) {
                    alert(response.message); // نمایش پیام موفقیت
                    loadMenu(); // بارگذاری مجدد منو
                    hideEditForm(); // مخفی کردن فرم ویرایش
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'خطا در ویرایش غذا.');
                }
            });
        });
    </script>
</body>

</html>
