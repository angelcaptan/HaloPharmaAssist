<?php
require_once 'controllers/general_controller.php';

// Retrieve the supplier details from the config file directly
$supplier_details = include 'config/supplier_config.php';
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
                        <h4 class="mb-3">Supplier Details</h4>
                        <p class="mb-0">Here Are The Details of Abeer's Current Supplier.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="light light-data">
                                <th>Supplier Name</th>
                                <th>Supplier Email</th>
                                <th>Supplier Phone</th>
                            </tr>
                        </thead>
                        <tbody class="light-body">
                            <tr>
                                <td><?php echo $supplier_details['supplier_name']; ?></td>
                                <td><?php echo $supplier_details['supplier_email']; ?></td>
                                <td><?php echo $supplier_details['supplier_phone']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive rounded mb-3 mt-4">
                    <h4>A Chance To Update Supplier Details</h4>
                    <form action="actions/update_supplier.php" method="POST">
                        <table class="table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="light light-data">
                                    <th>New Supplier Name</th>
                                    <th>New Supplier Email</th>
                                    <th>New Supplier Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="light-body">
                                <tr>
                                    <td><input type="text" name="supplier_name"
                                            placeholder="<?php echo $supplier_details['supplier_name']; ?>" required>
                                    </td>
                                    <td><input type="email" name="supplier_email"
                                            placeholder="<?php echo $supplier_details['supplier_email']; ?>" required>
                                    </td>
                                    <td><input type="text" name="supplier_phone"
                                            placeholder="<?php echo $supplier_details['supplier_phone']; ?>" required>
                                    </td>
                                    <td><button type="submit" class="btn btn-primary">Update</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
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
                        </span> <a href="#" class="">Halo Assist</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Backend Bundle JavaScript -->
<script src="assets/js/backend-bundle.min.js"></script>

<script src="assets/js/table-treeview.js"></script>

<script src="assets/js/customizer.js"></script>

<script async src="assets/js/chart-custom.js"></script>

<script src="assets/js/app.js"></script>
</body>

</html>
