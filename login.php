<?php
// Start session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'includes/db_connect.php';

$error_message = '';
$success_message = '';

// Check if user is already logged in
if (isLoggedIn()) {
    header('Location: admin/dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $stmt = $conn->prepare("SELECT user_id, username, password_hash, user_role, full_name FROM users WHERE username = ? AND is_active = TRUE");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password_hash'])) {
                // Login successful - regenerate session ID only if session is active
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_regenerate_id(true);
                }
                
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['user_role'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['login_time'] = time();
                
                // Update last login
                $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
                $update_stmt->bind_param("i", $user['user_id']);
                $update_stmt->execute();
                $update_stmt->close();
                
                header('Location: admin/dashboard.php');
                exit();
            } else {
                $error_message = 'Invalid username or password.';
            }
        } else {
            $error_message = 'Invalid username or password.';
        }
        $stmt->close();
    }
}

$page_title = 'Login';
?>

<?php include 'includes/header.php'; ?>

<section class="contact_section layout_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div style="background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h2 style="color: #f0b016; font-weight: bold;">Login</h2>
                    </div>
                    <?php if (!empty($error_message)): ?>
                        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
                            <?php echo htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div style="margin-bottom: 25px;">
                            <input type="text" name="username" placeholder="Username" 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="margin-bottom: 25px;">
                            <input type="password" name="password" placeholder="Password" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="text-align: center;">
                            <button type="submit" style="padding: 15px 65px; outline: none; border: none; border-radius: 30px; color: #fff; background: #f0b016; margin: auto; margin-top: 15px; font-size: 15px; cursor: pointer;">
                                LOGIN
                            </button>
                        </div>
                    </form>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <small style="color: #666;">
                            Don't have an account? <a href="register.php" style="color: #f0b016; text-decoration: none;">Register here</a>
                        </small>
                    </div>
                    
                    <div style="text-align: center; margin-top: 15px; padding: 15px; background-color: #f0f0f0; border-radius: 10px;">
                        <small style="color: #555;">
                            <strong>Demo Login:</strong><br>
                            Username: <strong>admin</strong><br>
                            Password: <strong>admin123</strong>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>