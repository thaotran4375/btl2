<?php

include 'components/connect.php';

session_start();

// Tạo một lớp User để quản lý thông tin người dùng
class User {
  // Khai báo các thuộc tính của lớp User
  private $id;
  private $name;
  private $email;
  private $password;

  // Khởi tạo một đối tượng User với các tham số truyền vào
  public function __construct($id, $name, $email, $password) {
    $this->id = $id;
    $this->name = $name;
    $this->email = $email;
    $this->password = $password;
  }

  // Tạo một phương thức để cập nhật thông tin người dùng trong cơ sở dữ liệu
  public function updateProfile($conn) {
    // Lọc các giá trị nhập vào để tránh SQL injection
    $this->name = filter_var($this->name, FILTER_SANITIZE_STRING);
    $this->email = filter_var($this->email, FILTER_SANITIZE_STRING);

    // Tạo một câu lệnh SQL để cập nhật thông tin người dùng
    $sql = "UPDATE `users` SET name = ?, email = ? WHERE id = ?";
    // Chuẩn bị và thực thi câu lệnh SQL với các giá trị của đối tượng User
    $stmt = $conn->prepare($sql);
    $stmt->execute([$this->name, $this->email, $this->id]);
  }

  // Tạo một phương thức để cập nhật mật khẩu người dùng trong cơ sở dữ liệu
  public function updatePassword($conn, $old_pass, $new_pass) {
    // Lọc các giá trị nhập vào để tránh SQL injection
    $old_pass = filter_var(sha1($old_pass), FILTER_SANITIZE_STRING);
    $new_pass = filter_var(sha1($new_pass), FILTER_SANITIZE_STRING);

    // Kiểm tra xem mật khẩu cũ có khớp với mật khẩu hiện tại của đối tượng User không
    if ($old_pass == $this->password) {
      // Nếu khớp, tạo một câu lệnh SQL để cập nhật mật khẩu mới
      $sql = "UPDATE `users` SET password = ? WHERE id = ?";
      // Chuẩn bị và thực thi câu lệnh SQL với các giá trị của đối tượng User
      $stmt = $conn->prepare($sql);
      $stmt->execute([$new_pass, $this->id]);
      return "<span style='color:green;'>Password Updated Successfully!</span>";
    } else {
      // Nếu không khớp, trả về một thông báo lỗi
      return "<span style='color:red;'>Old Password Not Matched!</span>";
    }
  }
}

// Kiểm tra xem người dùng đã đăng nhập chưa
if(isset($_SESSION['user_id'])){
   // Nếu đã đăng nhập, lấy id của người dùng từ session
   $user_id = $_SESSION['user_id'];
}else{
   // Nếu chưa đăng nhập, gán id của người dùng là rỗng
   $user_id = '';
};

// Kiểm tra xem người dùng có nhấn nút submit không
if(isset($_POST['submit'])){

   // Lấy các giá trị nhập vào từ form
   $name = $_POST['name'];
   $email = $_POST['email'];
   // Lấy mật khẩu hiện tại của người dùng từ form
   $prev_pass = $_POST['prev_pass'];
   // Lấy mật khẩu cũ, mật khẩu mới và xác nhận mật khẩu từ form
   $old_pass = $_POST['old_pass'];
   $new_pass = $_POST['new_pass'];
   $cpass = $_POST['cpass'];

   // Tạo một đối tượng User với các giá trị nhập vào
   $user = new User($user_id, $name, $email, $prev_pass);

   // Gọi phương thức updateProfile của đối tượng User để cập nhật thông tin người dùng
   $user->updateProfile($conn);

   // Kiểm tra xem người dùng có nhập mật khẩu mới không
   if ($new_pass != '') {
      // Nếu có, kiểm tra xem mật khẩu mới có khớp với xác nhận mật khẩu không
      if ($new_pass == $cpass) {
         // Nếu khớp, gọi phương thức updatePassword của đối tượng User để cập nhật mật khẩu mới
         $message[] = $user->updatePassword($conn, $old_pass, $new_pass);
      } else {
         // Nếu không khớp, trả về một thông báo lỗi
         $message[] = "<span style='color:red;'>Confirm Password Not Matched!</span>";
      }
   } else {
      // Nếu không nhập mật khẩu mới, trả về một thông báo lỗi
      $message[] = "<span style='color:red;'>Please enter a new password!</span>";
   }
   
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
   <style>
:root{
   --main-color:#2980b9;
   --orange:#f39c12;
   --red:#e74c3c;
   --black:#333;
   --white:#fff;
   --light-color:#666;
   --light-bg:#eee;
   --border:.1rem solid #696969;
   --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
   --yellow:#FFFF33;
   --pink:#FFCC66;
}
body{
   background-color: var(--white);
}
.btn{
   background-color: #FF8C00;
}

.header .flex .logo{
   font-size: 2.5rem;
   color: #000099;
}

.header .flex .logo span{
   color:var(--main-color);
}

.footer{
   background-color: var(--light-bg);
   /* padding-bottom: 7rem; */
}
</style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>Update Now</h3>
      <input type="hidden" name="prev_pass" value="<?= $fetch_profile["password"]; ?>">
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" value="<?= $fetch_profile["name"]; ?>">
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')" value="<?= $fetch_profile["email"]; ?>">
      <input type="password" name="old_pass" placeholder="Enter your old password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter your new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" placeholder="Confirm your new password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="update now" class="btn" name="submit">
   </form>

</section>


<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>