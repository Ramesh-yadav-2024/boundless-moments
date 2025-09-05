<?php
$page_title = 'Contact';
require_once 'includes/db_connect.php';
include 'includes/header.php';

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = sanitize_input($_POST['name']);
    $email = sanitize_input($_POST['email']);
    $phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
    $subject = isset($_POST['subject']) ? sanitize_input($_POST['subject']) : 'Contact Form Submission';
    $message = sanitize_input($_POST['message']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($message)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        // Insert message into database
        $stmt = $conn->prepare("INSERT INTO contact_messages (sender_name, sender_email, sender_phone, subject, message_content) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);
        
        if ($stmt->execute()) {
            $success_message = 'Thank you for your message! We will get back to you soon.';
            // Clear form data
            $_POST = array();
        } else {
            $error_message = 'Sorry, there was an error sending your message. Please try again.';
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
                Get In Touch
            </h2>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <form action="" method="POST">
                    <div>
                        <input type="text" name="name" placeholder="Name" 
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required />
                    </div>
                    <div>
                        <input type="email" name="email" placeholder="Email" 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required />
                    </div>
                    <div>
                        <input type="text" name="phone" placeholder="Phone Number" 
                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" />
                    </div>
                    <div>
                        <input type="text" name="message" class="message-box" placeholder="Message" 
                               value="<?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?>" required />
                    </div>
                    <div class="d-flex">
                        <button type="submit">
                            SEND
                        </button>
                    </div>
                </form>
            </div>
            <div class="col-md-6">
                <div class="img-box">
                    <img src="assets/images/contact-img.png" alt="">
                    <div class="img_overlay">
                        <h2>
                            Best <br>
                            Photo <br>
                            Studio
                        </h2>
                        <a href="about.php">
                            About Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end contact section -->

<!-- quality section -->
<section class="quality_section layout_padding">
    <div class="container">
        <div class="quality_container">
            <h2>
                Contact <br>
                Information
            </h2>
            <div class="box">
                <div class="detail-box">
                    <div class="img-box">
                        <i class="fas fa-phone" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <div class="text-box">
                        <h5>
                            Phone
                        </h5>
                        <p>
                            +977-1-4782573<br>
                            Available Mon-Fri 9AM-6PM
                        </p>
                    </div>
                </div>
                <div class="detail-box">
                    <div class="img-box">
                        <i class="fas fa-envelope" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <div class="text-box">
                        <h5>
                            Email
                        </h5>
                        <p>
                            info@boundlessmoments.com<br>
                            We respond within 24 hours
                        </p>
                    </div>
                </div>
            </div>
            <div class="box mt-4">
                <div class="detail-box">
                    <div class="img-box">
                        <i class="fas fa-map-marker-alt" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <div class="text-box">
                        <h5>
                            Location
                        </h5>
                        <p>
                            ISMT College, New Baneshwor<br>
                            Kathmandu, Nepal
                        </p>
                    </div>
                </div>
                <div class="detail-box">
                    <div class="img-box">
                        <i class="fas fa-clock" style="font-size: 2.5rem; color: white;"></i>
                    </div>
                    <div class="text-box">
                        <h5>
                            Hours
                        </h5>
                        <p>
                            Mon-Fri: 9AM-6PM<br>
                            Weekend: By Appointment
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- end quality section -->

<?php include 'includes/footer.php'; ?>