<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require __DIR__ . '/db.php';
$action = $_GET['action'] ?? $_POST['action'] ?? '';
function require_admin() {
  if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['ok'=>false,'error'=>'دسترسی غیرمجاز'], JSON_UNESCAPED_UNICODE);
    exit;
  }
}
switch ($action) {
  case 'login':
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $mysqli->prepare('SELECT id, username, password_hash, role FROM users WHERE username = ? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($row = $res->fetch_assoc()) {
      if (password_verify($password, $row['password_hash'])) {
        $_SESSION['user'] = ['id'=>$row['id'],'username'=>$row['username'],'role'=>$row['role']];
        echo json_encode(['ok'=>true,'user'=>$_SESSION['user']], JSON_UNESCAPED_UNICODE);
      } else {
        echo json_encode(['ok'=>false,'error'=>'رمز عبور نادرست است'], JSON_UNESCAPED_UNICODE);
      }
    } else {
      echo json_encode(['ok'=>false,'error'=>'کاربر یافت نشد'], JSON_UNESCAPED_UNICODE);
    }
    break;
  case 'logout':
    session_destroy();
    echo json_encode(['ok'=>true]);
    break;
  case 'add_employee':
    require_admin();
    $full_name = $_POST['full_name'] ?? '';
    $personnel_code = $_POST['personnel_code'] ?? '';
    $national_id = $_POST['national_id'] ?? '';
    $join_date = $_POST['join_date'] ?? '';
    $position = $_POST['position'] ?? '';
    $department = $_POST['department'] ?? '';
    $stmt = $mysqli->prepare('INSERT INTO employees (full_name, personnel_code, national_id, join_date, position, department) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->bind_param('ssssss', $full_name, $personnel_code, $national_id, $join_date, $position, $department);
    if ($stmt->execute()) {
      echo json_encode(['ok'=>true,'id'=>$stmt->insert_id], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['ok'=>false,'error'=>$stmt->error], JSON_UNESCAPED_UNICODE);
    }
    break;
  case 'list_employees':
    $res = $mysqli->query('SELECT * FROM employees ORDER BY id DESC');
    echo json_encode(['ok'=>true,'data'=>$res->fetch_all(MYSQLI_ASSOC)], JSON_UNESCAPED_UNICODE);
    break;
  case 'get_ticker':
    $res = $mysqli->query('SELECT * FROM ticker WHERE id = 1');
    echo json_encode(['ok'=>true,'data'=>$res->fetch_assoc()], JSON_UNESCAPED_UNICODE);
    break;
  default:
    echo json_encode(['ok'=>false,'error'=>'اقدام نامعتبر'], JSON_UNESCAPED_UNICODE);
}