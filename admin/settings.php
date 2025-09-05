<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Settings';
$success_message = '';
$error_message = '';

// Handle form submission for updating settings
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['update_profile'])) {
        $full_name = sanitize_input($_POST['full_name']);
        $email = sanitize_input($_POST['email']);
        $user_id = $_SESSION['user_id'];
        
        if (!empty($full_name) && !empty($email)) {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ? WHERE user_id = ?");
            $stmt->bind_param("ssi", $full_name, $email, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Profile updated successfully!";
            } else {
                $error_message = "Error updating profile.";
            }
        }
    }
    
    if (isset($_POST['change_password'])) {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        $user_id = $_SESSION['user_id'];
        
        if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
            if ($new_password !== $confirm_password) {
                $error_message = "New passwords do not match.";
            } elseif (strlen($new_password) < 6) {
                $error_message = "New password must be at least 6 characters long.";
            } else {
                // Verify current password
                $stmt = $conn->prepare("SELECT password_hash FROM users WHERE user_id = ?");
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result->fetch_assoc();
                
                if (password_verify($current_password, $user['password_hash'])) {
                    $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt = $conn->prepare("UPDATE users SET password_hash = ? WHERE user_id = ?");
                    $update_stmt->bind_param("si", $new_hash, $user_id);
                    
                    if ($update_stmt->execute()) {
                        $success_message = "Password changed successfully!";
                    } else {
                        $error_message = "Error changing password.";
                    }
                } else {
                    $error_message = "Current password is incorrect.";
                }
            }
        } else {
            $error_message = "Please fill in all password fields.";
        }
    }
}

// Get current user info
$stmt = $conn->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user_info = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Boundless Moments Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css?family=Dosis:500|Raleway:400,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Raleway', sans-serif;
            background-color: #f8f9fa;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(180deg, #2c3e50 0%, #34495e 50%, #2c3e50 100%);
            box-shadow: 2px 0 15px rgba(0,0,0,0.2);
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%);
        }
        
        .sidebar-header h3 {
            color: white;
            font-weight: 600;
            margin: 0;
            font-size: 1.4rem;
        }
        
        .sidebar-header .logo-icon {
            font-size: 2rem;
            color: white;
            margin-bottom: 10px;
        }
        
        .sidebar-menu {
            padding: 20px 0;
        }
        
        .sidebar-menu ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            margin: 5px 15px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-weight: 500;
        }
        
        .sidebar-menu a:hover {
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar-menu a.active {
            background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%);
            box-shadow: 0 2px 10px rgba(240, 176, 22, 0.3);
            transform: translateX(5px);
        }
        
        .sidebar-menu a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        /* Top Header */
        .top-header {
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            height: 70px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }
        
        .top-header h1 {
            margin: 0;
            color: #343a40;
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: white;
            font-weight: 600;
            border-radius: 15px 15px 0 0 !important;
            border: none;
        }
        
        .btn-custom {
            background-color: #f0b016;
            border-color: #f0b016;
            color: white;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 500;
        }
        
        .btn-custom:hover {
            background-color: #d49c14;
            border-color: #d49c14;
            color: white;
        }
        
        .alert-custom {
            border-radius: 10px;
            border: none;
        }
    </style>
</head>
<body>
    <!-- Left Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <i class="fas fa-camera"></i>
            </div>
            <h3>Boundless Moments</h3>
            <small style="color: rgba(255,255,255,0.8);">Admin Panel</small>
        </div>
        
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="dashboard.php">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="manage-gallery.php">
                        <i class="fas fa-images"></i>
                        Manage Gallery
                    </a>
                </li>
                <li>
                    <a href="messages.php">
                        <i class="fas fa-envelope"></i>
                        Messages
                    </a>
                </li>
                <li>
                    <a href="bookings.php">
                        <i class="fas fa-calendar"></i>
                        Bookings
                    </a>
                </li>
                <li>
                    <a href="settings.php" class="active">
                        <i class="fas fa-cog"></i>
                        Settings
                    </a>
                </li>
                <li style="margin-top: 20px; border-top: 1px solid rgba(255,255,255,0.2); padding-top: 20px;">
                    <a href="../index.php">
                        <i class="fas fa-external-link-alt"></i>
                        View Public Site
                    </a>
                </li>
                <li>
                    <a href="../logout.php">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <h1><i class="fas fa-cog me-2" style="color: #e74c3c;"></i>Settings</h1>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success alert-custom"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger alert-custom"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>
        
        <div class="row">
            <!-- Profile Settings -->
            <div class="col-lg-6">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i>Profile Settings</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" 
                                       value="<?php echo htmlspecialchars($user_info['username']); ?>" disabled>
                                <small class="form-text text-muted">Username cannot be changed</small>
                            </div>
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($user_info['full_name'] ?? ''); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user_info['email']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="user_role" class="form-label">Role</label>
                                <input type="text" class="form-control" id="user_role" 
                                       value="<?php echo htmlspecialchars(ucfirst($user_info['user_role'])); ?>" disabled>
                            </div>
                            <button type="submit" name="update_profile" class="btn btn-custom">
                                <i class="fas fa-save me-1"></i>Update Profile
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Password Change -->
            <div class="col-lg-6">
                <div class="card card-custom">
                    <div class="card-header card-header-custom">
                        <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" 
                                       name="current_password" required>
                            </div>
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" 
                                       name="new_password" required>
                                <small class="form-text text-muted">Minimum 6 characters</small>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" 
                                       name="confirm_password" required>
                            </div>
                            <button type="submit" name="change_password" class="btn btn-custom">
                                <i class="fas fa-key me-1"></i>Change Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Account Information -->
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Account Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Account Created:</strong> <?php echo date('M d, Y', strtotime($user_info['created_at'])); ?></p>
                        <p><strong>Last Login:</strong> 
                            <?php 
                            if ($user_info['last_login']) {
                                echo date('M d, Y H:i', strtotime($user_info['last_login']));
                            } else {
                                echo 'Never';
                            }
                            ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Account Status:</strong> 
                            <?php if ($user_info['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Inactive</span>
                            <?php endif; ?>
                        </p>
                        <p><strong>User ID:</strong> #<?php echo $user_info['user_id']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>