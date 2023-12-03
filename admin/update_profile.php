<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

// Nếu có id, lấy thông tin của người quản trị từ cơ sở dữ liệu bằng phương thức getAdminById của lớp Admin và gán cho biến $admin
$admin = Admin::getAdminById($admin_id);

// Nếu có dữ liệu được gửi từ biểu mẫu, gọi phương thức updateProfile của đối tượng Admin và gán kết quả cho biến $message
if (isset($_POST['submit'])) {
   $name = $_POST['name'];
   $old_pass = $_POST['old_pass'];
   $new_pass = $_POST['new_pass'];
   $confirm_pass = $_POST['confirm_pass'];

   // Gọi phương thức updateProfile của đối tượng Admin và gán kết quả cho biến $message
   $message = $admin->updateProfile($name, $old_pass, $new_pass, $confirm_pass);
}

// Tạo lớp Admin
class Admin {
   private $id;
   private $name;
   private $password;
   private static $conn;

   public static function connect() {
      if (!isset(self::$conn)) {
         // Khai báo các biến kết nối
         $dsn = "mysql:host=localhost;dbname=shop_db";
         $username = "root";
         $password = "";

         // Tạo một đối tượng PDO với thông tin kết nối
         self::$conn = new PDO($dsn, $username, $password);
      }

      // Trả về biến kết nối
      return self::$conn;
   }

   // Tạo một phương thức lấy thông tin của người quản trị theo id
   public static function getAdminById($id) {
      $conn = self::connect();
      $sql = "SELECT * FROM admins WHERE id = :id";
      $stmt = $conn->prepare($sql);

      // Gán giá trị cho tham số id
      $stmt->bindValue(":id", $id, PDO::PARAM_INT);
      $stmt->execute();

      // Lấy dòng kết quả đầu tiên
      $row = $stmt->fetch();

      // Nếu có dòng kết quả, tạo một đối tượng Admin với các thuộc tính được gán giá trị từ dòng kết quả
      if ($row) {
         $admin = new Admin();
         $admin->id = $row['id'];
         $admin->name = $row['name'];
         $admin->password = $row['password'];

         return $admin;
      } else {
         // Nếu không có dòng kết quả, trả về null
         return null;
      }
   }

   // Tạo một phương thức cập nhật thông tin của người quản trị trong cơ sở dữ liệu và trả về một mảng thông báo
   public function updateProfile($name, $old_pass, $new_pass, $confirm_pass) {

      // Gọi phương thức kết nối để lấy biến kết nối
      $conn = self::connect();

      // Tạo một mảng rỗng để chứa các thông báo
      $message = array();

      // Lọc các giá trị nhập vào từ biểu mẫu
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $old_pass = filter_var(sha1($old_pass), FILTER_SANITIZE_STRING);
      $new_pass = filter_var(sha1($new_pass), FILTER_SANITIZE_STRING);
      $confirm_pass = filter_var(sha1($confirm_pass), FILTER_SANITIZE_STRING);

      // Chuẩn bị câu truy vấn cập nhật tên của người quản trị theo id
      $sql = "UPDATE admins SET name = :name WHERE id = :id";
      $stmt = $conn->prepare($sql);

      // Gán giá trị cho các tham số
      $stmt->bindValue(":name", $name, PDO::PARAM_STR);
      $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

      // Thực thi truy vấn
      $stmt->execute();

      // Kiểm tra mật khẩu cũ có trống không
$empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
if ($old_pass == $empty_pass) {
   // Nếu trống, thêm thông báo lỗi vào mảng
   $message[] = "<span style='color:red';>Please enter old password!</span>";
} elseif ($old_pass != $this->password) {
   // Nếu không trùng với mật khẩu hiện tại, thêm thông báo lỗi vào mảng
   $message[] = "<span style='color:red';>Old password not matched!</span>";
} elseif ($new_pass != $confirm_pass) {
   // Nếu mật khẩu mới không trùng với mật khẩu xác nhận, thêm thông báo lỗi vào mảng
   $message[] = "<span style='color:red';>Confirm password not matched!</span>";
} else {
   // Nếu mật khẩu mới không trống, cập nhật mật khẩu mới trong cơ sở dữ liệu
   if ($new_pass != $empty_pass) {
      // Chuẩn bị câu truy vấn cập nhật mật khẩu của người quản trị theo id
      $sql = "UPDATE admins SET password = :password WHERE id = :id";
      $stmt = $conn->prepare($sql);

      // Gán giá trị cho các tham số
      $stmt->bindValue(":password", $confirm_pass, PDO::PARAM_STR);
      $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
      $stmt->execute();

      // Cập nhật thuộc tính password của đối tượng Admin
      $this->password = $confirm_pass;

      // Thêm thông báo thành công vào mảng
      $message[] = "<span style='color:green';>Password updated successfully!</span>";
   } else {
      // Nếu mật khẩu mới trống, thêm thông báo lỗi vào mảng
      $message[] = "<span style='color:red';>Please enter a new password!</span>";
   }
}
return $message;
}
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Profile</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
   :root{
   --main-color:#2980b9;
   --orange:#f39c12;
   --red:#e74c3c;
   --black:#444;
   --white:#fff;
   --light-color:#777;
   --light-bg:#f5f5f5;
   --border:.1rem solid var(--black);
   --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
}
</style>
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Update Profile</h3>
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile['password']; ?>">
      <input type="text" name="name" value="<?= $fetch_profile['name']; ?>" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="old_pass" placeholder="Enter old password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="Confirm new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="update now" class="btn" name="submit">
   </form>

</section>


<script src="../js/admin_script.js"></script>
   
</body>
</html>