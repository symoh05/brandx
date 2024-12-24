<?php
include('db.php');
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header('Location: admin.php');
    exit();
}

// Handle form submission and file upload
if (isset($_POST['submit'])) {
    $target_dir = "uploads/";  // Directory for storing uploaded images
    $upload_ok = 1; // Flag to check if upload is successful

    // Process each uploaded image
    foreach ($_FILES["image"]["name"] as $key => $image_name) {
        $image_extension = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        // Generate a unique filename to avoid conflicts
        $unique_image_name = uniqid() . "." . $image_extension;
        $target_file = $target_dir . $unique_image_name;

        // Check if the file is an image
        $image_check = getimagesize($_FILES["image"]["tmp_name"][$key]);
        if ($image_check !== false) {
            $upload_ok = 1;
        } else {
            echo "File " . $image_name . " is not an image.<br>";
            $upload_ok = 0;
        }

        // Check if file size is less than 5MB
        if ($_FILES["image"]["size"][$key] > 5000000) {
            echo "Sorry, your file " . $image_name . " is too large.<br>";
            $upload_ok = 0;
        }

        // Check file extension (allow only certain types)
        if (!in_array($image_extension, ["jpg", "jpeg", "jfif", "png", "gif"])) {
            echo "Sorry, only JPG, JPEG, JFIF, PNG & GIF files are allowed.<br>";
            $upload_ok = 0;
        }

        // If everything is ok, try to upload the file
        if ($upload_ok == 1) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"][$key], $target_file)) {
                // Successfully uploaded image

                // Get form data
                $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
                $product_description = mysqli_real_escape_string($connection, $_POST['description']);
                $price_ksh = mysqli_real_escape_string($connection, $_POST['price_ksh']);
                $available_sizes = mysqli_real_escape_string($connection, $_POST['available_sizes']);
                $available_colors = mysqli_real_escape_string($connection, $_POST['available_colors']);
                $units_available = mysqli_real_escape_string($connection, $_POST['units_available']);
                $category = mysqli_real_escape_string($connection, $_POST['category']); // Get category from form

                // Insert product data into the database
                $sql = "INSERT INTO products (name, description, image, price_ksh, available_sizes, available_colors, units_available, category) 
                        VALUES ('$product_name', '$product_description', '$unique_image_name', '$price_ksh', '$available_sizes', '$available_colors', '$units_available', '$category')";

                if ($connection->query($sql) === TRUE) {
                    echo "New product added successfully!<br>";
                } else {
                    echo "Error: " . $sql . "<br>" . $connection->error . "<br>";
                }
            } else {
                echo "Sorry, there was an error uploading your file " . $image_name . ".<br>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="sty.css">  <!-- Link to your CSS file -->
    <script src="script.js"></script>
    <style>
        /* Basic form styling */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: Arial, sans-serif;
        }

        header {
            position: relative;
            padding: 1rem;
            background-color: #161b22;
    
        }
        form {
            max-width: 600px;
            margin: 0 auto;
           
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #238636;
            border-radius: 6px;
            background-color: #0d1117;
            color: #c9d1d9;
        }

        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        form input, form textarea, form select {
            padding: 10px;
            border-radius: 6px;
            background-color: #0d1117;
            border: 1px solid #238636;
            color: #c9d1d9;
            width: 95%;
        }

        form button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 95%;
        }

        form button:hover {
            background:none;
            border: 1px solid rgb(3, 252, 77);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 15px;
        }
    </style>
</head>

<div class="ham-menu" onclick="toggleMenu()">
            <span></span><span></span><span></span>
        </div>
        <h1>Sneaker Haven</h1>
        <div class="off-screen-menu" id="menu">
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="orders_made.php">Order Updates</a></li>
            </ul>
        </div>
<body>
    <h1>Add New Product</h1>

    <form action="add_product.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="product_name">Product Name:</label>
            <input type="text" name="product_name" id="product_name" required>
        </div>

        <div class="form-group">
            <label for="description">Description:</label>
            <textarea name="description" id="description" required></textarea>
        </div>

        <div class="form-group">
            <label for="image">Select image(s) to upload:</label>
            <!-- Allow multiple files to be selected -->
            <input type="file" name="image[]" id="image" required multiple>
        </div>

        <div class="form-group">
            <label for="price_ksh">Price (Ksh):</label>
            <input type="number" step="0.01" name="price_ksh" id="price_ksh" required>
        </div>

        <div class="form-group">
            <label for="available_sizes">Available Sizes:</label>
            <input type="text" name="available_sizes" id="available_sizes" required>
        </div>

        <div class="form-group">
            <label for="available_colors">Available Colors:</label>
            <input type="text" name="available_colors" id="available_colors" required>
        </div>

        <div class="form-group">
            <label for="units_available">Units Available:</label>
            <input type="number" name="units_available" id="units_available" required>
        </div>

        <!-- Category Selector -->
        <div class="form-group">
            <label for="category">Select Category:</label>
            <select name="category" id="category" required>
                <option value="menwear">Men's Wear</option>
                <option value="womenwear">Women's Wear</option>
                <option value="shoes">Shoes</option>
                <option value="watches">Watches</option>
                <option value="accessories">Accessories</option>
            </select>
        </div>

        <button type="submit" name="submit">Add Product</button>
    </form>

</body>
</html>

<?php
// Close DB connection
$connection->close();
?>
