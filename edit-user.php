<?php
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get user ID from the query string
if (!isset($_GET['id'])) {
    echo "No user ID provided.";
    exit;
}

$user_id = $_GET['id'];
echo "User ID: " . $user_id . "<br>";

$user = get_user_by_id($user_id);
if ($user === false) {
    echo "User not found.";
    exit;
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $gender = $_POST['gender'];

    // Validate and sanitize inputs here

    $result = update_user($user_id, $first_name, $last_name, $email, $phone, $role, $gender);

    if ($result) {
        echo "User updated successfully.";
        header("Refresh: 2; URL=list-users.php");
        exit;
    } else {
        echo "Failed to update user.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>

    <title>Edit User</title>
  
</head>

<body>
<?php include 'navigation.php'; ?>
<div class="content-page">
    <div class="container-fluid">
        <h2>Edit User</h2>
        <form method="post">
            <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
            <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control" id="first_name" name="first_name"
                    value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name"
                    value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" class="form-control" id="phone" name="phone"
                    value="<?php echo htmlspecialchars($user['phone']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="Pharmacist" <?php echo $user['role'] == 'Pharmacist' ? 'selected' : ''; ?>>Pharmacist
                    </option>
                    <option value="Manager" <?php echo $user['role'] == 'Manager' ? 'selected' : ''; ?>>Manager</option>
                </select>
            </div>
            <div class="form-group">
                <label for="gender">Gender</label>
                <select class="form-control" id="gender" name="gender" required>
                    <option value="Male" <?php echo $user['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                    <option value="Female" <?php echo $user['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </form>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
<!-- Footer -->
    <footer class="iq-footer">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item"><a href="">Privacy Policy</a></li>
                                <li class="list-inline-item"><a href="">Terms of Use</a></li>
                            </ul>
                        </div>
                        <div class="col-lg-6 text-right">
                            <span class="mr-1">
                                <script>document.write(new Date().getFullYear())</script>©
                            </span> <a href="#" class="">Halo Pharma Assist</a>.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script src="assets/js/backend-bundle.min.js"></script>
    <script src="assets/js/table-treeview.js"></script>
    <script src="assets/js/customizer.js"></script>
    <script src="assets/js/chart-custom.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>