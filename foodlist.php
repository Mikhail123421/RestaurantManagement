<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
    </style>
</head>

<body>
    <!-- Logout Button -->
<a href="logout.php" id="logoutBtn">
    <button type="button">Logout</button>
</a>


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
            <!-- Food items will be loaded here -->
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
        // Load the menu initially
        loadMenu();

        function loadMenu() {
            $.ajax({
                url: 'foodList.json', // Path to the JSON file
                method: 'GET',
                dataType: 'json', // Expect JSON response
                success: function(menuItems) {
                    const tableBody = $('#menuTable tbody'); // Target table body
                    tableBody.empty(); // Clear existing rows

                    // Loop through the menu items and append rows
                    menuItems.forEach(function(item) {
                        tableBody.append(`
                    <tr>
                        <td id="food-id-${item.id}">${item.id}</td> <!-- Add ID to the first column -->
                        <td id="food-name-${item.id}" class="food-name">${item.name}</td> <!-- Add name to the second column -->
                        <td id="food-price-${item.id}" class="food-price">${item.price}</td> <!-- Add price to the third column -->
                        <td>
                            <button onclick="deleteFood(${item.id})">حذف</button>
                            <button onclick="editFood(${item.id})">ویرایش</button>
                        </td>
                    </tr>
                `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error loading menu:', error); // Log error
                    alert('Failed to load menu. Please try again.');
                }
            });
        }


        function deleteFood(id) {
            if (confirm("آیا غذا حذف شود ?")) {
                $.ajax({
                    url: 'foodHandler.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        action: 'delete',
                        id: id
                    },
                    success: function(response) {
                        console.log('Delete successful:', response); // Debug log
                        alert(response.message);
                        loadMenu(); // Reload the menu
                    },
                    error: function(xhr) {
                        console.error('Delete failed:', xhr.responseJSON?.message || xhr.statusText);
                        alert(xhr.responseJSON?.message || 'Failed to delete food.');
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
                }, // Include action parameter
                success: function(response) {
                    alert(response.message); // Show success message
                    loadMenu(); // Reload the menu
                    $('#foodForm')[0].reset(); // Reset the form
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'Failed to add food.');
                }
            });
        }

    function editFood(id) {
    const name = $(`#food-name-${id}`).text();  // Get the food name using the id
    const price = $(`#food-price-${id}`).text(); // Get the food price using the id

    $('#editName').val(name);
    $('#editPrice').val(price);
    $('#editId').val(id); // Set the hidden input with the food id

    $('#editFoodForm').show(); // Make the edit form visible

    $('#foodForm').hide();
}



        function hideEditForm() {
            $('#editFoodForm').hide(); // Hide the form
            $('#editFoodForm')[0].reset(); // Reset the form fields
        }




        $('#foodForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            const name = $('#name').val();
            const price = $('#price').val();
            addFood(name, price); // Call the `addFood` function
        });

        $('#editFoodForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission

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
                    alert(response.message); // Show success message
                    loadMenu(); // Reload the menu
                    hideEditForm(); // Hide the edit form
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message || 'Failed to update food.');
                }
            });
        });
    </script>
</body>

</html>