<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transform U | Skill Up Your Future</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root { --primary: #4f46e5; --green: #10b981; --dark: #0a0f1e; --dark2: #111827; --muted: #94a3b8; --border: rgba(255,255,255,0.08); }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body { font-family: 'DM Sans', sans-serif; background: var(--dark); color: white; overflow-x: hidden; }
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 8%; background: rgba(10,15,30,0.85);
            backdrop-filter: blur(20px); border-bottom: 1px solid var(--border);
        }
        .logo { font-family: 'Syne', sans-serif; font-weight: 900; font-size: 22px; text-decoration: none; color: white; }
        .logo span { color: var(--green); }
        .nav-links { display: flex; gap: 30px; list-style: none; }
        .nav-links a { color: var(--muted); text-decoration: none; font-size: 15px; transition: color 0.3s; }
        .nav-links a:hover { color: white; }
        .nav-cta { background: var(--green); color: white; padding: 10px 24px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.3s; }
        .nav-cta:hover { background: #059669; }
        .hero {
            min-height: 100vh; display: flex; flex-direction: column;
            justify-content: center; align-items: center;
            text-align: center; padding: 120px 8% 80px;
            background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(79,70,229,0.15) 0%, transparent 60%),
                        radial-gradient(ellipse 60% 40% at 80% 80%, rgba(16,185,129,0.08) 0%, transparent 50%), var(--dark);
        }
        .hero-badge { display: inline-flex; align-items: center; gap: 8px; background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.3); color: var(--green); padding: 8px 18px; border-radius: 50px; font-size: 13px; font-weight: 600; margin-bottom: 30px; animation: fadeUp 0.6s ease both; }
        .hero h1 { font-family: 'Syne', sans-serif; font-size: clamp(42px, 7vw, 80px); font-weight: 900; line-height: 1.05; letter-spacing: -2px; margin-bottom: 24px; animation: fadeUp 0.6s 0.1s ease both; }
        .hero h1 .accent { color: var(--green); }
        .hero p { font-size: 18px; color: var(--muted); max-width: 620px; line-height: 1.7; margin-bottom: 44px; animation: fadeUp 0.6s 0.2s ease both; }
        .hero-btns { display: flex; gap: 16px; flex-wrap: wrap; justify-content: center; animation: fadeUp 0.6s 0.3s ease both; }
        .btn-main { background: var(--green); color: white; padding: 16px 36px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 16px; transition: 0.3s; box-shadow: 0 0 40px rgba(16,185,129,0.3); }
        .btn-main:hover { transform: translateY(-2px); box-shadow: 0 0 60px rgba(16,185,129,0.5); }
        .btn-outline { background: transparent; color: white; padding: 16px 36px; border-radius: 50px; text-decoration: none; font-weight: 600; font-size: 16px; border: 1px solid rgba(255,255,255,0.2); transition: 0.3s; }
        .btn-outline:hover { background: rgba(255,255,255,0.05); }
        .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 2px; margin: 80px 8% 0; background: var(--border); border: 1px solid var(--border); border-radius: 24px; overflow: hidden; }
        .stat-box { background: var(--dark2); padding: 40px 30px; text-align: center; }
        .stat-num { font-family: 'Syne', sans-serif; font-size: 48px; font-weight: 900; color: var(--green); line-height: 1; }
        .stat-label { color: var(--muted); font-size: 14px; margin-top: 8px; }
        .section { padding: 100px 8%; }
        .section-label { display: inline-block; background: rgba(79,70,229,0.1); border: 1px solid rgba(79,70,229,0.3); color: #818cf8; padding: 6px 16px; border-radius: 50px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
        .section-title { font-family: 'Syne', sans-serif; font-size: clamp(32px, 4vw, 48px); font-weight: 800; letter-spacing: -1px; margin-bottom: 16px; }
        .section-sub { color: var(--muted); font-size: 16px; max-width: 500px; line-height: 1.6; }
        .features-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; margin-top: 60px; }
        .f-card { background: var(--dark2); border: 1px solid var(--border); border-radius: 20px; padding: 32px; transition: 0.3s; }
        .f-card:hover { border-color: rgba(16,185,129,0.3); transform: translateY(-4px); }
        .f-icon { font-size: 36px; margin-bottom: 20px; }
        .f-card h3 { font-family: 'Syne', sans-serif; font-size: 20px; margin-bottom: 10px; }
        .f-card p { color: var(--muted); font-size: 14px; line-height: 1.6; }
        .courses-preview { background: var(--dark2); border-top: 1px solid var(--border); border-bottom: 1px solid var(--border); }
        .courses-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-top: 60px; }
        .course-card { background: var(--dark); border: 1px solid var(--border); border-radius: 20px; padding: 28px; transition: 0.3s; text-decoration: none; color: white; display: block; }
        .course-card:hover { border-color: var(--green); transform: translateY(-4px); }
        .course-tag { display: inline-block; background: rgba(16,185,129,0.1); color: var(--green); padding: 4px 12px; border-radius: 50px; font-size: 12px; font-weight: 600; margin-bottom: 16px; }
        .course-card h3 { font-family: 'Syne', sans-serif; font-size: 20px; margin-bottom: 8px; }
        .course-card p { color: var(--muted); font-size: 13px; }
        .course-duration { margin-top: 20px; color: var(--muted); font-size: 13px; }
        .testimonials-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; margin-top: 60px; }
        .t-card { background: var(--dark2); border: 1px solid var(--border); border-radius: 20px; padding: 32px; }
        .t-stars { color: #fbbf24; margin-bottom: 16px; font-size: 18px; }
        .t-text { color: #cbd5e1; font-size: 15px; line-height: 1.7; margin-bottom: 24px; }
        .t-author { display: flex; align-items: center; gap: 14px; }
        .t-avatar { width: 46px; height: 46px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--green)); display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 800; font-size: 18px; }
        .t-name { font-weight: 600; font-size: 15px; }
        .t-role { color: var(--muted); font-size: 13px; }
        .cta-section { margin: 80px 8%; border-radius: 28px; padding: 80px 60px; background: linear-gradient(135deg, rgba(79,70,229,0.2) 0%, rgba(16,185,129,0.1) 100%); border: 1px solid rgba(79,70,229,0.3); text-align: center; }
        .cta-section h2 { font-family: 'Syne', sans-serif; font-size: clamp(28px, 4vw, 44px); font-weight: 800; margin-bottom: 16px; }
        .cta-section p { color: var(--muted); font-size: 16px; margin-bottom: 36px; }
        footer { border-top: 1px solid var(--border); padding: 60px 8% 30px; }
        .footer-top { display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px; margin-bottom: 50px; }
        .footer-brand p { color: var(--muted); font-size: 14px; line-height: 1.7; margin-top: 12px; max-width: 260px; }
        .footer-col h4 { font-family: 'Syne', sans-serif; font-size: 15px; margin-bottom: 18px; }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 10px; }
        .footer-col a { color: var(--muted); text-decoration: none; font-size: 14px; transition: color 0.3s; }
        .footer-col a:hover { color: white; }
        .footer-bottom { border-top: 1px solid var(--border); padding-top: 24px; display: flex; justify-content: space-between; align-items: center; }
        .footer-bottom p { color: var(--muted); font-size: 13px; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { nav { padding: 16px 5%; } .nav-links { display: none; } .hero, .section { padding-left: 5%; padding-right: 5%; } .stats { margin: 60px 5% 0; } .footer-top { grid-template-columns: 1fr 1fr; } .cta-section { margin: 40px 5%; padding: 50px 30px; } }
    </style>
</head>
<body>

<nav>
    <a href="index.php" class="logo">TRANSFORM <span>U</span> 🚀</a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="courses.php">Courses</a></li>
        <li><a href="about.php">About</a></li>
        <li><a href="contact.php">Contact</a></li>
    </ul>
    <a href="courses.php" class="nav-cta">Enroll Now →</a>
</nav>

<section class="hero">
    <div class="hero-badge">🎓 Bangladesh's Fastest Growing IT Academy</div>
    <h1>Skill Up Your Future<br>with <span class="accent">Transform U</span></h1>
    <p>From complete beginner to confident professional — our expert-led courses are designed for the global job market of 2026.</p>
    <div class="hero-btns">
        <a href="courses.php" class="btn-main">Explore Courses →</a>
        <a href="about.php" class="btn-outline">Learn More</a>
    </div>
</section>

<div class="stats">
    <div class="stat-box"><div class="stat-num" data-target="500">0</div><div class="stat-label">Students Enrolled</div></div>
    <div class="stat-box"><div class="stat-num" data-target="4">0</div><div class="stat-label">Expert Courses</div></div>
    <div class="stat-box"><div class="stat-num" data-target="98">0</div><div class="stat-label">% Success Rate</div></div>
    <div class="stat-box"><div class="stat-num" data-target="3">0</div><div class="stat-label">Years of Excellence</div></div>
</div>

<section class="section">
    <div class="section-label">Why Transform U?</div>
    <h2 class="section-title">Everything You Need to Succeed</h2>
    <p class="section-sub">We provide more than just courses — a complete career transformation.</p>
    <div class="features-grid">
        <div class="f-card"><div class="f-icon">🎯</div><h3>Industry Curriculum</h3><p>Learn exactly what top companies are looking for. Updated every quarter.</p></div>
        <div class="f-card"><div class="f-icon">🛠️</div><h3>Hands-on Projects</h3><p>Build real-world applications and portfolios from day one.</p></div>
        <div class="f-card"><div class="f-icon">👨‍💼</div><h3>Expert Mentorship</h3><p>Learn from professionals with 5+ years of industry experience.</p></div>
        <div class="f-card"><div class="f-icon">⏰</div><h3>Flexible Scheduling</h3><p>Morning, afternoon, evening, and night batches available.</p></div>
        <div class="f-card"><div class="f-icon">📜</div><h3>Certification</h3><p>Earn a recognized certificate upon completion.</p></div>
        <div class="f-card"><div class="f-icon">🤝</div><h3>Job Support</h3><p>CV review and interview prep to help you land your first job.</p></div>
    </div>
</section>

<section class="section courses-preview">
    <div class="section-label">Our Courses</div>
    <h2 class="section-title">Start Your Journey Today</h2>
    <p class="section-sub">4 professional courses designed to make you job-ready.</p>
    <div class="courses-grid">
        <a href="courses.php" class="course-card"><div class="course-tag">💻 Tech</div><h3>Web Development</h3><p>HTML, CSS, JavaScript, React JS, Node.js</p><div class="course-duration">⏱️ 6 Months · Evening (8 PM)</div></a>
        <a href="courses.php" class="course-card"><div class="course-tag">🎨 Design</div><h3>Graphics Design</h3><p>Photoshop, Illustrator, Branding, UI/UX</p><div class="course-duration">⏱️ 4 Months · Afternoon (3 PM)</div></a>
        <a href="courses.php" class="course-card"><div class="course-tag">📣 Marketing</div><h3>Digital Marketing</h3><p>SEO, Social Media Ads, Email Marketing</p><div class="course-duration">⏱️ 3 Months · Night (9 PM)</div></a>
        <a href="courses.php" class="course-card"><div class="course-tag">⚙️ Engineering</div><h3>Software Engineering</h3><p>Data Structures, Algorithms, System Design</p><div class="course-duration">⏱️ 12 Months · Morning (10 AM)</div></a>
    </div>
</section>

<section class="section">
    <div class="section-label">Student Reviews</div>
    <h2 class="section-title">What Our Students Say</h2>
    <div class="testimonials-grid">
        <div class="t-card"><div class="t-stars">★★★★★</div><p class="t-text">"Transform U changed my life. I went from knowing nothing about coding to landing a job as a junior developer in just 8 months!"</p><div class="t-author"><div class="t-avatar">R</div><div><div class="t-name">Rahim Uddin</div><div class="t-role">Web Developer · Dhaka</div></div></div></div>
        <div class="t-card"><div class="t-stars">★★★★★</div><p class="t-text">"The Digital Marketing course is incredibly practical. I was running ads for my own business within the first month!"</p><div class="t-author"><div class="t-avatar">S</div><div><div class="t-name">Sumaiya Akter</div><div class="t-role">Digital Marketer · Chittagong</div></div></div></div>
        <div class="t-card"><div class="t-stars">★★★★★</div><p class="t-text">"Best Graphics Design course in Bangladesh. I got my first freelance client before even finishing the course!"</p><div class="t-author"><div class="t-avatar">K</div><div><div class="t-name">Karim Hassan</div><div class="t-role">Freelance Designer · Sylhet</div></div></div></div>
    </div>
</section>

<div class="cta-section">
    <h2>Ready to Transform Your Future?</h2>
    <p>Join 500+ students who already took the first step.</p>
    <a href="courses.php" class="btn-main">Get Started Now →</a>
</div>

<footer>
    <div class="footer-top">
        <div class="footer-brand">
            <a href="index.php" class="logo">TRANSFORM <span>U</span> 🚀</a>
            <p>Bangladesh's leading online IT academy. Transforming lives through quality tech education since 2022.</p>
        </div>
        <div class="footer-col"><h4>Quick Links</h4><ul><li><a href="index.php">Home</a></li><li><a href="courses.php">Courses</a></li><li><a href="about.php">About Us</a></li><li><a href="contact.php">Contact</a></li></ul></div>
        <div class="footer-col"><h4>Courses</h4><ul><li><a href="courses.php">Web Development</a></li><li><a href="courses.php">Graphics Design</a></li><li><a href="courses.php">Digital Marketing</a></li><li><a href="courses.php">Software Engineering</a></li></ul></div>
        <div class="footer-col"><h4>Contact</h4><ul><li><a href="mailto:info@transformu.com">info@transformu.com</a></li><li><a href="#">+880 170 000 0000</a></li><li><a href="#">Dhaka, Bangladesh</a></li><li><a href="admin.php">Admin Panel</a></li></ul></div>
    </div>
    <div class="footer-bottom"><p>© 2026 Transform U. All rights reserved.</p><p>Made with ❤️ in Bangladesh</p></div>
</footer>

<script>
const counters = document.querySelectorAll('.stat-num');
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const el = entry.target;
            const target = parseInt(el.dataset.target);
            let current = 0;
            const step = target / 60;
            const timer = setInterval(() => {
                current += step;
                if (current >= target) { current = target; clearInterval(timer); }
                el.textContent = Math.floor(current) + (el.dataset.target == "98" ? "%" : "+");
            }, 20);
            observer.unobserve(el);
        }
    });
}, { threshold: 0.5 });
counters.forEach(c => observer.observe(c));
</script>
</body>
</html>
