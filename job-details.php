<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
$jobId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM jobs WHERE id = ? AND is_active = 1");
$stmt->execute([$jobId]);
$job = $stmt->fetch();
if (!$job) { header('Location: jobs.php'); exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apply'])) {
    requireLogin();
    if (isAdmin()) {
        flash('error', 'حسابات HR لا يمكنها التقديم.');
        header("Location: job-details.php?id=".$jobId); exit;
    }
    $check = $pdo->prepare("SELECT id FROM applications WHERE user_id = ? AND job_id = ?");
    $check->execute([currentUserId(), $jobId]);
    if ($check->fetch()) {
        flash('error', 'لقد تقدمت بالفعل على هذه الوظيفة.');
    } else {
        $coverLetter = trim($_POST['cover_letter'] ?? '');
        $insert = $pdo->prepare("INSERT INTO applications (user_id, job_id, cover_letter) VALUES (?, ?, ?)");
        $insert->execute([currentUserId(), $jobId, $coverLetter]);
        flash('success', 'تم التقديم بنجاح.');
    }
    header("Location: job-details.php?id=".$jobId); exit;
}
$pageTitle = 'HireSmart — تفاصيل الوظيفة';
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / الوظائف / تفاصيل</div><h1><?= e($job['title']) ?></h1><div class="kv"><span class="tag"><?= e($job['company']) ?></span><span class="tag"><?= e($job['location']) ?></span><span class="tag"><?= e($job['level']) ?></span><span class="tag"><?= e($job['salary']) ?></span></div></div></section>
<section class="section"><div class="container layout">
  <div class="card">
    <?php if ($msg = flash('success')): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="alert alert--error"><?= e($msg) ?></div><?php endif; ?>
    <h2>وصف الوظيفة</h2><p><?= nl2br(e($job['description'])) ?></p>
    <div class="divider"></div>
    <h2>المهام</h2><ul class="list"><?php foreach (explode("\n", $job['responsibilities']) as $item): if (trim($item)!==''): ?><li><?= e(trim($item)) ?></li><?php endif; endforeach; ?></ul>
    <div class="divider"></div>
    <h2>المتطلبات</h2><ul class="list"><?php foreach (explode("\n", $job['requirements']) as $item): if (trim($item)!==''): ?><li><?= e(trim($item)) ?></li><?php endif; endforeach; ?></ul>
  </div>
  <aside class="sidebar">
    <div class="card">
      <h3>التقديم السريع</h3>
      <?php if (!isLoggedIn()): ?>
        <a class="btn btn--primary" style="width:100%" href="auth.php">سجّل أولًا</a>
      <?php elseif (isAdmin()): ?>
        <div class="alert alert--info">أنت مسجل كـ HR.</div>
      <?php else: ?>
        <form method="POST">
          <label class="field"><span>رسالة تعريفية</span><textarea name="cover_letter" placeholder="اكتب رسالة مختصرة..."></textarea></label>
          <button class="btn btn--primary" style="width:100%" type="submit" name="apply">قدّم الآن</button>
        </form>
      <?php endif; ?>
    </div>
  </aside>
</div></section>
<?php include 'includes/footer.php'; ?>
