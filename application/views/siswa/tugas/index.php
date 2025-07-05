<?php
$this->load->view('templates/header', ['title' => $title, 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<div class="content-wrapper">
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

  <section class="content">
    <div class="container-fluid">
      
      <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="fas fa-check"></i> <?= $this->session->flashdata('success') ?>
        </div>
      <?php endif; ?>

      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-clipboard-list"></i> Daftar Tugas
          </h3>
        </div>
        
        <div class="card-body">
          <?php if (empty($tugas_list)): ?>
            <div class="text-center py-5">
              <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">Belum ada tugas</h5>
              <p class="text-muted">Tugas dari guru akan muncul di sini</p>
            </div>
          <?php else: ?>
            <div class="row">
              <?php foreach ($tugas_list as $t): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card mb-3 <?= $t->submission_id ? 'border-success' : (strtotime($t->deadline) < time() ? 'border-danger' : 'border-warning') ?>">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">
                          <strong><?= htmlspecialchars($t->judul) ?></strong>
                        </h6>
                        <?php if ($t->submission_id): ?>
                          <span class="badge badge-success">Sudah Dikumpulkan</span>
                        <?php elseif (strtotime($t->deadline) < time()): ?>
                          <span class="badge badge-danger">Terlambat</span>
                        <?php else: ?>
                          <span class="badge badge-warning">Pending</span>
                        <?php endif; ?>
                      </div>
                      
                      <p class="card-text">
                        <small class="text-muted">
                          <i class="fas fa-chalkboard"></i> <?= htmlspecialchars($t->nama_kelas) ?><br>
                          <i class="fas fa-clock"></i> Deadline: <?= date('d/m/Y H:i', strtotime($t->deadline)) ?>
                        </small>
                      </p>
                      
                      <p class="card-text">
                        <small><?= htmlspecialchars(substr($t->deskripsi, 0, 100)) ?>...</small>
                      </p>
                      
                      <?php if ($t->nilai !== null): ?>
                        <div class="alert alert-info py-2">
                          <strong>Nilai: <?= $t->nilai ?>/<?= $t->max_poin ?></strong>
                        </div>
                      <?php endif; ?>
                      
                      <a href="<?= base_url('siswa/tugas/detail/' . $t->id) ?>" class="btn btn-primary btn-sm btn-block">
                        <i class="fas fa-eye"></i> Lihat Detail
                      </a>
                    </div>
                    <div class="card-footer text-muted">
                      <small>
                        <?php if ($t->submitted_at): ?>
                          Dikumpulkan: <?= date('d/m/Y H:i', strtotime($t->submitted_at)) ?>
                          <?php if ($t->is_late): ?>
                            <span class="text-danger">(Terlambat)</span>
                          <?php endif; ?>
                        <?php else: ?>
                          Belum dikumpulkan
                        <?php endif; ?>
                      </small>
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
