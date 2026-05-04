<?php
session_start();
require 'db.php';

$success = false;
$error   = "";

if (isset($_POST['send'])) {
    $name    = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email   = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $subject = trim(mysqli_real_escape_string($conn, $_POST['subject']));
    $message = trim(mysqli_real_escape_string($conn, $_POST['message']));

    if (!$name || !$email || !$message) {
        $error = "Please fill in all required fields!";
    } else {
        $conn->query("INSERT INTO messages (name, email, subject, message) VALUES ('$name','$email','$subject','$message')");
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transform U | Contact</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --green: #10b981; --dark: #0a0f1e; --dark2: #111827; --muted: #94a3b8; --border: rgba(255,255,255,0.08); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--dark); color: white; }
        nav { position: sticky; top: 0; z-index: 1000; display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: rgba(10,15,30,0.95); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
        .logo { font-family: 'Syne', sans-serif; font-weight: 900; font-size: 22px; text-decoration: none; color: white; }
        .logo span { color: var(--green); }
        .nav-links { display: flex; gap: 30px; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: 15px; transition: color 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: white; }
        .btn-green { background: var(--green); color: white; padding: 10px 24px; border-radius: 50px; font-size: 14px; font-weight: 600; text-decoration: none; }
        .page-header { padding: 80px 8% 60px; text-align: center; background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(79,70,229,0.12) 0%, transparent 60%); }
        .section-label { display: inline-block; background: rgba(79,70,229,0.1); border: 1px solid rgba(79,70,229,0.3); color: #818cf8; padding: 6px 16px; border-radius: 50px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .page-header h1 { font-family: 'Syne', sans-serif; font-size: clamp(36px, 5vw, 60px); font-weight: 900; letter-spacing: -1.5px; }
        .page-header p { color: var(--muted); font-size: 17px; margin-top: 14px; }
        .contact-wrapper { display: grid; grid-template-columns: 1fr 1.4fr; gap: 40px; padding: 60px 8% 80px; }
        .contact-info h2 { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 800; margin-bottom: 16px; }
        .contact-info p { color: var(--muted); font-size: 15px; line-height: 1.7; margin-bottom: 36px; }
        .info-item { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 24px; }
        .info-icon { width: 46px; height: 46px; border-radius: 14px; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.2); display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .info-label { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .info-value { font-size: 15px; font-weight: 500; }
        .contact-form { background: var(--dark2); border: 1px solid var(--border); border-radius: 28px; padding: 40px; }
        .contact-form h3 { font-family: 'Syne', sans-serif; font-size: 22px; margin-bottom: 6px; }
        .contact-form > p { color: var(--muted); font-size: 14px; margin-bottom: 28px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 8px; font-weight: 500; }
        .form-group input, .form-group select, .form-group textarea { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 12px; color: white; font-family: inherit; font-size: 15px; transition: 0.3s; resize: none; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus { outline: none; border-color: var(--green); }
        select option { background: var(--dark2); }
        .form-submit { width: 100%; padding: 14px; background: var(--green); color: white; border: none; border-radius: 14px; font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; margin-top: 4px; }
        .form-submit:hover { background: #059669; }
        .alert { padding: 14px 20px; border-radius: 14px; font-size: 14px; font-weight: 500; margin-bottom: 20px; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: var(--green); text-align: center; padding: 24px; }
        .alert-success .s-icon { font-size: 36px; margin-bottom: 8px; }
        .faq-section { padding: 0 8% 80px; }
        .faq-section h2 { font-family: 'Syne', sans-serif; font-size: 36px; font-weight: 800; letter-spacing: -1px; margin-bottom: 36px; }
        .faq-item { border-bottom: 1px solid var(--border); }
        .faq-q { width: 100%; background: none; border: none; color: white; font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 700; padding: 22px 0; display: flex; justify-content: space-between; align-items: center; cursor: pointer; text-align: left; transition: 0.3s; }
        .faq-q:hover { color: var(--green); }
        .faq-q .arrow { transition: 0.3s; font-size: 20px; }
        .faq-q.open .arrow { transform: rotate(45deg); }
        .faq-a { color: var(--muted); font-size: 15px; line-height: 1.7; max-height: 0; overflow: hidden; transition: max-height 0.3s ease, padding 0.3s; }
        .faq-a.open { max-height: 200px; padding-bottom: 20px; }
        footer { border-top: 1px solid var(--border); padding: 30px 8%; text-align: center; }
        footer p { color: var(--muted); font-size: 14px; }
        @media (max-width: 768px) { .nav-links { display: none; } nav { padding: 16px 5%; } .contact-wrapper { grid-template-columns: 1fr; padding: 40px 5%; } .form-row { grid-template-columns: 1fr; } .faq-section { padding: 0 5% 60px; } }
    </style>
</head>
<body>
<nav>
    <a href="index.php" class="logo">TRANSFORM <span>U</span> 🚀</a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="courses.php">Courses</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php" class="active">Contact</a></li>
    </ul>
    <a href="courses.php" class="btn-green">Enroll Now →</a>
</nav>

<div class="page-header">
    <div class="section-label">Get In Touch</div>
    <h1>Contact Us</h1>
    <p>Have questions? We're happy to help.</p>
</div>

<div class="contact-wrapper">
    <div class="contact-info">
        <h2>Let's Talk</h2>
        <p>Whether you have a question about a course, enrollment, or anything else — our team is ready to help.</p>
        <div class="info-item"><div class="info-icon">📧</div><div><div class="info-label">Email</div><div class="info-value">info@transformu.com</div></div></div>
        <div class="info-item"><div class="info-icon">📱</div><div><div class="info-label">Phone / WhatsApp</div><div class="info-value">+880 170 000 0000</div></div></div>
        <div class="info-item"><div class="info-icon">📍</div><div><div class="info-label">Location</div><div class="info-value">Dhaka, Bangladesh</div></div></div>
        <div class="info-item"><div class="info-icon">⏰</div><div><div class="info-label">Support Hours</div><div class="info-value">Sat–Thu: 9 AM – 9 PM</div></div></div>
    </div>

    <div class="contact-form">
        <h3>Send Us a Message ✉️</h3>
        <p>We'll get back to you within 24 hours.</p>

        <?php if($error): ?>
        <div class="alert alert-error">❌ <?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <?php if($success): ?>
        <div class="alert alert-success">
            <div class="s-icon">✅</div>
            <p><b>Message sent successfully!</b><br>We'll reply within 24 hours.</p>
        </div>
        <?php else: ?>
        <form method="POST">
            <div class="form-row">
                <div class="form-group"><label>First Name *</label><input type="text" name="name" placeholder="Rahim" required></div>
                <div class="form-group"><label>Last Name</label><input type="text" name="lastname" placeholder="Uddin"></div>
            </div>
            <div class="form-group"><label>Email Address *</label><input type="email" name="email" placeholder="your@email.com" required></div>
            <div class="form-group">
                <label>What's this about?</label>
                <select name="subject">
                    <option value="">Select a topic...</option>
                    <option>Course Inquiry</option>
                    <option>Enrollment Help</option>
                    <option>Technical Issue</option>
                    <option>Payment / Pricing</option>
                    <option>Other</option>
                </select>
            </div>
            <div class="form-group"><label>Your Message *</label><textarea name="message" rows="5" placeholder="Tell us how we can help..." required></textarea></div>
            <button type="submit" name="send" class="form-submit">Send Message →</button>
        </form>
        <?php endif; ?>
    </div>
</div>

<section class="faq-section">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-item">
        <button class="faq-q" onclick="toggleFAQ(this)">Do I need any prior experience to enroll? <span class="arrow">+</span></button>
        <div class="faq-a">No prior experience is required. Our courses are designed for complete beginners and take you step by step to a professional level.</div>
    </div>
    <div class="faq-item">
        <button class="faq-q" onclick="toggleFAQ(this)">How are the classes conducted? <span class="arrow">+</span></button>
        <div class="faq-a">Classes are conducted online via live sessions with specific time slots so you can choose what fits your schedule best.</div>
    </div>
    <div class="faq-item">
        <button class="faq-q" onclick="toggleFAQ(this)">Will I get a certificate after completion? <span class="arrow">+</span></button>
        <div class="faq-a">Yes! Upon successful completion you will receive a recognized Transform U certificate to add to your portfolio and LinkedIn.</div>
    </div>
    <div class="faq-item">
        <button class="faq-q" onclick="toggleFAQ(this)">Can I enroll in more than one course? <span class="arrow">+</span></button>
        <div class="faq-a">Absolutely! You can enroll in multiple courses at the same time for maximum career impact.</div>
    </div>
    <div class="faq-item">
        <button class="faq-q" onclick="toggleFAQ(this)">What is the refund policy? <span class="arrow">+</span></button>
        <div class="faq-a">We offer a full refund within the first 7 days. After 7 days, a partial refund may be available. Contact us to discuss.</div>
    </div>
</section>

<footer><p>© 2026 Transform U. Made with ❤️ in Bangladesh</p></footer>

<script>
function toggleFAQ(btn) {
    const answer = btn.nextElementSibling;
    const isOpen = answer.classList.contains('open');
    document.querySelectorAll('.faq-a').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-q').forEach(b => b.classList.remove('open'));
    if (!isOpen) { answer.classList.add('open'); btn.classList.add('open'); }
}
</script>
</body>
</html>
