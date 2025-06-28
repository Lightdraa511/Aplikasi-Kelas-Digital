<?php
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

      <!-- Info alert -->
      <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <h5><i class="icon fas fa-info"></i> Monitoring Kelas</h5>
        Halaman ini menampilkan semua kelas untuk monitoring. Hanya guru yang dapat mengelola kelas mereka.
      </div>

      <!-- Main card -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-chalkboard"></i> Semua Kelas - Monitoring
          </h3>
        </div>
        
        <div class="card-body">
          <!-- Filters -->
          <div class="row mb-3">
            <div class="col-md-4">
              <?= form_open(base_url('admin/kelas'), 'method="GET" class="form-inline"') ?>
                <div class="input-group input-group-sm">
                  <input type="text" name="search" class="form-control" placeholder="Cari kelas, mapel, atau guru..." value="<?= htmlspecialchars($search) ?>">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              <?= form_close() ?>
            </div>
            <div class="col-md-3">
              <?= form_open(base_url('admin/kelas'), 'method="GET" class="form-inline"') ?>
                <select name="periode" class="form-control form-control-sm" onchange="this.form.submit()">
                  <option value="">Semua Periode</option>
                  <?php foreach ($periode_options as $periode): ?>
                    <option value="<?= $periode->id ?>" <?= $periode_filter == $periode->id ? 'selected' : '' ?>>
                      <?= $periode->tahun_ajaran ?> - <?= $periode->semester ?>
                      <?= $periode->is_active ? ' (Aktif)' : '' ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <?php if ($search): ?><input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                <?php if ($status_filter !== ''): ?><input type="hidden" name="status" value="<?= $status_filter ?>"><?php endif; ?>
              <?= form_close() ?>
            </div>
            <div class="col-md-3">
              <?= form_open(base_url('admin/kelas'), 'method="GET" class="form-inline"') ?>
                <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                  <option value="">Semua Status</option>
                  <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Aktif</option>
                  <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Nonaktif</option>
                </select>
                <?php if ($search): ?><input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>"><?php endif; ?>
                <?php if ($periode_filter): ?><input type="hidden" name="periode" value="<?= $periode_filter ?>"><?php endif; ?>
              <?= form_close() ?>
            </div>
            <div class="col-md-2">
              <?php if ($search || $periode_filter || $status_filter !== ''): ?>
                <a href="<?= base_url('admin/kelas') ?>" class="btn btn-outline-secondary btn-sm">
                  <i class="fas fa-times"></i> Reset
                </a>
              <?php endif; ?>
            </div>
          </div>

          <!-- Results info -->
          <div class="row mb-3">
            <div class="col-12">
              <small class="text-muted">
                Menampilkan <?= count($kelas_list) ?> dari <?= $total_kelas ?> kelas
                <?php 
                $active_filters = array();
                if ($search) $active_filters[] = 'Pencarian: "' . htmlspecialchars($search) . '"';
                if ($periode_filter) {
                  foreach ($periode_options as $p) {
                    if ($p->id == $periode_filter) {
                      $active_filters[] = 'Periode: ' . $p->tahun_ajaran . ' - ' . $p->semester;
                      break;
                    }
                  }
                }
                if ($status_filter !== '') $active_filters[] = 'Status: ' . ($status_filter ? 'Aktif' : 'Nonaktif');
                
                if (!empty($active_filters)): ?>
                  | Filter: <?= implode(', ', $active_filters) ?>
                <?php endif; ?>
              </small>
            </div>
          </div>

          <!-- Kelas table -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
              <thead>
                <tr>
                  <th style="width: 30px;">#</th>
                  <th>Kelas</th>
                  <th>Mata Pelajaran</th>
                  <th>Guru</th>
                  <th>Periode</th>
                  <th>Siswa</th>
                  <th>Status</th>
                  <th>Dibuat</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($kelas_list)): ?>
                  <tr>
                    <td colspan="8" class="text-center py-4">
                      <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>
                      <?php if ($search || $periode_filter || $status_filter !== ''): ?>
                        Tidak ada kelas yang sesuai dengan filter
                      <?php else: ?>
                        Belum ada kelas
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($kelas_list as $index => $k): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td>
                        <strong><?= htmlspecialchars($k->nama_kelas) ?></strong>
                        <?php if ($k->deskripsi): ?>
                          <br><small class="text-muted"><?= htmlspecialchars(substr($k->deskripsi, 0, 50)) ?><?= strlen($k->deskripsi) > 50 ? '...' : '' ?></small>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge badge-primary"><?= htmlspecialchars($k->mata_pelajaran) ?></span>
                      </td>
                      <td>
                        <small><?= htmlspecialchars($k->nama_guru) ?></small>
                      </td>
                      <td>
                        <small><?= $k->tahun_ajaran ?> - <?= $k->semester ?></small>
                      </td>
                      <td>
                        <span class="badge badge-info"><?= $k->stats['total_siswa'] ?> siswa</span>
                      </td>
                      <td>
                        <?php if ($k->is_active): ?>
                          <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                          <span class="badge badge-secondary">Nonaktif</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <small><?= date('d/m/Y', strtotime($k->created_at)) ?></small>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <?php if (!empty($pagination)): ?>
            <div class="row mt-3">
              <div class="col-12">
                <?= $pagination ?>
              </div>
            </div>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>