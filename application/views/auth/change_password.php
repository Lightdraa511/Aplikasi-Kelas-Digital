<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ubah Password - Kelas Digital</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="card card-outline card-warning">
    <div class="card-header text-center">
      <h1><b>Ubah</b>Password</h1>
      <p class="login-box-msg">Silakan ubah password default Anda</p>
    </div>
    <div class="card-body">
      
      <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?= $this->session->flashdata('error') ?>
        </div>
      <?php endif; ?>
      
      <?php if($this->session->flashdata('warning')): ?>
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?= $this->session->flashdata('warning') ?>
        </div>
      <?php endif; ?>

      <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> 
        Hai <strong><?= $user['nama_lengkap'] ?></strong>, untuk keamanan akun Anda, silakan ubah password default.
      </div>

      <?= form_open('change-password') ?>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="current_password" placeholder="Password Lama" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <?= form_error('current_password', '<small class="text-danger">', '</small>') ?>
        
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="new_password" placeholder="Password Baru (Min. 6 karakter)" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-key"></span>
            </div>
          </div>
        </div>
        <?= form_error('new_password', '<small class="text-danger">', '</small>') ?>
        
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="confirm_password" placeholder="Konfirmasi Password Baru" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-check"></span>
            </div>
          </div>
        </div>
        <?= form_error('confirm_password', '<small class="text-danger">', '</small>') ?>
        
        <div class="row">
          <div class="col-6">
            <a href="<?= base_url('logout') ?>" class="btn btn-default btn-block">Logout</a>
          </div>
          <div class="col-6">
            <button type="submit" class="btn btn-warning btn-block">Ubah Password</button>
          </div>
        </div>
      <?= form_close() ?>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>
