<?php

include 'components/connect.php';
include 'components/calc_tokens.php';
include 'components/update_tokens.php';
include 'components/update_offers.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_POST['submit'])){
   
   
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = $_POST['pass'];
   $cpass = $_POST['cpass'];

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR username = ?");
   $select_user->execute([$email, $name]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);
   
   $number = preg_match('@[A-Z]@', $cpass);
   $uppercase = preg_match('@[^\w]@', $cpass);
   $lowercase = preg_match('@[a-z]@', $cpass);
   $specialChars = preg_match('@[0-9]@', $cpass);


   if($select_user->rowCount() > 0){
      $message[] = 'Το e-mail ή το username χρησιμοποιούνται ήδη!';
   }else{
      if($pass != $cpass){
         $message[] = 'Οι κωδικοί δεν ταιριάζουν μεταξύ τους!';
      }elseif(strlen($cpass) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars){
         $message[] = 'Ο κωδικός πρέπει να είναι τουλάχιστον 8 χαρακτήρες και να περιέχει τουλάχιστον ένα κεφαλαίο, ένα μικρό γράμμα,έναν αριθμό και έναν ειδικό χαρακτήρα ';
      }
      else{
         $securepass = sha1($cpass);
         $insert_user = $conn->prepare("INSERT INTO `users`(username, password, email, signup_date, user_type) VALUES(?,?,?,NOW(),'user')");
         $insert_user->execute([$name, $securepass, $email]);
         $message[] = 'Επιτυχής εγγραφή! Συνδεθείτε στον λογαριασμό σας.';
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register</title>
   
   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">

   <form action="" method="post">
      <h3>register now</h3>
      <input type="text" name="name" required placeholder="enter your username" maxlength="20"  class="box">
      <input type="email" name="email" required placeholder="enter your email" maxlength="50"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="enter your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="confirm your password" maxlength="20"  class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="register now" class="btn" name="submit">
      <p>already have an account?</p>
      <a href="user_login.php" class="option-btn">login now</a>
   </form>

</section>













<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>