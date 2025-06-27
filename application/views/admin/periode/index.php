<?php
// =============================================================================
// 4. VIEW - application/views/admin/periode/index.php
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

      <!-- Active Period Info -->
      <?php if ($active_period): ?>
        <div class="alert alert-info alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h5><i class="icon fas fa-info"></i> Periode Aktif</h5>
          Saat ini sistem menggunakan periode <strong><?= $active_period->tahun_ajaran ?> - <?= $active_period->semester ?></strong>
        </div>
      <?php else: ?>
        <div class="alert alert-warning alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <h5><i class="icon fas fa-exclamation-triangle"></i> Tidak Ada Periode Aktif</h5>
          Silakan aktifkan salah satu periode akademik untuk mulai menggunakan sistem.
        </div>
      <?php endif; ?>

      <!-- Main card -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-calendar-alt"></i> Daftar Periode Akademik
          </h3>
          <div class="card-tools">
            <a href="<?= base_url('admin/periode/create') ?>" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Periode
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <!-- Search -->
          <div class="row mb-3">
            <div class="col-md-6">
              <?= form_open(base_url('admin/periode'), 'method="GET" class="form-inline"') ?>
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Cari tahun ajaran..." value="<?= htmlspecialchars($search) ?>">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              <?= form_close() ?>
            </div>
            <div class="col-md-6">
              <div class="text-right">
                <?php if ($search): ?>
                  <a href="<?= base_url('admin/periode') ?>" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i> Reset
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Results info -->
          <div class="row mb-3">
            <div class="col-12">
              <small class="text-muted">
                Menampilkan <?= count($periods) ?> dari <?= $total_periods ?> periode
                <?php if ($search): ?>
                  | Pencarian: <strong>"<?= htmlspecialchars($search) ?>"</strong>
                <?php endif; ?>
              </small>
            </div>
          </div>

          <!-- Periods table -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>Tahun Ajaran</th>
                  <th>Semester</th>
                  <th>Status</th>
                  <th>Statistik</th>
                  <th>Dibuat</th>
                  <th style="width: 180px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($periods)): ?>
                  <tr>
                    <td colspan="7" class="text-center py-4">
                      <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>
                      <?php if ($search): ?>
                        Tidak ada periode yang sesuai dengan pencarian
                      <?php else: ?>
                        Belum ada periode akademik
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($periods as $index => $p): ?>
                    <tr class="<?= $p->is_active ? 'table-success' : '' ?>">
                      <td><?= $index + 1 ?></td>
                      <td>
                        <strong><?= htmlspecialchars($p->tahun_ajaran) ?></strong>
                        <?php if ($p->is_active): ?>
                          <span class="badge badge-success ml-2">AKTIF</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge <?= $p->semester == 'Ganjil' ? 'badge-primary' : 'badge-info' ?>">
                          <?= $p->semester ?>
                        </span>
                      </td>
                      <td>
                        <?php if ($p->is_active): ?>
                          <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                          <span class="badge badge-secondary">Nonaktif</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <small>
                          <i class="fas fa-chalkboard"></i> <?= $p->stats['total_kelas'] ?> kelas<br>
                          <i class="fas fa-tasks"></i> <?= $p->stats['total_tugas'] ?> tugas<br>
                          <i class="fas fa-users"></i> <?= $p->stats['total_siswa'] ?> siswa
                        </small>
                      </td>
                      <td>
                        <small><?= date('d/m/Y', strtotime($p->created_at)) ?></small>
                      </td>
                      <td>
                        <div class="btn-group btn-group-sm" role="group">
                          <?php if (!$p->is_active): ?>
                            <button type="button" 
                                    class="btn btn-success btn-sm" 
                                    title="Aktifkan"
                                    onclick="confirmActivate(<?= $p->id ?>, '<?= htmlspecialchars($p->tahun_ajaran) ?>', '<?= $p->semester ?>')">
                              <i class="fas fa-check"></i>
                            </button>
                          <?php endif; ?>
                          
                          <a href="<?= base_url('admin/periode/edit/' . $p->id) ?>" 
                             class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          
                          <?php if (!$p->is_active && $p->stats['total_kelas'] == 0): ?>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus"
                                    onclick="confirmDelete(<?= $p->id ?>, '<?= htmlspecialchars($p->tahun_ajaran) ?>', '<?= $p->semester ?>')">
                              <i class="fas fa-trash"></i>
                            </button>
                          <?php endif; ?>
                        </div>
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

<script>
function confirmActivate(id, tahun, semester) {
    if (confirm('Yakin ingin mengaktifkan periode "' + tahun + ' - ' + semester + '"?\n\nPeriode aktif lainnya akan dinonaktifkan.')) {
        window.location.href = '<?= base_url("admin/periode/activate/") ?>' + id;
    }
}

function confirmDelete(id, tahun, semester) {
    if (confirm('Yakin ingin menghapus periode "' + tahun + ' - ' + semester + '"?\n\nHanya periode tanpa kelas yang dapat dihapus.')) {
        window.location.href = '<?= base_url("admin/periode/delete/") ?>' + id;
    }
}
</script>

<?php $this->load->view('templates/footer'); ?>

