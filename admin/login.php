<?php
define('APP_ROOT', dirname(__DIR__));
require_once APP_ROOT . '/app/config.php';
require_once APP_ROOT . '/app/Database.php';
require_once APP_ROOT . '/app/Auth.php';
require_once APP_ROOT . '/app/CSRF.php';

// Redirect if already logged in
if (Auth::check()) {
    header('Location: ' . base_url('admin/'));
    exit;
}

$error = null;

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Basic validation
    if (empty($username) || empty($password)) {
        $error = 'Vă rugăm să completați toate câmpurile.';
    } else {
        if (Auth::login($username, $password)) {
            header('Location: ' . base_url('admin/'));
            exit;
        } else {
            $error = 'Nume de utilizator sau parolă incorectă.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0d1b2a 0%, #1b263b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e0e1dd;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        h1 {
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            color: #fff;
        }
        
        .subtitle {
            color: #a8a8b3;
            margin-bottom: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            color: #fff;
            font-size: 1rem;
            transition: all 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #9d4edd;
            background: rgba(255, 255, 255, 0.15);
        }
        
        button {
            width: 100%;
            padding: 0.875rem;
            background: linear-gradient(135deg, #9d4edd 0%, #7b2cbf 100%);
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(157, 78, 221, 0.4);
        }
        
        button:active {
            transform: translateY(0);
        }
        
        .error {
            background: rgba(220, 53, 69, 0.2);
            border: 1px solid rgba(220, 53, 69, 0.5);
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            color: #ff6b6b;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Admin Panel</h1>
        <p class="subtitle">3 Sud Est</p>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label for="username">Nume utilizator</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            
            <div class="form-group">
                <label for="password">Parolă</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit">Autentificare</button>
        </form>
    </div>
</body>
</html>
