<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Bookings';

// Initialize variables
$total_bookings = 0;
$pending_bookings = 0;
$confirmed_bookings = 0;
$bookings_result = null;

try {
    // Check if table exists, create it if it doesn't
    $table_check = $conn->query("SHOW TABLES LIKE 'bookings'");
    
    if ($table_check->num_rows == 0) {
        // Create bookings table
        $create_table = "CREATE TABLE bookings (
            booking_id INT AUTO_INCREMENT PRIMARY KEY,
            client_name VARCHAR(255) NOT NULL,
            client_email VARCHAR(255) NOT NULL,
            client_phone VARCHAR(50),
            event_type VARCHAR(100),
            event_date DATE,
            event_time TIME,
            location VARCHAR(255),
            special_requirements TEXT,
            booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending'
        )";
        $conn->query($create_table);
    }
    
    // Get statistics
    $total_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings")->fetch_assoc()['count'];
    $pending_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'pending'")->fetch_assoc()['count'];
    $confirmed_bookings = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE booking_status = 'confirmed'")->fetch_assoc()['count'];
    
} catch (Exception $e) {
    $error_message = "Database error: " . $e->getMessage();
}

// Handle status update
if (isset($_GET['update_status'])) {
    $booking_id = intval($_GET['update_status']);
    $new_status = sanitize_input($_GET['status']);
    $stmt = $conn->prepare("UPDATE bookings SET booking_status = ? WHERE booking_id = ?");
    $stmt->bind_param("si", $new_status, $booking_id);
    $stmt->execute();
    header('Location: bookings.php');
    exit();
}

// Handle delete booking
if (isset($_GET['delete']) && isset($_GET['confirm'])) {
    $booking_id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    header('Location: bookings.php');
    exit();
}

// Get all bookings (moved to try block above)
try {
    $bookings_query = "SELECT * FROM bookings ORDER BY created_at DESC";
    $bookings_result = $conn->query($bookings_query);
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
        
        .stats-card.pending {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        
        .stats-card.confirmed {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
        }
        
        .stats-card.total {
            background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%);
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
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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
        
        .booking-pending {
            background-color: rgba(243, 156, 18, 0.1);
        }
        
        .booking-confirmed {
            background-color: rgba(39, 174, 96, 0.1);
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
                    <a href="bookings.php" class="active">
                        <i class="fas fa-calendar"></i>
                        Bookings
                        <?php if($pending_bookings > 0): ?>
                        <span class="badge bg-warning text-dark ms-auto"><?php echo $pending_bookings; ?></span>
                        <?php endif; ?>
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
        <h1><i class="fas fa-calendar-check me-2" style="color: #3b82f6;"></i>Booking Management</h1>
        <div class="header-actions">
            <span class="badge" style="background-color: #3b82f6;">Total: <?php echo $total_bookings; ?></span>
            <span class="badge" style="background-color: #f39c12;">Pending: <?php echo $pending_bookings; ?></span>
            <span class="badge" style="background-color: #27ae60;">Confirmed: <?php echo $confirmed_bookings; ?></span>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stats-card total">
                    <i class="fas fa-calendar fa-2x mb-2"></i>
                    <h3><?php echo $total_bookings; ?></h3>
                    <p class="mb-0">Total Bookings</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card pending">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h3><?php echo $pending_bookings; ?></h3>
                    <p class="mb-0">Pending Approval</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stats-card confirmed">
                    <i class="fas fa-check-circle fa-2x mb-2"></i>
                    <h3><?php echo $confirmed_bookings; ?></h3>
                    <p class="mb-0">Confirmed</p>
                </div>
            </div>
        </div>
        
        <!-- Bookings Table -->
        <div class="card card-custom">
            <div class="card-header card-header-custom">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>All Bookings</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-custom">
                            <tr>
                                <th>ID</th>
                                <th>Client</th>
                                <th>Event Type</th>
                                <th>Event Date</th>
                                <th>Location</th>
                                <th>Booking Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($bookings_result && $bookings_result->num_rows > 0) {
                                while($booking = $bookings_result->fetch_assoc()) {
                                    $status_class = '';
                                    if ($booking['booking_status'] == 'pending') $status_class = 'booking-pending';
                                    if ($booking['booking_status'] == 'confirmed') $status_class = 'booking-confirmed';
                                    ?>
                                    <tr class="<?php echo $status_class; ?>">
                                        <td><strong>#<?php echo $booking['booking_id']; ?></strong></td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($booking['client_name']); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['client_email']); ?></small><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['client_phone'] ?? 'No phone'); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?php echo htmlspecialchars($booking['event_type'] ?? 'Not specified'); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <strong><?php echo date('M d, Y', strtotime($booking['event_date'])); ?></strong><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($booking['event_time'] ?? 'Time TBD'); ?></small>
                                        </td>
                                        <td><?php echo htmlspecialchars($booking['location'] ?? 'TBD'); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($booking['created_at'])); ?></td>
                                        <td>
                                            <?php 
                                            $status = $booking['booking_status'] ?? 'pending';
                                            switch($status) {
                                                case 'confirmed':
                                                    echo '<span class="badge bg-success"><i class="fas fa-check me-1"></i>Confirmed</span>';
                                                    break;
                                                case 'cancelled':
                                                    echo '<span class="badge bg-danger"><i class="fas fa-times me-1"></i>Cancelled</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php if ($booking['booking_status'] != 'confirmed'): ?>
                                                <a href="?update_status=<?php echo $booking['booking_id']; ?>&status=confirmed" 
                                                   class="btn btn-sm btn-outline-success me-1">
                                                    <i class="fas fa-check"></i> Confirm
                                                </a>
                                            <?php endif; ?>
                                            
                                            <button class="btn btn-sm btn-outline-primary me-1" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#bookingModal<?php echo $booking['booking_id']; ?>">
                                                <i class="fas fa-eye"></i> View
                                            </button>
                                            
                                            <a href="?delete=<?php echo $booking['booking_id']; ?>&confirm=1" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Are you sure you want to delete this booking?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </a>
                                        </td>
                                    </tr>
                                    
                                    <!-- Booking Modal -->
                                    <div class="modal fade" id="bookingModal<?php echo $booking['booking_id']; ?>" tabindex="-1">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: linear-gradient(135deg, #f0b016 0%, #d49c14 100%); color: white;">
                                                    <h5 class="modal-title">Booking Details - #<?php echo $booking['booking_id']; ?></h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h6><i class="fas fa-user me-2"></i>Client Information</h6>
                                                            <p><strong>Name:</strong> <?php echo htmlspecialchars($booking['client_name']); ?></p>
                                                            <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['client_email']); ?></p>
                                                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($booking['client_phone'] ?? 'Not provided'); ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h6><i class="fas fa-calendar me-2"></i>Event Details</h6>
                                                            <p><strong>Type:</strong> <?php echo htmlspecialchars($booking['event_type'] ?? 'Not specified'); ?></p>
                                                            <p><strong>Date:</strong> <?php echo date('F d, Y', strtotime($booking['event_date'])); ?></p>
                                                            <p><strong>Time:</strong> <?php echo htmlspecialchars($booking['event_time'] ?? 'Time TBD'); ?></p>
                                                            <p><strong>Location:</strong> <?php echo htmlspecialchars($booking['location'] ?? 'TBD'); ?></p>
                                                        </div>
                                                    </div>
                                                    
                                                    <?php if (!empty($booking['special_requirements'])): ?>
                                                    <hr>
                                                    <h6><i class="fas fa-star me-2"></i>Special Requirements</h6>
                                                    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px;">
                                                        <?php echo nl2br(htmlspecialchars($booking['special_requirements'])); ?>
                                                    </div>
                                                    <?php endif; ?>
                                                    
                                                    <hr>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <p><strong>Booking Date:</strong> <?php echo date('F d, Y H:i', strtotime($booking['created_at'])); ?></p>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <p><strong>Status:</strong> 
                                                            <?php 
                                                            $status = $booking['booking_status'] ?? 'pending';
                                                            switch($status) {
                                                                case 'confirmed':
                                                                    echo '<span class="badge bg-success">Confirmed</span>';
                                                                    break;
                                                                case 'cancelled':
                                                                    echo '<span class="badge bg-danger">Cancelled</span>';
                                                                    break;
                                                                default:
                                                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                                            }
                                                            ?>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <a href="mailto:<?php echo htmlspecialchars($booking['client_email']); ?>" class="btn btn-custom">
                                                        <i class="fas fa-envelope me-1"></i>Contact Client
                                                    </a>
                                                    <?php if ($booking['booking_status'] != 'confirmed'): ?>
                                                    <a href="?update_status=<?php echo $booking['booking_id']; ?>&status=confirmed" class="btn btn-success">
                                                        <i class="fas fa-check me-1"></i>Confirm Booking
                                                    </a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="8" class="text-center py-4"><i class="fas fa-calendar fa-2x text-muted mb-2"></i><br>No bookings yet</td></tr>';
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