<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once (__DIR__ . "/settings/core.php");

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$general = new general_class();

$categories = $general->getAllCategories($limit, $offset);
$totalCategories = $general->getTotalCategories();
$totalPages = ceil($totalCategories / $limit);
?>

<!doctype html>
<html lang="en">

<?php include 'navigation.php'; ?>
<div class="content-page">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
                    <h4 class="mb-3">Category List</h4>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#addCategoryModal">Add
                        Category</a>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="table-responsive rounded mb-3">
                    <table class="table mb-0 tbl-server-info">
                        <thead class="bg-white text-uppercase">
                            <tr class="ligth ligth-data">
                                <th>Category Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody class="ligth-body">
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo $category['category_name']; ?></td>
                                    <td>
                                        <a href='#' class='badge badge-info' data-toggle='modal'
                                            data-target='#editCategoryModal'
                                            data-id='<?php echo $category['category_id']; ?>'
                                            data-name='<?php echo $category['category_name']; ?>'>Edit</a>
                                        <a href='actions/delete_category.php?id=<?php echo $category['category_id']; ?>'
                                            class='badge badge-danger'>Delete</a>
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
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                            </li>
                        <?php endif; ?>
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>"><a class="page-link"
                                    href="?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                        <?php endfor; ?>
                        <?php if ($page < $totalPages): ?>
                            <li class="page-item"><a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a></li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <!-- End Pagination -->
            </div>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="actions/add_category.php" method="POST">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <form action="actions/edit_category.php" method="POST">
                    <input type="hidden" id="edit_category_id" name="category_id">
                    <div class="form-group">
                        <label for="edit_category_name">Category Name</label>
                        <input type="text" class="form-control" id="edit_category_name" name="category_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        $('#editCategoryModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var categoryId = button.data('id');
            var categoryName = button.data('name');

            var modal = $(this);
            modal.find('.modal-body #edit_category_id').val(categoryId);
            modal.find('.modal-body #edit_category_name').val(categoryName);
        });
    });
</script>

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

<!-- Backend Bundle JavaScript -->
<script src="assets/js/backend-bundle.min.js"></script>

<script src="assets/js/table-treeview.js"></script>

<script src="assets/js/customizer.js"></script>

<script async src="assets/js/chart-custom.js"></script>

<script src="assets/js/app.js"></script>
</body>

</html>