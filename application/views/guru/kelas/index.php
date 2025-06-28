<?php
// =============================================================================
// 7. VIEW GURU - application/views/guru/kelas/index.php (MANAGEMENT)
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
      
      <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="fas fa-check"></i> <?= $this->session->flashdata('success') ?>
        </div>
      <?php endif; ?>
      
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
            <i class="fas fa-chalkboard"></i> Kelas Yang Saya Ajar
          </h3>
          <div class="card-tools">
            <a href="<?= base_url('guru/kelas/create') ?>" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Buat Kelas Baru
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <!-- Filters -->
          <div class="row mb-3">
            <div class="col-md-6">
              <?= form_open(base_url('guru/kelas'), 'method="GET" class="form-inline"') ?>
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Cari nama kelas atau mata pelajaran..." value="<?= htmlspecialchars($search) ?>">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              <?= form_close() ?>
            </div>
            <div class="col-md-4">
              <?= form_open(base_url('guru/kelas'), 'method="GET" class="form-inline"') ?>
                <select name="status" class="form-control" onchange="this.form.submit()">
                  <option value="">Semua Status</option>
                  <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Aktif</option>
                  <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
                <?php if ($search): ?><input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
              <?= form_close() ?>
            </div>
            <div class="col-md-2">
              <?php if ($search || $status_filter !== ''): ?>
                <a href="<?= base_url('guru/kelas') ?>" class="btn btn-outline-secondary">
                  <i class="fas fa-times"></i> Reset
                </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Results info -->
          <div class="row mb-3">
            <div class="col-12">
              <small class="text-muted">
                Anda memiliki <?= $total_kelas ?> kelas
                <?php if ($search || $status_filter !== ''): ?>
                  | Menampilkan <?= count($kelas_list) ?> kelas sesuai filter
                <?php endif; ?>
              </small>
            </div>
          </div>

          <!-- Kelas grid -->
          <?php if (empty($kelas_list)): ?>
            <div class="text-center py-5">
              <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">
                <?php if ($search || $status_filter !== ''): ?>
                  Tidak ada kelas yang sesuai dengan filter
                <?php else: ?>
                  Belum ada kelas
                <?php endif; ?>
              </h5>
              <?php if (!$search && $status_filter === ''): ?>
                <p class="text-muted">Mulai dengan membuat kelas baru untuk mengajar siswa</p>
                <a href="<?= base_url('guru/kelas/create') ?>" class="btn btn-primary">
                  <i class="fas fa-plus"></i> Buat Kelas Pertama
                </a>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="row">
              <?php foreach ($kelas_list as $k): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card mb-3 <?= !$k->is_active ? 'bg-light' : '' ?>">
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-start mb-2">
                        <h6 class="card-title mb-0">
                          <strong><?= htmlspecialchars($k->nama_kelas) ?></strong>
                        </h6>
                        <?php if ($k->is_active): ?>
                          <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                          <span class="badge badge-secondary">Nonaktif</span>
                        <?php endif; ?>
                      </div>
                      
                      <p class="card-text">
                        <span class="badge badge-primary mb-2"><?= htmlspecialchars($k->mata_pelajaran) ?></span><br>
                        <small class="text-muted"><?= $k->tahun_ajaran ?> - <?= $k->semester ?></small>
                      </p>
                      
                      <?php if ($k->deskripsi): ?>
                        <p class="card-text">
                          <small><?= htmlspecialchars(substr($k->deskripsi, 0, 80)) ?><?= strlen($k->deskripsi) > 80 ? '...' : '' ?></small>
                        </p>
                      <?php endif; ?>
                      
                      <div class="row text-center mb-3">
                        <div class="col-6">
                          <small class="text-muted">Siswa</small><br>
                          <strong><?= $k->stats['total_siswa'] ?></strong>
                        </div>
                        <div class="col-6">
                          <small class="text-muted">Tugas</small><br>
                          <strong><?= $k->stats['total_tugas'] ?></strong>
                        </div>
                      </div>
                      
                      <div class="btn-group btn-group-sm w-100" role="group">
                        <a href="<?= base_url('guru/kelas/students/' . $k->id) ?>" 
                           class="btn btn-info" title="Kelola Siswa">
                          <i class="fas fa-users"></i>
                        </a>
                        <a href="<?= base_url('guru/kelas/edit/' . $k->id) ?>" 
                           class="btn btn-warning" title="Edit Kelas">
                          <i class="fas fa-edit"></i>
                        </a>
                        <button type="button" 
                                class="btn <?= $k->is_active ? 'btn-secondary' : 'btn-success' ?>" 
                                title="<?= $k->is_active ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                onclick="toggleStatus(<?= $k->id ?>, '<?= htmlspecialchars($k->nama_kelas) ?>', <?= $k->is_active ?>)">
                          <i class="fas <?= $k->is_active ? 'fa-ban' : 'fa-check' ?>"></i>
                        </button>
                      </div>
                    </div>
                    <div class="card-footer text-muted">
                      <small>Dibuat: <?= date('d/m/Y', strtotime($k->created_at)) ?></small>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination)): ?>
              <div class="row mt-3">
                <div class="col-12">
                  <?= $pagination ?>
                </div>
              </div>
            <?php endif; ?>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
function toggleStatus(id, namaKelas, currentStatus) {
    var action = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
    if (confirm('Yakin ingin ' + action + ' kelas "' + namaKelas + '"?')) {
        window.location.href = '<?= base_url("guru/kelas/toggle/") ?>' + id;
    }
}
</script>

<?php $this->load->view('templates/footer'); ?>
