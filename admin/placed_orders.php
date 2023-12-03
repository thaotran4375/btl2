<?php

include '../components/connect.php';

session_start();

class Order {
   private $id;
   private $payment_status;

   public function __construct($id) {
      global $conn;
      $this->id = $id;
      // get the payment status from the database
      $stmt = $conn->prepare("SELECT payment_status FROM orders WHERE id = ?");
      $stmt->execute([$id]);
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->payment_status = $row['payment_status'];
   }

   public function updatePayment($new_status) {
      global $conn;
      // sanitize the input
      $new_status = filter_var($new_status, FILTER_SANITIZE_STRING);
      // update the payment status in the database
      $stmt = $conn->prepare("UPDATE orders SET payment_status = ? WHERE id = ?");
      $stmt->execute([$new_status, $this->id]);
      // update the payment status in the object
      $this->payment_status = $new_status;
      return "<span style='color:green';>Payment Status Updated!</span>";
   }

   public function delete() {
      global $conn;
      // delete the order from the database
      $stmt = $conn->prepare("DELETE FROM orders WHERE id = ?");
      $stmt->execute([$this->id]);
   }

   public function getId() {
      return $this->id;
   }

   public function getPaymentStatus() {
      return $this->payment_status;
   }
}

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
}

if(isset($_POST['update_payment'])){
   // create an order object with the post data
   $order_id = $_POST['order_id'];
   $order = new Order($order_id);
   // update the payment status of the order
   $payment_status = $_POST['payment_status'];
   $message[] = $order->updatePayment($payment_status);
}

if(isset($_GET['delete'])){
   // create an order object with the get data
   $delete_id = $_GET['delete'];
   $order = new Order($delete_id);
   // delete the order from the database
   $order->delete();
   header('location:placed_orders.php');
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>All Orders</title>

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

<section class="orders">

<h1 class="heading">All Orders</h1>

<div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      if($select_orders->rowCount() > 0){
         while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <div class="box">
      <p> Placed On : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total Price : <span><?= $fetch_orders['total_price']; ?>VNĐ</span> </p>
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="post">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="select">
         <option selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="Pending">Pending</option>
            <option value="Delivery">Delivery</option>
            <option value="Completed">Completed</option>
         </select>
        <div class="flex-btn">
         <input type="submit" value="update" class="option-btn" name="update_payment">
         <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete This Order?');">Delete</a>
        </div>
      </form>
   </div>
   <?php
         }
      }else{
         echo '<p class="empty">No Orders Placed Yet!</p>';
      }
   ?>

</div>

</section>

</section>


<script src="../js/admin_script.js"></script>
   
</body>
</html>