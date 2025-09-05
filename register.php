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
    $email = sanitize_input($_POST['email']);
    $full_name = sanitize_input($_POST['full_name']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validation
    if (empty($username) || empty($email) || empty($full_name) || empty($password) || empty($confirm_password)) {
        $error_message = 'Please fill in all fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } else {
        // Check if username or email already exists
        $check_stmt = $conn->prepare("SELECT user_id FROM users WHERE username = ? OR email = ?");
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();
        
        if ($check_result->num_rows > 0) {
            $error_message = 'Username or email already exists.';
        } else {
            // Hash password and insert new user
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_stmt = $conn->prepare("INSERT INTO users (username, email, full_name, password_hash, user_role) VALUES (?, ?, ?, ?, 'client')");
            $insert_stmt->bind_param("ssss", $username, $email, $full_name, $password_hash);
            
            if ($insert_stmt->execute()) {
                $success_message = 'Registration successful! You can now log in.';
            } else {
                $error_message = 'Registration failed. Please try again.';
            }
            $insert_stmt->close();
        }
        $check_stmt->close();
    }
}

$page_title = 'Register';
?>

<?php include 'includes/header.php'; ?>

<section class="contact_section layout_padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div style="background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
                    <div style="text-align: center; margin-bottom: 30px;">
                        <h2 style="color: #f0b016; font-weight: bold;">Register</h2>
                    </div>
                    
                    <?php if (!empty($error_message)): ?>
                        <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin-bottom: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($success_message)): ?>
                        <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
                            <?php echo htmlspecialchars($success_message); ?>
                            <div style="margin-top: 15px; text-align: center;">
                                <a href="login.php" style="padding: 10px 30px; background-color: #f0b016; color: white; border-radius: 25px; text-decoration: none; font-weight: bold;">Go to Login</a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" id="registerForm">
                        <div style="margin-bottom: 25px;">
                            <input type="text" name="full_name" placeholder="Full Name" 
                                   value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="margin-bottom: 25px;">
                            <input type="text" name="username" placeholder="Username" 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="margin-bottom: 25px;">
                            <input type="email" name="email" placeholder="Email" 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="margin-bottom: 25px;">
                            <input type="password" name="password" placeholder="Password (min 6 characters)" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="margin-bottom: 25px;">
                            <input type="password" name="confirm_password" placeholder="Confirm Password" 
                                   style="width: 100%; border: none; height: 50px; padding-left: 25px; background-color: #f8f8f8; outline: none; color: #101010; border-radius: 50px;" required>
                        </div>
                        
                        <div style="text-align: center;">
                            <button type="submit" style="padding: 15px 65px; outline: none; border: none; border-radius: 30px; color: #fff; background: #f0b016; margin: auto; margin-top: 15px; font-size: 15px; cursor: pointer;">
                                REGISTER
                            </button>
                        </div>
                    </form>
                    
                    <div style="text-align: center; margin-top: 20px;">
                        <small style="color: #666;">
                            Already have an account? <a href="login.php" style="color: #f0b016; text-decoration: none;">Login here</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
// Client-side password confirmation validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        alert('Passwords do not match!');
    }
});
</script>

<?php include 'includes/footer.php'; ?>