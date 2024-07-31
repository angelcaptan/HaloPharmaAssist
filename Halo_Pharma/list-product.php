<?php
// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once (__DIR__ . "/settings/core.php");

// Fetch filter and sort parameters from GET request
$filterCategory = isset($_GET['category']) ? $_GET['category'] : '';
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'product_name';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 10; // Number of products per page
$offset = ($page - 1) * $limit;

// Create a general class instance
$general = new general_class();

// Get products based on filter, sort criteria, and pagination
$products = $general->getAllProductsWithCategory($filterCategory, $sortColumn, $sortOrder, $limit, $offset);

// Get total number of products for pagination
$totalProducts = $general->getTotalProducts($filterCategory);

// Get all categories for filter dropdown
$categories = get_all_categories_without_pagination();


// Calculate total pages
$totalPages = ceil($totalProducts / $limit);
?>



<!doctype html>
<html lang="en">

<?php include 'navigation.php'; ?>
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <h4 class="mb-3">Product List</h4>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addProductModal">Add
                        Product</a>
                </div>
            </div>
            <form method="GET" action="list-product.php">
                <div class="form-group">
                    <label for="category">Filter by Category:</label>
                    <select id="category" name="category" class="form-control">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['category_id']; ?>" <?php echo ($filterCategory == $category['category_id']) ? 'selected' : ''; ?>>
                                <?php echo $category['category_name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Apply Filter</button>
            </form>
            <!-- End Filter Form -->

            <div class="table-responsive rounded mb-3">
                <table class="table mb-0 tbl-server-info">
                    <thead class="bg-white text-uppercase">
                        <tr class="ligth ligth-data">
                            <th><a
                                    href="?sort=product_name&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Product</a>
                            </th>
                            <th><a
                                    href="?sort=category_name&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Category</a>
                            </th>
                            <th><a
                                    href="?sort=price&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Price</a>
                            </th>
                            <th><a
                                    href="?sort=quantity&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Quantity</a>
                            </th>
                            <th><a href="?sort=expiry_date&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Expiry
                                    Date</a></th>
                            <th><a href="?sort=total_amount&order=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Total
                                    Amount</a></th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="ligth-body">
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['product_name']; ?></td>
                                <td><?php echo $product['category_name']; ?></td>
                                <td><?php echo $product['price']; ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo $product['expiry_date']; ?></td>
                                <td><?php echo $product['total_amount']; ?></td>
                                <td><img src="assets/images/stock/<?php echo $product['image']; ?>"
                                        class="img-fluid rounded avatar-50 mr-3" alt="image"></td>
                                <td>
                                    <div class='d-flex align-items-center list-action'>
                                        <a class='badge bg-success mr-2 edit-product'
                                            data-id='<?php echo $product['product_id']; ?>'
                                            data-name='<?php echo $product['product_name']; ?>'
                                            data-category-id='<?php echo $product['category']; ?>'
                                            data-price='<?php echo $product['price']; ?>'
                                            data-quantity='<?php echo $product['quantity']; ?>'
                                            data-expiry_date='<?php echo $product['expiry_date']; ?>'
                                            data-total_amount='<?php echo $product['total_amount']; ?>'
                                            data-image='<?php echo $product['image']; ?>' data-toggle='modal'
                                            data-target='#editModal'>
                                            <i class='ri-pencil-line mr-0'></i>
                                        </a>
                                        <a class='badge bg-warning mr-2' data-toggle='tooltip' data-placement='top'
                                            title='Delete'
                                            href='actions/delete_product.php?id=<?php echo $product['product_id']; ?>'><i
                                                class='ri-delete-bin-line mr-0'></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <?php if ($page > 1): ?>
                        <li class="page-item"><a class="page-link"
                                href="?page=<?php echo $page - 1; ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>">Previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link"
                                href="?page=<?php echo $i; ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item"><a class="page-link"
                                href="?page=<?php echo $page + 1; ?>&sort=<?php echo $sortColumn; ?>&order=<?php echo $sortOrder; ?>">Next</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <!-- End Pagination -->
        </div>
    </div>
    <!-- Edit Product Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="popup text-left">
                        <h3 class="mb-3">Edit Product</h3>
                        <form action="actions/edit_product.php" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="product_id" id="editProductId">
                            <div class="form-group">
                                <label for="editName">Name</label>
                                <input type="text" class="form-control" id="editName" name="product_name" required>
                            </div>
                            <div class="form-group">
                                <label for="productCategory">Category</label>
                                <select class="form-control" id="productCategory" name="category_id">
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>">
                                            <?php echo $category['category_name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editPrice">Price</label>
                                <input type="number" class="form-control" id="editPrice" name="price" required>
                            </div>
                            <div class="form-group">
                                <label for="editQuantity">Quantity</label>
                                <input type="number" class="form-control" id="editQuantity" name="quantity" required>
                            </div>
                            <div class="form-group">
                                <label for="editExpiryDate">Expiry Date</label>
                                <input type="date" class="form-control" id="editExpiryDate" name="expiry_date" required>
                            </div>
                            <div class="form-group">
                                <label for="editTotalAmount">Total Amount</label>
                                <input type="number" class="form-control" id="editTotalAmount" name="total_amount"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="editImage">Image</label>
                                <input type="file" class="form-control" id="editImage" name="image_name">
                            </div>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="actions/add-product.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="product_name">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" required>
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select class="form-control" id="category" name="category" required>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" class="form-control" id="price" name="price" required>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="form-group">
                            <label for="expiry_date">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                        </div>
                        <div class="form-group">
                            <label for="total_amount">Total Amount</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" required>
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" class="form-control" id="image" name="image" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.edit-product').forEach(button => {
            button.addEventListener('click', event => {
                const productId = button.getAttribute('data-id');
                fetch(`actions/get_product.php?id=${productId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editProductId').value = data.product_id;
                        document.getElementById('editName').value = data.product_name;
                        document.getElementById('productCategory').value = data.category_id;
                        document.getElementById('editPrice').value = data.price;
                        document.getElementById('editQuantity').value = data.quantity;
                        document.getElementById('editExpiryDate').value = data.expiry_date;
                        document.getElementById('editTotalAmount').value = data.total_amount;
                    });
            });
        });


        document.getElementById('editPrice').addEventListener('input', calculateEditTotalAmount);
        document.getElementById('editQuantity').addEventListener('input', calculateEditTotalAmount);

        function calculateEditTotalAmount() {
            var price = document.getElementById('editPrice').value;
            var quantity = document.getElementById('editQuantity').value;
            var totalAmount = price * quantity;
            document.getElementById('editTotalAmount').value = totalAmount.toFixed(2);
        }
        ;
    </script>

    </table>
</div>
</div>
<!-- FOOTER-->
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
                        </span> <a href="#" class="">Halo Pharma Assist</a>
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