<?php
require_once 'controllers/general_controller.php';


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
                        <h4 class="mb-3">Emergency Enquiries </h4>
                        <p class="mb-0">This Page Will Allow Users to Quickly send Complaints, Inquiries, or Updates to the Managers via Email</p>
                    </div>
                </div>
                <form action="actions/emergency_send.php" method="post">
                    <label for="manager">Select Manager:</label>
                    <select id="manager" name="manager" required>
                        <option value="">Select Manager</option>
                        <?php include 'actions/get_man_email.php'; ?>
                    </select>
                        
                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>
                
                    <label for="message">Message:</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                
                    <button type="submit">Send</button>
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
<!-- Backend Bundle JavaScript -->
<script src="assets/js/backend-bundle.min.js"></script>

<script src="assets/js/table-treeview.js"></script>

<script src="assets/js/customizer.js"></script>

<script async src="assets/js/chart-custom.js"></script>

<script src="assets/js/app.js"></script>


</body>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        input, textarea, select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        button {
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #32BDEA;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #28a0d4;
        }
</style>
</html>


