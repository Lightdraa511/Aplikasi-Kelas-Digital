<?php
// =============================================================================
// 11. VIEW TEMPLATE - application/views/templates/header.php
// =============================================================================
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? $title . ' - Kelas Digital' : 'Kelas Digital' ?></title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- AdminLTE -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
  
  <style>
    .content-wrapper { min-height: calc(100vh - 102px); }
    .main-header .navbar { border-bottom: 1px solid #dee2e6; }
    .brand-text { font-weight: 300; }
    @media (max-width: 768px) {
      .content-wrapper { padding: 10px; }
      .card { margin-bottom: 15px; }
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- User Account Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-user"></i>
          <span class="d-none d-md-inline"><?= $user['nama_lengkap'] ?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <div class="dropdown-header">
            <strong><?= $user['nama_lengkap'] ?></strong><br>
            <small class="text-muted"><?= ucwords(str_replace('_', ' ', $user['role'])) ?></small>
          </div>
          <div class="dropdown-divider"></div>
          <a href="<?= base_url('change-password') ?>" class="dropdown-item">
            <i class="fas fa-key mr-2"></i> Ubah Password
          </a>
          <div class="dropdown-divider"></div>
          <a href="<?= base_url('logout') ?>" class="dropdown-item dropdown-footer">
            <i class="fas fa-sign-out-alt mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>