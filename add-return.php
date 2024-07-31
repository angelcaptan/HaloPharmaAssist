<?php
require_once 'controllers/general_controller.php';

// Report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

$products = get_all_products();
$users = get_all_users();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $return_date = $_POST['return_date'];
    $reference_no = uniqid('RETURN-'); // Automatically generate a unique reference number
    $receiver_name = $_POST['receiver_name'];
    $customer_name = $_POST['customer_name'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $total_amount = $_POST['total_amount']; // This should be calculated from price * quantity
    $return_status = $_POST['return_status'];
    $return_note = $_POST['return_note'];

    $result = add_return($return_date, $reference_no, $receiver_name, $customer_name, $product_id, $quantity, $total_amount, $return_status, $return_note, $user_id);

    if ($result === true) {
        header('Location: list-returns.php');
    } else {
        echo "Error adding return: " . $result;
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
                            <h4 class="card-title">Add Return</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="add-return-form" action="actions/add_return.php" method="POST"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Date *</label>
                                        <input type="date" class="form-control" name="return_date" required>
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
                                        <label>Receiver *</label>
                                        <select name="receiver_name" class="form-control" required>
                                            <?php foreach ($users as $user): ?>
                                                <option
                                                    value="<?php echo $user['first_name'] . ' ' . $user['last_name']; ?>">
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
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Return Status *</label>
                                        <select name="return_status" class="form-control" required>
                                            <option value="Rejected">Rejected</option>
                                            <option value="Refunded">Refunded</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Return Note *</label>
                                        <textarea name="return_note" class="form-control" rows="3" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mr-2">Add Return</button>
                            <button type="reset" class="btn btn-danger">Reset</button>
                        </form>
                    </div>
                </div>
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
                        </span> <a href="#" class="">Halo Assist</a>.
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
<script>
    document.getElementById('add-product').addEventListener('click', function () {
        var productItem = document.querySelector('.product-item').cloneNode(true);
        productItem.querySelectorAll('input').forEach(input => input.value = '');
        productItem.querySelectorAll('select').forEach(select => select.value = '');
        document.getElementById('product-list').appendChild(productItem);
    });

    document.getElementById('product-list').addEventListener('input', function (event) {
        if (event.target.classList.contains('quantity-input') || event.target.classList.contains('product-select')) {
            updateTotalAmount();
        }
    });

    function updateTotalAmount() {
        let overallTotal = 0;
        document.querySelectorAll('.product-item').forEach(function (item) {
            const productSelect = item.querySelector('.product-select');
            const quantityInput = item.querySelector('.quantity-input');
            const totalAmountInput = item.querySelector('.total-amount-input');

            const price = parseFloat(productSelect.selectedOptions[0].getAttribute('data-price'));
            const quantity = parseFloat(quantityInput.value);

            const totalAmount = price * quantity;
            totalAmountInput.value = totalAmount.toFixed(2);

            overallTotal += totalAmount;
        });

        document.getElementById('overall_total_amount').value = overallTotal.toFixed(2);
    }

    document.getElementById('add-return-form').addEventListener('submit', function (event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);

        fetch(form.action, {
            method: form.method,
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'success') {
                    window.location.href = 'list-returns.php';
                }
            })
            .catch(error => {
                alert('An error occurred while processing the return.');
            });
    });
</script>
</html>