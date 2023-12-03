<?php
if(isset($_POST['add_to_cart'])){

if($user_id == ''){
   echo "<p style='color:red;'>Login before purchasing!</p>";
}else{
   $pid = $_POST['pid'];
   $sql = "SELECT * FROM products WHERE id = :pid";
   $stmt = $conn->prepare($sql);
   $stmt->bindParam(':pid', $pid);
   $stmt->execute();
   $product = $stmt->fetch(PDO::FETCH_ASSOC);
// Thêm sản phẩm vào bảng cart
   $pid = $_POST['pid'];
   $pid = filter_var($pid, FILTER_SANITIZE_STRING);
   $name = $_POST['p_name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $image = $_POST['image'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
   $check_cart_numbers->execute([$name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      echo "<p style='color:red;'>Already added to cart!</p>";
   }else{
      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, name, price, image) VALUES(?,?,?,?)");
      $insert_cart->execute([$user_id, $name, $price, $image]);
      echo "<p style='color:green;'>Added to cart!</p>";
     // Lấy khóa chính của bảng cart vừa thêm
$cid = $conn->lastInsertId();

// Thêm khóa chính của bảng carts và bảng products vào bảng cart_detail
$sql = "INSERT INTO cart_detail (cid, pid, quantity) VALUES (?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->execute([$cid,$pid,$qty]);
   }

}

}

?>
<style>
p{
  padding: 10px 10px 10px 30px;
   font-size: 2.5rem;
}</style>
