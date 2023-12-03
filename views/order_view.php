<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders Detail</title>
   
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
.checkout-orders form h3{
   border-radius: .5rem;
   background-color: var(--main-color);
   color:var(--white);
   padding:1.5rem 1rem;
   text-align: center;
   text-transform: uppercase;
   margin-bottom: 2rem;
   font-size: 2.5rem;
}
.box{
    font-size:20px;
    color:#696969;
    font-family: "Times New Roman", times, serif;
}
.box span{
    color: var(--main-color);
    padding-top: 50px;
  padding-bottom: 50px;
}
</style>
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="quick-view">

   <h1 class="heading">Orders Detail</h1>

   <?php
     $oid = $_GET['oid'];
     $select_orders = $conn->prepare("SELECT * FROM  (`orders` join `order_detail` on orders.id= order_detail.oid) join`products` on order_detail.pid=products.id WHERE oid = ?");
     $select_orders->execute([$oid]);
     if($select_orders->rowCount() > 0){
      while($fetch_product = $select_orders->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="oid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">  
      <input type="hidden" name="number" value="<?= $fetch_product['number']; ?>">
      <input type="hidden" name="email" value="<?= $fetch_product['email']; ?>">
      <input type="hidden" name="method" value="<?= $fetch_product['method']; ?>">
      <input type="hidden" name="address" value="<?= $fetch_product['address']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_product['p_name']; ?>">
      <input type ="hidden" name="total_product" value="<?= $fetch_product['total_product']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="placed_on" value="<?= $fetch_product['placed_on']; ?>">
      <input type="hidden" name="payment_status" value="<?= $fetch_product['payment_status']; ?>">
      
      <div class="box">
         <div class="name">Name:<span><?= $fetch_product['name']; ?></span></div> 
        <div class="number">SĐT:<span><?= $fetch_product['number']; ?></span></div>      
        <div class="email">Email:<span><?= $fetch_product['email']; ?></span></div>
        <div class="method">Method:<span><?= $fetch_product['method']; ?></span></div>
       <div class="address">Address:<span><?= $fetch_product['address']; ?></span></div>   
       <div class="p_name">Product_name:<span><?= $fetch_product['p_name']; ?></span></div>
       <div class="total_product">Product_total:<span><?= $fetch_product['total_product']; ?></span></div>
      <div class="price">One_product_price:<span><?= $fetch_product['price']; ?>VNĐ</span></div>
           <div class="placed_on"> Placed_on:<span><?= $fetch_product['placed_on']; ?></span></div>
           <div class="payment_status"> Payment_status: <span><?= $fetch_product['payment_status']; ?></span></div>  
         </div>
      </div>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">No orders added yet!</p>';
   }
   ?>
</div>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
