<?php
/**
 * API: Products CRUD
 */
require_once __DIR__ . '/../../admin/includes/auth_check.php';
require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

$input  = json_decode(file_get_contents('php://input'), true) ?? [];
if (empty($input) && !empty($_POST)) {
    $input = $_POST;
}
$action = $input['action'] ?? $_GET['action'] ?? 'list';

try {
    switch ($action) {
        case 'list':
            $stmt = $pdo->query("SELECT * FROM products ORDER BY name ASC");
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll()]);
            break;

        case 'create':
            $name = trim($input['name'] ?? '');
            $price = (float)($input['price'] ?? 0);
            $stock = (int)($input['stock'] ?? 0);
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // save uploaded file
                $filename = time() . '_' . basename($_FILES['image']['name']);
                $target = __DIR__ . '/../../images/products/';
                if (!is_dir($target)) @mkdir($target, 0777, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target . $filename)) {
                    $image = '/lms1025edu/images/products/' . $filename;
                }
            } else {
                $image = trim($input['image'] ?? '');
            }
            $description = trim($input['description'] ?? '');

            if (!$name) throw new Exception('Tên sản phẩm là bắt buộc.');

            $stmt = $pdo->prepare("INSERT INTO products (name, price, stock, image, description, created_at) VALUES (?,?,?,?,?, NOW())");
            $stmt->execute([$name, $price, $stock, $image, $description]);
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        case 'update':
            $id = (int)($input['id'] ?? 0);
            $name = trim($input['name'] ?? '');
            $price = (float)($input['price'] ?? 0);
            $stock = (int)($input['stock'] ?? 0);
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                // save uploaded file
                $filename = time() . '_' . basename($_FILES['image']['name']);
                $target = __DIR__ . '/../../images/products/';
                if (!is_dir($target)) @mkdir($target, 0777, true);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target . $filename)) {
                    $image = '/lms1025edu/images/products/' . $filename;
                }
            } else {
                $image = trim($input['image'] ?? '');
            }
            $description = trim($input['description'] ?? '');

            if (!$id || !$name) throw new Exception('Dữ liệu không hợp lệ.');

            if ($image !== '') {
                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, stock=?, image=?, description=? WHERE id=?");
                $stmt->execute([$name, $price, $stock, $image, $description, $id]);
            } else {
                $stmt = $pdo->prepare("UPDATE products SET name=?, price=?, stock=?, description=? WHERE id=?");
                $stmt->execute([$name, $price, $stock, $description, $id]);
            }
            echo json_encode(['success' => true]);
            break;

        case 'delete':
            $id = (int)($input['id'] ?? 0);
            if (!$id) throw new Exception('ID là bắt buộc');
            $pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
            echo json_encode(['success' => true]);
            break;

        default:
            throw new Exception('Action không hợp lệ');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
