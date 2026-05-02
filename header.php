<?php
require_once __DIR__ . '/auth.php';
if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
?>
<!doctype html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($pageTitle) ? e($pageTitle) : 'HireSmart' ?></title>
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,
<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'>
<style>
@keyframes spin {0%{transform:rotate(0)}50%{transform:rotate(10deg)}100%{transform:rotate(0)}}
@keyframes pulse {0%{transform:scale(1)}50%{transform:scale(1.1)}100%{transform:scale(1)}}
.rotate{transform-origin:center;animation:spin 3s infinite linear}
.pulse{transform-origin:center;animation:pulse 2s infinite}
</style>

<g class='rotate'>
<circle cx='28' cy='28' r='16' stroke='%230a2540' stroke-width='4' fill='none'/>
<line x1='40' y1='40' x2='55' y2='55' stroke='%230a2540' stroke-width='4' stroke-linecap='round'/>
</g>

<g class='pulse'>
<circle cx='28' cy='24' r='5' fill='%230a2540'/>
<rect x='24' y='28' width='8' height='10' rx='4' fill='%230a2540'/>
<polygon points='28,28 31,36 28,40 25,36' fill='%231d4ed8'/>
</g>

</svg>">

  <link rel="stylesheet" href="assets/css/styles.css">
  <script src="assets/js/main.js" defer></script>
</head>
<body>
<header class="topbar">
  <div class="container topbar__inner">
    <a class="brand" href="index.php">
      <span class="brand__dot"></span>
      <span class="brand__name">HireSmart</span>
    </a>
    <nav class="nav">
      <a href="index.php">الرئيسية</a>
      <a href="jobs.php">الوظائف</a>
      <?php if (isLoggedIn() && !isAdmin()): ?>
        <a href="upload-cv.php">رفع السيرة</a>
        <a href="candidate-profile.php">بروفايلي</a>
      <?php endif; ?>
      <?php if (isAdmin()): ?>
        <a href="hr-dashboard.php">لوحة HR</a>
      <?php endif; ?>
      <a href="about.php">عنّا</a>
      <a href="contact.php">تواصل</a>
    </nav>
    <div class="topbar__actions">
      <?php if (isLoggedIn()): ?>
        <span class="welcome">مرحبًا، <?= e($_SESSION['user']['name'] ?? 'مستخدم') ?></span>
        <a class="btn btn--ghost" href="logout.php">تسجيل خروج</a>
      <?php else: ?>
        <a class="btn btn--ghost" href="auth.php">تسجيل / دخول</a>
      <?php endif; ?>
      <a class="btn btn--primary" href="jobs.php">ابدأ الآن</a>
    </div>
  </div>
</header>
<main>
