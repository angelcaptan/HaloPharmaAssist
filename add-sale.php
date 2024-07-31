<?php
require_once 'controllers/general_controller.php';


//report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);



$products = get_all_products();
$users = get_all_users(); 


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sale_date = $_POST['sale_date'];
    $reference_no = uniqid('SALE-'); // Automatically generate a unique reference number
    $biller_id = $_POST['biller_id'];
    $customer_name = $_POST['customer_name'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_amount = $_POST['total_amount']; // This should be calculated from price * quantity
    $sale_status = $_POST['sale_status'];
    $payment_status = $_POST['payment_status'];
    $sale_note = $_POST['sale_note'];

    $result = add_sale($sale_date, $reference_no, $biller_id, $customer_name, $product_id, $quantity, $total_amount, $sale_status, $payment_status, $sale_note, $user_id);

    if ($result === true) {
        header('Location: ../list-sales.php');
    } else {
        echo "Error adding sale: " . $result;
    }
}
?>

<!doctype html>
<html lang="en">

<?php include 'navigation.php'; ?>
<div class="content-page">
    <div class="container-fluid add-form-list">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="header-title">
                            <h4 class="card-title">Add Sale</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="actions/add_sale.php" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date *</label>
                                        <input type="date" class="form-control" name="sale_date" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Reference No *</label>
                                        <input type="text" class="form-control" name="reference_no"
                                            value="<?php echo 'REF-' . time(); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Biller *</label>
                                        <select name="biller_id" class="form-control" required>
                                            <?php foreach ($users as $user): ?>
                                                <option value="<?php echo $user['user_id']; ?>">
                                                    <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer *</label>
                                        <input type="text" class="form-control" name="customer_name"
                                            placeholder="Enter Customer Name" required>
                                    </div>
                                </div>
                            </div>

                            <div id="product-list">
                                <div class="row product-item">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Product *</label>
                                            <select name="product_id[]" class="form-control product-select" required>
                                                <?php foreach ($products as $product): ?>
                                                    <option value="<?php echo $product['product_id']; ?>"
                                                        data-price="<?php echo $product['price']; ?>">
                                                        <?php echo $product['product_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Quantity *</label>
                                            <input type="number" class="form-control quantity-input" name="quantity[]"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Total Amount *</label>
                                            <input type="number" class="form-control total-amount-input"
                                                name="total_amount[]" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-secondary" id="add-product">Add Another
                                Product</button>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Overall Total *</label>
                                        <input type="number" class="form-control" id="overall_total_amount"
                                            name="overall_total_amount" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sale Status *</label>
                                        <select name="sale_status" class="form-control" required>
                                            <option value="Completed">Completed</option>
                                            <option value="Pending">Pending</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Status *</label>
                                        <select name="payment_status" class="form-control" required>
                                            <option value="Pending">Pending</option>
                                            <option value="Due">Due</option>
                                            <option value="Paid">Paid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Sale Note *</label>
                                        <textarea name="sale_note" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Add Sale</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
<script>
    document.addEventListener('DOMContentLoaded', function () {
        function updateTotalAmount() {
            const productItems = document.querySelectorAll('.product-item');
            let overallTotal = 0;

            productItems.forEach(function (item) {
                const quantity = parseFloat(item.querySelector('.quantity-input').value);
                const price = parseFloat(item.querySelector('.product-select').selectedOptions[0].getAttribute('data-price'));
                const totalAmount = quantity * price;
                item.querySelector('.total-amount-input').value = totalAmount.toFixed(2);
                overallTotal += totalAmount;
            });

            document.getElementById('overall_total_amount').value = overallTotal.toFixed(2);
        }

        document.getElementById('product-list').addEventListener('input', updateTotalAmount);

        document.getElementById('add-product').addEventListener('click', function () {
            const productItem = document.querySelector('.product-item');
            const newProductItem = productItem.cloneNode(true);
            newProductItem.querySelectorAll('input').forEach(input => input.value = '');
            document.getElementById('product-list').appendChild(newProductItem);
            updateTotalAmount();
        });
    });
</script>
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
                        </span> <a href="#" class="">Halo Assist</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
</html>