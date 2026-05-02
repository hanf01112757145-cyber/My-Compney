<?php
require_once 'includes/functions.php';
require_once 'config/db.php';
if (isLoggedIn()) { header('Location: ' . (isAdmin() ? 'hr-dashboard.php' : 'candidate-profile.php')); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'candidate';
        if ($name === '' || $email === '' || $password === '') {
            flash('error', 'يرجى إدخال جميع البيانات المطلوبة.'); header('Location: auth.php'); exit;
        }
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) { flash('error', 'هذا البريد مسجل بالفعل.'); header('Location: auth.php'); exit; }
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $email, $hashed, $role]);
        flash('success', 'تم إنشاء الحساب بنجاح.'); header('Location: auth.php'); exit;
    }
    if (isset($_POST['login'])) {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = $user;
            header('Location: ' . ($user['role'] === 'admin' ? 'hr-dashboard.php' : 'candidate-profile.php')); exit;
        }
        flash('error', 'بيانات الدخول غير صحيحة.'); header('Location: auth.php'); exit;
    }
}
$pageTitle = 'HireSmart — تسجيل / دخول';
include 'includes/header.php';
?>
<section class="pagehead"><div class="container"><div class="breadcrumb">الرئيسية / تسجيل</div><h1>تسجيل الدخول / إنشاء حساب</h1></div></section>
<section class="section"><div class="container grid grid-2">
  <div class="card">
    <?php if ($msg = flash('success')): ?><div class="alert alert--success"><?= e($msg) ?></div><?php endif; ?>
    <?php if ($msg = flash('error')): ?><div class="alert alert--error"><?= e($msg) ?></div><?php endif; ?>
    <h2>تسجيل الدخول</h2>
    <form method="POST">
      <label class="field"><span>الإيميل</span><input type="email" name="email"></label>
      <label class="field"><span>كلمة المرور</span><input type="password" name="password"></label>
      <div class="form__actions"><button class="btn btn--primary" type="submit" name="login">دخول</button></div>
    </form>
  </div>
  <div class="card">
    <h2>إنشاء حساب</h2>
    <form method="POST">
      <label class="field"><span>الاسم</span><input type="text" name="name"></label>
      <label class="field"><span>الإيميل</span><input type="email" name="email"></label>
      <label class="field"><span>كلمة المرور</span><input type="password" name="password"></label>
      <label class="field"><span>نوع الحساب</span><select name="role"><option value="candidate">متقدم</option><option value="admin">شركة / HR</option></select></label>
      <div class="form__actions"><button class="btn btn--primary" type="submit" name="register">إنشاء حساب</button></div>
    </form>
  </div>
</div></section>
<?php include 'includes/footer.php'; ?>
