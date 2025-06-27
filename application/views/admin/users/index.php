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
            <i class="fas fa-users"></i> Daftar Pengguna
          </h3>
          <div class="card-tools">
            <a href="<?= base_url('admin/users/create') ?>" class="btn btn-primary btn-sm">
              <i class="fas fa-plus"></i> Tambah Pengguna
            </a>
          </div>
        </div>
        
        <div class="card-body">
          <!-- Filter and Search -->
          <div class="row mb-3">
            <div class="col-md-6">
              <?= form_open(base_url('admin/users'), 'method="GET" class="form-inline"') ?>
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Cari nama atau NISN/NIP..." value="<?= htmlspecialchars($search) ?>">
                  <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                  </div>
                </div>
              <?= form_close() ?>
            </div>
            <div class="col-md-6">
              <?= form_open(base_url('admin/users'), 'method="GET" class="form-inline justify-content-end"') ?>
                <select name="role" class="form-control mr-2" onchange="this.form.submit()">
                  <option value="">Semua Role</option>
                  <option value="super_admin" <?= $role_filter == 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                  <option value="guru" <?= $role_filter == 'guru' ? 'selected' : '' ?>>Guru</option>
                  <option value="siswa" <?= $role_filter == 'siswa' ? 'selected' : '' ?>>Siswa</option>
                </select>
                <?php if ($search): ?>
                  <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                <?php endif; ?>
              <?= form_close() ?>
            </div>
          </div>

          <!-- Results info -->
          <div class="row mb-3">
            <div class="col-12">
              <small class="text-muted">
                Menampilkan <?= count($users) ?> dari <?= $total_users ?> pengguna
                <?php if ($search || $role_filter): ?>
                  | Filter aktif: 
                  <?php if ($search): ?><strong>Pencarian: "<?= htmlspecialchars($search) ?>"</strong><?php endif; ?>
                  <?php if ($role_filter): ?><strong>Role: <?= ucwords(str_replace('_', ' ', $role_filter)) ?></strong><?php endif; ?>
                  <a href="<?= base_url('admin/users') ?>" class="btn btn-link btn-sm p-0">Reset Filter</a>
                <?php endif; ?>
              </small>
            </div>
          </div>

          <!-- Users table -->
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style="width: 50px;">#</th>
                  <th>NISN/NIP</th>
                  <th>Nama Lengkap</th>
                  <th>Role</th>
                  <th>Status</th>
                  <th>Dibuat</th>
                  <th style="width: 200px;">Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($users)): ?>
                  <tr>
                    <td colspan="7" class="text-center py-4">
                      <i class="fas fa-info-circle fa-2x text-muted mb-2"></i><br>
                      <?php if ($search || $role_filter): ?>
                        Tidak ada pengguna yang sesuai dengan filter
                      <?php else: ?>
                        Belum ada pengguna
                      <?php endif; ?>
                    </td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($users as $index => $u): ?>
                    <tr>
                      <td><?= $index + 1 ?></td>
                      <td><code><?= htmlspecialchars($u->nisn_nip) ?></code></td>
                      <td><?= htmlspecialchars($u->nama_lengkap) ?></td>
                      <td>
                        <?php
                        $role_badges = array(
                          'super_admin' => 'badge-danger',
                          'guru' => 'badge-success', 
                          'siswa' => 'badge-info'
                        );
                        $role_names = array(
                          'super_admin' => 'Super Admin',
                          'guru' => 'Guru',
                          'siswa' => 'Siswa'
                        );
                        ?>
                        <span class="badge <?= $role_badges[$u->role] ?>">
                          <?= $role_names[$u->role] ?>
                        </span>
                      </td>
                      <td>
                        <?php if ($u->is_active): ?>
                          <span class="badge badge-success">Aktif</span>
                        <?php else: ?>
                          <span class="badge badge-secondary">Nonaktif</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <small><?= date('d/m/Y H:i', strtotime($u->created_at)) ?></small>
                      </td>
                      <td>
                        <div class="btn-group btn-group-sm" role="group">
                          <a href="<?= base_url('admin/users/edit/' . $u->id) ?>" 
                             class="btn btn-warning btn-sm" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                          
                          <button type="button" 
                                  class="btn btn-info btn-sm" 
                                  title="Reset Password"
                                  onclick="confirmResetPassword(<?= $u->id ?>, '<?= htmlspecialchars($u->nama_lengkap) ?>')">
                            <i class="fas fa-key"></i>
                          </button>
                          
                          <button type="button" 
                                  class="btn <?= $u->is_active ? 'btn-secondary' : 'btn-success' ?> btn-sm" 
                                  title="<?= $u->is_active ? 'Nonaktifkan' : 'Aktifkan' ?>"
                                  onclick="confirmToggleStatus(<?= $u->id ?>, '<?= htmlspecialchars($u->nama_lengkap) ?>', <?= $u->is_active ?>)">
                            <i class="fas <?= $u->is_active ? 'fa-ban' : 'fa-check' ?>"></i>
                          </button>
                          
                          <?php if ($u->id != get_user_data('user_id')): ?>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus"
                                    onclick="confirmDelete(<?= $u->id ?>, '<?= htmlspecialchars($u->nama_lengkap) ?>')">
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
function confirmDelete(id, name) {
    if (confirm('Yakin ingin menghapus pengguna "' + name + '"?\n\nPengguna akan dinonaktifkan dan tidak dapat login.')) {
        window.location.href = '<?= base_url("admin/users/delete/") ?>' + id;
    }
}

function confirmToggleStatus(id, name, currentStatus) {
    var action = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
    if (confirm('Yakin ingin ' + action + ' pengguna "' + name + '"?')) {
        window.location.href = '<?= base_url("admin/users/toggle/") ?>' + id;
    }
}

function confirmResetPassword(id, name) {
    if (confirm('Yakin ingin mereset password pengguna "' + name + '"?\n\nPassword akan dikembalikan ke default dan user harus mengubahnya saat login.')) {
        window.location.href = '<?= base_url("admin/users/reset-password/") ?>' + id;
    }
}
</script>

<?php $this->load->view('templates/footer'); ?>
