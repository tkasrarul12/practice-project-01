<?php
header('Content-Type: application/json');


// 1. Database Configuration 

$host     = getenv('DB_HOST') ?: 'condb';
$db_name  = getenv('DB_DATABASE') ?: 'testing';
$username = getenv('DB_USERNAME') ?: 'root';
$password = getenv('DB_PASSWORD') ?: '12345';

try {
    
    $pdo = new PDO("mysql:host={$host};dbname={$db_name};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    
    $tableQuery = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(150) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB;";
    $pdo->exec($tableQuery);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status'  => 'error',
        'message' => 'Database Connection Failed: ' . $e->getMessage()
    ]);
    exit;
}

// 2. Auth Logic (Signup & Login API)

$data   = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';

if ($action === 'signup') {
    $fullName = trim($data['full_name'] ?? '');
    $email    = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $data['password'] ?? '';

    if (empty($fullName) || !$email || strlen($password) < 6) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid inputs or password too short (min 6 chars).']);
        exit;
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Email is already registered.']);
        exit;
    }

    // Secure Password Hashing
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $insertStmt     = $pdo->prepare("INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)");
    
    if ($insertStmt->execute([$fullName, $email, $hashedPassword])) {
        echo json_encode(['status' => 'success', 'message' => 'Account created successfully!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create account.']);
    }

} elseif ($action === 'login') {
    $email    = filter_var(trim($data['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $password = $data['password'] ?? '';

    if (!$email || empty($password)) {
        echo json_encode(['status' => 'error', 'message' => 'Please provide valid email and password.']);
        exit;
    }

    // Fetch user details
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        echo json_encode([
            'status'  => 'success',
            'message' => 'Login successful!',
            'user'    => [
                'name'  => $user['full_name'],
                'email' => $user['email']
            ]
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request action.']);
}
?>
