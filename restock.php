<?php
session_start();
require_once 'controllers/general_controller.php';

$low_stock_products = get_low_stock_products_with_category();
$soon_to_expire_products = get_soon_to_expire_products_with_category();
$all_products = get_all_products(); 
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
                        <h4 class="mb-3">Restock List</h4>
                        <p class="mb-0">Approve the Contents Needed to Generate The Restock List.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <form action="./actions/supplier_send.php" method="POST">
                    <div class="table-responsive rounded mb-3">
                        <h2>Low Stock Products</h2>
                        <table class="table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="light light-data">
                                    <th>Select</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Expiry Date</th>
                                </tr>
                            </thead>
                            <tbody class="light-body">
                                <?php foreach ($low_stock_products as $product): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="low_stock_products[]"
                                                value="<?php echo $product['product_id']; ?>" checked>
                                        </td>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td><?php echo $product['category_name']; ?></td>
                                        <td><?php echo $product['quantity']; ?></td>
                                        <td><?php echo $product['price']; ?></td>
                                        <td><?php echo $product['expiry_date']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive rounded mb-3">
                        <h2>Soon to Expire Products</h2>
                        <table class="table mb-0 tbl-server-info">
                            <thead class="bg-white text-uppercase">
                                <tr class="light light-data">
                                    <th>Select</th>
                                    <th>Product ID</th>
                                    <th>Product Name</th>
                                    <th>Category</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Expiry Date</th>
                                </tr>
                            </thead>
                            <tbody class="light-body">
                                <?php foreach ($soon_to_expire_products as $product): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="soon_to_expire_products[]"
                                                value="<?php echo $product['product_id']; ?>" checked>
                                        </td>
                                        <td><?php echo $product['product_id']; ?></td>
                                        <td><?php echo $product['product_name']; ?></td>
                                        <td><?php echo $product['category_name']; ?></td>
                                        <td><?php echo $product['quantity']; ?></td>
                                        <td><?php echo $product['price']; ?></td>
                                        <td><?php echo $product['expiry_date']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mb-3">
                        <h2>Add Additional Products</h2>
                        <div class="form-group">
                            <label for="additional_products">Select Products</label>
                            <select id="additional_products" name="additional_products[]" class="form-control">
                                <option value="">-- Select a Product --</option> <!-- Blank option -->
                                <?php foreach ($all_products as $product): ?>
                                    <option value="<?php echo $product['product_id']; ?>">
                                        <?php echo $product['product_name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button id="previewPdf" class="btn btn-secondary" name="previewPdf" type="submit">Preview
                        PDF</button>
                    <button id="savePdf" class="btn btn-secondary" name="savePdf" type="submit">Save PDF</button>
                    <?php if ($_SESSION['role'] === 'Manager'): ?>
                        <button type="submit" name="sendEmail" class="btn btn-primary">Send to Supplier</button>
                    <?php endif; ?>
                </form>
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
<!-- Include necessary JS files -->
<script src="assets/js/backend-bundle.min.js"></script>
<script src="assets/js/table-treeview.js"></script>
<script src="assets/js/customizer.js"></script>
<script async src="assets/js/chart-custom.js"></script>
<script src="assets/js/app.js"></script>
</body>

</html>
