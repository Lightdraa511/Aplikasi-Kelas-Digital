<?php
// =============================================================================
// 11. VIEW SISWA - application/views/siswa/kelas/index.php
// =============================================================================
$this->load->view('templates/header', ['title' => $title, 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= $title ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active"><?= $title ?></li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <?php if($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="fas fa-times"></i> <?= $this->session->flashdata('error') ?>
        </div>
      <?php endif; ?>

      <!-- Main card -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-graduation-cap"></i> Kelas Yang Saya Ikuti
          </h3>
        </div>
        
        <div class="card-body">
          <?php if (empty($kelas_list)): ?>
            <div class="text-center py-5">
              <i class="fas fa-book fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">Belum terdaftar di kelas manapun</h5>
              <p class="text-muted">Hubungi guru untuk mendaftarkan Anda ke kelas</p>
            </div>
          <?php else: ?>
            <div class="row">
              <?php foreach ($kelas_list as $k): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card mb-3 border-left-primary">
                    <div class="card-body">
                      <h6 class="card-title">
                        <strong><?= htmlspecialchars($k->nama_kelas) ?></strong>
                      </h6>
                      
                      <p class="card-text">
                        <span class="badge badge-primary mb-2"><?= htmlspecialchars($k->mata_pelajaran) ?></span><br>
                        <small class="text-muted">
                          <i class="fas fa-chalkboard-teacher"></i> <?= htmlspecialchars($k->nama_guru) ?><br>
                          <i class="fas fa-calendar"></i> <?= $k->tahun_ajaran ?> - <?= $k->semester ?>
                        </small>
                      </p>
                      
                      <?php if ($k->deskripsi): ?>
                        <p class="card-text">
                          <small><?= htmlspecialchars(substr($k->deskripsi, 0, 80)) ?><?= strlen($k->deskripsi) > 80 ? '...' : '' ?></small>
                        </p>
                      <?php endif; ?>
                      
                      <div class="row text-center mb-3">
                        <div class="col-6">
                          <small class="text-muted">Teman Sekelas</small><br>
                          <strong><?= $k->stats['total_siswa'] ?></strong>
                        </div>
                        <div class="col-6">
                          <small class="text-muted">Tugas</small><br>
                          <strong><?= $k->stats['total_tugas'] ?></strong>
                        </div>
                      </div>
                      
                      <a href="<?= base_url('siswa/kelas/detail/' . $k->id) ?>" 
                         class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-eye"></i> Lihat Detail
                      </a>
                    </div>
                    <div class="card-footer text-muted">
                      <small>Bergabung: <?= date('d/m/Y', strtotime($k->joined_at)) ?></small>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
