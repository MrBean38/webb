<?php
session_start();
require 'db.php';

// Course data
$courses = [
    "Web Development"      => ["emoji" => "💻", "tag" => "Tech",        "duration" => "6 Months",  "session" => "Evening (8 PM)",   "link" => "https://www.w3schools.com",  "syllabus" => ["HTML5 & CSS3","JavaScript","React JS","Node.js"]],
    "Graphics Design"      => ["emoji" => "🎨", "tag" => "Design",      "duration" => "4 Months",  "session" => "Afternoon (3 PM)", "link" => "https://www.adobe.com",       "syllabus" => ["Photoshop CC","Illustrator","Branding","UI/UX"]],
    "Digital Marketing"    => ["emoji" => "📣", "tag" => "Marketing",   "duration" => "3 Months",  "session" => "Night (9 PM)",     "link" => "https://skillshop.google.com","syllabus" => ["SEO","Social Media Ads","Email Marketing","Analytics"]],
    "Software Engineering" => ["emoji" => "⚙️", "tag" => "Engineering", "duration" => "12 Months", "session" => "Morning (10 AM)",  "link" => "https://www.coursera.org",    "syllabus" => ["Data Structures","Algorithms","System Design","DevOps"]],
];

$success_msg = "";
$error_msg   = "";

// ---- ENROLL ----
if (isset($_POST['enroll'])) {
    $name   = trim(mysqli_real_escape_string($conn, $_POST['name']));
    $email  = trim(mysqli_real_escape_string($conn, $_POST['email']));
    $phone  = trim(mysqli_real_escape_string($conn, $_POST['phone']));
    $course = trim(mysqli_real_escape_string($conn, $_POST['course']));

    if (!$name || !$phone || !$course) {
        $error_msg = "Please fill in all required fields!";
    } elseif (strlen($phone) < 10) {
        $error_msg = "Please enter a valid phone number!";
    } else {
        $conn->query("INSERT INTO enrollments (name, email, phone, course) VALUES ('$name','$email','$phone','$course')");
        $_SESSION['student_phone'] = $phone;
        $_SESSION['student_name']  = $name;
        $success_msg = $course;
    }
}

// ---- LOGIN ----
if (isset($_POST['login'])) {
    $phone = trim(mysqli_real_escape_string($conn, $_POST['login_phone']));
    $res   = $conn->query("SELECT * FROM enrollments WHERE phone='$phone' LIMIT 1");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $_SESSION['student_phone'] = $phone;
        $_SESSION['student_name']  = $row['name'];
    } else {
        $error_msg = "This phone number is not registered. Please enroll first.";
    }
}

// ---- LOGOUT ----
if (isset($_GET['logout'])) {
    unset($_SESSION['student_phone'], $_SESSION['student_name']);
    header("Location: courses.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transform U | Courses</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --green: #10b981; --dark: #0a0f1e; --dark2: #111827; --muted: #94a3b8; --border: rgba(255,255,255,0.08); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--dark); color: white; }
        nav { position: sticky; top: 0; z-index: 1000; display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: rgba(10,15,30,0.95); backdrop-filter: blur(20px); border-bottom: 1px solid var(--border); }
        .logo { font-family: 'Syne', sans-serif; font-weight: 900; font-size: 22px; text-decoration: none; color: white; }
        .logo span { color: var(--green); }
        .nav-links { display: flex; gap: 30px; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: 15px; transition: color 0.3s; }
        .nav-links a:hover, .nav-links a.active { color: white; }
        .nav-right { display: flex; align-items: center; gap: 14px; }
        .btn { cursor: pointer; font-family: inherit; font-weight: 600; transition: 0.3s; text-decoration: none; display: inline-block; border: none; }
        .btn-green { background: var(--green); color: white; padding: 10px 24px; border-radius: 50px; font-size: 14px; }
        .btn-green:hover { background: #059669; }
        .btn-outline-sm { background: transparent; color: white; padding: 10px 20px; border-radius: 50px; font-size: 14px; border: 1px solid rgba(255,255,255,0.2); }
        .btn-red { background: #ef4444; color: white; padding: 8px 16px; border-radius: 50px; font-size: 13px; }
        .page-header { padding: 80px 8% 50px; text-align: center; background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(79,70,229,0.12) 0%, transparent 60%); }
        .page-header h1 { font-family: 'Syne', sans-serif; font-size: clamp(36px, 5vw, 60px); font-weight: 900; letter-spacing: -1.5px; }
        .page-header p { color: var(--muted); font-size: 17px; margin-top: 14px; }
        .courses-section { padding: 40px 8% 80px; }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; }
        .card { background: var(--dark2); border: 1px solid var(--border); border-radius: 24px; overflow: hidden; transition: 0.35s; }
        .card:hover { border-color: rgba(16,185,129,0.4); transform: translateY(-6px); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .card-top { padding: 32px 28px 24px; }
        .course-emoji { font-size: 44px; margin-bottom: 16px; display: block; }
        .course-tag { display: inline-block; background: rgba(16,185,129,0.12); color: var(--green); padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
        .card h3 { font-family: 'Syne', sans-serif; font-size: 22px; font-weight: 800; margin-bottom: 10px; }
        .card-meta { display: flex; gap: 16px; margin-top: 14px; flex-wrap: wrap; }
        .meta-item { color: var(--muted); font-size: 13px; }
        .card-syllabus { padding: 20px 28px; border-top: 1px solid var(--border); background: rgba(255,255,255,0.02); }
        .card-syllabus h4 { font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
        .syllabus-tags { display: flex; flex-wrap: wrap; gap: 8px; }
        .syllabus-tag { background: rgba(255,255,255,0.05); border: 1px solid var(--border); color: #cbd5e1; padding: 5px 12px; border-radius: 50px; font-size: 12px; }
        .card-footer { padding: 20px 28px; border-top: 1px solid var(--border); }
        .enroll-btn { width: 100%; padding: 14px; background: var(--green); color: white; border: none; border-radius: 14px; font-family: 'Syne', sans-serif; font-size: 15px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .enroll-btn:hover { background: #059669; }
        /* MODAL */
        .modal-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 2000; backdrop-filter: blur(8px); align-items: center; justify-content: center; }
        .modal-overlay.active { display: flex; }
        .modal { background: var(--dark2); border: 1px solid var(--border); border-radius: 28px; padding: 40px; width: 90%; max-width: 420px; position: relative; animation: modalIn 0.3s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
        .modal-close { position: absolute; top: 18px; right: 22px; background: none; border: none; color: var(--muted); font-size: 22px; cursor: pointer; }
        .modal h2 { font-family: 'Syne', sans-serif; font-size: 24px; margin-bottom: 6px; }
        .modal p { color: var(--muted); font-size: 14px; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 6px; }
        .form-group input { width: 100%; padding: 12px 16px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 12px; color: white; font-family: inherit; font-size: 15px; transition: 0.3s; }
        .form-group input:focus { outline: none; border-color: var(--green); }
        .form-group input[readonly] { color: var(--green); font-weight: 600; }
        .form-submit { width: 100%; padding: 14px; background: var(--green); color: white; border: none; border-radius: 14px; font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; cursor: pointer; margin-top: 8px; transition: 0.3s; }
        .form-submit:hover { background: #059669; }
        /* DASHBOARD */
        .dashboard { padding: 40px 8%; }
        .dashboard-card { background: var(--dark2); border: 1px solid var(--border); border-radius: 28px; padding: 40px; }
        .dashboard-header { display: flex; justify-content: space-between; align-items: center; padding-bottom: 24px; border-bottom: 1px solid var(--border); margin-bottom: 28px; }
        .dashboard-header h2 { font-family: 'Syne', sans-serif; font-size: 26px; }
        .enrolled-item { display: flex; justify-content: space-between; align-items: center; padding: 20px; background: rgba(255,255,255,0.03); border: 1px solid var(--border); border-radius: 16px; margin-bottom: 14px; }
        .enrolled-item h3 { font-family: 'Syne', sans-serif; font-size: 17px; margin-bottom: 4px; }
        .enrolled-item small { color: var(--muted); font-size: 13px; }
        .enter-btn { background: var(--primary); color: white; padding: 10px 22px; border-radius: 50px; text-decoration: none; font-size: 14px; font-weight: 600; transition: 0.3s; }
        .enter-btn:hover { background: #3730a3; }
        .empty-state { text-align: center; padding: 40px; color: var(--muted); }
        /* ALERTS */
        .alert { padding: 14px 20px; border-radius: 14px; margin: 0 8% 20px; font-size: 14px; font-weight: 500; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        /* SUCCESS MODAL */
        .success-icon { font-size: 60px; margin-bottom: 16px; }
        .success-title { font-family: 'Syne', sans-serif; font-size: 24px; color: var(--green); margin-bottom: 8px; }
        @media (max-width: 768px) { nav { padding: 16px 5%; } .nav-links { display: none; } .courses-section, .dashboard { padding-left: 5%; padding-right: 5%; } }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">TRANSFORM <span>U</span> 🚀</a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="courses.php" class="active">Courses</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <div class="nav-right">
        <?php if(isset($_SESSION['student_phone'])): ?>
            <span style="color: var(--muted); font-size: 14px;">👤 <?php echo htmlspecialchars($_SESSION['student_name']); ?></span>
            <a href="courses.php?logout=1" class="btn btn-red">Logout</a>
        <?php else: ?>
            <button class="btn btn-outline-sm" onclick="openModal('loginModal')">Dashboard Login</button>
            <button class="btn btn-green" onclick="document.querySelector('.courses-section').scrollIntoView({behavior:'smooth'})">Enroll Now</button>
        <?php endif; ?>
    </div>
</nav>

<div class="page-header">
    <h1>Our Courses</h1>
    <p>Choose your path and start your transformation today</p>
</div>

<?php if($error_msg): ?>
<div class="alert alert-error">❌ <?php echo htmlspecialchars($error_msg); ?></div>
<?php endif; ?>

<!-- Course Cards -->
<section class="courses-section">
    <div class="courses-grid">
        <?php foreach($courses as $cname => $data): ?>
        <div class="card">
            <div class="card-top">
                <span class="course-emoji"><?php echo $data['emoji']; ?></span>
                <div class="course-tag"><?php echo $data['tag']; ?></div>
                <h3><?php echo $cname; ?></h3>
                <div class="card-meta">
                    <span class="meta-item">⏱️ <?php echo $data['duration']; ?></span>
                    <span class="meta-item">🕐 <?php echo $data['session']; ?></span>
                </div>
            </div>
            <div class="card-syllabus">
                <h4>Syllabus</h4>
                <div class="syllabus-tags">
                    <?php foreach($data['syllabus'] as $s): ?>
                    <span class="syllabus-tag"><?php echo $s; ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="card-footer">
                <button class="enroll-btn" onclick="openEnroll('<?php echo addslashes($cname); ?>')">Enroll Now →</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<!-- Student Dashboard -->
<?php if(isset($_SESSION['student_phone'])): ?>
<div class="dashboard" id="userDashboard">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <div>
                <h2>My Learning Dashboard 📚</h2>
                <p style="color: var(--muted); font-size: 14px; margin-top: 4px;">Welcome back, <?php echo htmlspecialchars($_SESSION['student_name']); ?>! Click 'Enter Class' to start.</p>
            </div>
            <a href="courses.php?logout=1" class="btn btn-red">Logout</a>
        </div>
        <?php
            $phone = $_SESSION['student_phone'];
            $res   = $conn->query("SELECT * FROM enrollments WHERE phone='$phone' ORDER BY id DESC");
            if ($res->num_rows > 0):
                while($row = $res->fetch_assoc()):
                    $cn = $row['course'];
                    $link = isset($courses[$cn]['link']) ? $courses[$cn]['link'] : '#';
        ?>
        <div class="enrolled-item">
            <div>
                <h3><?php echo htmlspecialchars($cn); ?></h3>
                <small>📅 Enrolled: <?php echo date('d M, Y', strtotime($row['reg_date'])); ?></small>
            </div>
            <a href="<?php echo $link; ?>" target="_blank" class="enter-btn">Enter Class →</a>
        </div>
        <?php endwhile; else: ?>
        <div class="empty-state">You are not enrolled in any courses yet.</div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Enroll Modal -->
<div class="modal-overlay" id="enrollModal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('enrollModal')">✕</button>
        <h2>Enroll Now ✍️</h2>
        <p>Fill in your details to register for the course.</p>
        <form method="POST">
            <div class="form-group"><label>Full Name *</label><input type="text" name="name" placeholder="Your full name" required></div>
            <div class="form-group"><label>Email Address</label><input type="email" name="email" placeholder="your@email.com"></div>
            <div class="form-group"><label>Phone Number * (Login ID)</label><input type="text" name="phone" placeholder="01XXXXXXXXX" required></div>
            <div class="form-group"><label>Selected Course</label><input type="text" id="enrollCourse" name="course" readonly></div>
            <button type="submit" name="enroll" class="form-submit">Confirm Enrollment →</button>
        </form>
    </div>
</div>

<!-- Login Modal -->
<div class="modal-overlay" id="loginModal">
    <div class="modal">
        <button class="modal-close" onclick="closeModal('loginModal')">✕</button>
        <h2>Student Login 🔐</h2>
        <p>Enter your registered phone number to access your dashboard.</p>
        <form method="POST">
            <div class="form-group"><label>Phone Number</label><input type="text" name="login_phone" placeholder="01XXXXXXXXX" required></div>
            <button type="submit" name="login" class="form-submit">Access Dashboard →</button>
        </form>
    </div>
</div>

<!-- Success Modal -->
<?php if($success_msg): ?>
<div class="modal-overlay active" id="successModal">
    <div class="modal" style="text-align:center;">
        <div class="success-icon">🎉</div>
        <div class="success-title">Registration Successful!</div>
        <p style="margin-bottom: 28px;">You have been enrolled in <b><?php echo htmlspecialchars($success_msg); ?></b>. Use your phone number to login anytime.</p>
        <button class="form-submit" onclick="document.getElementById('successModal').classList.remove('active'); document.getElementById('userDashboard').scrollIntoView({behavior:'smooth'})">Go to My Dashboard →</button>
    </div>
</div>
<?php endif; ?>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
window.onclick = e => { if(e.target.classList.contains('modal-overlay')) e.target.classList.remove('active'); };
function openEnroll(name) { document.getElementById('enrollCourse').value = name; openModal('enrollModal'); }
<?php if(isset($_SESSION['student_phone']) && !$success_msg): ?>
document.addEventListener('DOMContentLoaded', () => { document.getElementById('userDashboard')?.scrollIntoView({behavior:'smooth'}); });
<?php endif; ?>
</script>
</body>
</html>
