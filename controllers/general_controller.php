<?php
// Connect to the user account class
require_once (__DIR__ . "/../classes/general_class.php");


function register_user_ctr($first_name, $last_name, $email, $phone, $role, $gender, $password, $confirm_password)
{
    $register = new general_class();
    return $register->registerUser($first_name, $last_name, $email, $phone, $role, $gender, $password, $confirm_password);
}

function add_product($product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id)
{
    $general = new general_class();
    return $general->addProduct($product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id);
}


function add_category($category_name, $user_id)
{
    $general = new general_class();
    return $general->addCategory($category_name, $user_id);
}

function add_sale($sale_date, $reference_no, $biller_id, $customer_name, $product_id, $quantity, $total_amount, $sale_status, $payment_status, $sale_note, $user_id)
{
    $general = new general_class();
    return $general->addSale($sale_date, $reference_no, $biller_id, $customer_name, $product_id, $quantity, $total_amount, $sale_status, $payment_status, $sale_note, $user_id);
}


function add_return($return_date, $reference_no, $receiver_name, $customer_name, $product_id, $quantity, $total_amount, $return_status, $return_note, $user_id)
{
    $general = new general_class();
    return $general->addReturn($return_date, $reference_no, $receiver_name, $customer_name, $product_id, $quantity, $total_amount, $return_status, $return_note, $user_id);
}



function delete_product_ctr($product_id, $user_id)
{
    $product = new general_class();
    return $product->deleteProduct($product_id, $user_id);
}


function delete_category($category_id, $user_id)
{
    $category = new general_class();
    return $category->deleteCategory($category_id, $user_id);
}


function delete_sale($sale_id, $user_id)
{
    $general = new general_class();
    return $general->deleteSale($sale_id, $user_id);
}


function delete_return_ctr($return_id, $user_id)
{
    $return = new general_class();
    return $return->deleteReturn($return_id, $user_id);
}


function delete_user($user_id, $admin_id)
{
    $general = new general_class();
    return $general->delete_user($user_id, $admin_id);
}


function get_all_users()
{
    $general = new general_class();
    return $general->getAllUsers();
}

function get_users()
{
    $general = new general_class();
    return $general->getUsers();
}

function get_user_by_id($user_id)
{
    $general = new general_class();
    return $general->get_user_by_id($user_id);
}

function update_user($user_id, $first_name, $last_name, $email, $phone, $role, $gender)
{
    $general = new general_class();
    return $general->update_user($user_id, $first_name, $last_name, $email, $phone, $role, $gender);
}



function get_sale_by_id($sale_id)
{
    $general = new general_class();
    return $general->getSaleById($sale_id);
}



function get_all_sales_filtered_sorted($filterStatus = '', $sortColumn = 'sale_date', $sortOrder = 'ASC', $searchQuery = '', $limit = 10, $offset = 0)
{
    $general_class = new general_class();
    return $general_class->getAllSalesFilteredSorted($filterStatus, $sortColumn, $sortOrder, $searchQuery, $limit, $offset);
}

function get_total_sales($filterStatus = '', $searchQuery = '')
{
    $general_class = new general_class();
    return $general_class->getTotalSales($filterStatus, $searchQuery);
}

function get_return_by_id($return_id)
{
    $general = new general_class();
    return $general->getReturnById($return_id);
}

function get_all_products()
{
    $general = new general_class();
    return $general->getAllProducts();
}

function get_all_returns_filtered_sorted($sortColumn = 'return_date', $sortOrder = 'ASC', $filter = [], $limit = 10, $offset = 0)
{
    $general = new general_class();
    return $general->getAllReturnsFilteredSorted($sortColumn, $sortOrder, $filter, $limit, $offset);
}

function get_total_returns($filter = [])
{
    $general = new general_class();
    return $general->getTotalReturns($filter);
}

function update_return($return_id, $customer_name, $quantity, $total_amount, $return_status, $return_note, $user_id)
{
    $general = new general_class();
    return $general->updateReturn($return_id, $customer_name, $quantity, $total_amount, $return_status, $return_note, $user_id);
}




function get_all_products_with_category()
{
    $product = new general_class();
    return $product->getAllProductsWithCategory();
}

function get_category_by_id($category_id)
{
    $general = new general_class();
    return $general->getCategoryById($category_id);
}

function get_all_categories()
{
    $category = new general_class();
    return $category->getAllCategories();
}

function get_all_categories_without_pagination()
{
    $category = new general_class();
    return $category->getAllCategoriesWithoutPagination();
}



//--UPDATE--//
function update_product($product_id, $product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id)
{
    $general = new general_class();
    return $general->updateProduct($product_id, $product_name, $category_id, $price, $quantity, $expiry_date, $total_amount, $image, $user_id);
}



function archive_category($category_id, $user_id)
{
    $general = new general_class();
    return $general->archiveCategory($category_id, $user_id);
}





function update_category($category_id, $category_name, $user_id)
{
    $category = new general_class();
    return $category->updateCategory($category_id, $category_name, $user_id);
}




function update_sale($sale_id, $sale_date, $customer_name, $total_amount, $sale_status, $payment_status, $sale_note, $admin_id)
{
    $general = new general_class();
    return $general->updateSale($sale_id, $sale_date, $customer_name, $total_amount, $sale_status, $payment_status, $sale_note, $admin_id);
}



function getProductsByIds($product_ids)
{
    $general = new general_class();
    return $general->getProductsByIds($product_ids);
}

function get_supplier_details()
{
    $class = new general_class();
    return $class->get_supplier_details();
}



function get_soon_to_finish_or_expire_medications()
{
    $general = new general_class();
    return $general->getSoonToFinishOrExpireMedications();
}

//--SELECT--//
function signin_user_ctr($email, $password)
{
    $general = new general_class();
    $result = $general->signinUser($email, $password);
    if (is_array($result)) {
        return $result;
    } else {
        return false;
    }
}





//--Stock Management--//

function get_low_stock_products_with_category()
{
    $general_class = new general_class();
    return $general_class->get_low_stock_products_with_category();
}

function get_soon_to_expire_products_with_category()
{
    $general_class = new general_class();
    return $general_class->get_soon_to_expire_products_with_category();
}



function updateProductQuantity($product_id, $quantity_change)
{
    $general = new general_class();
    return $general->updateProductQuantity($product_id, $quantity_change);
}

function update_product_quantity_for_return($product_id, $quantity_change)
{
    $general = new general_class();
    return $general->updateProductQuantityForReturn($product_id, $quantity_change);
}

function archive_product_ctr($product_id, $user_id)
{
    $product = new general_class();
    return $product->archiveProduct($product_id, $user_id);
}



// Chart Functions
function get_customers_count($timeframe)
{
    $general = new general_class();
    return $general->getCustomersCount($timeframe);
}

function get_products_sold_count($timeframe)
{
    $general = new general_class();
    return $general->getProductsSoldCount($timeframe);
}


function log_action($user_id, $action, $table_name, $record_id)
{
    $general = new general_class();
    return $general->log_action($user_id, $action, $table_name, $record_id);
}

function get_audit_logs($start_date = null, $end_date = null)
{
    $general = new general_class();
    return $general->get_audit_logs($start_date, $end_date);
}

function get_user_info($user_id)
{
    $general = new general_class();
    return $general->getUserInfo($user_id);
}

// Password Reset Functions
function getUserByEmail($email)
{
    $general = new general_class();
    return $general->getUserByEmail($email);
}

function storePasswordResetToken($user_id, $token, $expiry)
{
    $general = new general_class();
    return $general->storePasswordResetToken($user_id, $token, $expiry);
}

function validatePasswordResetToken($token)
{
    $general = new general_class();
    return $general->validatePasswordResetToken($token);
}

function resetPassword($user_id, $password_hashed)
{
    $general = new general_class();
    return $general->resetPassword($user_id, $password_hashed);
}

?>