<?php

include '../components/connect.php';

session_start();

class User {
   private $id;

   public function __construct($id) {
      $this->id = $id;
   }

   public function delete() {
      global $conn;
      // delete the user from the users table
      $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
      $stmt->execute([$this->id]);
      // delete the orders related to the user
      $stmt = $conn->prepare("DELETE FROM orders WHERE user_id = ?");
      $stmt->execute([$this->id]);
      // delete the messages related to the user
      $stmt = $conn->prepare("DELETE FROM messages WHERE user_id = ?");
      $stmt->execute([$this->id]);
      // delete the cart items related to the user
      $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
      $stmt->execute([$this->id]);
   }
}

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_GET['delete'])){
   // create a user object with the get data
   $user = new User($_GET['delete']);
   // delete the user and its related data
   $user->delete();
   // redirect to the users accounts page
   header('location:users_accounts.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Users Accounts</title>

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

<section class="accounts">

   <h1 class="heading">User Accounts</h1>

   <div class="box-container">

   <?php
      $select_accounts = $conn->prepare("SELECT * FROM `users`");
      $select_accounts->execute();
      if($select_accounts->rowCount() > 0){
         while($fetch_accounts = $select_accounts->fetch(PDO::FETCH_ASSOC)){   
   ?>
   <div class="box">
      <p> User ID : <span><?= $fetch_accounts['id']; ?></span> </p>
      <p> Username : <span><?= $fetch_accounts['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_accounts['email']; ?></span> </p>
      <a href="users_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account? The user related information will also be delete!')" class="delete-btn">Delete</a>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No accounts available!</p>';
      }
   ?>

   </div>

</section>


<script src="../js/admin_script.js"></script>
   
</body>
</html>