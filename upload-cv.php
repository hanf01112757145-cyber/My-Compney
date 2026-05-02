<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
requireLogin();
if (isAdmin()) { header('Location: hr-dashboard.php'); exit; }
$userId = currentUserId();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jobTitle = trim($_POST['job_title'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $experienceYears = trim($_POST['experience_years'] ?? '');
    $skills = trim($_POST['skills'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $cvPath = $_SESSION['user']['cv_path'] ?? null;
    if (!empty($_FILES['cv']['name'])) {
        $allowed = ['pdf','doc','docx'];
        $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed, true)) {
            flash('error', 'صيغة الملف غير مدعومة.');
            header('Location: upload-cv.php'); exit;
        }
        $newName = 'cv_'.$userId.'_'.time().'.'.$ext;
        $target = 'uploads/'.$newName;
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $target)) { $cvPath = $target; }
    }
    $stmt = $pdo->prepare("UPDATE users SET job_title=?, phone=?, location=?, experience_years=?, skills=?, bio=?, cv_path=? WHERE id=?");
    $stmt->execute([$jobTitle,$phone,$location,$experienceYears,$skills,$bio,$cvPath,$userId]);
    $refresh = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $refresh->execute([$userId]);
    $_SESSION['user'] = $refresh->fetch();
    flash('success','تم تحديث بياناتك بنجاح.');
    header('Location: candidate-profile.php'); exit;
}
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();
$pageTitle = 'HireSmart — رفع السيرة';
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / رفع السيرة</div><h1>رفع السيرة الذاتية</h1></div></section>
<section class="section"><div class="container grid grid-2">
  <div class="card">
    <?php if ($msg = flash('error')): ?><div class="alert alert--error"><?= e($msg) ?></div><?php endif; ?>
    <h2>بيانات المتقدم</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="grid grid-2">
        <label class="field"><span>الاسم</span><input type="text" value="<?= e($user['name']) ?>" disabled></label>
        <label class="field"><span>الإيميل</span><input type="email" value="<?= e($user['email']) ?>" disabled></label>
      </div>
      <div class="grid grid-2">
        <label class="field"><span>رقم الهاتف</span><input type="text" name="phone" value="<?= e($user['phone'] ?? '') ?>"></label>
        <label class="field"><span>المكان</span><input type="text" name="location" value="<?= e($user['location'] ?? '') ?>"></label>
      </div>
      <div class="grid grid-2">
        <label class="field"><span>المسمى الوظيفي</span><input type="text" name="job_title" value="<?= e($user['job_title'] ?? '') ?>"></label>
        <label class="field"><span>سنوات الخبرة</span><input type="number" min="0" name="experience_years" value="<?= e((string)($user['experience_years'] ?? 0)) ?>"></label>
      </div>
      <label class="field"><span>المهارات</span><input type="text" name="skills" value="<?= e($user['skills'] ?? '') ?>"></label>
      <label class="field"><span>نبذة</span><textarea name="bio"><?= e($user['bio'] ?? '') ?></textarea></label>
      <label class="field"><span>ملف CV</span><div class="upload-box"><input type="file" name="cv"></div></label>
      <div class="form__actions"><button class="btn btn--primary" type="submit">حفظ</button></div>
    </form>
  </div>
  <div class="card">
    <h2>حالة الملف</h2>
    <?php if (!empty($user['cv_path'])): ?><a class="btn btn--primary" href="<?= e($user['cv_path']) ?>" target="_blank">فتح الملف</a><?php else: ?><div class="alert alert--info">لم يتم رفع CV بعد.</div><?php endif; ?>
  </div>
</div></section>
<?php include 'includes/footer.php'; ?>
