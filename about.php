<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transform U | About Us</title>
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
        .page-header p { color: var(--muted); font-size: 17px; margin-top: 14px; max-width: 600px; margin-left: auto; margin-right: auto; line-height: 1.7; }
        .section { padding: 80px 8%; }
        .mission-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center; margin-top: 60px; }
        .mission-text h2 { font-family: 'Syne', sans-serif; font-size: 38px; font-weight: 800; letter-spacing: -1px; margin-bottom: 20px; }
        .mission-text p { color: var(--muted); font-size: 16px; line-height: 1.8; margin-bottom: 16px; }
        .mission-visual { background: var(--dark2); border: 1px solid var(--border); border-radius: 28px; padding: 50px; text-align: center; }
        .mission-visual .big-emoji { font-size: 80px; margin-bottom: 20px; display: block; }
        .mission-visual p { color: var(--muted); font-size: 15px; line-height: 1.7; }
        .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 50px; }
        .value-card { background: var(--dark2); border: 1px solid var(--border); border-radius: 20px; padding: 30px; transition: 0.3s; }
        .value-card:hover { border-color: rgba(16,185,129,0.3); transform: translateY(-4px); }
        .value-card .v-icon { font-size: 36px; margin-bottom: 16px; }
        .value-card h3 { font-family: 'Syne', sans-serif; font-size: 18px; margin-bottom: 10px; }
        .value-card p { color: var(--muted); font-size: 14px; line-height: 1.6; }
        .team-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 24px; margin-top: 50px; }
        .team-card { background: var(--dark2); border: 1px solid var(--border); border-radius: 24px; padding: 32px; text-align: center; transition: 0.3s; }
        .team-card:hover { border-color: rgba(79,70,229,0.4); transform: translateY(-4px); }
        .team-avatar { width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--green)); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 900; font-size: 28px; margin: 0 auto 16px; }
        .team-card h3 { font-family: 'Syne', sans-serif; font-size: 18px; margin-bottom: 6px; }
        .team-card .role { color: var(--green); font-size: 13px; font-weight: 600; margin-bottom: 12px; }
        .team-card p { color: var(--muted); font-size: 13px; line-height: 1.6; }
        .stats-band { display: grid; grid-template-columns: repeat(4, 1fr); background: var(--dark2); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .stat-item { padding: 40px 20px; text-align: center; border-right: 1px solid var(--border); }
        .stat-item:last-child { border-right: none; }
        .stat-num { font-family: 'Syne', sans-serif; font-size: 42px; font-weight: 900; color: var(--green); }
        .stat-label { color: var(--muted); font-size: 14px; margin-top: 6px; }
        .cta-section { margin: 0 8% 80px; border-radius: 28px; padding: 70px 60px; background: linear-gradient(135deg, rgba(79,70,229,0.2) 0%, rgba(16,185,129,0.1) 100%); border: 1px solid rgba(79,70,229,0.3); text-align: center; }
        .cta-section h2 { font-family: 'Syne', sans-serif; font-size: 38px; font-weight: 800; margin-bottom: 14px; }
        .cta-section p { color: var(--muted); font-size: 16px; margin-bottom: 32px; }
        .btn-cta { background: var(--green); color: white; padding: 16px 36px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 16px; transition: 0.3s; display: inline-block; }
        .btn-cta:hover { background: #059669; transform: translateY(-2px); }
        footer { border-top: 1px solid var(--border); padding: 30px 8%; text-align: center; }
        footer p { color: var(--muted); font-size: 14px; }
        @media (max-width: 768px) { .nav-links { display: none; } nav { padding: 16px 5%; } .section { padding: 60px 5%; } .mission-grid { grid-template-columns: 1fr; gap: 30px; } .stats-band { grid-template-columns: 1fr 1fr; } .cta-section { margin: 0 5% 60px; padding: 40px 24px; } }
    </style>
</head>
<body>
<nav>
    <a href="index.php" class="logo">TRANSFORM <span>U</span> 🚀</a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="courses.php">Courses</a></li>
        <li><a href="about.php" class="active">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="courses.php" class="btn-green">Enroll Now →</a>
</nav>

<div class="page-header">
    <div class="section-label">Our Story</div>
    <h1>About Transform U</h1>
    <p>We started with a simple mission: make world-class tech education accessible to every Bangladeshi student.</p>
</div>

<section class="section">
    <div class="mission-grid">
        <div class="mission-text">
            <h2>We Transform Careers, Not Just Skills</h2>
            <p>Founded in 2022, Transform U was born from a frustration — quality tech education was either too expensive, too theoretical, or too far away for most people in Bangladesh.</p>
            <p>We set out to change that. Our courses are designed by people who actually work in the industry, taught in a way that makes real-world sense, and priced to be accessible to every motivated learner.</p>
            <p>Today, 500+ students have gone through our programs and landed real jobs — as developers, designers, marketers, and engineers.</p>
        </div>
        <div class="mission-visual">
            <span class="big-emoji">🚀</span>
            <p>"Our goal is not just to teach you a skill — it's to help you build a career you're proud of."</p>
            <p style="color: var(--green); font-weight: 600; margin-top: 16px; font-size: 14px;">— Transform U Team</p>
        </div>
    </div>
</section>

<div class="stats-band">
    <div class="stat-item"><div class="stat-num">500+</div><div class="stat-label">Students Graduated</div></div>
    <div class="stat-item"><div class="stat-num">4</div><div class="stat-label">Professional Courses</div></div>
    <div class="stat-item"><div class="stat-num">98%</div><div class="stat-label">Satisfaction Rate</div></div>
    <div class="stat-item"><div class="stat-num">3yrs</div><div class="stat-label">Industry Experience</div></div>
</div>

<section class="section">
    <div class="section-label">Our Values</div>
    <h2 style="font-family:'Syne',sans-serif;font-size:38px;font-weight:800;letter-spacing:-1px;margin-top:12px;">What We Stand For</h2>
    <div class="values-grid">
        <div class="value-card"><div class="v-icon">🎯</div><h3>Practical First</h3><p>Every lesson connects to real work. No fluff — just skills you can use immediately.</p></div>
        <div class="value-card"><div class="v-icon">🌍</div><h3>Global Standards</h3><p>We teach to international industry standards so you can compete globally.</p></div>
        <div class="value-card"><div class="v-icon">❤️</div><h3>Student-Centered</h3><p>Our success is measured by your success. We're invested in your journey.</p></div>
        <div class="value-card"><div class="v-icon">🔄</div><h3>Always Updated</h3><p>Our curriculum is reviewed every quarter to stay current and relevant.</p></div>
    </div>
</section>

<section class="section" style="padding-top:0;">
    <div class="section-label">Our Team</div>
    <h2 style="font-family:'Syne',sans-serif;font-size:38px;font-weight:800;letter-spacing:-1px;margin-top:12px;">Meet the Instructors</h2>
    <div class="team-grid">
        <div class="team-card"><div class="team-avatar">A</div><h3>Arif Rahman</h3><div class="role">Lead Web Dev Instructor</div><p>5+ years at top tech companies. React & Node specialist.</p></div>
        <div class="team-card"><div class="team-avatar">N</div><h3>Nadia Islam</h3><div class="role">Graphics Design Expert</div><p>International design awards winner. Adobe Certified Expert.</p></div>
        <div class="team-card"><div class="team-avatar">T</div><h3>Tanvir Ahmed</h3><div class="role">Digital Marketing Lead</div><p>Managed $1M+ in ad spend. Google & Meta certified.</p></div>
        <div class="team-card"><div class="team-avatar">R</div><h3>Rina Khatun</h3><div class="role">Software Engineering</div><p>Ex-FAANG engineer. Algorithms & System Design expert.</p></div>
    </div>
</section>

<div class="cta-section">
    <h2>Ready to Join Us?</h2>
    <p>Start your journey with Transform U today.</p>
    <a href="courses.php" class="btn-cta">Browse Courses →</a>
</div>

<footer><p>© 2026 Transform U. Made with ❤️ in Bangladesh</p></footer>
</body>
</html>
