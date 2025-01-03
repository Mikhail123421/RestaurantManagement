<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="./lib/jquery-3.6.0.min.js"></script>
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
                <th>نام</th>
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
        if (users && users.length > 0) {
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
        } else {
            alert("No users found.");
        }
    },
    error: function(xhr, status, error) {
        console.error("AJAX Error: ",  error);
        alert('خطا در بارگذاری کاربران');
    }
});

            }

            window.deleteUser = function (id) {
                if (confirm("آیا این کاربر حذف شود؟")) {
                    $.ajax({
                        url: 'userHandler.php',
                        method: 'POST',
                        dataType: 'json',
                        data: {
                            action: 'delete',
                            id: id
                        },
                        success: function (response) {
                            alert(response.message);
                            loadUsers();
                        },
                        error: function () {
                            alert('خطا در حذف کاربر');
                        }
                    });
                }
            };

            window.editUser = function (id) {
                const row = $(`#userTable tr`).filter(function () {
                    return $(this).find('td').first().text() == id;
                });

                const firstName = row.find('td:nth-child(2)').text();
                const lastName = row.find('td:nth-child(3)').text();
                const email = row.find('td:nth-child(4)').text();

                $('#editId').val(id);
                $('#editFirstName').val(firstName);
                $('#editLastName').val(lastName);
                $('#editEmail').val(email);

                $('#editUserForm').show();
            };

            $('#editUserForm').on('submit', function (e) {
                e.preventDefault();
                const id = $('#editId').val();
                const firstName = $('#editFirstName').val();
                const lastName = $('#editLastName').val();
                const email = $('#editEmail').val();

                $.ajax({
                    url: 'userHandler.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'edit',
                        id: id,
                        f_name: firstName,
                        l_name: lastName,
                        email: email
                    },
                    success: function (response) {
                        alert(response.message);
                        loadUsers();
                        $('#editUserForm').hide();
                    },
                    error: function () {
                        alert('خطا در ویرایش کاربر');
                    }
                });
            });
    
    </script>
</body>

</html>
