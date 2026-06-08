<?php
include 'config.php';

$status = null;
$name_display = '';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $name_display = htmlspecialchars($name);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = 'error';
        $message = 'That email address doesn\'t look right. Please go back and try again.';
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (name, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $email);
        if ($stmt->execute()) {
            $status = 'success';
            $message = 'Your message has been received. I\'ll be in touch soon.';
        } else {
            $status = 'error';
            $message = 'Something went wrong on our end. Please try again later.';
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title><?php echo $status === 'success' ? 'Message Received' : 'Oops!'; ?> | Anirban</title>
  <link rel="icon" type="image/png" href="Images/logo.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer"/>
  <style>
    *, *::before, *::after {
      margin: 0; padding: 0;
      box-sizing: border-box;
      text-decoration: none;
      border: none; outline: none;
    }

    :root {
      --navy: #0a1f3e;
      --gold: #d4af37;
      --gold-dim: rgba(212, 175, 55, 0.12);
      --gold-glow: rgba(212, 175, 55, 0.4);
      --gold-border: rgba(212, 175, 55, 0.25);
      --white: #ffffff;
      --muted: rgba(255,255,255,0.55);
    }

    html { font-size: 62.5%; scroll-behavior: smooth; }

    body {
      width: 100%;
      min-height: 100vh;
      background-color: var(--navy);
      color: var(--white);
      font-family: 'Syne', sans-serif;
      cursor: none;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      position: relative;
      overflow: hidden;
    }

    /* Custom cursor */
    .cursor {
      width: 10px; height: 10px;
      background: var(--gold);
      border-radius: 50%;
      position: fixed;
      pointer-events: none;
      z-index: 9999;
      transform: translate(-50%, -50%);
    }
    .cursor-ring {
      width: 36px; height: 36px;
      border: 1.5px solid var(--gold);
      border-radius: 50%;
      position: fixed;
      pointer-events: none;
      z-index: 9998;
      transform: translate(-50%, -50%);
      opacity: 0.6;
      transition: width 0.3s, height 0.3s, opacity 0.3s;
    }
    body:has(a:hover) .cursor-ring { width: 60px; height: 60px; opacity: 0.3; }

    /* Grid lines */
    body::after {
      content: '';
      position: fixed; inset: 0;
      background-image:
        linear-gradient(rgba(212,175,55,0.03) 1px, transparent 1px),
        linear-gradient(90deg, rgba(212,175,55,0.03) 1px, transparent 1px);
      background-size: 60px 60px;
      pointer-events: none;
      z-index: 0;
    }

    /* Ambient gold glow */
    body::before {
      content: '';
      position: fixed;
      width: 600px; height: 600px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(212,175,55,0.07) 0%, transparent 70%);
      top: 50%; left: 50%;
      transform: translate(-50%, -50%);
      pointer-events: none;
      z-index: 0;
    }

    /* Header */
    header {
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      padding: 2rem 8%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      z-index: 100;
      background: rgba(10, 31, 62, 0.75);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border-bottom: 1px solid rgba(212,175,55,0.12);
    }

    .logo {
      font-family: 'Space Mono', monospace;
      font-size: 2.4rem;
      color: var(--gold);
      font-weight: 700;
      letter-spacing: -0.02em;
      cursor: none;
      position: relative;
    }
    .logo::after {
      content: '';
      position: absolute;
      bottom: -3px; left: 0;
      width: 100%; height: 1px;
      background: var(--gold);
      transform: scaleX(0);
      transform-origin: right;
      transition: transform 0.4s ease;
    }
    .logo:hover::after { transform: scaleX(1); transform-origin: left; }

    nav a {
      font-family: 'Space Mono', monospace;
      font-size: 1.3rem;
      color: var(--muted);
      margin-left: 3.5rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      transition: color 0.3s;
      cursor: none;
      position: relative;
    }
    nav a::after {
      content: '';
      position: absolute;
      bottom: -4px; left: 0;
      width: 100%; height: 1px;
      background: var(--gold);
      transform: scaleX(0);
      transform-origin: right;
      transition: transform 0.35s;
    }
    nav a:hover { color: var(--gold); }
    nav a:hover::after { transform: scaleX(1); transform-origin: left; }

    /* Decorative vertical line */
    .deco-line {
      position: fixed;
      top: 15%; right: 5%;
      width: 1px; height: 40%;
      background: linear-gradient(to bottom, transparent, var(--gold), transparent);
      opacity: 0.2;
      z-index: 0;
    }

    /* Main card */
    .card {
      position: relative;
      z-index: 1;
      background: rgba(255,255,255,0.02);
      border: 1px solid var(--gold-border);
      border-radius: 4px;
      padding: 6rem 5.5rem 5rem;
      max-width: 520px;
      width: 90%;
      text-align: center;
      box-shadow:
        0 0 0 1px rgba(212,175,55,0.04),
        0 40px 80px rgba(0,0,0,0.45),
        0 0 80px rgba(212,175,55,0.05);
      animation: fadeUp 0.7s cubic-bezier(0.16, 1, 0.3, 1) both;
    }

    /* Corner brackets — same as your .img-frame */
    .card::before,
    .card::after,
    .card .corner-br,
    .card .corner-tl {
      content: '';
      position: absolute;
      width: 20px; height: 20px;
      border-color: var(--gold);
      border-style: solid;
    }
    .card::before { top: -8px; right: -8px; border-width: 2px 2px 0 0; }
    .card::after  { bottom: -8px; left: -8px; border-width: 0 0 2px 2px; }
    .card .corner-br { bottom: -8px; right: -8px; border-width: 0 2px 2px 0; }
    .card .corner-tl { top: -8px; left: -8px; border-width: 2px 0 0 2px; }

    /* Status icon */
    .icon-wrap {
      width: 72px; height: 72px;
      border-radius: 50%;
      margin: 0 auto 3rem;
      display: flex; align-items: center; justify-content: center;
      background: var(--gold-dim);
      border: 1px solid var(--gold-border);
      box-shadow: 0 0 30px rgba(212,175,55,0.12);
      animation: pop 0.5s 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) both;
    }
    .icon-wrap svg {
      width: 30px; height: 30px;
      stroke: var(--gold);
      stroke-width: 2.5;
      fill: none;
      stroke-linecap: round;
      stroke-linejoin: round;
    }
    .icon-wrap svg path,
    .icon-wrap svg polyline {
      stroke-dasharray: 100;
      stroke-dashoffset: 100;
      animation: draw 0.6s 0.85s ease forwards;
    }

    /* Eyebrow */
    .eyebrow {
      font-family: 'Space Mono', monospace;
      font-size: 1.1rem;
      letter-spacing: 0.22em;
      color: var(--gold);
      text-transform: uppercase;
      margin-bottom: 1.4rem;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      opacity: 0;
      animation: fadeUp 0.6s 0.5s ease forwards;
    }
    .eyebrow::before, .eyebrow::after {
      content: '';
      display: inline-block;
      width: 24px; height: 1px;
      background: var(--gold);
      opacity: 0.5;
    }

    h1 {
      font-family: 'Syne', sans-serif;
      font-size: 4.2rem;
      font-weight: 800;
      letter-spacing: -0.03em;
      color: var(--white);
      margin-bottom: 1.4rem;
      line-height: 1.1;
      opacity: 0;
      animation: fadeUp 0.6s 0.6s ease forwards;
    }
    h1 span { color: var(--gold); }

    .subtitle {
      font-family: 'Space Mono', monospace;
      font-size: 1.3rem;
      color: var(--muted);
      line-height: 1.85;
      margin-bottom: 3.5rem;
      opacity: 0;
      animation: fadeUp 0.6s 0.7s ease forwards;
    }

    .divider {
      width: 48px; height: 1px;
      background: linear-gradient(to right, transparent, var(--gold), transparent);
      margin: 0 auto 3rem;
      opacity: 0.4;
    }

    /* Redirect section */
    .redirect-wrap {
      opacity: 0;
      animation: fadeUp 0.6s 0.85s ease forwards;
    }

    .redirect-info {
      font-family: 'Space Mono', monospace;
      font-size: 1.2rem;
      color: var(--muted);
      margin-bottom: 1.6rem;
      letter-spacing: 0.04em;
    }
    .redirect-info span {
      color: var(--gold);
      font-weight: 700;
    }

    .progress-bar-wrap {
      height: 2px;
      background: rgba(212,175,55,0.08);
      border-radius: 999px;
      overflow: hidden;
      margin-bottom: 3rem;
    }
    .progress-bar {
      height: 100%;
      width: 100%;
      border-radius: 999px;
      background: linear-gradient(to right, rgba(212,175,55,0.3), var(--gold));
      box-shadow: 0 0 8px var(--gold-glow);
      transform-origin: left;
      animation: drain 9s linear forwards;
    }

    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 1rem;
      font-family: 'Space Mono', monospace;
      font-size: 1.2rem;
      letter-spacing: 0.1em;
      text-transform: uppercase;
      padding: 1.3rem 3rem;
      border: 1px solid var(--gold-border);
      color: var(--gold);
      border-radius: 2px;
      cursor: none;
      transition: border-color 0.25s, background 0.25s, transform 0.25s, box-shadow 0.25s;
    }
    .back-btn:hover {
      border-color: var(--gold);
      background: var(--gold-dim);
      transform: translateY(-3px);
      box-shadow: 0 8px 30px var(--gold-glow);
    }
    .back-btn svg {
      width: 14px; height: 14px;
      stroke: currentColor; fill: none;
      stroke-width: 2;
      stroke-linecap: round; stroke-linejoin: round;
      transition: transform 0.25s;
    }
    .back-btn:hover svg { transform: translateX(-4px); }

    /* Animations */
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    @keyframes pop {
      from { transform: scale(0); opacity: 0; }
      to   { transform: scale(1); opacity: 1; }
    }
    @keyframes draw { to { stroke-dashoffset: 0; } }
    @keyframes drain {
      from { transform: scaleX(1); }
      to   { transform: scaleX(0); }
    }

    @media (max-width: 600px) {
      .card { padding: 5rem 2.8rem 4rem; }
      h1 { font-size: 3rem; }
      nav { display: none; }
    }
  </style>
</head>
<body>

<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>
<div class="deco-line"></div>

<header>
  <a href="index.html" class="logo">Anirban.</a>
  <nav>
    <a href="index.html">Home</a>
    <a href="skills.html">Skills</a>
    <a href="projects.html">Projects</a>
    <a href="about.html">About Me</a>
  </nav>
</header>

<div class="card">
  <div class="corner-tl"></div>
  <div class="corner-br"></div>

  <div class="icon-wrap">
    <?php if ($status === 'success'): ?>
    <svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
    <?php else: ?>
    <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
    <?php endif; ?>
  </div>

  <p class="eyebrow"><?php echo $status === 'success' ? 'Message Received' : 'Error'; ?></p>

  <h1>
    <?php if ($status === 'success'): ?>
      Thank You<?php if ($name_display): ?>, <span><?php echo $name_display; ?></span><?php endif; ?>!
    <?php else: ?>
      Oh <span>no…</span>
    <?php endif; ?>
  </h1>

  <p class="subtitle"><?php echo htmlspecialchars($message); ?></p>

  <div class="divider"></div>

  <div class="redirect-wrap">
    <p class="redirect-info">→ Redirecting to homepage in <span id="count">9</span>s</p>
    <div class="progress-bar-wrap">
      <div class="progress-bar"></div>
    </div>
    <a href="index.html" class="back-btn">
      <svg viewBox="0 0 24 24"><path d="M19 12H5"/><path d="M12 5l-7 7 7 7"/></svg>
      Go Home
    </a>
  </div>
</div>

<script>
  // Gold cursor (matching your logic.js)
  const cursor = document.getElementById('cursor');
  const ring   = document.getElementById('cursorRing');
  let mx = 0, my = 0, rx = 0, ry = 0;
  document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
  (function tick() {
    cursor.style.left = mx + 'px'; cursor.style.top = my + 'px';
    rx += (mx - rx) * 0.12; ry += (my - ry) * 0.12;
    ring.style.left = rx + 'px'; ring.style.top  = ry + 'px';
    requestAnimationFrame(tick);
  })();

  // Countdown + redirect
  let s = 9;
  const el = document.getElementById('count');
  const timer = setInterval(() => {
    s--;
    el.textContent = s;
    if (s <= 0) { clearInterval(timer); window.location.href = 'index.html'; }
  }, 1000);
</script>
</body>
</html>
