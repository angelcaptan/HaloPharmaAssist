<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connect to database class
require (__DIR__ . "/../settings/db_class.php");


class general_class extends db_connection
{

    
    public function registerUser($first_name, $last_name, $email, $phone, $role, $gender, $password, $confirm_password)
{
    // Validate inputs
    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($role) || empty($gender) || empty($password) || empty($confirm_password)) {
        return "Please fill in all fields.";
    }

    if ($password !== $confirm_password) {
        return "Passwords do not match.";
    }

    // Hash password
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    // Prepare statement
    $stmt = $this->db_conn()->prepare("INSERT INTO `Users` (`first_name`, `last_name`, `email`, `phone`, `role`, `gender`, `password`) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        return "Error preparing statement.";
    }
        $stmt->bind_param("sssssss", $first_name, $last_name, $email, $phone, $role, $gender, $password_hashed);
        $success = $stmt->execute();

        if ($success) {
            return "User registered successfully.";
        } else {
            return "An error occurred during registration.";
        }
    }

    // Bind parameters and execute

    public function addProduct($product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id)
    {
        $conn = $this->db_conn();

        $stmt = $conn->prepare("INSERT INTO `Products` (`product_name`, `category_id`, `price`, `quantity`, `expiry_date`, `total_amount`, `image`) VALUES (?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("sidisds", $product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            // Log the action
            $this->logAction($user_id, 'create', 'Products', $conn->insert_id);
        }

        $conn->close();

        return $success;
    }

    public function addCategory($category_name, $user_id)
    {
        $stmt = $this->db_conn()->prepare("INSERT INTO `Categories` (`category_name`) VALUES (?)");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("s", $category_name);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $category_id = $this->db_conn()->insert_id; // Get the last inserted category ID
            $this->logAction($user_id, 'create', 'Categories', $category_id); // Log the action
            return true;
        } else {
            return false;
        }
    }


    public function addSale($sale_date, $reference_no, $biller_id, $customer_name, $product_id, $quantity, $total_amount, $sale_status, $payment_status, $sale_note, $user_id)
    {
        $stmt = $this->db_conn()->prepare("INSERT INTO Sales (sale_date, reference_no, biller_id, customer_name, product_id, quantity, total_amount, sale_status, payment_status, sale_note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("ssisisdsss", $sale_date, $reference_no, $biller_id, $customer_name, $product_id, $quantity, $total_amount, $sale_status, $payment_status, $sale_note);
        $success = $stmt->execute();
        $sale_id = $stmt->insert_id; // Get the ID of the newly inserted sale
        $stmt->close();

        if ($success) {
            // Log the action
            $this->logAction($user_id, 'add', 'Sales', $sale_id);
            // Update product quantity in inventory
            $this->updateProductQuantity($product_id, -$quantity);
            return true;
        } else {
            return $stmt->error;
        }
    }


    public function addReturn($return_date, $reference_no, $receiver_name, $customer_name, $product_id, $quantity, $total_amount, $return_status, $return_note, $user_id)
    {
        $stmt = $this->db_conn()->prepare("INSERT INTO returns (return_date, reference_no, receiver_name, customer_name, product_id, quantity, total_amount, return_status, return_note) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("ssssiiiss", $return_date, $reference_no, $receiver_name, $customer_name, $product_id, $quantity, $total_amount, $return_status, $return_note);
        $success = $stmt->execute();
        $return_id = $stmt->insert_id; // Get the ID of the newly inserted return
        $stmt->close();

        if ($success) {
            // Log the action
            $this->logAction($user_id, 'add', 'Returns', $return_id);
            return true;
        } else {
            return $stmt->error;
        }
    }



    public function getAllSalesFilteredSorted($filterStatus = '', $sortColumn = 'sale_date', $sortOrder = 'ASC', $searchQuery = '', $limit = 10, $offset = 0)
    {
        $conn = $this->db_conn();

        $query = "SELECT s.sale_id, s.reference_no, s.customer_name, s.sale_date, s.total_amount, s.sale_status, s.payment_status, s.sale_note, u.first_name as biller_name, p.product_name
              FROM Sales s
              JOIN Users u ON s.biller_id = u.user_id
              JOIN Products p ON s.product_id = p.product_id
              WHERE 1=1";

        if (!empty($filterStatus)) {
            $query .= " AND s.sale_status = ?";
        }

        if (!empty($searchQuery)) {
            $query .= " AND (s.customer_name LIKE ? OR s.reference_no LIKE ? OR u.first_name LIKE ? OR p.product_name LIKE ?)";
            $searchQuery = "%$searchQuery%";
        }

        $query .= " ORDER BY $sortColumn $sortOrder LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($query);

        if (!empty($filterStatus) && !empty($searchQuery)) {
            $stmt->bind_param("sssssii", $filterStatus, $searchQuery, $searchQuery, $searchQuery, $searchQuery, $limit, $offset);
        } elseif (!empty($filterStatus)) {
            $stmt->bind_param("sii", $filterStatus, $limit, $offset);
        } elseif (!empty($searchQuery)) {
            $stmt->bind_param("sssii", $searchQuery, $searchQuery, $searchQuery, $searchQuery, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $sales = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $sales;
    }


    public function getTotalSales($filterStatus = '', $searchQuery = '')
    {
        $conn = $this->db_conn();

        $query = "SELECT COUNT(*) as total FROM Sales s
              JOIN Users u ON s.biller_id = u.user_id
              JOIN Products p ON s.product_id = p.product_id
              WHERE 1=1";

        if (!empty($filterStatus)) {
            $query .= " AND s.sale_status = ?";
        }

        if (!empty($searchQuery)) {
            $query .= " AND (s.customer_name LIKE ? OR s.reference_no LIKE ? OR u.first_name LIKE ? OR p.product_name LIKE ?)";
            $searchQuery = "%$searchQuery%";
        }

        $stmt = $conn->prepare($query);

        if (!empty($filterStatus) && !empty($searchQuery)) {
            $stmt->bind_param("sssss", $filterStatus, $searchQuery, $searchQuery, $searchQuery, $searchQuery);
        } elseif (!empty($filterStatus)) {
            $stmt->bind_param("s", $filterStatus);
        } elseif (!empty($searchQuery)) {
            $stmt->bind_param("ssss", $searchQuery, $searchQuery, $searchQuery, $searchQuery);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];

        $stmt->close();
        $conn->close();

        return $total;
    }



    // Get category by ID
    public function getCategoryById($category_id)
    {
        $stmt = $this->db_conn()->prepare("SELECT * FROM `Categories` WHERE `category_id` = ?");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("i", $category_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function getAllCategories($limit = 10, $offset = 0)
    {
        $conn = $this->db_conn();

        $query = "SELECT category_id, category_name FROM Categories WHERE archived = 0 LIMIT ? OFFSET ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("ii", $limit, $offset);
        $stmt->execute();
        $result = $stmt->get_result();

        $categories = $result->fetch_all(MYSQLI_ASSOC);

        $result->free();
        $stmt->close();
        $conn->close();

        return $categories;
    }

    public function getAllCategoriesWithoutPagination()
    {
        $conn = $this->db_conn();
        $query = "SELECT category_id, category_name FROM Categories WHERE archived = 0"; // Exclude archived categories
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $categories = $result->fetch_all(MYSQLI_ASSOC);

        $result->free();
        $stmt->close();
        $conn->close();

        return $categories;
    }


    public function getTotalCategories()
    {
        $conn = $this->db_conn();

        $query = "SELECT COUNT(*) AS total FROM Categories WHERE archived = 0";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];

        $stmt->close();
        $conn->close();

        return $total;
    }

    public function getAllProductsWithCategory($filterCategory = '', $sortColumn = 'product_name', $sortOrder = 'ASC', $limit = 10, $offset = 0)
    {
        $conn = $this->db_conn();

        $query = "SELECT p.product_id, p.product_name, c.category_name, p.price, p.quantity, p.expiry_date, p.total_amount, p.image 
              FROM Products p
              JOIN Categories c ON p.category_id = c.category_id
              WHERE p.archived = 0";  // Exclude archived Products

        if ($filterCategory != '') {
            $query .= " AND p.category_id = ?";
        }

        $query .= " ORDER BY $sortColumn $sortOrder LIMIT ? OFFSET ?";

        $stmt = $conn->prepare($query);

        if ($filterCategory != '') {
            $stmt->bind_param("iii", $filterCategory, $limit, $offset);
        } else {
            $stmt->bind_param("ii", $limit, $offset);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $Products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $Products;
    }


    public function getTotalProducts($filterCategory = '')
    {
        $conn = $this->db_conn();

        $query = "SELECT COUNT(*) as total FROM Products WHERE archived = 0";  // Exclude archived Products

        if ($filterCategory != '') {
            $query .= " AND category_id = ?";
        }

        $stmt = $conn->prepare($query);

        if ($filterCategory != '') {
            $stmt->bind_param("i", $filterCategory);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];

        $stmt->close();
        $conn->close();

        return $total;
    }


    public function getUsers()
    {
        $stmt = $this->db_conn()->prepare("SELECT `user_id`, `first_name`, `last_name`, `email`, `phone`, `role`, `gender` FROM `Users` WHERE `deleted_at` IS NULL");
        if ($stmt === false) {
            echo "Failed to prepare statement for getUsers";
            return [];
        }
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to delete a product and log the action
    public function deleteProduct($product_id, $user_id)
    {
        if (!is_numeric($product_id)) {
            return "Invalid product ID.";
        }

        // Prepare the SQL statement for deletion
        $stmt = $this->db_conn()->prepare("DELETE FROM `Products` WHERE `product_id` = ?");
        if ($stmt === false) {
            return "Error preparing statement.";
        }

        // Bind parameters and execute
        $stmt->bind_param("i", $product_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'delete', 'Products', $product_id); // Log the action
        }

        return $success;
    }


    // Method to delete a category and log the action
    public function deleteCategory($category_id, $user_id)
    {
        if (!is_numeric($category_id)) {
            return false;
        }

        // Prepare the SQL statement for deletion
        $stmt = $this->db_conn()->prepare("DELETE FROM `Categories` WHERE `category_id` = ?");
        if ($stmt === false) {
            return false; // Error preparing statement
        }

        // Bind parameters and execute
        $stmt->bind_param("i", $category_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'delete', 'Categories', $category_id); // Log the action
        }

        return $success;
    }

    // Method to delete a sale and log the action
    public function deleteSale($sale_id, $user_id)
    {
        // Fetch the sale details
        $sale = $this->getSaleById($sale_id);
        $quantity = $sale['quantity'];
        $product_id = $sale['product_id'];

        $conn = $this->db_conn();
        $query = "DELETE FROM Sales WHERE sale_id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("i", $sale_id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();

        if ($result) {
            // Adjust the product quantity
            $this->updateProductQuantity($product_id, $quantity);
            $this->logAction($user_id, 'delete', 'sales', $sale_id); // Log the action
        }

        return $result;
    }

    // Method to delete a return and log the action
    public function deleteReturn($return_id, $user_id)
    {
        if (!is_numeric($return_id)) {
            return "Invalid return ID.";
        }

        // Prepare the SQL statement for deletion
        $stmt = $this->db_conn()->prepare("DELETE FROM `returns` WHERE `return_id` = ?");
        if ($stmt === false) {
            return "Error preparing statement.";
        }

        // Bind parameters and execute
        $stmt->bind_param("i", $return_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'delete', 'returns', $return_id); // Log the action
        }

        return $success;
    }

    // Method to delete a user and log the action
    public function delete_user($user_id, $admin_id)
    {
        $stmt = $this->db_conn()->prepare("UPDATE `Users` SET `deleted_at` = NOW() WHERE `user_id` = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("i", $user_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($admin_id, 'delete', 'Users', $user_id); // Log the action
        }

        return $success;
    }



    public function updateProduct($product_id, $product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id)
    {
        // Prepare statement
        $stmt = $this->db_conn()->prepare("UPDATE `Products` SET `product_name` = ?, `category_id` = ?, `price` = ?, `quantity` = ?, `expiry_date` = ?, `total_amount` = ?, `image` = ? WHERE `product_id` = ?");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        // Bind parameters and execute
        $stmt->bind_param("sidisisi", $product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $product_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'update', 'Products', $product_id); // Log the action
            return true;
        } else {
            return false;
        }
    }











    public function updateCategory($category_id, $category_name, $user_id)
    {
        // Prepare the SQL statement for updating the category
        $stmt = $this->db_conn()->prepare("UPDATE `Categories` SET `category_name` = ? WHERE `category_id` = ?");

        if ($stmt === false) {
            return false; // Error preparing statement
        }

        // Bind parameters and execute
        $stmt->bind_param("si", $category_name, $category_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'update', 'Categories', $category_id); // Log the action
            return true;
        } else {
            return false;
        }
    }





    public function getAllUsers()
    {
        $stmt = $this->db_conn()->prepare("SELECT * FROM Users WHERE deleted_at IS NULL");

        if ($stmt === false) {
            return [];
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['name'] = $row['first_name'] . ' ' . $row['last_name'];
                $users[] = $row;
            }
        }

        $stmt->close();

        return $users;
    }


    public function getAllReturnsFilteredSorted($sortColumn = 'return_date', $sortOrder = 'ASC', $filter = [], $limit = 10, $offset = 0)
    {
        $sql = "SELECT r.return_id, r.return_date, r.reference_no, r.receiver_name, r.customer_name, p.product_name, r.quantity, r.total_amount, r.return_status, r.return_note 
        FROM returns r 
        JOIN Products p ON r.product_id = p.product_id";

        // Apply filters if any
        if (!empty($filter)) {
            $filterClauses = [];
            foreach ($filter as $key => $value) {
                $filterClauses[] = "$key LIKE ?";
            }
            if (!empty($filterClauses)) {
                $sql .= " WHERE " . implode(" AND ", $filterClauses);
            }
        }

        // Apply sorting
        $sql .= " ORDER BY $sortColumn $sortOrder LIMIT ? OFFSET ?";

        $stmt = $this->db_conn()->prepare($sql);

        // Check if statement preparation is successful
        if ($stmt === false) {
            die('Error preparing statement: ' . $this->db_conn()->error);
        }

        // Bind filter values
        if (!empty($filter)) {
            $types = str_repeat('s', count($filter)) . 'ii'; // assuming all filters are strings
            $values = array_values($filter);
            $values[] = $limit;
            $values[] = $offset;
            $stmt->bind_param($types, ...$values);
        } else {
            $stmt->bind_param('ii', $limit, $offset);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            die('Error executing statement: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        $stmt->close();

        // Debug: print the number of returns fetched
        echo 'Number of returns fetched: ' . count($data);

        return $data;
    }

    public function getTotalReturns($filter = [])
    {
        $sql = "SELECT COUNT(*) as total FROM returns r JOIN Products p ON r.product_id = p.product_id";

        // Apply filters if any
        if (!empty($filter)) {
            $filterClauses = [];
            foreach ($filter as $key => $value) {
                $filterClauses[] = "$key LIKE ?";
            }
            if (!empty($filterClauses)) {
                $sql .= " WHERE " . implode(" AND ", $filterClauses);
            }
        }

        $stmt = $this->db_conn()->prepare($sql);

        // Check if statement preparation is successful
        if ($stmt === false) {
            die('Error preparing statement: ' . $this->db_conn()->error);
        }

        // Bind filter values
        if (!empty($filter)) {
            $types = str_repeat('s', count($filter)); // assuming all filters are strings
            $values = array_values($filter);
            $stmt->bind_param($types, ...$values);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            die('Error executing statement: ' . $stmt->error);
        }

        $result = $stmt->get_result();
        $total = $result->fetch_assoc()['total'];
        $stmt->close();

        return $total;
    }




    public function getProduct($product_id)
    {
        // Prepare statement
        $stmt = $this->db_conn()->prepare("SELECT * FROM `Products` WHERE `product_id` = ?");

        if ($stmt === false) {
            return null;
        }

        // Bind parameters and execute
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllProducts()
    {
        $stmt = $this->db_conn()->prepare("
        SELECT p.product_id, p.product_name, c.category_name, p.quantity, p.price, p.expiry_date
        FROM Products p
        JOIN Categories c ON p.category_id = c.category_id
        WHERE p.archived = 0");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $Products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $Products;
    }



    public function getSoonToFinishOrExpireMedications()
    {
        $stmt = $this->db_conn()->prepare("
    SELECT product_name AS name, expiry_date 
    FROM Products 
    WHERE (quantity < 10 OR expiry_date < NOW() + INTERVAL 1 MONTH) AND archived = 0
    ");
        if ($stmt === false) {
            return [];
        }
        $stmt->execute();
        $result = $stmt->get_result();
        $medications = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $medications;
    }

    public function get_user_by_id($user_id)
    {
        try {
            $stmt = $this->db_conn()->prepare("SELECT `user_id`, `first_name`, `last_name`, `email`, `phone`, `role`, `gender` FROM `Users` WHERE `user_id` = ? AND `deleted_at` IS NULL");
            if ($stmt === false) {
                echo "Failed to prepare statement for get_user_by_id";
                return false;
            }
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                return $result->fetch_assoc();
            } else {
                echo "User not found in database";
                return false;
            }
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
            return false;
        }
    }

    public function getUserById($user_id)
    {
        $conn = $this->db_conn();
        $query = "SELECT * FROM Users WHERE user_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            return null;
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $result;
    }

    public function getSaleById($sale_id)
    {
        $conn = $this->db_conn();
        $query = "SELECT * FROM Sales WHERE sale_id = ?";
        $stmt = $conn->prepare($query);

        if ($stmt === false) {
            return null;
        }

        $stmt->bind_param("i", $sale_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $result;
    }

    // Method to update a user and log the action
    public function update_user($user_id, $first_name, $last_name, $email, $phone, $role, $gender) {
        $stmt = $this->db_conn()->prepare("UPDATE `Users` SET `first_name` = ?, `last_name` = ?, `email` = ?, `phone` = ?, `role` = ?, `gender` = ? WHERE `user_id` = ?");
        if ($stmt === false) {
            echo "Failed to prepare statement for update_user";
            return false;
        }
        $success = $stmt->execute([$first_name, $last_name, $email, $phone, $role, $gender, $user_id]);
        if ($success) {
            return true;
        } else {
            echo "Failed to execute update_user statement";
            return false;
        }
    }
     


    public function updateReturn($return_id, $customer_name, $quantity, $total_amount, $return_status, $return_note, $user_id)
    {
        $stmt = $this->db_conn()->prepare("UPDATE returns SET customer_name = ?, quantity = ?, total_amount = ?, return_status = ?, return_note = ? WHERE return_id = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("siissi", $customer_name, $quantity, $total_amount, $return_status, $return_note, $return_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            // Adjust the product quantity
            $old_return = $this->getReturnById($return_id);
            $product_id = $old_return['product_id'];
            $this->updateProductQuantityForReturn($product_id, $quantity - $old_return['quantity']);

            // Log the action
            $this->logAction($user_id, 'update', 'Returns', $return_id);
        }

        return $success;
    }


    public function getReturnById($return_id)
    {
        $stmt = $this->db_conn()->prepare("SELECT * FROM returns WHERE return_id = ?");
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("i", $return_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result;
    }







    

    // Method to update a sale and log the action
    public function updateSale($sale_id, $sale_date, $customer_name, $total_amount, $sale_status, $payment_status, $sale_note, $admin_id)
    {
        // Fetch the old sale details
        $old_sale = $this->getSaleById($sale_id);
        $old_quantity = $old_sale['quantity'];
        $product_id = $old_sale['product_id'];

        $stmt = $this->db_conn()->prepare("UPDATE Sales SET sale_date = ?, customer_name = ?, total_amount = ?, sale_status = ?, payment_status = ?, sale_note = ? WHERE sale_id = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ssisssi", $sale_date, $customer_name, $total_amount, $sale_status, $payment_status, $sale_note, $sale_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($admin_id, 'update', 'sales', $sale_id); // Log the action
        }

        return $success;
    }



    public function get_supplier_details()
    {
        $config = include '../config/supplier_config.php';
        return [
            'supplier_name' => $config['supplier_name'],
            'supplier_email' => $config['supplier_email'],
            'supplier_phone' => $config['supplier_phone']
        ];
    }


    // Sign in user method
    public function signinUser($email, $password)
    {
        // Validate inputs
        if (empty($email) || empty($password)) {
            return "Please fill in all fields.";
        }

        // Prepare statement
        $stmt = $this->db_conn()->prepare("SELECT `user_id`, `first_name`, `last_name`, `email`, `phone`, `role`, `password` FROM `Users` WHERE `email` = ?");
        if ($stmt === false) {
            return "Error preparing statement.";
        }

        // Bind parameters and execute
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        // Check if user exists
        if ($stmt->num_rows === 0) {
            return false; // User not found
        }

        // Fetch the user data
        $stmt->bind_result($id, $first_name, $last_name, $email, $phone, $role, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Return user details
            return [
                'user_id' => $id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $email,
                'phone' => $phone,
                'role' => $role
            ];
        } else {
            return false; // Incorrect password
        }
    }

    public function getProductsByIds($product_ids)
    {
        if (empty($product_ids)) {
            return [];
        }

        // Prepare the SQL statement to fetch Products by IDs
        $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
        $stmt = $this->db_conn()->prepare("SELECT * FROM Products WHERE product_id IN ($placeholders)");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        // Bind parameters dynamically
        $types = str_repeat('i', count($product_ids));
        $stmt->bind_param($types, ...$product_ids);
        $stmt->execute();
        $result = $stmt->get_result();
        $Products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();

        return $Products;
    }




    //  stock  management 

    public function get_low_stock_Products_with_category()
    {
        $conn = $this->db_conn();
        $query = "
        SELECT p.product_id, p.product_name, c.category_name, p.quantity, p.price, p.expiry_date
        FROM Products p
        JOIN Categories c ON p.category_id = c.category_id
        WHERE p.quantity < ? AND p.archived = 0";  // Assuming a threshold value for low stock

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $low_stock_threshold = 10;  // Example threshold value
        $stmt->bind_param("i", $low_stock_threshold);
        $stmt->execute();
        $result = $stmt->get_result();
        $low_stock_Products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $low_stock_Products;
    }

    public function get_soon_to_expire_Products_with_category()
    {
        $conn = $this->db_conn();
        $query = "
    SELECT p.product_id, p.product_name, c.category_name, p.quantity, p.price, p.expiry_date
    FROM Products p
    JOIN Categories c ON p.category_id = c.category_id
    WHERE p.expiry_date <= ? AND p.archived = 0";

        $stmt = $conn->prepare($query);
        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $one_week_ahead = date('Y-m-d', strtotime('+1 week'));
        $stmt->bind_param("s", $one_week_ahead);
        $stmt->execute();
        $result = $stmt->get_result();
        $soon_to_expire_Products = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $soon_to_expire_Products;
    }






    // Update product quantity 
    public function getProductById($product_id)
    {
        $stmt = $this->db_conn()->prepare("SELECT * FROM Products WHERE product_id = ?");
        if ($stmt === false) {
            return null;
        }
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function updateProductQuantity($product_id, $quantity_change)
    {
        $stmt = $this->db_conn()->prepare("UPDATE Products SET quantity = quantity + ? WHERE product_id = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ii", $quantity_change, $product_id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }


    public function updateProductQuantityForReturn($product_id, $quantity_change)
    {
        $stmt = $this->db_conn()->prepare("UPDATE Products SET quantity = quantity + ? WHERE product_id = ?");
        if ($stmt === false) {
            return false;
        }
        $stmt->bind_param("ii", $quantity_change, $product_id);
        return $stmt->execute();
    }


    // Method to archive a product and log the action
    public function archiveProduct($product_id, $user_id)
    {
        if (!is_numeric($product_id)) {
            return "Invalid product ID.";
        }

        // Prepare the SQL statement for archiving
        $stmt = $this->db_conn()->prepare("UPDATE `Products` SET `archived` = 1 WHERE `product_id` = ?");
        if ($stmt === false) {
            return "Error preparing statement.";
        }

        // Bind parameters and execute
        $stmt->bind_param("i", $product_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'archive', 'Products', $product_id); // Log the action
        }

        return $success;
    }

    // In your general class or relevant model
    public function archiveCategory($category_id, $user_id)
    {
        $stmt = $this->db_conn()->prepare("UPDATE `Categories` SET `archived` = 1 WHERE `category_id` = ?");

        if ($stmt === false) {
            return "Error preparing statement.";
        }

        $stmt->bind_param("i", $category_id);
        $success = $stmt->execute();
        $stmt->close();

        if ($success) {
            $this->logAction($user_id, 'archive', 'Categories', $category_id); // Log the action
            return true;
        } else {
            return false;
        }
    }


    // Controller function
    function archive_category($category_id, $user_id)
    {
        $general = new general_class();
        return $general->archiveCategory($category_id, $user_id);
    }



    // Chart Functions
    public function getCustomersCount($timeframe)
    {
        $sql = "SELECT COUNT(DISTINCT customer_name) AS count FROM Sales WHERE ";

        switch ($timeframe) {
            case 'day':
                $sql .= "DATE(sale_date) = CURDATE()";
                break;
            case 'week':
                $sql .= "WEEK(sale_date) = WEEK(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
                break;
            case 'month':
                $sql .= "MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
                break;
            case 'year':
                $sql .= "YEAR(sale_date) = YEAR(CURDATE())";
                break;
        }

        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    public function getProductsSoldCount($timeframe) {
        $sql = "SELECT SUM(quantity) AS count FROM Sales WHERE ";

        switch ($timeframe) {
            case 'day':
                $sql .= "DATE(sale_date) = CURDATE()";
                break;
            case 'week':
                $sql .= "WEEK(sale_date) = WEEK(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
                break;
            case 'month':
                $sql .= "MONTH(sale_date) = MONTH(CURDATE()) AND YEAR(sale_date) = YEAR(CURDATE())";
                break;
            case 'year':
                $sql .= "YEAR(sale_date) = YEAR(CURDATE())";
                break;
        }

        $result = $this->db->query($sql);
        $row = $result->fetch_assoc();
        return $row['count'];
    }

    // Audit Log Functions

    // Private method to log actions
    private function logAction($user_id, $action, $table_name, $record_id)
    {
        $stmt = $this->db_conn()->prepare("INSERT INTO AuditLogs (user_id, action, table_name, record_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("issi", $user_id, $action, $table_name, $record_id);
        $stmt->execute();
        $stmt->close();
    }

    public function log_action($user_id, $action, $table_name, $record_id)
    {
        $stmt = $this->db_conn()->prepare("INSERT INTO AuditLogs (user_id, action, table_name, record_id, timestamp) VALUES (?, ?, ?, ?, NOW())");

        if ($stmt === false) {
            return false;
        }

        $stmt->bind_param("issi", $user_id, $action, $table_name, $record_id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    // Function to fetch audit logs
    public function get_audit_logs($start_date = null, $end_date = null)
    {
        $conn = $this->db_conn();
        $query = "SELECT AuditLogs.log_id, AuditLogs.user_id, AuditLogs.action, AuditLogs.table_name, 
                     AuditLogs.record_id, AuditLogs.timestamp, Users.first_name, Users.last_name 
              FROM AuditLogs 
              JOIN Users ON AuditLogs.user_id = Users.user_id";

        if ($start_date && $end_date) {
            $query .= " WHERE AuditLogs.timestamp BETWEEN ? AND ?";
            $stmt = $conn->prepare($query);
            $end_date = date('Y-m-d 23:59:59', strtotime($end_date)); // Include the entire end date
            $stmt->bind_param("ss", $start_date, $end_date);
        } elseif ($start_date) {
            $query .= " WHERE AuditLogs.timestamp >= ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $start_date);
        } elseif ($end_date) {
            $query .= " WHERE AuditLogs.timestamp <= ?";
            $stmt = $conn->prepare($query);
            $end_date = date('Y-m-d 23:59:59', strtotime($end_date)); // Include the entire end date
            $stmt->bind_param("s", $end_date);
        } else {
            $stmt = $conn->prepare($query);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $logs = $result->fetch_all(MYSQLI_ASSOC);

        $stmt->close();
        $conn->close();

        return $logs;
    }

    public function getUserInfo($user_id)
    {
        $conn = $this->db_conn();
        $sql = "SELECT email, role, gender FROM Users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user_info = $result->fetch_assoc();
        $stmt->close();
        $conn->close();
        return $user_info;
    }




    //Password recovery functions
    public function getUserByEmail($email)
    {
        $stmt = $this->db_conn()->prepare("SELECT * FROM `Users` WHERE `email` = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        $stmt->close();
        return $user;
    }

    public function storePasswordResetToken($user_id, $token, $expiry)
    {
        $stmt = $this->db_conn()->prepare("INSERT INTO `PasswordResets` (`user_id`, `token`, `expiry`) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $token, $expiry);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }

    public function validatePasswordResetToken($token)
    {
        $stmt = $this->db_conn()->prepare("SELECT `user_id` FROM `PasswordResets` WHERE `token` = ? AND `expiry` > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->bind_result($user_id);
        $stmt->fetch();
        $stmt->close();
        return $user_id;
    }

    public function resetPassword($user_id, $password_hashed)
    {
        $stmt = $this->db_conn()->prepare("UPDATE `Users` SET `password` = ? WHERE `user_id` = ?");
        $stmt->bind_param("si", $password_hashed, $user_id);
        $success = $stmt->execute();
        $stmt->close();
        return $success;
    }
}











?>
