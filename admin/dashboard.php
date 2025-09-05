<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Dashboard';

// Get statistics
$total_images = $conn->query("SELECT COUNT(*) as count FROM gallery_images")->fetch_assoc()['count'];
$total_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];
$unread_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = FALSE")->fetch_assoc()['count'];
$total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];

// Get recent messages
$recent_messages = $conn->query("SELECT * FROM contact_messages ORDER BY submission_date DESC LIMIT 5");
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
        
        .sidebar-footer {
            position: absolute;
            bottom: 20px;
            left: 15px;
            right: 15px;
        }
        
        .user-info {
            background-color: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 10px;
            color: white;
            text-align: center;
        }
        
        .user-info .user-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .user-info .user-role {
            font-size: 0.9rem;
            opacity: 0.8;
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
        
        .top-header .header-actions {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .top-header .btn-outline-primary {
            border-color: #f0b016;
            color: #f0b016;
        }
        
        .top-header .btn-outline-primary:hover {
            background-color: #f0b016;
            border-color: #f0b016;
        }
        
        /* Main Content */
        .main-content {
            margin-left: 250px;
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }
        
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(240, 176, 22, 0.1);
            transition: transform 0.3s ease;
            border-left: 4px solid #f0b016;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(240, 176, 22, 0.2);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #f0b016;
            font-family: 'Dosis', sans-serif;
        }
        .table-custom {
            background-color: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .table-custom thead {
            background-color: #f0b016;
            color: white;
        }
        .btn-custom {
            background-color: #f0b016;
            border-color: #f0b016;
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 14px;
        }
        .btn-custom:hover {
            background-color: #d49c14;
            border-color: #d49c14;
            color: white;
        }
        .badge-custom {
            background-color: #f0b016;
            color: white;
        }
        .card-header-custom {
            background-color: #f0b016;
            color: white;
            font-weight: 600;
            border-radius: 15px 15px 0 0 !important;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .top-header {
                left: 0;
            }
            .main-content {
                margin-left: 0;
            }
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
                    <a href="dashboard.php" class="active">
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
                    <a href="manage-experience.php">
                        <i class="fas fa-user-tie"></i>
                        Experience & Skills
                    </a>
                </li>
                <li>
                    <a href="messages.php">
                        <i class="fas fa-envelope"></i>
                        Messages
                        <?php if($unread_messages > 0): ?>
                        <span class="badge bg-danger ms-auto"><?php echo $unread_messages; ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a href="bookings.php">
                        <i class="fas fa-calendar"></i>
                        Bookings
                    </a>
                </li>
                <li>
                    <a href="settings.php">
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
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-name">
                    <i class="fas fa-user-circle me-2"></i>
                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                </div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <h1><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h1>
        <div class="header-actions">
            <button class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Quick Add
            </button>
            <button class="btn btn-primary btn-sm" style="background-color: #f0b016; border-color: #f0b016;">
                <i class="fas fa-bell me-1"></i>
                Notifications
                <?php if($unread_messages > 0): ?>
                <span class="badge bg-danger ms-1"><?php echo $unread_messages; ?></span>
                <?php endif; ?>
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-images fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Total Images</h6>
                        <div class="stat-number"><?php echo $total_images; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-envelope fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Total Messages</h6>
                        <div class="stat-number"><?php echo $total_messages; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-bell fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Unread Messages</h6>
                        <div class="stat-number"><?php echo $unread_messages; ?></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card dashboard-card">
                    <div class="card-body text-center">
                        <i class="fas fa-calendar fa-2x text-muted mb-3"></i>
                        <h6 class="text-muted">Total Bookings</h6>
                        <div class="stat-number"><?php echo $total_bookings; ?></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Messages -->
        <div class="card table-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Recent Messages</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($recent_messages && $recent_messages->num_rows > 0) {
                                while($message = $recent_messages->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($message['sender_name']); ?></td>
                                        <td><?php echo htmlspecialchars($message['sender_email']); ?></td>
                                        <td><?php echo htmlspecialchars($message['subject'] ?? 'No subject'); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($message['submission_date'])); ?></td>
                                        <td>
                                            <?php if ($message['is_read']): ?>
                                                <span class="badge bg-success">Read</span>
                                            <?php else: ?>
                                                <span class="badge badge-custom">Unread</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="view-message.php?id=<?php echo $message['message_id']; ?>" class="btn btn-custom btn-sm">View</a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center py-4">No messages yet</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Admin Footer -->
        <footer class="mt-4 py-3" style="background-color: white; border-top: 1px solid #dee2e6;">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">&copy; 2024 Boundless Moments Admin Panel. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 text-end">
                    <small class="text-muted">Last login: <?php echo date('M d, Y H:i'); ?></small>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Debug sidebar navigation
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarLinks = document.querySelectorAll('.sidebar-menu a');
            sidebarLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    console.log('Sidebar link clicked:', this.href);
                    console.log('Link text:', this.textContent.trim());
                    
                    // Don't prevent default, let it navigate
                    // This is just for debugging
                });
            });
        });
    </script>
</body>
</html>