<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

// lấy trang hiện tại
$page= isset( $_GET['page'] ) ? (int) $_GET['page'] : 1;
//Số sản phẩm hiển thị trên mỗi trang
$perPage = 3;

//Hiển thị sản phẩm trong trang hiện tại 
$start =($page - 1) * $perPage;

// query
$products = $conn->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM products LIMIT {$start}, {$perPage}" );
$products->execute();
$products = $products->fetchAll( PDO::FETCH_ASSOC );

// Tính tổng số trang dựa trên số sản phẩm trên mỗi trang
$total = $conn->query( "SELECT FOUND_ROWS() as total" )->fetch()['total'];
$pages = ceil( $total / $perPage );

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Product</title>
   
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
   --border:.2rem solid #696969;
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
</style>
</head>
<body>
<?php include 'components/addToCart.php'; ?>  
<?php include 'components/user_header.php'; ?>

<div class="container">
			<div class="col-md-12">
<section class="products">
<h1 class="heading">All Products</h1>
<div class="box-container">
				<?php foreach ( $products as $product ): ?>
				<div class="article">
            <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $product['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $product['p_name']; ?>">
      <input type="hidden" name="price" value="<?= $product['price']; ?>">
      <input type="hidden" name="image" value="<?= $product['image_01']; ?>">
      <a href="quick_view.php?pid=<?= $product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $product['image_01']; ?>" alt="">
      <div class="name"><?= $product['p_name']; ?></div>
      <div class="flex">
         <div class="price"><?= $product['price']; ?><span>VNĐ</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
				</div>
				<?php endforeach ?>
			</div>
			<div class="col-md-12">
				<div class="well well-sm">
            <div class="pagination">
  <?php if ($pages > 1) : ?>
    <a class="first" href="?page=<?php echo $page - 1; ?>&per_page=<?php echo $perPage; ?>">&laquo;</a>
  <?php endif; ?>

  <?php for ($i = 1; $i <= $pages; $i++) : ?>
    <a class="middle" href="?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>" <?php echo ($i == $page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
  <?php endfor; ?>

  <?php if ($page < $pages) : ?>
    <a class="last" href="?page=<?php echo $page + 1; ?>&per_page=<?php echo $perPage; ?>">&raquo;</a>
  <?php endif; ?>
</div>
				</div>
			</div>
      </section>
		</div><!--end main container-->

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>