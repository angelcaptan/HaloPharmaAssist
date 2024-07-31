<?php
//report all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__."/settings/core.php");

// Fetch categories
$categories = get_all_categories_without_pagination();
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
                                <h4 class="card-title">Add Product</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="actions/add_product.php" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name" required>
                                </div>
                                <div class="form-group">
                                    <label for="category">Category</label>
                                    <select class="form-control" id="category" name="category_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="price">Price</label>
                                    <input type="text" class="form-control" id="price" name="price" required>
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
                                    <input type="" class="form-control" id="total_amount" name="total_amount" required>
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
            <!-- Page end  -->
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
                            <span class="mr-1"><script>document.write(new Date().getFullYear())</script>©</span> <a href="#" class="">Halo Pharma Assist</a>.
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

    <script>
        document.getElementById('price').addEventListener('input', calculateTotalAmount);
        document.getElementById('quantity').addEventListener('input', calculateTotalAmount);

        function calculateTotalAmount() {
            var price = parseFloat(document.getElementById('price').value);
            var quantity = parseInt(document.getElementById('quantity').value);
            if (!isNaN(price) && !isNaN(quantity)) {
                var totalAmount = price * quantity;
                document.getElementById('total_amount').value = totalAmount.toFixed(2);
            } else {
                document.getElementById('total_amount').value = '';
            }
        }
    </script>
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
</body>
</html>