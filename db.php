<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'employee_portal';
$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
  http_response_code(500);
  die(json_encode(['ok'=>false,'error'=>'DB connection failed: '.$mysqli->connect_error], JSON_UNESCAPED_UNICODE));
}
$mysqli->set_charset('utf8mb4');
?>