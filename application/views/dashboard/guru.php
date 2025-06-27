<?php
// =============================================================================
// 15. VIEW DASHBOARD - application/views/dashboard/guru.php  
// =============================================================================
$this->load->view('templates/header', ['title' => 'Dashboard Guru', 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Guru</h1>
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
        <div class="col-12 col-sm-6 col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chalkboard"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Kelas Diajar</span>
              <span class="info-box-number"><?= $stats['total_kelas'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-4">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-tasks"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Tugas Aktif</span>
              <span class="info-box-number"><?= $stats['total_tugas_aktif'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-4">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Menunggu Review</span>
              <span class="info-box-number">0</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Main row -->
      <div class="row">
        <!-- Quick Actions -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-bolt mr-1"></i>
                Quick Actions
              </h3>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-6">
                  <a href="#" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus"></i> Buat Kelas
                  </a>
                  <a href="#" class="btn btn-success btn-block">
                    <i class="fas fa-tasks"></i> Buat Tugas
                  </a>
                </div>
                <div class="col-6">
                  <a href="#" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-star"></i> Nilai Tugas
                  </a>
                  <a href="#" class="btn btn-warning btn-block">
                    <i class="fas fa-users"></i> Kelola Siswa
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Upcoming Deadlines -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-calendar-alt mr-1"></i>
                Deadline Mendatang
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center py-4">
                <i class="fas fa-info-circle fa-2x text-muted mb-3"></i>
                <p class="text-muted">Belum ada tugas dengan deadline mendatang</p>
                <a href="#" class="btn btn-sm btn-outline-primary">Buat Tugas Baru</a>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-history mr-1"></i>
                Aktivitas Terbaru
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center py-4">
                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada aktivitas</h5>
                <p class="text-muted">Mulai dengan membuat kelas atau tugas baru</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>