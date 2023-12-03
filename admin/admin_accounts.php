<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
   header('location:admin_login.php');
}

class Admin {
   private $id;
   private $name;

   // Tạo một hàm khởi tạo để gán giá trị cho các thuộc tính khi tạo một đối tượng Admin mới
   public function __construct($id, $name) {
      $this->id = $id;
      $this->name = $name;
   }

   // Tạo một phương thức để xóa một đối tượng Admin khỏi cơ sở dữ liệu bằng id của nó
   public static function deleteAdmin($id) {
      global $conn;
      $sql = "DELETE FROM `admins` WHERE id = ?";
      $stmt = $conn->prepare($sql);
      $stmt->execute([$id]);
   }

   // Tạo một phương thức để lấy tất cả các đối tượng Admin từ cơ sở dữ liệu và trả về một mảng chứa các đối tượng Admin
   public static function getAllAdmins() {
      global $conn;
      $sql = "SELECT * FROM `admins`";
      $stmt = $conn->prepare($sql);
      $stmt->execute();

      // Tạo một mảng rỗng để chứa các đối tượng Admin
      $admins = [];

      // Lặp qua các kết quả trả về bằng phương thức fetch của đối tượng PDOStatement và gán cho biến $row
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
         // Tạo một đối tượng Admin mới bằng hàm khởi tạo và truyền vào giá trị id và name từ biến $row
         $admin = new Admin($row['id'], $row['name']);

        // Thêm đối tượng Admin vào mảng $admins
                $admins[] = $admin;
              }
        // Trả về mảng $admins
              return $admins;
           }
        
        // Tạo các phương thức getter để lấy giá trị của các thuộc tính
           public function getId() {
              return $this->id;
           }
        
           public function getName() {
              return $this->name;
           }
        }
        
        // Nếu có dữ liệu được gửi từ biến GET, gọi phương thức deleteAdmin của lớp Admin và truyền vào giá trị của biến delete
        if (isset($_GET['delete'])) {
           $delete_id = $_GET['delete'];
           Admin::deleteAdmin($delete_id);
           header('location:admin_accounts.php');
        }
        
        ?>
        
        <!DOCTYPE html>
        <html lang="en">
        <head>
        <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

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
        
           <h1 class="heading">Admin Accounts</h1>
        
           <div class="box-container">
        
           <div class="box">
              <p>Add New Admin</p>
              <a href="register_admin.php" class="option-btn">Register Admin</a>
           </div>
        
           <?php
              // Gọi phương thức getAllAdmins của lớp Admin và gán kết quả cho biến $admins
              $admins = Admin::getAllAdmins();
        
              // Kiểm tra xem biến $admins có phải là một mảng hay không và có phần tử hay không
              if (is_array($admins) && count($admins) > 0) {
                 // Lặp qua các phần tử trong mảng $admins và gán cho biến $admin
                 foreach ($admins as $admin) {
                    // Lấy giá trị id và name của đối tượng Admin bằng các phương thức getter
                    $id = $admin->getId();
                    $name = $admin->getName();
           ?>
           <div class="box">
              <p> Admin ID : <span><?= $id; ?></span> </p>
              <p> Admin Name : <span><?= $name; ?></span> </p>
              <div class="flex-btn">
                 <a href="admin_accounts.php?delete=<?= $id; ?>" onclick="return confirm('Delete this account?')" class="delete-btn">Delete</a>
                 <?php
                    if ($id == $admin_id) {
                       echo '<a href="update_profile.php" class="option-btn">Update</a>';
                    }
                 ?>
              </div>
           </div>
           <?php
                 }
              } else {
                 echo '<p class="empty">No Accounts Available!</p>';
              }
           ?>
        
           </div>
        
        </section>
        
        <script src="../js/admin_script.js"></script> 
        </body>
        </html>
        