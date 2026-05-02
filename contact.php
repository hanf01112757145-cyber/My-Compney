<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name !== '' && $email !== '' && $message !== '') {
        $stmt = $pdo->prepare("INSERT INTO contacts (name, email, message) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $message]);
        flash('success', 'تم إرسال رسالتك بنجاح.');
    } else {
        flash('error', 'يرجى ملء جميع الحقول.');
    }
    header('Location: contact.php'); exit;
}
$pageTitle = 'HireSmart — تواصل';
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / تواصل</div><h1>تواصل معنا</h1></div></section>
<section class="section"><div class="container grid grid-2">
  <div class="card">
    <?php if ($msg = flash('success')): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="alert alert--error"><?= e($msg) ?></div><?php endif; ?>
    <form method="POST">
      <label class="field"><span>الاسم</span><input type="text" name="name"></label>
      <label class="field"><span>الإيميل</span><input type="email" name="email"></label>
      <label class="field"><span>الرسالة</span><textarea name="message"></textarea></label>
      <div class="form__actions"><button class="btn btn--primary" type="submit">إرسال</button></div>
    </form>
  </div>
  <div class="card"><h2>معلومات التواصل</h2><p><strong>البريد:</strong> support@hiresmart.test</p><p><strong>الهاتف:</strong> +20 111 275 7145</p></div>
</div></section>
<?php include 'includes/footer.php'; ?>
