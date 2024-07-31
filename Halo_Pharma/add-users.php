<?php
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role = $_POST['role'];
    $gender = $_POST['gender'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $result = register_user_ctr($first_name, $last_name, $email, $phone, $role, $gender, $password, $confirm_password);

    if ($result === "User registered successfully.") {
        header('Location: list-users.php');
    } else {
        echo "Error adding user: " . $result;
    }
}
?>


<!doctype html>
<html lang="en">

<?php include 'navigation.php'; ?>
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <div>
                        <h4 class="mb-3">Add User</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="number" class="form-control" id="phone" name="phone" required>
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select class="form-control" id="role" name="role" required>
                                    <option value="Pharmacist">Pharmacist</option>
                                    <option value="Manager">Manager</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" id="gender" name="gender" required>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password"
                                    name="confirm_password" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Add User</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<style>
    /* Remove arrows from number input */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type=number] {
        -moz-appearance: textfield;
    }
</style>
<script src="assets/js/backend-bundle.min.js"></script>
<script src="assets/js/table-treeview.js"></script>
<script src="assets/js/customizer.js"></script>
<script async src="assets/js/chart-custom.js"></script>
<script src="assets/js/app.js"></script>
</body>

</html>