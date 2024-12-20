<?php

  require_once('db_connection.php');

  if(!isset($_POST['action'])){
    echo "Error";
    exit();
  }