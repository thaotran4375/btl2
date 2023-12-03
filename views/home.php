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
   <title>Home</title>

   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
 
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

::-webkit-scrollbar-thumb{
   background-color: var(--main-color);
}

html{
   font-size: 62.5%;
   overflow-x: hidden;
}

body{
   background-color: var(--white);
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

.btn:hover,
.delete-btn:hover,
.option-btn:hover{
   background-color: var(--black);
}

.btn{
   background-color: #FF8C00;
}

.option-btn{
   background-color: var(--main-color);
}

.delete-btn{
   background-color: var(--red);
}

.flex-btn{
   display: flex;
   gap:1rem;
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

.message span{
   font-size: 2rem;
   color:var(--black);
}

.message i{
   cursor: pointer;
   color:var(--red);
   font-size: 2.5rem;
}

.message i:hover{
   color:var(--black);
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

.disabled{
   pointer-events: none;
   user-select: none;
   opacity: .5;
}

@keyframes fadeIn{
   0%{
      transform: translateY(1rem);
   }
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
   color: #000099;
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


.header .flex .icons > *{
   margin-left: 1rem;
   font-size: 2.5rem;
   cursor: pointer;
   color:var(--black);
}

.header .flex .icons > *:hover{
   color:var(--main-color);
}

.header .flex .icons a span{
   font-size: 2rem;
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

.home-bg{
   background:url(https://scontent.xx.fbcdn.net/v/t1.15752-9/248422086_891731298154284_5534876449748916072_n.jpg?stp=dst-jpg_p403x403&_nc_cat=106&cb=99be929b-3346023f&ccb=1-7&_nc_sid=aee45a&_nc_ohc=SzH3YiRdamwAX_15knc&_nc_ad=z-m&_nc_cid=0&_nc_ht=scontent.xx&oh=03_AdSaedk6nZ4TRN42xYJhw0RqT7H_NZvGfsoTzYxJ6MU_2g&oe=64F9CC2A) no-repeat;
   background-size: cover;
   background-position: center;
}

.home-bg .home .slide{
   display: flex;
   align-items: center;
   flex-wrap: wrap;
   gap:1.5rem;
   padding-bottom: 6rem;
   padding-top: 2rem;
   user-select: none;
}

.home-bg .home .slide .image{
   flex:1 1 40rem;
}

.home-bg .home .slide .image img{
   height: 40rem;
   width: 100%;
   object-fit: contain;
}

.home-bg .home .slide .content{
   flex:1 1 40rem;
}

.home-bg .home .slide .content span{
   font-size: 2rem;
   color:var(--white);
}

.home-bg .home .slide .content h3{
   margin-top: 1rem;
   font-size: 4rem;
   color:var(--white);
   text-transform: uppercase;
}

.home-bg .home .slide .content .btn{
   display: inline-block;
   width: auto;
}

.swiper-pagination-bullet-active{
   background-color: var(--white);
}

.category .slide{
   margin-bottom: 5rem;
   box-shadow: var(--box-shadow);
   border:var(--border);
   text-align: center;
   padding:2rem;
   background: #F5F5F5;
   border-radius: .5rem;
}

.category .slide:hover{
   background-color: #2980B9;
}

.category .slide:hover img{
   filter:invert();
}

.category .slide:hover h3{
   color:var(--black);
}

.category .slide img{
   height: 7rem;
   width: 100%;
   object-fit: contain;
   margin-bottom: 1rem;
   user-select: none;
}

.category .slide h3{
   font-size: 2rem;
   color:var(--black);
   user-select: none;
}

.home-products .slide{
   position: relative;
   padding:2rem;
   border-radius: .5rem;
   border:var(--border);
   background-color:var(--white);
   box-shadow: var(--box-shadow);
   margin-bottom: 5rem;
   overflow: hidden;
   user-select: none;
}

.home-products .slide img{
   width: 100%;
   height: 20rem;
   object-fit: contain;
   margin-bottom: 2rem;
}

.home-products .slide .name{
   font-size: 2rem;
   color:var(--black);
}

.home-products .slide .flex{
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap:1rem;
}

.home-products .slide .flex .qty{
   width: 7rem;
   padding:1rem;
   border:var(--border);
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
}

.home-products .slide .flex .price{
   margin:1rem 0;
   font-size: 2rem;
   color:var(--red);
}

.home-products .slide .fa-heart,
.home-products .slide .fa-eye{
   position: absolute;
   top:1rem;
   height: 4.5rem;
   width: 4.5rem;
   line-height: 4.2rem;
   font-size: 2rem;
   background-color: var(--white);
   border:var(--border);
   border-radius: .5rem;
   text-align: center;
   color:var(--black);
   cursor: pointer;
   transition: .2s linear;
}

.home-products .slide .fa-heart{
   right: -6rem;
}

.home-products .slide .fa-eye{
   left: -6rem;
}

.home-products .slide .fa-heart:hover,
.home-products .slide .fa-eye:hover{
   background-color: var(--black);
   color:var(--white);
}

.home-products .slide:hover .fa-heart{
   right: 1rem;
}

.home-products .slide:hover .fa-eye{
   left: 1rem;
}

.quick-view form{
   padding:2rem;
   border-radius: .5rem;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   margin-top: 1rem;
}

.quick-view form .row{
   display: flex;
   align-items: center;
   gap:1.5rem;
   flex-wrap: wrap;
}

.quick-view form .row .image-container{
   margin-bottom: 2rem;
   flex:1 1 40rem;
}

.quick-view form .row .image-container .main-image img{
   height: 30rem;
   width: 100%;
   object-fit: contain;
}

.quick-view form .row .image-container .sub-image{
   display: flex;
   gap:1.5rem;
   justify-content: center;
   margin-top: 2rem;
}

.quick-view form .row .image-container .sub-image img{
   height: 7rem;
   width: 10rem;
   object-fit: contain;
   padding:.5rem;
   border:var(--border);
   cursor: pointer;
   transition: .2s linear;
}

.quick-view form .flex .image-container .sub-image img:hover{
   transform: scale(1.1);
}

.quick-view form img{
   width: 100%;
   height: 20rem;
   object-fit: contain;
   margin-bottom: 2rem;
}

.quick-view form .row .content{
   flex:1 1 40rem;
}

.quick-view form .row .content .name{
   font-size: 2rem;
   color:var(--black);
}

.quick-view form .row .flex{
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap:1rem;
   margin:1rem 0;
}

.quick-view form .row .flex .qty{
   width: 7rem;
   padding:1rem;
   border:var(--border);
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
}

.quick-view form .row .flex .price{
   font-size: 2rem;
   color:var(--red);
}

.quick-view form .row .content .details{
   font-size: 1.6rem;
   color:var(--light-color);
   line-height: 2;
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

.products .box-container .box .fa-heart,
.products .box-container .box .fa-eye{
   position: absolute;
   top:1rem;
   height: 4.5rem;
   width: 4.5rem;
   line-height: 4.2rem;
   font-size: 2rem;
   background-color: var(--white);
   border:var(--border);
   border-radius: .5rem;
   text-align: center;
   color:var(--black);
   cursor: pointer;
   transition: .2s linear;
}

.products .box-container .box .fa-heart{
   right: -6rem;
}

.products .box-container .box .fa-eye{
   left: -6rem;
}

.products .box-container .box .fa-heart:hover,
.products .box-container .box .fa-eye:hover{
   background-color: var(--black);
   color:var(--white);
}

.products .box-container .box:hover .fa-heart{
   right:1rem;
}

.products .box-container .box:hover .fa-eye{
   left:1rem;
}

.products .box-container .box .name{
   font-size: 2rem;
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

.form-container form{
   background-color: var(--white);
   padding:2rem;
   border-radius: .5rem;
   border:var(--border);
   box-shadow: var(--box-shadow);
   text-align: center;
   margin:0 auto;
   max-width: 50rem;
}

.form-container form h3{
   font-size: 2.5rem;
   text-transform: uppercase;
   color:var(--black);
}

.form-container form p{
   font-size: 2rem;
   color:var(--light-color);
   margin:1.5rem 0;
}

.form-container form .box{
   margin:1rem 0;
   background-color: var(--light-bg);
   padding:1.4rem;
   font-size: 1.8rem;
   color:var(--black);  
   width: 100%;
   border-radius: .5rem;
}

.about .row{
   display: flex;
   align-items: center;
   flex-wrap: wrap;
   gap:1.5rem;
}

.about .row .image{
   flex:1 1 40rem;
}

.about .row .image img{
   width: 100%;
}

.about .row .content{
   flex:1 1 40rem;
}

.about .row .content h3{
   font-size: 3rem;
   color:var(--black);
}

.about .row .content p{
   line-height: 2;
   font-size: 1.5rem;
   color:var(--light-color);
   padding:1rem 0;
}

.about .row .content .btn{
   display: inline-block;
   width: auto;
}

.reviews .slide{
   padding:2rem;
   text-align: center;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   border:var(--border);
   margin-bottom: 5rem;
   user-select: none;
}

.reviews .slide img{
   height: 10rem;
   width: 10rem;
   border-radius: 50%;
   margin-bottom: .5rem;
}

.reviews .slide p{
   padding:1rem 0;
   line-height: 2;
   font-size: 1.5rem;
   color:var(--light-color);
}

.reviews .slide .stars{
   display: inline-block;
   margin-bottom: 1rem;
   background-color: var(--light-bg);
   padding:1rem 1.5rem;
   border-radius: .5rem;
}

.reviews .slide .stars i{
   margin:0 .3rem;
   font-size: 1.7rem;
   color:var(--orange);
}

.reviews .slide h3{
   font-size: 2rem;
   color:var(--black);
}

.contact form{
   padding:2rem;
   text-align: center;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   border:var(--border);
   max-width: 50rem;
   margin:0 auto;
}

.contact form h3{
   margin-bottom: 1rem;
   text-transform: capitalize;
   font-size: 2.5rem;
   color:var(--black);
}

.contact form .box{
   margin:1rem 0;
   width: 100%;
   background-color: var(--light-bg);
   padding:1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
}

.contact form textarea{
   height: 15rem;
   resize: none;
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

.wishlist-total{
   max-width: 50rem;
   margin:0 auto;
   margin-top: 3rem;
   background-color: var(--white);
   border:var(--border);
   border-radius: .5rem;;
   padding:2rem;
   text-align: center;
}

.wishlist-total p{
   font-size: 2.5rem;
   color:var(--black);
   margin-bottom: 2rem;
}

.wishlist-total p span{
   color:var(--red);
}

.shopping-cart .fa-edit{
   height: 4.5rem;
   border-radius: .5rem;
   background-color: var(--orange);
   width: 5rem;
   font-size: 2rem;
   color:var(--white);
   cursor: pointer;
}

.shopping-cart .fa-edit:hover{
   background-color: var(--black);
}

.shopping-cart .sub-total{
   margin:2rem 0;
   font-size: 2rem;
   color:var(--light-color);
}

.shopping-cart .sub-total span{
   color:var(--red);
}

.cart-total{
   max-width: 50rem;
   margin:0 auto;
   margin-top: 3rem;
   background-color: var(--white);
   border:var(--border);
   border-radius: .5rem;;
   padding:2rem;
   text-align: center;
}

.cart-total p{
   font-size: 2.5rem;
   color:var(--black);
   margin-bottom: 2rem;
}

.cart-total p span{
   color:var(--red);
}

.display-orders{
   text-align: center;
   padding-bottom: 0;
}

.display-orders p{
   display: inline-block;
   padding:1rem 2rem;
   margin:1rem .5rem;
   font-size: 2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.display-orders p span{
   color:var(--red);
}

.display-orders .grand-total{
   margin-top: 1.5rem;
   margin-bottom: 2.5rem;
   font-size: 2.5rem;
   color:var(--light-color);
}

.display-orders .grand-total span{
   color:var(--red);
}

.checkout-orders form{
   padding:2rem;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.checkout-orders form h3{
   border-radius: .5rem;
   background-color: var(--black);
   color:var(--white);
   padding:1.5rem 1rem;
   text-align: center;
   text-transform: uppercase;
   margin-bottom: 2rem;
   font-size: 2.5rem;
}

.checkout-orders form .flex{
   display: flex;
   flex-wrap: wrap;
   gap:1.5rem;
   justify-content: space-between;
}

.checkout-orders form .flex .inputBox{
   width: 49%;
}

.checkout-orders form .flex .inputBox .box{
   width: 100%;
   border:var(--border);
   border-radius: .5rem;
   font-size: 1.8rem;
   color:var(--black);
   padding:1.2rem 1.4rem;
   margin:1rem 0;
   background-color: var(--light-bg);
}

.checkout-orders form .flex .inputBox span{
   font-size: 1.8rem;
   color:var(--light-color);
}

.orders .box-container{
   display: flex;
   flex-wrap: wrap;
   gap:1.5rem;
   align-items: flex-start;
}

.orders .box-container .box{
   padding:1rem 2rem;
   flex:1 1 40rem;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.orders .box-container .box p{
   margin:.5rem 0;
   line-height: 1.8;
   font-size: 2rem;
   color:var(--light-color);
}

.orders .box-container .box p span{
   color:var(--main-color);
}

.footer{
   background-color: var(--light-bg);
   /* padding-bottom: 7rem; */
}

.footer .grid{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr));
   gap:1.5rem;
   align-items: flex-start;
}

.footer .grid .box h3{
   font-size: 2rem;
   color:var(--black);
   margin-bottom: 2rem;
   text-transform: capitalize;
}

.footer .grid .box a{
   display: block;
   margin:1.5rem 0;
   font-size: 1.7rem;
   color:var(--light-color);
}

.footer .grid .box a i{
   padding-right: 1rem;
   color:var(--main-color);
   transition: .2s linear;
}

.footer .grid .box a:hover{
   color:var(--main-color);
}

.footer .grid .box a:hover i{
   padding-right: 2rem;
}

.footer .credit{
   text-align: center;
   padding: 2.5rem 2rem;
   border-top: var(--border);
   font-size: 2rem;
   color:var(--black);
}

.footer .credit span{
   color:var(--main-color);
}

@media (max-width:991px){

   html{
      font-size: 55%;
   }

}

@media (max-width:768px){

   #menu-btn{
      display: inline-block;
   }

   .header .flex .navbar{
      position: absolute;
      top:99%; left:0; right:0;
      border-top: var(--border);
      border-bottom: var(--border);
      background-color: var(--white);
      transition: .2s linear;
      clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
   }

   .header .flex .navbar.active{
      clip-path: polygon(0 0, 100% 0, 100% 100%, 0% 100%);
   }

   .header .flex .navbar a{
      display: block;
      margin:2rem;
   }

   .home-bg .home .slide .content{
      text-align: center;
   }

   .home-bg .home .slide .content h3{
      font-size: 3rem;
   }

}

@media (max-width:450px){

   html{
      font-size: 50%;
   }

   .heading{
      font-size: 3rem;
   }

   .flex-btn{
      flex-flow: column;
      gap:0;
   }

   .quick-view form .row .image-container .sub-image img{
      width: 8rem;
   }

   .checkout-orders form .flex .inputBox{
      width: 100%;
   }
}
</style>

</head>
<body>
<?php include 'components/addToCart.php'; ?>  
<?php include 'components/user_header.php'; ?>

<div class="home-bg">
<section class="home">

   <div class="swiper home-slider">
   
   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-1.png" alt="">
         </div>
         <div class="content">
            <span>Upto 50% off</span>
            <h3>New Smartphones</h3>
            <a href="shop.php" class="btn btn-red">Shop Now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-2.png" alt="">
         </div>
         <div class="content">
            <span>Upto 50% off</span>
            <h3>New Watches</h3>
            <a href="shop.php" class="btn">Shop Now</a>
         </div>
      </div>

      <div class="swiper-slide slide">
         <div class="image">
            <img src="images/home-img-3.png" alt="">
         </div>
         <div class="content">
            <span>Upto 50% off</span>
            <h3>New Headsets</h3>
            <a href="shop.php" class="btn">Shop Now</a>
         </div>
      </div>

   </div>

      <div class="swiper-pagination"></div>

   </div>

</section>

</div>

<section class="category">

   <h1 class="heading">Shop By Category</h1>

   <div class="swiper category-slider">

   <div class="swiper-wrapper">

   <div class="swiper-slide slide">
      <img src="images/laptop.png" alt="">
      <h3>Laptop</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/tivi.png" alt="">
      <h3>TV</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/camera.png" alt="">
      <h3>Camera</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/mouse_computer.png" alt="">
      <h3>Mouse</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/fridge.png" alt="">
      <h3>Fridge</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/washing_machine.png" alt="">
      <h3>Washing Machine</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/smartphone.png" alt="">
      <h3>Smartphone</h3>
   </div>

   <div class="swiper-slide slide">
      <img src="images/watch.png" alt="">
      <h3>Watch</h3>
   </div>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<section class="home-products">

   <h1 class="heading">New Products</h1>

   <div class="swiper products-slider">

   <div class="swiper-wrapper">

   <?php
     $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6"); 
     $select_products->execute();
     if($select_products->rowCount() > 0){
      while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="swiper-slide slide">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="p_name" value="<?= $fetch_product['p_name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <a href="quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['p_name']; ?></div>
      <div class="flex">
         <div class="price"><?= $fetch_product['price']; ?><span>VNĐ</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">No Products Added Yet!</p>';
   }
   ?>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".home-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
    },
});

 var swiper = new Swiper(".category-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
         slidesPerView: 2,
       },
      650: {
        slidesPerView: 3,
      },
      768: {
        slidesPerView: 4,
      },
      1024: {
        slidesPerView: 5,
      },
   },
});

var swiper = new Swiper(".products-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      550: {
        slidesPerView: 2,
      },
      768: {
        slidesPerView: 2,
      },
      1024: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>