<?php

include '../components/connect.php';

session_start();

class Admin {
   private $admin_id;
   private $name;
   private $password;

   public function __construct($admin_id) {
      global $conn;
      $this->admin_id = $admin_id;
      // get the name and password from the database
      $stmt = $conn->prepare("SELECT name, password FROM admins WHERE id = ?");
      $stmt->execute([$admin_id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->name = $row['name'];
      $this->password = $row['password'];
   }

   public function register($name, $pass, $cpass) {
      global $conn;
      // sanitize the inputs
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $pass = filter_var(sha1($pass), FILTER_SANITIZE_STRING);
      $cpass = filter_var(sha1($cpass), FILTER_SANITIZE_STRING);

      // check if the name already exists
      $stmt = $conn->prepare("SELECT * FROM admins WHERE name = ?");
      $stmt->execute([$name]);

      if($stmt->rowCount() > 0){
         return "<span style='color:red';>Username already exist!</span>";
      }else{
         // check if the passwords match
         if($pass != $cpass){
            return "<span style='color:red';>Confirm password not matched!</span>";
         }else{
            // insert the new admin into the database
            $stmt = $conn->prepare("INSERT INTO admins(name, password) VALUES(?,?)");
            $stmt->execute([$name, $cpass]);
            return "<span style='color:green';>New admin registered successfully!</span>";
         }
      }
   }

   public function getName() {
      return $this->name;
   }

   public function getPassword() {
      return $this->password;
   }
}

if(isset($_SESSION['admin_id'])){
   // create an admin object with the session id
   $admin = new Admin($_SESSION['admin_id']);
}else{
   // redirect to login page or show error message
   header('location:admin_login.php');
}

if(isset($_POST['submit'])){
   // register a new admin with the post data
   $message[] = $admin->register($_POST['name'], $_POST['pass'], $_POST['cpass']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Register Admin</title>

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
      <h3>Register Now</h3>
      <input type="text" name="name" required placeholder="Enter your username" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="submit">
   </form>

</section>


<script src="../js/admin_script.js"></script>
   
</body>
</html>