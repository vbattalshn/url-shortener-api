<?php
!isset($include) ? die(json_encode(array("success" => false, "message" => "Access denied"))) : null;

// Fill according to your sql information
define("DB_NAME", "");
define("DB_USER", "");
define("DB_PASSWORD", "");
define("DB_HOST", "");
define("DB_CHARSET", "UTF8");

session_set_cookie_params("", "", false, true);
session_start();
ob_start();
