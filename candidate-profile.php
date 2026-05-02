<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
requireLogin();
if (isAdmin()) { header('Location: hr-dashboard.php'); exit; }
$userId = currentUserId();
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$userId]);
$user = $userStmt->fetch();
$appStmt = $pdo->prepare("SELECT applications.*, jobs.title, jobs.company FROM applications JOIN jobs ON jobs.id = applications.job_id WHERE applications.user_id = ? ORDER BY applications.created_at DESC");
$appStmt->execute([$userId]);
$applications = $appStmt->fetchAll();
$pageTitle = 'HireSmart — بروفايلي';
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / بروفايلي</div><h1>بروفايل المتقدم</h1></div></section>
<section class="section"><div class="container layout">
  <div class="card">
    <?php if ($msg = flash('success')): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
    <h2>المعلومات العامة</h2>
    <p><strong>الاسم:</strong> <?= e($user['name']) ?></p>
    <p><strong>الإيميل:</strong> <?= e($user['email']) ?></p>
    <p><strong>المسمى الوظيفي:</strong> <?= e($user['job_title'] ?? 'غير محدد') ?></p>
    <p><strong>المكان:</strong> <?= e($user['location'] ?? 'غير محدد') ?></p>
    <div class="divider"></div>
    <h2>طلبات التقديم</h2>
    <?php foreach ($applications as $app): ?>
      <div class="card" style="margin-top:12px;">
        <div class="job__top"><div><h3><?= e($app['title']) ?></h3><p class="muted"><?= e($app['company']) ?> • <?= formatDate($app['created_at']) ?></p></div><span class="tag"><?= e($app['status']) ?></span></div>
      </div>
    <?php endforeach; ?>
    <?php if (!$applications): ?><div class="alert alert--info">لم تتقدم على أي وظيفة بعد.</div><?php endif; ?>
  </div>
  <aside class="sidebar">
    <div class="card">
      <h3>السيرة الذاتية</h3>
      <?php if (!empty($user['cv_path'])): ?><a class="btn btn--primary" style="width:100%" href="<?= e($user['cv_path']) ?>" target="_blank">عرض CV</a><?php else: ?><div class="alert alert--info">لم يتم رفع CV بعد.</div><?php endif; ?>
      <a class="btn btn--ghost" style="width:100%;margin-top:10px;" href="upload-cv.php">تحديث البيانات</a>
    </div>
  </aside>
</div></section>
<?php include 'includes/footer.php'; ?>
