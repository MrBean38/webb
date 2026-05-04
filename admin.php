<?php
session_start();
require 'db.php';

// ---- ADMIN LOGIN ----
if (isset($_POST['login'])) {
    $user = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $pass = $_POST['password'];
    $res  = $conn->query("SELECT * FROM admins WHERE username='$user' LIMIT 1");
    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (password_verify($pass, $row['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_user']      = $user;
            header("Location: admin.php");
            exit;
        }
    }
    $login_error = "Incorrect username or password!";
}

// ---- ADMIN LOGOUT ----
if (isset($_GET['logout'])) {
    unset($_SESSION['admin_logged_in'], $_SESSION['admin_user']);
    header("Location: admin.php");
    exit;
}

// ---- DELETE ENROLLMENT ----
if (isset($_GET['delete']) && isset($_SESSION['admin_logged_in'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM enrollments WHERE id=$id");
    header("Location: admin.php");
    exit;
}

// ---- DELETE MESSAGE ----
if (isset($_GET['del_msg']) && isset($_SESSION['admin_logged_in'])) {
    $id = intval($_GET['del_msg']);
    $conn->query("DELETE FROM messages WHERE id=$id");
    header("Location: admin.php?tab=messages");
    exit;
}

// ---- CHANGE PASSWORD ----
$pass_msg = "";
if (isset($_POST['change_pass']) && isset($_SESSION['admin_logged_in'])) {
    $np = $_POST['new_pass'];
    $cp = $_POST['confirm_pass'];
    if (strlen($np) < 6) {
        $pass_msg = "error:Password must be at least 6 characters!";
    } elseif ($np !== $cp) {
        $pass_msg = "error:Passwords do not match!";
    } else {
        $hashed = password_hash($np, PASSWORD_DEFAULT);
        $conn->query("UPDATE admins SET password='$hashed' WHERE username='{$_SESSION['admin_user']}'");
        $pass_msg = "success:Password updated successfully!";
    }
}

// ---- FETCH DATA ----
$tab         = isset($_GET['tab']) ? $_GET['tab'] : 'enrollments';
$search      = isset($_GET['search']) ? trim(mysqli_real_escape_string($conn, $_GET['search'])) : '';
$filter      = isset($_GET['course']) ? trim(mysqli_real_escape_string($conn, $_GET['course'])) : '';

$where = "WHERE 1=1";
if ($search) $where .= " AND (name LIKE '%$search%' OR phone LIKE '%$search%' OR email LIKE '%$search%')";
if ($filter) $where .= " AND course='$filter'";

$enrollments  = $conn->query("SELECT * FROM enrollments $where ORDER BY id DESC");
$total        = $conn->query("SELECT COUNT(*) as c FROM enrollments")->fetch_assoc()['c'];
$messages_res = $conn->query("SELECT * FROM messages ORDER BY id DESC");

$course_counts = [];
foreach(['Web Development','Graphics Design','Digital Marketing','Software Engineering'] as $c) {
    $r = $conn->query("SELECT COUNT(*) as cnt FROM enrollments WHERE course='$c'")->fetch_assoc();
    $course_counts[$c] = $r['cnt'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transform U | Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --green: #10b981; --dark: #0a0f1e; --dark2: #111827; --muted: #94a3b8; --border: rgba(255,255,255,0.08); --red: #ef4444; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DM Sans', sans-serif; background: var(--dark); color: white; min-height: 100vh; }
        /* LOGIN */
        .login-screen { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(ellipse 80% 60% at 50% 30%, rgba(79,70,229,0.15) 0%, transparent 60%), var(--dark); }
        .login-box { background: var(--dark2); border: 1px solid var(--border); border-radius: 28px; padding: 48px; width: 90%; max-width: 400px; text-align: center; }
        .login-box .lock { font-size: 52px; margin-bottom: 16px; }
        .login-box h2 { font-family: 'Syne', sans-serif; font-size: 28px; font-weight: 800; margin-bottom: 6px; }
        .login-box p { color: var(--muted); font-size: 14px; margin-bottom: 32px; }
        .form-group { margin-bottom: 18px; text-align: left; }
        .form-group label { display: block; font-size: 13px; color: var(--muted); margin-bottom: 8px; }
        .form-group input, .form-group select { width: 100%; padding: 13px 16px; background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 12px; color: white; font-family: inherit; font-size: 15px; transition: 0.3s; }
        .form-group input:focus { outline: none; border-color: var(--primary); }
        .login-btn { width: 100%; padding: 14px; background: var(--primary); color: white; border: none; border-radius: 14px; font-family: 'Syne', sans-serif; font-size: 16px; font-weight: 700; cursor: pointer; transition: 0.3s; }
        .login-btn:hover { background: #3730a3; }
        .alert { padding: 12px 18px; border-radius: 12px; font-size: 13px; margin-bottom: 16px; }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; }
        .alert-success { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: var(--green); }
        /* ADMIN PANEL */
        .admin-nav { background: var(--dark2); border-bottom: 1px solid var(--border); padding: 18px 5%; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; }
        .logo { font-family: 'Syne', sans-serif; font-weight: 900; font-size: 20px; text-decoration: none; color: white; }
        .logo span { color: var(--green); }
        .admin-badge { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 4px 14px; border-radius: 50px; font-size: 12px; font-weight: 600; }
        .logout-btn { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; padding: 8px 18px; border-radius: 50px; font-size: 13px; cursor: pointer; font-family: inherit; text-decoration: none; }
        .admin-content { padding: 40px 5%; }
        .admin-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 36px; }
        .a-stat { background: var(--dark2); border: 1px solid var(--border); border-radius: 20px; padding: 24px; }
        .a-stat-num { font-family: 'Syne', sans-serif; font-size: 32px; font-weight: 900; color: var(--green); }
        .a-stat-label { color: var(--muted); font-size: 13px; margin-top: 6px; }
        /* TABS */
        .tabs { display: flex; gap: 8px; margin-bottom: 24px; }
        .tab-btn { padding: 10px 22px; border-radius: 50px; border: 1px solid var(--border); background: transparent; color: var(--muted); font-family: inherit; font-size: 14px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.3s; }
        .tab-btn.active, .tab-btn:hover { background: var(--primary); color: white; border-color: var(--primary); }
        /* TABLE */
        .table-section { background: var(--dark2); border: 1px solid var(--border); border-radius: 24px; overflow: hidden; }
        .table-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        .table-header h2 { font-family: 'Syne', sans-serif; font-size: 18px; }
        .table-actions { display: flex; gap: 10px; flex-wrap: wrap; }
        .search-input { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 10px; color: white; padding: 8px 14px; font-family: inherit; font-size: 14px; }
        .search-input:focus { outline: none; border-color: var(--green); }
        .filter-select { background: rgba(255,255,255,0.04); border: 1px solid var(--border); border-radius: 10px; color: white; padding: 8px 12px; font-family: inherit; font-size: 14px; }
        .filter-select option { background: var(--dark2); }
        .export-btn { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: var(--green); padding: 8px 16px; border-radius: 10px; font-size: 13px; cursor: pointer; font-family: inherit; font-weight: 600; text-decoration: none; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(255,255,255,0.03); padding: 12px 18px; text-align: left; font-size: 12px; color: var(--muted); text-transform: uppercase; letter-spacing: 1px; white-space: nowrap; }
        td { padding: 14px 18px; border-top: 1px solid var(--border); font-size: 14px; }
        tr:hover td { background: rgba(255,255,255,0.02); }
        .course-pill { display: inline-block; background: rgba(79,70,229,0.1); border: 1px solid rgba(79,70,229,0.25); color: #a5b4fc; padding: 3px 10px; border-radius: 50px; font-size: 12px; white-space: nowrap; }
        .delete-btn { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: #fca5a5; padding: 5px 12px; border-radius: 8px; font-size: 12px; text-decoration: none; }
        .empty-row td { text-align: center; color: var(--muted); padding: 50px; }
        /* SETTINGS */
        .settings-section { margin-top: 24px; background: var(--dark2); border: 1px solid var(--border); border-radius: 24px; padding: 28px; }
        .settings-section h3 { font-family: 'Syne', sans-serif; font-size: 18px; margin-bottom: 20px; }
        .settings-row { display: flex; gap: 14px; flex-wrap: wrap; align-items: flex-end; }
        .settings-row .form-group { flex: 1; min-width: 160px; }
        .change-btn { background: var(--primary); color: white; border: none; padding: 13px 24px; border-radius: 12px; font-family: 'Syne', sans-serif; font-size: 14px; font-weight: 700; cursor: pointer; white-space: nowrap; }
        @media (max-width: 600px) { .admin-content { padding: 24px 4%; } .table-header { flex-direction: column; align-items: flex-start; } }
    </style>
</head>
<body>

<?php if (!isset($_SESSION['admin_logged_in'])): ?>
<!-- LOGIN SCREEN -->
<div class="login-screen">
    <div class="login-box">
        <div class="lock">🔐</div>
        <h2>Admin Login</h2>
        <p>Enter your credentials to access the dashboard.</p>
        <?php if (isset($login_error)): ?>
        <div class="alert alert-error">❌ <?php echo htmlspecialchars($login_error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="form-group"><label>Username</label><input type="text" name="username" value="admin" required></div>
            <div class="form-group"><label>Password</label><input type="password" name="password" placeholder="••••••••" required></div>
            <button type="submit" name="login" class="login-btn">Login to Dashboard →</button>
        </form>
        <p style="color:var(--muted);font-size:12px;margin-top:20px;">Default: admin / admin123</p>
    </div>
</div>

<?php else: ?>
<!-- ADMIN PANEL -->
<nav class="admin-nav">
    <div style="display:flex;align-items:center;gap:12px;">
        <a href="index.php" class="logo">TRANSFORM <span>U</span> 🚀</a>
        <span class="admin-badge">ADMIN</span>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
        <span style="color:var(--muted);font-size:13px;">👤 <?php echo htmlspecialchars($_SESSION['admin_user']); ?></span>
        <a href="admin.php?logout=1" class="logout-btn">Logout</a>
    </div>
</nav>

<div class="admin-content">
    <h1 style="font-family:'Syne',sans-serif;font-size:26px;font-weight:800;margin-bottom:6px;">Admin Dashboard</h1>
    <p style="color:var(--muted);font-size:14px;margin-bottom:28px;">Manage all student enrollments and messages.</p>

    <!-- Stats -->
    <div class="admin-stats">
        <div class="a-stat"><div class="a-stat-num"><?php echo $total; ?></div><div class="a-stat-label">Total Enrollments</div></div>
        <?php foreach($course_counts as $cn => $cnt): ?>
        <div class="a-stat"><div class="a-stat-num"><?php echo $cnt; ?></div><div class="a-stat-label"><?php echo $cn; ?></div></div>
        <?php endforeach; ?>
        <div class="a-stat"><div class="a-stat-num"><?php echo $conn->query("SELECT COUNT(*) as c FROM messages")->fetch_assoc()['c']; ?></div><div class="a-stat-label">Messages</div></div>
    </div>

    <!-- Tabs -->
    <div class="tabs">
        <a href="admin.php?tab=enrollments" class="tab-btn <?php echo $tab=='enrollments'?'active':''; ?>">📋 Enrollments</a>
        <a href="admin.php?tab=messages" class="tab-btn <?php echo $tab=='messages'?'active':''; ?>">✉️ Messages</a>
    </div>

    <?php if($tab == 'enrollments'): ?>
    <!-- Enrollments Table -->
    <div class="table-section">
        <div class="table-header">
            <h2>All Enrollments</h2>
            <div class="table-actions">
                <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
                    <input type="hidden" name="tab" value="enrollments">
                    <input class="search-input" type="text" name="search" placeholder="Search name, phone..." value="<?php echo htmlspecialchars($search); ?>">
                    <select class="filter-select" name="course" onchange="this.form.submit()">
                        <option value="">All Courses</option>
                        <?php foreach(array_keys($course_counts) as $c): ?>
                        <option <?php echo $filter==$c?'selected':''; ?>><?php echo $c; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="export-btn">Search</button>
                </form>
                <a href="export.php" class="export-btn">Export CSV</a>
            </div>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Course</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                <?php if($enrollments->num_rows > 0):
                    $i = 1;
                    while($row = $enrollments->fetch_assoc()): ?>
                <tr>
                    <td style="color:var(--muted);"><?php echo $i++; ?></td>
                    <td><b><?php echo htmlspecialchars($row['name']); ?></b></td>
                    <td style="color:var(--muted);"><?php echo htmlspecialchars($row['email'] ?: '—'); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><span class="course-pill"><?php echo htmlspecialchars($row['course']); ?></span></td>
                    <td style="color:var(--muted);"><?php echo date('d M, Y', strtotime($row['reg_date'])); ?></td>
                    <td><a href="admin.php?delete=<?php echo $row['id']; ?>" class="delete-btn" onclick="return confirm('Delete this enrollment?')">Delete</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr class="empty-row"><td colspan="7">No enrollments found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php else: ?>
    <!-- Messages Table -->
    <div class="table-section">
        <div class="table-header"><h2>Contact Messages</h2></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>#</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                <?php if($messages_res->num_rows > 0):
                    $i = 1;
                    while($row = $messages_res->fetch_assoc()): ?>
                <tr>
                    <td style="color:var(--muted);"><?php echo $i++; ?></td>
                    <td><b><?php echo htmlspecialchars($row['name']); ?></b></td>
                    <td style="color:var(--muted);"><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['subject'] ?: '—'); ?></td>
                    <td style="color:var(--muted);max-width:200px;"><?php echo htmlspecialchars(substr($row['message'],0,60)).'...'; ?></td>
                    <td style="color:var(--muted);"><?php echo date('d M, Y', strtotime($row['sent_date'])); ?></td>
                    <td><a href="admin.php?del_msg=<?php echo $row['id']; ?>&tab=messages" class="delete-btn" onclick="return confirm('Delete this message?')">Delete</a></td>
                </tr>
                <?php endwhile; else: ?>
                <tr class="empty-row"><td colspan="7">No messages yet.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

    <!-- Change Password -->
    <div class="settings-section">
        <h3>⚙️ Change Admin Password</h3>
        <?php if($pass_msg): 
            $type = strpos($pass_msg,'error') === 0 ? 'alert-error' : 'alert-success';
            $msg  = substr($pass_msg, strpos($pass_msg,':')+1);
        ?>
        <div class="alert <?php echo $type; ?>"><?php echo htmlspecialchars($msg); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="settings-row">
                <div class="form-group"><label style="display:block;font-size:13px;color:var(--muted);margin-bottom:8px;">New Password</label><input type="password" name="new_pass" placeholder="New password" class="search-input" style="width:100%;"></div>
                <div class="form-group"><label style="display:block;font-size:13px;color:var(--muted);margin-bottom:8px;">Confirm Password</label><input type="password" name="confirm_pass" placeholder="Confirm password" class="search-input" style="width:100%;"></div>
                <button type="submit" name="change_pass" class="change-btn">Update Password</button>
            </div>
        </form>
    </div>
</div>
<?php endif; ?>
</body>
</html>
