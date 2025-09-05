<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$page_title = 'Manage Experience & Skills';
$current_page = 'experience';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $section_type = sanitize_input($_POST['section_type']);
                $title = sanitize_input($_POST['title']);
                $subtitle = sanitize_input($_POST['subtitle']);
                $description = sanitize_input($_POST['description']);
                $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
                $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
                $organization = sanitize_input($_POST['organization']);
                $location = sanitize_input($_POST['location']);
                $skill_level = !empty($_POST['skill_level']) ? $_POST['skill_level'] : null;
                $display_order = (int)$_POST['display_order'];
                
                $stmt = $conn->prepare("INSERT INTO experience_skills (section_type, title, subtitle, description, start_date, end_date, organization, location, skill_level, display_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssi", $section_type, $title, $subtitle, $description, $start_date, $end_date, $organization, $location, $skill_level, $display_order);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Item added successfully!";
                } else {
                    $_SESSION['error'] = "Error adding item: " . $conn->error;
                }
                break;
                
            case 'edit':
                $id = (int)$_POST['id'];
                $section_type = sanitize_input($_POST['section_type']);
                $title = sanitize_input($_POST['title']);
                $subtitle = sanitize_input($_POST['subtitle']);
                $description = sanitize_input($_POST['description']);
                $start_date = !empty($_POST['start_date']) ? $_POST['start_date'] : null;
                $end_date = !empty($_POST['end_date']) ? $_POST['end_date'] : null;
                $organization = sanitize_input($_POST['organization']);
                $location = sanitize_input($_POST['location']);
                $skill_level = !empty($_POST['skill_level']) ? $_POST['skill_level'] : null;
                $display_order = (int)$_POST['display_order'];
                $is_visible = isset($_POST['is_visible']) ? 1 : 0;
                
                $stmt = $conn->prepare("UPDATE experience_skills SET section_type=?, title=?, subtitle=?, description=?, start_date=?, end_date=?, organization=?, location=?, skill_level=?, display_order=?, is_visible=? WHERE id=?");
                $stmt->bind_param("sssssssssiis", $section_type, $title, $subtitle, $description, $start_date, $end_date, $organization, $location, $skill_level, $display_order, $is_visible, $id);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Item updated successfully!";
                } else {
                    $_SESSION['error'] = "Error updating item: " . $conn->error;
                }
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                $stmt = $conn->prepare("DELETE FROM experience_skills WHERE id=?");
                $stmt->bind_param("i", $id);
                
                if ($stmt->execute()) {
                    $_SESSION['success'] = "Item deleted successfully!";
                } else {
                    $_SESSION['error'] = "Error deleting item: " . $conn->error;
                }
                break;
        }
        
        header('Location: manage-experience.php');
        exit();
    }
}

// Get filter parameters
$filter_type = isset($_GET['type']) ? $_GET['type'] : 'all';

// Fetch items based on filter
$where_clause = "";
if ($filter_type !== 'all') {
    $where_clause = "WHERE section_type = '" . $conn->real_escape_string($filter_type) . "'";
}

$query = "SELECT * FROM experience_skills $where_clause ORDER BY section_type, display_order";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Boundless Moments Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: #2c3e50;
            min-height: 100vh;
            padding: 20px 0;
        }
        .sidebar a {
            color: #ecf0f1;
            text-decoration: none;
            padding: 10px 20px;
            display: block;
            transition: background 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background: #34495e;
            color: #3498db;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border: none;
        }
        .badge-experience { background: #3498db; }
        .badge-skill { background: #2ecc71; }
        .badge-award { background: #f39c12; }
        .badge-education { background: #9b59b6; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="text-center text-white mb-4">Admin Panel</h4>
                <a href="dashboard.php"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
                <a href="manage-gallery.php"><i class="fas fa-images me-2"></i> Gallery</a>
                <a href="manage-experience.php" class="active"><i class="fas fa-user-tie me-2"></i> Experience</a>
                <a href="bookings.php"><i class="fas fa-calendar-alt me-2"></i> Bookings</a>
                <a href="messages.php"><i class="fas fa-envelope me-2"></i> Messages</a>
                <a href="settings.php"><i class="fas fa-cog me-2"></i> Settings</a>
                <hr class="text-white">
                <a href="../index.php" target="_blank"><i class="fas fa-external-link-alt me-2"></i> View Site</a>
                <a href="../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-user-tie me-2"></i> Manage Experience & Skills</h2>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                        <i class="fas fa-plus"></i> Add New
                    </button>
                </div>

                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Filter Tabs -->
                <ul class="nav nav-tabs mb-4">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter_type === 'all' ? 'active' : ''; ?>" href="?type=all">All Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter_type === 'experience' ? 'active' : ''; ?>" href="?type=experience">Experience</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter_type === 'skill' ? 'active' : ''; ?>" href="?type=skill">Skills</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter_type === 'award' ? 'active' : ''; ?>" href="?type=award">Awards</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $filter_type === 'education' ? 'active' : ''; ?>" href="?type=education">Education</a>
                    </li>
                </ul>

                <!-- Items Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Organization</th>
                                        <th>Date Range</th>
                                        <th>Status</th>
                                        <th>Order</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <span class="badge badge-<?php echo $row['section_type']; ?>">
                                                        <?php echo ucfirst($row['section_type']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($row['title']); ?></strong>
                                                    <?php if ($row['subtitle']): ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($row['subtitle']); ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo htmlspecialchars($row['organization'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <?php if ($row['start_date']): ?>
                                                        <?php echo date('M Y', strtotime($row['start_date'])); ?> - 
                                                        <?php echo $row['end_date'] ? date('M Y', strtotime($row['end_date'])) : 'Present'; ?>
                                                    <?php else: ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-<?php echo $row['is_visible'] ? 'success' : 'secondary'; ?>">
                                                        <?php echo $row['is_visible'] ? 'Visible' : 'Hidden'; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo $row['display_order']; ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info" onclick="editItem(<?php echo htmlspecialchars(json_encode($row)); ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger" onclick="deleteItem(<?php echo $row['id']; ?>, '<?php echo htmlspecialchars($row['title']); ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center py-4">
                                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">No items found</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="itemForm" method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" id="formAction" value="add">
                        <input type="hidden" name="id" id="itemId">
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Section Type *</label>
                                <select name="section_type" id="sectionType" class="form-select" required>
                                    <option value="">Select Type</option>
                                    <option value="experience">Experience</option>
                                    <option value="skill">Skill</option>
                                    <option value="award">Award</option>
                                    <option value="education">Education</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" id="displayOrder" class="form-control" value="0">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Title *</label>
                            <input type="text" name="title" id="title" class="form-control" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Subtitle</label>
                            <input type="text" name="subtitle" id="subtitle" class="form-control">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Organization</label>
                                <input type="text" name="organization" id="organization" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" id="location" class="form-control">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="startDate" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">End Date</label>
                                <input type="date" name="end_date" id="endDate" class="form-control">
                                <small class="form-text text-muted">Leave empty for current/ongoing</small>
                            </div>
                        </div>
                        
                        <div class="mb-3" id="skillLevelDiv" style="display: none;">
                            <label class="form-label">Skill Level</label>
                            <select name="skill_level" id="skillLevel" class="form-select">
                                <option value="">Select Level</option>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>
                        
                        <div class="form-check" id="visibilityCheck" style="display: none;">
                            <input type="checkbox" name="is_visible" id="isVisible" class="form-check-input" checked>
                            <label class="form-check-label" for="isVisible">Visible on website</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete "<span id="deleteItemTitle"></span>"?</p>
                    <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" id="deleteItemId">
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide skill level field based on section type
        document.getElementById('sectionType').addEventListener('change', function() {
            const skillDiv = document.getElementById('skillLevelDiv');
            if (this.value === 'skill') {
                skillDiv.style.display = 'block';
            } else {
                skillDiv.style.display = 'none';
            }
        });

        function editItem(item) {
            document.getElementById('modalTitle').textContent = 'Edit Item';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('itemId').value = item.id;
            document.getElementById('sectionType').value = item.section_type;
            document.getElementById('title').value = item.title;
            document.getElementById('subtitle').value = item.subtitle || '';
            document.getElementById('description').value = item.description || '';
            document.getElementById('organization').value = item.organization || '';
            document.getElementById('location').value = item.location || '';
            document.getElementById('startDate').value = item.start_date || '';
            document.getElementById('endDate').value = item.end_date || '';
            document.getElementById('skillLevel').value = item.skill_level || '';
            document.getElementById('displayOrder').value = item.display_order;
            document.getElementById('isVisible').checked = item.is_visible == 1;
            
            // Show/hide skill level field
            const skillDiv = document.getElementById('skillLevelDiv');
            const visibilityCheck = document.getElementById('visibilityCheck');
            if (item.section_type === 'skill') {
                skillDiv.style.display = 'block';
            } else {
                skillDiv.style.display = 'none';
            }
            visibilityCheck.style.display = 'block';
            
            new bootstrap.Modal(document.getElementById('addModal')).show();
        }

        function deleteItem(id, title) {
            document.getElementById('deleteItemId').value = id;
            document.getElementById('deleteItemTitle').textContent = title;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }

        // Reset form when modal is closed
        document.getElementById('addModal').addEventListener('hidden.bs.modal', function () {
            document.getElementById('modalTitle').textContent = 'Add New Item';
            document.getElementById('formAction').value = 'add';
            document.getElementById('itemForm').reset();
            document.getElementById('skillLevelDiv').style.display = 'none';
            document.getElementById('visibilityCheck').style.display = 'none';
        });
    </script>
</body>
</html>