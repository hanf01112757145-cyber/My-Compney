<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
$pageTitle = 'HireSmart — الوظائف';
$q = trim($_GET['q'] ?? '');
$location = trim($_GET['location'] ?? '');
$level = trim($_GET['level'] ?? '');
$sql = "SELECT * FROM jobs WHERE is_active = 1";
$params = [];
if ($q !== '') {
    $sql .= " AND (title LIKE ? OR company LIKE ? OR skills LIKE ?)";
    $like = "%$q%";
    array_push($params, $like, $like, $like);
}
if ($location !== '') { $sql .= " AND location = ?"; $params[] = $location; }
if ($level !== '') { $sql .= " AND level = ?"; $params[] = $level; }
$sql .= " ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$jobs = $stmt->fetchAll();
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / الوظائف</div><h1>الوظائف</h1><p class="muted">ابحث عن الوظيفة المناسبة.</p></div></section>
<section class="section">
  <div class="container">
    <form class="banner" method="GET">
      <div><strong>فلترة الوظائف</strong></div>
      <div class="filters">
        <label class="field"><span>بحث</span><input type="search" name="q" value="<?= e($q) ?>" placeholder="مثال: Front-End"></label>
        <label class="field"><span>المكان</span><select name="location">
          <option value="">الكل</option>
          <option value="القاهرة" <?= $location === 'القاهرة' ? 'selected' : '' ?>>القاهرة</option>
          <option value="الجيزة" <?= $location === 'الجيزة' ? 'selected' : '' ?>>الجيزة</option>
          <option value="دبي" <?= $location === 'دبي' ? 'selected' : '' ?>>دبي</option>
          <option value="عن بُعد" <?= $location === 'عن بُعد' ? 'selected' : '' ?>>عن بُعد</option>
        </select></label>
        <label class="field"><span>المستوى</span><select name="level">
          <option value="">الكل</option>
          <option value="Junior" <?= $level === 'Junior' ? 'selected' : '' ?>>Junior</option>
          <option value="Mid" <?= $level === 'Mid' ? 'selected' : '' ?>>Mid</option>
          <option value="Senior" <?= $level === 'Senior' ? 'selected' : '' ?>>Senior</option>
        </select></label>
        <div class="actions-inline"><button class="btn btn--primary" type="submit">تطبيق</button><a class="btn btn--ghost" href="jobs.php">إعادة ضبط</a></div>
      </div>
    </form>
    <div class="jobs" style="margin-top:16px;">
      <?php foreach ($jobs as $job): ?>
      <article class="card">
        <div class="job__top">
          <div><h3><?= e($job['title']) ?></h3><p class="muted"><?= e($job['company']) ?> • <?= e($job['location']) ?> • <?= e($job['employment_type']) ?></p></div>
          <span class="chip"><?= e($job['level']) ?></span>
        </div>
        <p><?= e($job['description']) ?></p>
        <div class="tags"><?php foreach (explode(',', $job['skills']) as $skill): ?><span class="tag"><?= e(trim($skill)) ?></span><?php endforeach; ?></div>
        <div class="divider"></div>
        <a class="btn btn--primary btn--sm" href="job-details.php?id=<?= (int)$job['id'] ?>">تفاصيل</a>
      </article>
      <?php endforeach; ?>
      <?php if (!$jobs): ?><div class="card center"><p class="muted">لا توجد نتائج.</p></div><?php endif; ?>
    </div>
  </div>
</section>
<?php include 'includes/footer.php'; ?>
