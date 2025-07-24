<?php
// =============================================================================
// 4. CREATE VIEW - application/views/guru/tugas/index.php
// =============================================================================
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
            <i class="fas fa-tasks"></i> Daftar Tugas
          </h3>
          <div class="card-tools">
            <a href="<?= base_url('guru/tugas/create') ?>" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Buat Tugas Baru
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <!-- Filters -->
          <div class="row mb-3">
            <div class="col-md-4">
              <?= form_open(base_url('guru/tugas'), 'method="GET"') ?>
                <input type="text" name="search" class="form-control" placeholder="Cari judul tugas..." value="<?= htmlspecialchars($search) ?>">
              <?= form_close() ?>
            </div>
            <div class="col-md-3">
              <?= form_open(base_url('guru/tugas'), 'method="GET"') ?>
                <select name="kelas" class="form-control" onchange="this.form.submit()">
                  <option value="">Semua Kelas</option>
                  <?php foreach ($kelas_options as $kelas): ?>
                    <option value="<?= $kelas->id ?>" <?= $kelas_filter == $kelas->id ? 'selected' : '' ?>>
                      <?= $kelas->nama_kelas ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php if ($search): ?><input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
              <?= form_close() ?>
            </div>
            <div class="col-md-3">
              <?= form_open(base_url('guru/tugas'), 'method="GET"') ?>
                <select name="status" class="form-control" onchange="this.form.submit()">
                  <option value="">Semua Status</option>
                  <option value="draft" <?= $status_filter == 'draft' ? 'selected' : '' ?>>Draft</option>
                  <option value="published" <?= $status_filter == 'published' ? 'selected' : '' ?>>Published</option>
                  <option value="closed" <?= $status_filter == 'closed' ? 'selected' : '' ?>>Closed</option>
                </select>
                <?php if ($search): ?><input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                <?php if ($kelas_filter): ?><input type="hidden" name="kelas" value="<?= $kelas_filter ?>"><?php endif; ?>
              <?= form_close() ?>
            </div>
          </div>

          <?php if (empty($tugas_list)): ?>
            <div class="text-center py-5">
              <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
              <h5 class="text-muted">Belum ada tugas</h5>
              <a href="<?= base_url('guru/tugas/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Buat Tugas Pertama
              </a>
            </div>
          <?php else: ?>
            <div class="table-responsive">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Judul</th>
                    <th>Kelas</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($tugas_list as $index => $t): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td>
                        <strong><?= htmlspecialchars($t->judul) ?></strong>
                        <?php if ($t->file_materi): ?>
                          <br><small><i class="fas fa-paperclip"></i> Ada materi</small>
                        <?php endif; ?>
                      </td>
                      <td><?= htmlspecialchars($t->nama_kelas) ?></td>
                      <td>
                        <small><?= date('d/m/Y H:i', strtotime($t->deadline)) ?></small>
                        <?php if (strtotime($t->deadline) < time()): ?>
                          <br><span class="badge badge-danger">Terlambat</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php
                        $badges = array(
                          'draft' => 'badge-secondary',
                          'published' => 'badge-success',
                          'closed' => 'badge-danger'
                        );
                        ?>
                        <span class="badge <?= $badges[$t->status] ?>"><?= ucfirst($t->status) ?></span>
                      </td>
                      <td>
                        <a href="<?= base_url('guru/tugas/detail/' . $t->id) ?>" class="btn btn-info btn-sm">
                          <i class="fas fa-eye"></i>
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>