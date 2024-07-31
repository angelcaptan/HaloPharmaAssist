<?php

include_once(__DIR__.'/../controllers/general_controller.php');


function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

?>
