<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
} else {
    $admin_id = '';
}

if (isset($_POST['submit'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass']; // Do not sanitize the password for admins

    $select_admin = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ? AND user_type = 'admin'");
    $select_admin->execute([$email, $pass]);
    $row = $select_admin->fetch(PDO::FETCH_ASSOC);

    if ($select_admin->rowCount() > 0) {
        $_SESSION['admin_id'] = $row['user_id'];
        header('location: products.php'); 
    } else {
        $message[] = 'Incorrect email or password!';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Login</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body>

<?php
   if (isset($message)) {
       foreach ($message as $message) {
           echo '
         <div class="message">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
         </div>
         ';
       }
   }
?>

<section class="form-container">

   <form action="" method="post">
      <h3>Login Login</h3>
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login Now" class="btn" name="submit">
   </form>

</section>
   
</body>
</html>
