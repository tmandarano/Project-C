<?php

$data = file_get_contents("php://input");
error_log($data);
error_log($data["username"]);
error_log($_POST["username"]);
error_log($_GET["username"]);
error_log($_SERVER["username"]);
error_log('----------------------');

?>

test