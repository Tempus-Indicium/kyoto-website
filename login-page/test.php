<?php
function login(){
include 'connect.php';
if (!empty($_POST['password'])) {
    if (!empty($_POST['email'])){
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        connect($email,$password);}
    } else {
        return("Please fill in your credentials");
    }
  }
  
?>
