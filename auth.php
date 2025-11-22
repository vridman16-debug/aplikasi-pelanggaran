<?php
session_start();
require_once __DIR__ . '/config.php';

function login($username, $password){
  global $pdo;
  $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
  $stmt->execute([$username]);
  $user = $stmt->fetch();
  if($user && password_verify($password, $user['password'])){
    $_SESSION['user'] = [
      'id'=>$user['id'],
      'username'=>$user['username'],
      'name'=>$user['name'],
      'role'=>$user['role']
    ];
    return true;
  }
  return false;
}

function require_login(){
  if(empty($_SESSION['user'])){ header('Location: /public/index.php'); exit; }
}

function is_admin(){
  return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}
