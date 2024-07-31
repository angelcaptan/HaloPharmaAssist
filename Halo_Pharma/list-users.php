<?php
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$users = get_users();
?>

<!doctype html>
<html lang="en">

<head>
    <title>User List</title>
</head>

<body>
    <?php include 'navigation.php'; ?>
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                        <div>
                            <h4 class="mb-3">User List</h4>
                            <p class="mb-0">An Overview of User List with Access to the Most
                                Important Data,<br> Functions and Controls.</p>
                        </div>
                        <a href="add-users.php" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Add
                            User</a>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="table-responsive rounded mb-3">
                        <table class="data-table table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="light light-data">
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Gender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="light-body">
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($user['gender'])); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center list-action">
                                                <a class="badge bg-success mr-2"
                                                    href="edit-user.php?id=<?php echo $user['user_id']; ?>"
                                                    data-toggle="tooltip" data-placement="top" title="Edit"><i
                                                        class="ri-pencil-line mr-0"></i></a>
                                                <a class="badge bg-warning mr-2 delete-user"
                                                    data-user-id="<?php echo $user['user_id']; ?>" data-toggle="tooltip"
                                                    data-placement="top" title="Delete" href="#"><i
                                                        class="ri-delete-bin-line mr-0"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Delete user
            document.querySelectorAll('.delete-user').forEach(function (button) {
                button.addEventListener('click', function () {
                    var userId = this.getAttribute('data-user-id');
                    if (confirm('Are you sure you want to delete this user?')) {
                        fetch(`actions/delete_user.php`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ user_id: userId })
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    location.reload();
                                } else {
                                    alert(data.message);
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred. Check the console for details.');
                            });
                    }
                });
            });
        });
    </script>
    
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