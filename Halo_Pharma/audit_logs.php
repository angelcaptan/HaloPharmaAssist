<?php

require_once 'controllers/general_controller.php';
include 'permissions.php';
checkPermission('Manager'); // Only managers can see this page

// Fetch audit logs
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$audit_logs = get_audit_logs($start_date, $end_date);
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include 'navigation.php'; ?>
    <div class="content-page">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <div class="header-title">
                                <h4 class="card-title">Audit Logs</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="actions/audit_print.php" method="POST">
                                <div class="form-group">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" id="start_date" name="start_date" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="end_date">End Date:</label>
                                    <input type="date" id="end_date" name="end_date" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-primary">Download PDF</button>
                            </form>
                            <div class="table-responsive mt-3">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>User</th>
                                            <th>Action</th>
                                            <th>Table Name</th>
                                            <th>Record ID</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($audit_logs as $log): ?>
                                            <tr>
                                                <td><?php echo $log['log_id']; ?></td>
                                                <td><?php echo $log['first_name'] . ' ' . $log['last_name']; ?></td>
                                                <td><?php echo $log['action']; ?></td>
                                                <td><?php echo $log['table_name']; ?></td>
                                                <td><?php echo $log['record_id']; ?></td>
                                                <td><?php echo $log['timestamp']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Page end  -->
        </div>
    </div>
    <!-- Wrapper End-->
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

    <script async src="assets/js/chart-custom.js"></script>

    <script src="assets/js/app.js"></script>
</body>

</html>