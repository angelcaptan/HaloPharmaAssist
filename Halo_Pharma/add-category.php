
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
                                    <h4 class="card-title">Add Category</h4>
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="actions/add_category.php" method="POST" data-toggle="validator">
                                    <div class="row">                                
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Category Name *</label>
                                                <input type="text" class="form-control" name="category_name" placeholder="Enter Category Name" required>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>
                                    </div>                            
                                    <button type="submit" class="btn btn-primary mr-2">Add Category</button>
                                    <button type="reset" class="btn btn-danger">Reset</button>
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
  </body>

</html>