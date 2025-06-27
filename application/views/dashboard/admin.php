<?php
// =============================================================================
// 14. VIEW DASHBOARD - application/views/dashboard/admin.php
// =============================================================================
$this->load->view('templates/header', ['title' => 'Dashboard Admin', 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Administrator</h1>
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
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-user-shield"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Admin</span>
              <span class="info-box-number"><?= $stats['total_super_admin'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-chalkboard-teacher"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Guru</span>
              <span class="info-box-number"><?= $stats['total_guru'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-graduation-cap"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Siswa</span>
              <span class="info-box-number"><?= $stats['total_siswa'] ?></span>
            </div>
          </div>
        </div>
        
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total User</span>
              <span class="info-box-number"><?= ($stats['total_super_admin'] + $stats['total_guru'] + $stats['total_siswa']) ?></span>
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
                    <i class="fas fa-user-plus"></i> Tambah Guru
                  </a>
                  <a href="#" class="btn btn-success btn-block">
                    <i class="fas fa-user-graduate"></i> Tambah Siswa
                  </a>
                </div>
                <div class="col-6">
                  <a href="#" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-calendar-plus"></i> Periode Baru
                  </a>
                  <a href="#" class="btn btn-warning btn-block">
                    <i class="fas fa-download"></i> Export Data
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- System Info -->
        <div class="col-md-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info-circle mr-1"></i>
                Informasi Sistem
              </h3>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-4">Versi Sistem:</dt>
                <dd class="col-sm-8">Kelas Digital v1.0.0</dd>
                
                <dt class="col-sm-4">Framework:</dt>
                <dd class="col-sm-8">CodeIgniter 3.1.13</dd>
                
                <dt class="col-sm-4">Database:</dt>
                <dd class="col-sm-8">MySQL 5.7+</dd>
                
                <dt class="col-sm-4">Template:</dt>
                <dd class="col-sm-8">AdminLTE 3.2.0</dd>
              </dl>
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
              <div class="timeline timeline-inverse">
                <div class="time-label">
                  <span class="bg-success">Sistem Aktif</span>
                </div>
                <div>
                  <i class="fas fa-check bg-green"></i>
                  <div class="timeline-item">
                    <span class="time"><i class="far fa-clock"></i> Hari ini</span>
                    <h3 class="timeline-header">Sistem Kelas Digital beroperasi normal</h3>
                    <div class="timeline-body">
                      Database terhubung, semua fitur berfungsi dengan baik.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>