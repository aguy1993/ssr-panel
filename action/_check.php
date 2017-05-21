<?php
session_start();
if(isset($_SESSION['uid']) && $_SESSION['uid'] != null){
    $uid = $_SESSION['uid'];
}else{
    header("Location:../login.php");
}