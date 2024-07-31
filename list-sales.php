<?php
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$filterStatus = isset($_GET['filterStatus']) ? $_GET['filterStatus'] : '';
$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'sale_date';
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC';
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 10; // Number of sales per page
$offset = ($page - 1) * $limit;

$sales = get_all_sales_filtered_sorted($filterStatus, $sortColumn, $sortOrder, $searchQuery, $limit, $offset);
$totalSales = get_total_sales($filterStatus, $searchQuery);
$totalPages = ceil($totalSales / $limit);
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
                        <h4 class="mb-3">Sale List</h4>
                        <p class="mb-0">Here You Can View, Edit or Delete Past Sales, But Your Role Determines Your Access.</p>
                    </div>
                    <a href="add-sale.php" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Add Sale</a>
                </div>
                <form method="GET" action="">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" name="searchQuery" placeholder="Search..."
                                value="<?php echo htmlspecialchars($searchQuery); ?>">
                        </div>
                        <div class="form-group col-md-4">
                            <select class="form-control" name="filterStatus">
                                <option value="">All Statuses</option>
                                <option value="Completed" <?php echo $filterStatus == 'Completed' ? 'selected' : ''; ?>>
                                    Completed</option>
                                <option value="Pending" <?php echo $filterStatus == 'Pending' ? 'selected' : ''; ?>>
                                    Pending</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" name="sortColumn">
                                <option value="sale_date" <?php echo $sortColumn == 'sale_date' ? 'selected' : ''; ?>>Date
                                </option>
                                <option value="customer_name" <?php echo $sortColumn == 'customer_name' ? 'selected' : ''; ?>>Customer</option>
                                <option value="total_amount" <?php echo $sortColumn == 'total_amount' ? 'selected' : ''; ?>>Total</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <select class="form-control" name="sortOrder">
                                <option value="ASC" <?php echo $sortOrder == 'ASC' ? 'selected' : ''; ?>>Ascending
                                </option>
                                <option value="DESC" <?php echo $sortOrder == 'DESC' ? 'selected' : ''; ?>>Descending
                                </option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="data-table table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>
                                    <div class="checkbox d-inline-block">
                                        <input type="checkbox" class="checkbox-input" id="checkbox1">
                                        <label for="checkbox1" class="mb-0"></label>
                                    </div>
                                </th>
                                <th><a
                                        href="?sortColumn=sale_date&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Date</a>
                                </th>
                                <th><a
                                        href="?sortColumn=customer_name&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Customer</a>
                                </th>
                                <th><a
                                        href="?sortColumn=product_name&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Products
                                        Sold</a></th>
                                <th><a
                                        href="?sortColumn=total_amount&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Total</a>
                                </th>
                                <th><a
                                        href="?sortColumn=sale_status&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Status</a>
                                </th>
                                <th><a
                                        href="?sortColumn=biller_name&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Biller</a>
                                </th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            <?php foreach ($sales as $sale): ?>
                                <tr>
                                    <td>
                                        <div class="checkbox d-inline-block">
                                            <input type="checkbox" class="checkbox-input"
                                                id="checkbox<?php echo $sale['sale_id']; ?>">
                                            <label for="checkbox<?php echo $sale['sale_id']; ?>" class="mb-0"></label>
                                        </div>
                                    </td>
                                    <td><?php echo $sale['sale_date']; ?></td>
                                    <td><?php echo $sale['customer_name']; ?></td>
                                    <td><?php echo $sale['product_name']; ?></td>
                                    <td><?php echo $sale['total_amount']; ?></td>
                                    <td>
                                        <div
                                            class="badge badge-<?php echo $sale['sale_status'] == 'Completed' ? 'success' : 'warning'; ?>">
                                            <?php echo $sale['sale_status']; ?>
                                        </div>
                                    </td>
                                    <td><?php echo $sale['biller_name']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center list-action">
                                            <a class="badge bg-success mr-2 edit-sale"
                                                data-sale-id="<?php echo $sale['sale_id']; ?>" data-toggle="tooltip"
                                                data-placement="top" title="Edit" href="#"><i
                                                    class="ri-pencil-line mr-0"></i></a>
                                            <a class="badge bg-warning mr-2 delete-sale"
                                                data-sale-id="<?php echo $sale['sale_id']; ?>" data-toggle="tooltip"
                                                data-placement="top" title="Delete"
                                                href="actions/delete_sale.php?id=<?php echo $sale['sale_id']; ?>"><i
                                                    class="ri-delete-bin-line mr-0"></i></a>
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
                                    href="?page=<?php echo $page - 1; ?>&filterStatus=<?php echo $filterStatus; ?>&sortColumn=<?php echo $sortColumn; ?>&sortOrder=<?php echo $sortOrder; ?>&searchQuery=<?php echo htmlspecialchars($searchQuery); ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link"
                                    href="?page=<?php echo $i; ?>&filterStatus=<?php echo $filterStatus; ?>&sortColumn=<?php echo $sortColumn; ?>&sortOrder=<?php echo $sortOrder; ?>&searchQuery=<?php echo htmlspecialchars($searchQuery); ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link"
                                    href="?page=<?php echo $page + 1; ?>&filterStatus=<?php echo $filterStatus; ?>&sortColumn=<?php echo $sortColumn; ?>&sortOrder=<?php echo $sortOrder; ?>&searchQuery=<?php echo htmlspecialchars($searchQuery); ?>">Next</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- End Pagination -->
            </div>
        </div>
        <!-- Page end  -->
    </div>
</div>

<!-- Edit Sale Modal -->
<div class="modal fade" id="editSaleModal" tabindex="-1" role="dialog" aria-labelledby="editSaleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSaleModalLabel">Edit Sale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editSaleForm">
                    <input type="hidden" id="editSaleId">
                    <div class="form-group">
                        <label for="editSaleDate">Date</label>
                        <input type="date" class="form-control" id="editSaleDate" required>
                    </div>
                    <div class="form-group">
                        <label for="editCustomerName">Customer</label>
                        <input type="text" class="form-control" id="editCustomerName" required>
                    </div>
                    <div class="form-group">
                        <label for="editTotalAmount">Total Amount</label>
                        <input type="number" class="form-control" id="editTotalAmount" required readonly>
                    </div>
                    <div class="form-group">
                        <label for="editSaleStatus">Sale Status</label>
                        <select class="form-control" id="editSaleStatus" required>
                            <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editPaymentStatus">Payment Status</label>
                        <select class="form-control" id="editPaymentStatus" required>
                            <option value="Paid">Paid</option>
                            <option value="Pending">Pending</option>
                            <option value="Due">Due</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editSaleNote">Sale Note</label>
                        <textarea class="form-control" id="editSaleNote" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
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
<script src="assets/js/jquery.min.js"></script> 
<script src="assets/js/backend-bundle.min.js"></script>
<script src="assets/js/table-treeview.js"></script>
<script src="assets/js/customizer.js"></script>
<script async src="assets/js/chart-custom.js"></script>
<script src="assets/js/app.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-sale');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function (event) {
                const saleId = this.getAttribute('data-sale-id');
                if (confirm('Are you sure you want to delete this sale?')) {
                    fetch(`actions/delete_sale.php?id=${saleId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });

        const editButtons = document.querySelectorAll('.edit-sale');
        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const saleId = this.getAttribute('data-sale-id');
                fetch(`actions/get_sale.php?id=${saleId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('editSaleId').value = data.sale_id;
                        document.getElementById('editSaleDate').value = data.sale_date;
                        document.getElementById('editCustomerName').value = data.customer_name;
                        document.getElementById('editTotalAmount').value = data.total_amount;
                        document.getElementById('editSaleStatus').value = data.sale_status;
                        document.getElementById('editPaymentStatus').value = data.payment_status;
                        document.getElementById('editSaleNote').value = data.sale_note;
                        $('#editSaleModal').modal('show');
                    });
            });
        });

        document.getElementById('editSaleForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const saleId = document.getElementById('editSaleId').value;
        const saleData = {
            sale_id: saleId,
            sale_date: document.getElementById('editSaleDate').value,
            customer_name: document.getElementById('editCustomerName').value,
            total_amount: document.getElementById('editTotalAmount').value,
            sale_status: document.getElementById('editSaleStatus').value,
            payment_status: document.getElementById('editPaymentStatus').value,
            sale_note: document.getElementById('editSaleNote').value
        };

        fetch('actions/edit_sale.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(saleData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Sale updated successfully');
                location.reload();
            } else {
                alert('Failed to update sale: ' + data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
</body>

</html>