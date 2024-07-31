<?php
include_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$general = new general_class();

$sortColumn = isset($_GET['sortColumn']) ? $_GET['sortColumn'] : 'return_date';
$sortOrder = isset($_GET['sortOrder']) ? $_GET['sortOrder'] : 'ASC';

$filter = [];
if (!empty($_GET['search'])) {
    $search = $_GET['search'];
    $filter = [
        'customer_name' => "%$search%",
        'reference_no' => "%$search%",
        'receiver_name' => "%$search%",
        'product_name' => "%$search%"
    ];
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Number of records to show per page
$offset = ($page - 1) * $limit;

$returns = get_all_returns_filtered_sorted($sortColumn, $sortOrder, $filter, $limit, $offset);
$totalReturns = get_total_returns($filter);
$totalPages = ceil($totalReturns / $limit);


?>


    
    
    <!doctype html>
    <html lang="en">
    
    <?php include 'navigation.php'; ?>
    <div class="wrapper">
        <div class="content-page">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                            <div>
                                <h4 class="mb-3">Returns List</h4>
                                <p class="mb-0">Customer Product Returns are Listed Here & Can be Viewed, Edited or Deleted Based On Your Role.</p>
                            </div>
                            <a href="add-return.php" class="btn btn-primary add-list"><i class="las la-plus mr-3"></i>Add Return</a>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="table-responsive rounded mb-3">
                            <form method="GET" action="list-returns.php">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="input-group mb-3">
                                            <input type="text" name="search" class="form-control" placeholder="Search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-outline-secondary" type="submit">Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="data-table table mb-0 tbl-server-info">
                                <thead class="bg-white text-uppercase">
                                    <tr class="ligth ligth-data">
                                        <th><a
                                                href="?sortColumn=return_date&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Date</a>
                                        </th>
                                        <th><a
                                                href="?sortColumn=reference_no&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Reference
                                                No</a></th>
                                        <th><a
                                                href="?sortColumn=receiver_name&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Receiver
                                                Name</a></th>
                                        <th><a
                                                href="?sortColumn=customer_name&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Customer
                                                Name</a></th>
                                        <th><a
                                                href="?sortColumn=product_name&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Product</a>
                                        </th>
                                        <th><a
                                                href="?sortColumn=quantity&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Quantity</a>
                                        </th>
                                        <th><a
                                                href="?sortColumn=total_amount&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Total
                                                Amount</a></th>
                                        <th><a
                                                href="?sortColumn=return_status&sortOrder=<?php echo $sortOrder === 'ASC' ? 'DESC' : 'ASC'; ?>">Status</a>
                                        </th>
                                        <th>Note</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="ligth-body">
                                    <?php foreach ($returns as $return): ?>
                                        <tr>
                                            <td><?php echo $return['return_date']; ?></td>
                                            <td><?php echo $return['reference_no']; ?></td>
                                            <td><?php echo $return['receiver_name']; ?></td>
                                            <td><?php echo $return['customer_name']; ?></td>
                                            <td><?php echo $return['product_name']; ?></td>
                                            <td><?php echo $return['quantity']; ?></td>
                                            <td><?php echo $return['total_amount']; ?></td>
                                            <td><?php echo $return['return_status']; ?></td>
                                            <td><?php echo $return['return_note']; ?></td>
                                            <td>
                                                <div class="d-flex align-items-center list-action">
                                                    <a class="badge bg-success mr-2 edit-return"
                                                        data-return-id="<?php echo $return['return_id']; ?>"
                                                        data-toggle="tooltip" data-placement="top" title="Edit" href="#"><i
                                                            class="ri-pencil-line mr-0"></i></a>
                                                    <a class="badge bg-warning mr-2" data-toggle="tooltip" data-placement="top"
                                                        title="Delete"
                                                        href="actions/delete_return.php?id=<?php echo $return['return_id']; ?>"><i
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
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>&sortColumn=<?php echo $sortColumn; ?>&sortOrder=<?php echo $sortOrder; ?>">Previous</a></li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>&sortColumn=<?php echo $sortColumn; ?>&sortOrder=<?php echo $sortOrder; ?>"><?php echo $i; ?></a></li>
                                <?php endfor; ?>
                                <?php if ($page < $totalPages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo $search; ?>&sortColumn=<?php echo $sortColumn; ?>&sortOrder=<?php echo $sortOrder; ?>">Next</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <!-- End Pagination -->
                    </div>
                </div>
            </div>
            <!-- Edit Return Modal -->
            <div class="modal fade" id="editReturnModal" tabindex="-1" role="dialog" aria-labelledby="editReturnModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editReturnModalLabel">Edit Return</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form id="editReturnForm" action="actions/edit_return.php" method="POST">
                                <input type="hidden" name="return_id" id="editReturnId">
                                <div class="form-group">
                                    <label for="editCustomerName">Customer Name</label>
                                    <input type="text" class="form-control" name="customer_name" id="editCustomerName" required>
                                </div>
                                <div class="form-group">
                                    <label for="editQuantity">Quantity</label>
                                    <input type="number" class="form-control" name="quantity" id="editQuantity" required>
                                </div>
                                <div class="form-group">
                                    <label for="editTotalAmount">Total Amount</label>
                                    <input type="number" class="form-control" name="total_amount" id="editTotalAmount" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="editReturnStatus">Return Status</label>
                                    <select class="form-control" name="return_status" id="editReturnStatus" required>
                                        <option value="Rejected">Rejected</option>
                                        <option value="Refunded">Refunded</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editReturnNote">Return Note</label>
                                    <textarea class="form-control" name="return_note" id="editReturnNote" rows="3" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
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
                                            <li class="list-inline-item"><a href="">Privacy Policy</a>
                                            </li>
                                            <li class="list-inline-item"><a href="">Terms of Use</a>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 text-right">
                                        <span class="mr-1">
                                            <script>document.write(new Date().getFullYear())</script>©
                                        </span> <a href="#" class="">Halo Pharma</a>.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
            const editButtons = document.querySelectorAll('.edit-return');
        
            editButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
        
                    const returnId = this.getAttribute('data-return-id');
                    const productPrice = parseFloat(this.getAttribute('data-product-price'));
        
                    fetch('actions/get_return.php?id=' + returnId)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const returnData = data.return;
        
                                document.getElementById('editReturnId').value = returnData.return_id;
                                document.getElementById('editCustomerName').value = returnData.customer_name;
                                document.getElementById('editQuantity').value = returnData.quantity;
                                document.getElementById('editReturnStatus').value = returnData.return_status;
                                document.getElementById('editReturnNote').value = returnData.return_note;
        
                                // Calculate total amount based on quantity and price
                                const totalAmount = returnData.quantity * productPrice;
                                document.getElementById('editTotalAmount').value = totalAmount.toFixed(2);
        
                                
                                document.getElementById('editQuantity').addEventListener('input', function () {
                                    const newQuantity = parseFloat(this.value);
                                    const newTotalAmount = newQuantity * productPrice;
                                    document.getElementById('editTotalAmount').value = newTotalAmount.toFixed(2);
                                });
        
                                $('#editReturnModal').modal('show');
                            } else {
                                alert('Failed to fetch return data: ' + data.message);
                            }
                        })
                        .catch(error => console.error('Error:', error));
                });
            });
        });
        </script>
        <script src="assets/js/backend-bundle.min.js"></script>
        <script src="assets/js/table-treeview.js"></script>
        <script src="assets/js/customizer.js"></script>
        <script async src="assets/js/chart-custom.js"></script>
        <script src="assets/js/app.js"></script>
    </div>
</body>

</html>