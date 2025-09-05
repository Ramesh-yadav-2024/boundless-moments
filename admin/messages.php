<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Messages';

// Initialize variables
$total_messages = 0;
$unread_messages = 0;
$messages_result = null;

try {
    // Check if table exists, create it if it doesn't
    $table_check = $conn->query("SHOW TABLES LIKE 'contact_messages'");
    
    if ($table_check->num_rows == 0) {
        // Create contact_messages table
        $create_table = "CREATE TABLE contact_messages (
            message_id INT AUTO_INCREMENT PRIMARY KEY,
            sender_name VARCHAR(255) NOT NULL,
            sender_email VARCHAR(255) NOT NULL,
            sender_phone VARCHAR(50),
            subject VARCHAR(255),
            message_content TEXT NOT NULL,
            submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            is_read BOOLEAN DEFAULT FALSE
        )";
        $conn->query($create_table);
    }
    
    // Get statistics
    $total_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages")->fetch_assoc()['count'];
    $unread_messages = $conn->query("SELECT COUNT(*) as count FROM contact_messages WHERE is_read = FALSE")->fetch_assoc()['count'];
    
} catch (Exception $e) {
    $error_message = "Database error: " . $e->getMessage();
}

// Handle mark as read
if (isset($_GET['mark_read'])) {
    $message_id = intval($_GET['mark_read']);
    $stmt = $conn->prepare("UPDATE contact_messages SET is_read = TRUE WHERE message_id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    header('Location: messages.php');
    exit();
}

// Handle delete message
if (isset($_GET['delete']) && isset($_GET['confirm'])) {
    $message_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM contact_messages WHERE message_id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    header('Location: messages.php');
    exit();
}

// Get all messages (moved to try block above)
try {
    $messages_query = "SELECT * FROM contact_messages ORDER BY submission_date DESC";
    $messages_result = $conn->query($messages_query);
} catch (Exception $e) {
    // Already handled above
}
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
        
        .stats-card {
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            color: white;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .stats-card.total {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        }
        
        .stats-card.unread {
            background: linear-gradient(135deg, #ef4444 0%, #f97316 100%);
        }
        
        .stats-card h3 {
            font-size: 2rem;
            font-weight: bold;
            margin: 0;
        }
        
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
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
            padding: 5px 15px;
            font-size: 14px;
        }
        
        .btn-custom:hover {
            background-color: #d49c14;
            border-color: #d49c14;
            color: white;
        }
        
        .table-custom thead {
            background-color: #f0b016;
            color: white;
        }
        
        .message-unread {
            background-color: rgba(240, 176, 22, 0.1);
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
                    <a href="messages.php" class="active">
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
    </div>

    <!-- Top Header -->
    <div class="top-header">
        <h1><i class="fas fa-mail-bulk me-2" style="color: #6366f1;"></i>Message Center</h1>
        <div class="header-actions">
            <span class="badge" style="background-color: #6366f1;">Total: <?php echo $total_messages; ?></span>
            <span class="badge" style="background-color: #ef4444;">Unread: <?php echo $unread_messages; ?></span>
            <button class="btn btn-outline-primary btn-sm">
                <i class="fas fa-sync me-1"></i>Refresh
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stats-card total">
                    <i class="fas fa-envelope fa-2x mb-2"></i>
                    <h3><?php echo $total_messages; ?></h3>
                    <p class="mb-0">Total Messages</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="stats-card unread">
                    <i class="fas fa-bell fa-2x mb-2"></i>
                    <h3><?php echo $unread_messages; ?></h3>
                    <p class="mb-0">Unread Messages</p>
                </div>
            </div>
        </div>
        
        <!-- Messages Table -->
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-inbox me-2"></i>All Messages</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-custom">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($messages_result && $messages_result->num_rows > 0) {
                                while($message = $messages_result->fetch_assoc()) {
                                    $unread_class = !$message['is_read'] ? 'message-unread' : '';
                                    ?>
                                    <tr class="<?php echo $unread_class; ?>">
                                        <td><strong>#<?php echo $message['message_id']; ?></strong></td>
                                        <td>
                                            <?php if (!$message['is_read']): ?>
                                                <i class="fas fa-circle text-warning me-1" style="font-size: 8px;"></i>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars($message['sender_name']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($message['sender_email']); ?></td>
                                        <td><?php echo htmlspecialchars($message['subject'] ?? 'No Subject'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($message['submission_date'])); ?></td>
                                        <td>
                                            <?php if($message['is_read']): ?>
                                                <span class="badge bg-success">Read</span>
                                            <?php else: ?>
                                                <span class="badge" style="background-color: #f0b016;">Unread</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if (!$message['is_read']): ?>
                                                <a href="?mark_read=<?php echo $message['message_id']; ?>" 
                                                   class="btn btn-sm btn-outline-success me-1">
                                                    <i class="fas fa-check"></i> Mark Read
                                                </a>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-outline-primary me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#messageModal<?php echo $message['message_id']; ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            <a href="?delete=<?php echo $message['message_id']; ?>&confirm=1" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this message?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Message Modal -->
                                    <div class="modal fade" id="messageModal<?php echo $message['message_id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%); color: white;">
                                                    <h5 class="modal-title">Message from <?php echo htmlspecialchars($message['sender_name']); ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <strong>From:</strong> <?php echo htmlspecialchars($message['sender_name']); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Email:</strong> <?php echo htmlspecialchars($message['sender_email']); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row mt-2">
                                                        <div class="col-md-6">
                                                            <strong>Phone:</strong> <?php echo htmlspecialchars($message['sender_phone'] ?? 'Not provided'); ?>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <strong>Date:</strong> <?php echo date('M d, Y H:i', strtotime($message['submission_date'])); ?>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                    <strong>Subject:</strong> <?php echo htmlspecialchars($message['subject'] ?? 'No Subject'); ?><br><br>
                                                    <strong>Message:</strong><br>
                                                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
                                                        <?php echo nl2br(htmlspecialchars($message['message_content'])); ?>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="mailto:<?php echo htmlspecialchars($message['sender_email']); ?>" class="btn btn-custom">
                                                        <i class="fas fa-reply me-1"></i>Reply via Email
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="7" class="text-center py-4"><i class="fas fa-inbox fa-2x text-muted mb-2"></i><br>No messages yet</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>