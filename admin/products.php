<?php

include '../components/connect.php';

session_start();
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
if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   $delete_products = $conn->prepare("DELETE FROM products WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM cart WHERE id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">
   <style>
   @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@200;300;400;500;600;700&display=swap');

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

*{
   font-family: 'Nunito', sans-serif;
   margin:0; padding:0;
   box-sizing: border-box;
   outline: none; border:none;
   text-decoration: none;
}

*::selection{
   background-color: var(--main-color);
   color:var(--white);
}

::-webkit-scrollbar{
   height: .5rem;
   width: 1rem;
}

::-webkit-scrollbar-track{
   background-color: transparent;
}

html{
   font-size: 62.5%;
   overflow-x: hidden;
}

.btn{
   background-color: var(--main-color);
}

.option-btn{
   background-color: var(--orange);
}

.delete-btn{
   background-color: var(--red);
}

.flex-btn{
   display: flex;
   gap:1rem;
}
.empty{
   padding:1.5rem;
   background-color: var(--white);
   border: var(--border);
   box-shadow: var(--box-shadow);
   text-align: center;
   color:var(--red);
   border-radius: .5rem;
   font-size: 2rem;
   text-transform: capitalize;
}
.header{
   position: sticky;
   top:0; left:0; right:0;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   z-index: 1000;
}

.header .flex{
   display: flex;
   align-items: center;
   justify-content: space-between;
   position: relative;
}

.header .flex .logo{
   font-size: 2.5rem;
   color:var(--black);
}

.header .flex .logo span{
   color:var(--main-color);
}

.header .flex .navbar a{
   margin:0 1rem;
   font-size: 2rem;
   color:var(--black);
}

.header .flex .navbar a:hover{
   color:var(--main-color);
   text-decoration: underline;
}

.header .flex .icons div{
   margin-left: 1.7rem;
   font-size: 2.5rem;
   cursor: pointer;
   color:var(--black);
}

.header .flex .icons div:hover{
   color:var(--main-color);
}

.header .flex .profile{
   position: absolute;
   top:120%; right:2rem;
   background-color: var(--white);
   border-radius: .5rem;
   box-shadow: var(--box-shadow);
   border:var(--border);
   padding:2rem;
   width: 30rem;
   padding-top: 1.2rem;
   display: none;
   animation:fadeIn .2s linear;
}

.header .flex .profile.active{
   display: inline-block;
}

.header .flex .profile p{
   text-align: center;
   color:var(--black);
   font-size: 2rem;
   margin-bottom: 1rem;
}

#menu-btn{
   display: none;
}

section{
   padding:2rem;
   max-width: 1200px;
   margin:0 auto;
}

.heading{
   font-size: 4rem;
   color:var(--black);
   margin-bottom: 2rem;
   text-align: center;
   text-transform: uppercase;
}

.btn,
.delete-btn,
.option-btn{
   display: block;
   width: 100%;
   margin-top: 1rem;
   border-radius: .5rem;
   padding:1rem 3rem;
   font-size: 1.7rem;
   text-transform: capitalize;
   color:var(--white);
   cursor: pointer;
   text-align: center;
}

.products .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.products .box-container .box{
   position: relative;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   border:var(--border);
   padding:2rem;
   overflow: hidden;
}

.products .box-container .box img{
   height: 20rem;
   width: 100%;
   object-fit: contain;
   margin-bottom: 1rem;
}

.products .box-container .box .name{
   font-size: 2rem;
   color:var(--black);
}
.products .box-container .box .price{
   font-size: 2rem;
   color:var(--main-color);
}
.products .box-container .box .details{
   font-size: 1.5rem;
   color:var(--black);
}

.products .box-container .box .flex{
   display: flex;
   align-items: center;
   gap:1rem;
}

.products .box-container .box .flex .qty{
   width: 7rem;
   padding:1rem;
   border:var(--border);
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
}

.products .box-container .box .flex .price{
   font-size: 2rem;
   color:var(--red);
   margin-right: auto;
}

.search-form form{
   display: flex;
   gap:1rem;
}

.search-form form input{
   width: 100%;
   border:var(--border);
   border-radius: .5rem;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   padding:1.4rem;
   font-size: 1.8rem;
   color:var(--black);
}

.search-form form button{
   font-size: 2.5rem;
   height: 5.5rem;
   line-height: 5.5rem;
   background-color: var(--main-color);
   cursor: pointer;
   color:var(--white);
   border-radius: .5rem;
   width: 6rem;
   text-align: center;
}

.search-form form button:hover{
   background-color: var(--black);
}
.btn_add{

color: white;
padding: 10px 20px; 
float: right;
border: none; 
cursor: pointer;
}
.pagination {
      display: inline-block;
      padding: 50px 30px 30px 450px ;
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
<?php include '../components/admin_header.php'; ?>
<div class="btn_add">
<a class="btn btn-success" href="add_product.php">Add Product</a>
</div>

<section class="search-form">
   <form action="../admin/search.php" method="post">
      <input type="text" name="search_box" placeholder="Search Here..." maxlength="100" class="box" required>
      <button type="submit" class="fas fa-search" name="search_btn"></button>
   </form>
</section>
<br><br>
<section class="show-products">

   <h1 class="heading">All Products</h1>
<br><br>
   <div class="box-container">
	<?php foreach ( $products as $product ): ?>
   <div class="box">
      <img src="../uploaded_img/<?= $product['image_01']; ?>" alt="">
      <div class="name"><?= $product['p_name']; ?></div>
      <div class="price"><span><?= $product['price']; ?></span>VNĐ</div>
      <div class="details"><span><?= $product['details']; ?></span></div>
      <div class="flex-btn">
         <a href="update_product.php?update=<?= $product['id']; ?>" class="option-btn">Update</a>
         <a href="products.php?delete=<?= $product['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">Delete</a>
      </div>
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
    <a class="middle"  href="?page=<?php echo $i; ?>&per_page=<?php echo $perPage; ?>" class="btn btn-primary active" role="button" data-bs-toggle="button" aria-pressed="true"<?php echo ($i == $page) ? 'class="active"'  : ''; ?>><?php echo $i; ?></a>
  <?php endfor; ?>
  <?php if ($page < $pages) : ?>
    <a class="last" href="?page=<?php echo $page + 1; ?>&per_page=<?php echo $perPage; ?>">&raquo;</a>
  <?php endif; ?>
</div>
				</div>
			</div>
      </section>
<script src="../js/admin_script.js"></script>
   
</body>
</html>