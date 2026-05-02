<?php
require_once 'includes/functions.php';
$pageTitle = 'HireSmart — الرئيسية';
include 'includes/header.php';
?>
<section class="hero">
  <div class="container hero__grid">
    <div class="hero__panel">
      <h1>اعثر على الوظيفة المناسبة أو المرشح الأنسب بسرعة</h1>
      <p class="muted">منصة توظيف متكاملة تدعم تسجيل المستخدمين، رفع السيرة الذاتية، التقديم على الوظائف، ولوحة HR لإدارة المرشحين.</p>
      <p>المشروع مبني بتقنيات <strong>PHP + MySQL + HTML/CSS/JavaScript</strong> ومناسب للتسليم كمشروع Full Stack.</p>
      <div class="hero__cta">
        <a class="btn btn--primary" href="jobs.php">تصفح الوظائف</a>
        <a class="btn btn--ghost" href="auth.php">أنشئ حسابًا</a>
      </div>
      <div class="stats">
        <div class="stat"><strong>+120</strong><span class="muted">شركة نشطة</span></div>
        <div class="stat"><strong>+1240</strong><span class="muted">وظيفة متاحة</span></div>
        <div class="stat"><strong>92%</strong><span class="muted">دقة ترشيح</span></div>
        <div class="stat"><strong>24/7</strong><span class="muted">خدمة مستمرة</span></div>
      </div>
    </div>
    <div class="hero-illustration hero__panel">
      <div class="hero-illustration__box">
        <h3>رحلة المستخدم</h3>
        <div class="divider"></div>
        <p><strong>1)</strong> يسجل حسابه</p>
        <p><strong>2)</strong> يرفع السيرة الذاتية والمهارات</p>
        <p><strong>3)</strong> يتقدم للوظائف المناسبة</p>
        <p><strong>4)</strong> تراجع HR الطلبات وتحدث الحالة</p>
      </div>
    </div>
  </div>
</section>
<section class="section">
  <div class="container grid grid-3">
    <div class="card"><h3>للمتقدمين</h3><p class="muted">حساب شخصي، رفع CV، متابعة الطلبات.</p></div>
    <div class="card"><h3>للشركات وHR</h3><p class="muted">عرض المتقدمين وتحديث حالاتهم.</p></div>
    <div class="card"><h3>قاعدة بيانات فعلية</h3><p class="muted">كل شيء محفوظ في MySQL وقابل للتطوير.</p></div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
