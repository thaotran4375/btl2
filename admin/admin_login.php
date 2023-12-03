<?php

include '../components/connect.php';

session_start();

class Admin {
  private $name;
  private $password;

  public function __construct($name, $password) {
    $this->name = $name;
    $this->password = $password;
  }

  // Khai báo các getter và setter cho các thuộc tính
  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  // Khai báo phương thức login để kiểm tra thông tin đăng nhập và chuyển hướng người dùng
  public function login($conn) {
    // Lọc và mã hóa các giá trị nhập vào
    $name = filter_var($this->name, FILTER_SANITIZE_STRING);
    $password = sha1($this->password);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    // Truy vấn cơ sở dữ liệu để lấy thông tin admin
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ? AND password = ?");
    $select_admin->execute([$name, $password]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);

    // Nếu có kết quả trả về, thiết lập biến session và chuyển hướng người dùng
    if($select_admin->rowCount() > 0){
      $_SESSION['admin_id'] = $row['id'];
      header('location:dashboard.php');
    }else{
      // Nếu không có kết quả trả về, hiển thị thông báo lỗi
      echo "<span style='color:red;'>Incorrect Username or Password!</span>";
    }
  }
}

// Kiểm tra nếu người dùng nhấn nút submit
if(isset($_POST['submit'])){
  // Tạo một đối tượng admin với các giá trị nhập vào từ form
  $admin = new Admin($_POST['name'], $_POST['pass']);
  // Gọi phương thức login của đối tượng admin với tham số là kết nối cơ sở dữ liệu
  $admin->login($conn);
}
?>


<html>
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
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
<?php
   if(isset($message)){
      foreach($message as $message){
         echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
      }
   }
?>

<section class="form-container">

   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="login now" class="btn" name="submit">
   </form>

</section>
   
</body>
</html>