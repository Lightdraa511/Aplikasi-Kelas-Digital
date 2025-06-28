<?php
// =============================================================================
// 12. VIEW SISWA - application/views/siswa/kelas/detail.php
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
          <h1 class="m-0"><?= htmlspecialchars($kelas->nama_kelas) ?></h1>
          <p class="text-muted"><?= htmlspecialchars($kelas->mata_pelajaran) ?></p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('siswa/kelas') ?>">Kelas Saya</a></li>
            <li class="breadcrumb-item active">Detail Kelas</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <div class="row">
        <!-- Kelas Info -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info-circle"></i> Informasi Kelas
              </h3>
            </div>
            
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">Nama Kelas:</dt>
                <dd class="col-sm-9"><strong><?= htmlspecialchars($kelas->nama_kelas) ?></strong></dd>
                
                <dt class="col-sm-3">Mata Pelajaran:</dt>
                <dd class="col-sm-9">
                  <span class="badge badge-primary"><?= htmlspecialchars($kelas->mata_pelajaran) ?></span>
                </dd>
                
                <dt class="col-sm-3">Guru Pengajar:</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($kelas->nama_guru) ?></dd>
                
                <dt class="col-sm-3">Periode:</dt>
                <dd class="col-sm-9"><?= $kelas->tahun_ajaran ?> - <?= $kelas->semester ?></dd>
                
                <?php if ($kelas->deskripsi): ?>
                <dt class="col-sm-3">Deskripsi:</dt>
                <dd class="col-sm-9"><?= nl2br(htmlspecialchars($kelas->deskripsi)) ?></dd>
                <?php endif; ?>
              </dl>
            </div>
          </div>
          
          <!-- Tugas Section (Ready for Phase 5) -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-tasks"></i> Tugas Terbaru
              </h3>
            </div>
            
            <div class="card-body">
              <div class="text-center py-4">
                <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada tugas</h5>
                <p class="text-muted">Tugas dari guru akan muncul di sini</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
          <!-- Statistics -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-bar"></i> Statistik
              </h3>
            </div>
            <div class="card-body">
              <div class="row text-center">
                <div class="col-6">
                  <div class="border-right">
                    <strong class="d-block"><?= $stats['total_siswa'] ?></strong>
                    <small class="text-muted">Siswa</small>
                  </div>
                </div>
                <div class="col-6">
                  <strong class="d-block"><?= $stats['total_tugas'] ?></strong>
                  <small class="text-muted">Tugas</small>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Classmates -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-users"></i> Teman Sekelas
              </h3>
            </div>
            
            <div class="card-body" style="max-height: 300px; overflow-y: auto;">
              <?php if (empty($students)): ?>
                <p class="text-muted text-center">Belum ada siswa lain</p>
              <?php else: ?>
                <div class="list-group list-group-flush">
                  <?php foreach ($students as $s): ?>
                    <div class="list-group-item border-0 px-0 py-1">
                      <small>
                        <i class="fas fa-user text-muted"></i>
                        <?= htmlspecialchars($s->nama_lengkap) ?>
                      </small>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Quick Actions -->
          <div class="card">
            <div class="card-body text-center">
              <a href="<?= base_url('siswa/kelas') ?>" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelas
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
