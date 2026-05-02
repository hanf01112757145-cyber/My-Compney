<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
requireAdmin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['application_id'], $_POST['status'])) {
    $applicationId = (int)$_POST['application_id'];
    $status = $_POST['status'];
    $allowed = ['new', 'reviewed', 'interview', 'accepted', 'rejected'];
    if (in_array($status, $allowed, true)) {
        $stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
        $stmt->execute([$status, $applicationId]);
        flash('success', 'تم تحديث الحالة بنجاح.');
    }
    header('Location: hr-dashboard.php'); exit;
}
$jobs = $pdo->query("SELECT id, title FROM jobs WHERE is_active = 1 ORDER BY title")->fetchAll();
$jobFilter = trim($_GET['job_id'] ?? '');
$statusFilter = trim($_GET['status'] ?? '');
$sql = "SELECT applications.*, users.name AS candidate_name, users.email, users.phone, users.cv_path, users.skills AS user_skills, jobs.title AS job_title, jobs.company FROM applications JOIN users ON users.id = applications.user_id JOIN jobs ON jobs.id = applications.job_id WHERE 1=1";
$params = [];
if ($jobFilter !== '') { $sql .= " AND jobs.id = ?"; $params[] = $jobFilter; }
if ($statusFilter !== '') { $sql .= " AND applications.status = ?"; $params[] = $statusFilter; }
$sql .= " ORDER BY applications.created_at DESC";
$stmt = $pdo->prepare($sql); $stmt->execute($params); $applications = $stmt->fetchAll();
$pageTitle = 'HireSmart — لوحة HR';
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / لوحة HR</div><h1>لوحة تحكم HR</h1></div></section>
<section class="section"><div class="container">
  <?php if ($msg = flash('success')): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
  <form class="banner" method="GET">
    <div><strong>فلترة المتقدمين</strong></div>
    <div class="filters">
      <label class="field"><span>الوظيفة</span><select name="job_id"><option value="">كل الوظائف</option><?php foreach ($jobs as $job): ?><option value="<?= (int)$job['id'] ?>" <?= $jobFilter == $job['id'] ? 'selected' : '' ?>><?= e($job['title']) ?></option><?php endforeach; ?></select></label>
      <label class="field"><span>الحالة</span><select name="status"><option value="">كل الحالات</option><?php foreach (['new','reviewed','interview','accepted','rejected'] as $s): ?><option value="<?= $s ?>" <?= $statusFilter === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select></label>
      <div class="actions-inline"><button class="btn btn--primary" type="submit">تطبيق</button></div>
    </div>
  </form>
  <div class="card" style="margin-top:16px;">
    <div class="table-wrap">
      <table>
        <thead><tr><th>الاسم</th><th>الوظيفة</th><th>التواصل</th><th>المهارات</th><th>CV</th><th>الحالة</th></tr></thead>
        <tbody>
          <?php foreach ($applications as $app): ?>
          <tr>
            <td><?= e($app['candidate_name']) ?></td>
            <td><?= e($app['job_title']) ?><br><span class="muted"><?= e($app['company']) ?></span></td>
            <td><?= e($app['email']) ?><br><span class="muted"><?= e($app['phone'] ?? '—') ?></span></td>
            <td><?= e($app['user_skills'] ?? '—') ?></td>
            <td><?php if (!empty($app['cv_path'])): ?><a class="btn btn--ghost btn--sm" target="_blank" href="<?= e($app['cv_path']) ?>">فتح</a><?php else: ?><span class="muted">غير مرفوع</span><?php endif; ?></td>
            <td>
              <form method="POST">
                <input type="hidden" name="application_id" value="<?= (int)$app['id'] ?>">
                <select name="status" onchange="this.form.submit()"><?php foreach (['new','reviewed','interview','accepted','rejected'] as $s): ?><option value="<?= $s ?>" <?= $app['status'] === $s ? 'selected' : '' ?>><?= $s ?></option><?php endforeach; ?></select>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
          <?php if (!$applications): ?><tr><td colspan="6" class="center muted">لا توجد طلبات.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div></section>
<?php include 'includes/footer.php'; ?>
