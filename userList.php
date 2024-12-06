<?php
session_start(); // شروع سشن
?>

<?php if (isset($_SESSION['user'])): ?>
    <!-- نمایش دکمه خروج اگر کاربر وارد شده باشد -->
    <a href="logout.php">
        <button type="button">خروج</button>
    </a>
<?php endif; ?>




<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
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
    </style>
</head>

<body>
    <h2 style="text-align: center;">مدیریت کاربران</h2>
    <table id="userTable">
        <thead>
            <tr>
                <th>کد</th>
                <th>نام </th>
                <th>نام خانوادگی</th>
                <th>ایمیل</th>
                <th>حذف</th>
                <th>ویرایش</th>
            </tr>
        </thead>
        <tbody>
            <!-- User items will be loaded here -->
        </tbody>
    </table>

    <form id="editUserForm" style="display: none;">
        <h3>ویرایش کاربر</h3>
        <input type="hidden" id="editId" name="id">
        <input type="text" id="editFirstName" name="firstName" placeholder="نام" required>
        <input type="text" id="editLastName" name="lastName" placeholder="نام خانوادگی" required>
        <input type="email" id="editEmail" name="email" placeholder="ایمیل" required>
        <button type="submit">ویرایش</button>
    </form>


    <script>
        loadUsers();

        function loadUsers() {
            $.ajax({
                url: 'userHandler.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'loadUsers'
                },
                success: function(users) {
                    const tableBody = $('#userTable tbody');
                    tableBody.empty();
                    users.forEach(function(user) {
                        tableBody.append(`
                            <tr>
                                <td>${user.ID}</td>
                                <td>${user.F_NAME}</td>
                                <td>${user.L_NAME}</td>
                                <td>${user.EMAIL}</td>
                                <td><button onclick="deleteUser(${user.ID})">حذف</button></td>
                                <td><button onclick="editUser(${user.ID})">ویرایش</button></td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr) {
                    alert('Failed to load users.');
                }
            });
        }

        function deleteUser(id) {
            if (confirm("آیا این کاربر حذف شود?")) {
                $.ajax({
                    url: 'userHandler.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        alert(response.message);
                        loadUsers();
                    },
                    error: function(xhr) {
                        alert('Failed to delete user.');
                    }
                });
            }
        }

        function editUser(id) {
            // پیدا کردن ردیفی که دکمه ویرایش در آن کلیک شده است
            const row = $(`#userTable tr`).filter(function() {
                return $(this).find('td').first().text() == id; // تطبیق ID در ستون اول
            });

            // استخراج مقادیر نام، نام خانوادگی و ایمیل از ردیف کلیک شده
            const firstName = row.find('td:nth-child(2)').text(); // F_NAME
            const lastName = row.find('td:nth-child(3)').text(); // L_NAME
            const email = row.find('td:nth-child(4)').text(); // EMAIL

            // پر کردن فرم ویرایش با این مقادیر
            $('#editId').val(id);
            $('#editFirstName').val(firstName);
            $('#editLastName').val(lastName);
            $('#editEmail').val(email);

            // نمایش فرم ویرایش
            $('#editUserForm').show();
        }

        $('#editUserForm').on('submit', function(e) {
            e.preventDefault();
            const id = $('#editId').val();
            const firstName = $('#editFirstName').val(); // Changed to firstName
            const lastName = $('#editLastName').val(); // Changed to lastName
            const email = $('#editEmail').val();

            $.ajax({
                url: 'userHandler.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    action: 'edit',
                    id: id,
                    f_name: firstName, // Ensure this matches server-side parameter
                    l_name: lastName, // Ensure this matches server-side parameter
                    email: email
                },
                success: function(response) {
                    alert(response.message);
                    loadUsers(); // Refresh users list
                    $('#editUserForm').hide();
                },
                error: function(xhr) {
                    alert('Failed to edit user.');
                }
            });
        });
    </script>
</body>

</html>