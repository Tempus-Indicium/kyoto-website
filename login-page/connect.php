<?php
function connect($email,$password)
{
  $connection = mysql_connect("localhost", "root", "");
  $db = mysql_select_db("company",$connection);
  $query = mysql_query("select * from login where password='$password' AND username='$email'", $connection);
  $rows = mysql_num_rows($query);
  if ($rows == 1) {
    session_start();
    $_SESSION['login_user']=$email;
    header("location: index.html");
  }
  else {
    return("Email or Password is invalid");
  }
  mysql_close($connection);
}
?>
