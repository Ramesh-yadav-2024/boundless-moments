<?php
$page_title = 'Booking';
require_once 'includes/db_connect.php';
include 'includes/header.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = sanitize_input($_POST['client_name']);
    $client_email = sanitize_input($_POST['client_email']);
    $client_phone = sanitize_input($_POST['client_phone']);
    $event_type = sanitize_input($_POST['event_type']);
    $event_date = sanitize_input($_POST['event_date']);
    $event_time = sanitize_input($_POST['event_time']);
    $location = sanitize_input($_POST['location']);
    $special_requirements = sanitize_input($_POST['special_requirements']);
    
    // Basic validation
    if (empty($client_name) || empty($client_email) || empty($client_phone) || empty($event_date)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($client_email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } elseif (strtotime($event_date) < strtotime(date('Y-m-d'))) {
        $error_message = 'Event date cannot be in the past.';
    } else {
        // Insert booking into database
        $stmt = $conn->prepare("INSERT INTO bookings (client_name, client_email, client_phone, event_type, event_date, event_time, location, special_requirements) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $client_name, $client_email, $client_phone, $event_type, $event_date, $event_time, $location, $special_requirements);
        
        if ($stmt->execute()) {
            $success_message = 'Your booking request has been submitted successfully! We will contact you soon.';
            $_POST = array(); // Clear form
        } else {
            $error_message = 'Error submitting booking. Please try again.';
        }
        $stmt->close();
    }
}
?>

<?php if (!empty($success_message)): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 15px; margin: 20px; border: 1px solid #c3e6cb; border-radius: 5px;">
        <?php echo htmlspecialchars($success_message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error_message)): ?>
    <div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 20px; border: 1px solid #f5c6cb; border-radius: 5px;">
        <?php echo htmlspecialchars($error_message); ?>
    </div>
<?php endif; ?>

<!-- contact section -->
<section class="contact_section layout_padding">
    <div class="container">
        <div class="">
            <h2 class="">
                Book Your Session
            </h2>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <form action="" method="POST">
                    <div>
                        <input type="text" name="client_name" placeholder="Your Name *" required 
                               value="<?php echo isset($_POST['client_name']) ? htmlspecialchars($_POST['client_name']) : ''; ?>" />
                    </div>
                    <div>
                        <input type="email" name="client_email" placeholder="Email *" required 
                               value="<?php echo isset($_POST['client_email']) ? htmlspecialchars($_POST['client_email']) : ''; ?>" />
                    </div>
                    <div>
                        <input type="tel" name="client_phone" placeholder="Phone Number *" required 
                               value="<?php echo isset($_POST['client_phone']) ? htmlspecialchars($_POST['client_phone']) : ''; ?>" />
                    </div>
                    <div>
                        <select name="event_type" style="width: 100%; border: none; height: 50px; margin-bottom: 25px; padding-left: 25px; background-color: #ffffff; outline: none; color: #101010; border-radius: 50px;">
                            <option value="">Select Event Type</option>
                            <option value="Wedding" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'Wedding') ? 'selected' : ''; ?>>Wedding</option>
                            <option value="Portrait" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'Portrait') ? 'selected' : ''; ?>>Portrait Session</option>
                            <option value="Corporate" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'Corporate') ? 'selected' : ''; ?>>Corporate Event</option>
                            <option value="Other" <?php echo (isset($_POST['event_type']) && $_POST['event_type'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    <div>
                        <input type="date" name="event_date" placeholder="Event Date *" required 
                               min="<?php echo date('Y-m-d'); ?>"
                               value="<?php echo isset($_POST['event_date']) ? htmlspecialchars($_POST['event_date']) : ''; ?>" />
                    </div>
                    <div>
                        <input type="time" name="event_time" placeholder="Event Time" 
                               value="<?php echo isset($_POST['event_time']) ? htmlspecialchars($_POST['event_time']) : ''; ?>" />
                    </div>
                    <div>
                        <input type="text" name="location" placeholder="Location" 
                               value="<?php echo isset($_POST['location']) ? htmlspecialchars($_POST['location']) : ''; ?>" />
                    </div>
                    <div>
                        <input type="text" name="special_requirements" class="message-box" placeholder="Special Requirements" 
                               value="<?php echo isset($_POST['special_requirements']) ? htmlspecialchars($_POST['special_requirements']) : ''; ?>" />
                    </div>
                    <div class="d-flex">
                        <button type="submit">
                            BOOK SESSION
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-4">
                <div class="img-box">
                    <img src="assets/images/contact-img.png" alt="">
                    <div class="img_overlay">
                        <h2>
                            Professional <br>
                            Photography <br>
                            Services
                        </h2>
                        <a href="contact.php">
                            Contact Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end contact section -->

<?php include 'includes/footer.php'; ?>