<?php

include 'components/connect.php';

session_start();

class Message {
  private $user_id;
  private $name;
  private $email;
  private $number;
  private $msg;

  public function __construct($user_id, $name, $email, $number, $msg) {
    $this->user_id = $user_id;
    $this->name = $name;
    $this->email = $email;
    $this->number = $number;
    $this->msg = $msg;
  }

  // Khai báo các getter và setter cho các thuộc tính
  public function getUserID() {
    return $this->user_id;
  }

  public function setUserID($user_id) {
    $this->user_id = $user_id;
  }

  public function getName() {
    return $this->name;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function getEmail() {
    return$this->email;
   }
 
   public function setEmail($email) {
     $this->email = $email;
   }
 
   public function getNumber() {
     return $this->number;
   }
 
   public function setNumber($number) {
     $this->number = $number;
   }
 
   public function getMsg() {
     return $this->msg;
   }
 
   public function setMsg($msg) {
     $this->msg = $msg;
   }
 
   // Khai báo phương thức send để gửi tin nhắn của người dùng
   public function send($conn) {
     // Truy vấn cơ sở dữ liệu để kiểm tra tin nhắn của người dùng đã tồn tại chưa
     $select_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
     $select_message->execute([$this->name, $this->email, $this->number, $this->msg]);
 
     // Nếu tin nhắn đã tồn tại, hiển thị thông báo đã gửi
     if($select_message->rowCount() > 0){
       echo "<p style = 'color: red';>Already sent message!</p>";
     }else{
       // Nếu tin nhắn chưa tồn tại, truy vấn cơ sở dữ liệu để chèn tin nhắn vào bảng messages với các thông tin của người dùng và tin nhắn
       $insert_message = $conn->prepare("INSERT INTO `messages`(user_id, name, email, number, message) VALUES(?,?,?,?,?)");
       $insert_message->execute([$this->user_id, $this->name, $this->email, $this->number, $this->msg]);
 
       // Hiển thị thông báo gửi thành công
       echo "<p style = 'color: green';>Sent message successfully!</p>";
     }
   }
 }
 
 // Nếu có biến session user_id, gán nó cho biến user_id, ngược lại gán user_id là rỗng và chuyển hướng người dùng về trang đăng nhập người dùng
 if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
 }else{
    $user_id = '';
    header('location:user_login.php');
 };
 
 // Nếu có biến POST send, lọc và mã hóa các giá trị nhập vào và khởi tạo một đối tượng Message với các thông tin đó. Sau đó gọi phương thức send để gửi tin nhắn.
 if(isset($_POST['send'])){
 
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_STRING);
    $number = $_POST['number'];
    $number = filter_var($number, FILTER_SANITIZE_STRING);
    $msg = $_POST['msg'];
    $msg = filter_var($msg, FILTER_SANITIZE_STRING);
 
    // Tạo một đối tượng Message với các thông tin đã lọc và mã hóa
    $message = new Message($user_id, $name, $email, $number, $msg);
 
    // Gọi phương thức send để gửi tin nhắn
    $message->send($conn);
 
 }
 
 ?>
 

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Contact</title>
   
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
span{
   font-size: 2rem;
   color:var(--black);
}
body{
   background-color: var(--white);
}
.header .flex .logo{
   font-size: 2.5rem;
   color: #000099;
}

.header .flex .logo span{
   color:var(--main-color);
}
.btn{
   background-color: #FF8C00;
}
.message{
   position: sticky;
   top:0;
   max-width: 1200px;
   margin:0 auto;
   background-color: white;
   padding:2rem;
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap:1.5rem;
   z-index: 1100;
}
.footer{
   background-color: var(--light-bg);
   /* padding-bottom: 7rem; */
}
.pagination {
      display: inline-block;
      padding: 30px 30px 30px 420px ;
      text-align: center;
    }

    .pagination a.first {
      color: #2980b9;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      font-size: 20px;
      background-color: #fff;
    }
    
    
    .pagination a.middle {
      color: #2980b9;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      font-size: 20px;
      background-color: #fff;
    }
    
    .pagination a.last {
      color: #2980b9;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      font-size: 20px;
      background-color: #fff;
    }

    .pagination a:hover:not(.active) {
    background-color: #ddd;
    }
    p{
      padding: 5px 5px 10px 30px;
      font-size: 2.5rem;
    }
    
</style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="contact">

   <form action="" method="post">
      <h3>Get in touch</h3>
      <input type="text" name="name" placeholder="Enter your name" required maxlength="20" class="box">
      <input type="email" name="email" placeholder="Enter your email" required maxlength="50" class="box">
      <input type="number" name="number" min="0" max="9999999999" placeholder="Enter your number" required onkeypress="if(this.value.length == 10) return false;" class="box">
      <textarea name="msg" class="box" placeholder="Enter your message" cols="30" rows="10"></textarea>
      <input type="submit" value="send message" name="send" class="btn">
   </form>

</section>


<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>