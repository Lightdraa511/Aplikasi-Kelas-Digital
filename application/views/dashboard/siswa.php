<?php
// =============================================================================
// 16. VIEW DASHBOARD - application/views/dashboard/siswa.php
// =============================================================================
$this->load->view('templates/header', ['title' => 'Dashboard Siswa', 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Siswa</h1>
          <p class="text-muted">Selamat datang, <?= $user['nama_lengkap'] ?>!</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="fas fa-check"></i> <?= $this->session->flashdata('success') ?>
        </div>
      <?php endif; ?>

      <!-- Info boxes -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Kelas Diikuti</span>
              <span class="info-box-number"><?= $stats['total_kelas'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tugas Mendatang</span>
              <span class="info-box-number"><?= $stats['tugas_mendatang'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tugas Terlambat</span>
              <span class="info-box-number"><?= $stats['tugas_terlambat'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-trophy"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Nilai Terbaru</span>
              <span class="info-box-number">0</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Main row -->
      <div class="row">
        <!-- Tugas Mendatang -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-calendar-check mr-1"></i>
                Tugas Mendatang
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center py-4">
                <i class="fas fa-clipboard-check fa-2x text-muted mb-3"></i>
                <p class="text-muted">Belum ada tugas mendatang</p>
                <small class="text-muted">Tugas baru akan muncul di sini</small>
              </div>
            </div>
          </div>
        </div>

        <!-- Nilai Terbaru -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-star mr-1"></i>
                Nilai Terbaru
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center py-4">
                <i class="fas fa-medal fa-2x text-muted mb-3"></i>
                <p class="text-muted">Belum ada nilai</p>
                <small class="text-muted">Nilai akan muncul setelah guru melakukan penilaian</small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Kelas Yang Diikuti -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-graduation-cap mr-1"></i>
                Kelas Yang Diikuti
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center py-4">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum terdaftar di kelas manapun</h5>
                <p class="text-muted">Hubungi guru untuk mendaftarkan Anda ke kelas</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>