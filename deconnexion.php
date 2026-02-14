<?php

require_once __DIR__ . "/../config/auth.php";
session_destroy();
header("Location: /ashop/public/login.php");
exit;

?>