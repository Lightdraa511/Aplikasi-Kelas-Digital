<?php
// =============================================================================
// 6. VIEW - application/views/admin/users/edit.php
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
            <li class="breadcrumb-item"><a href="<?= base_url('admin/users') ?>">Kelola Pengguna</a></li>
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

      <div class="row">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-user-edit"></i> Edit Pengguna: <?= htmlspecialchars($edit_user->nama_lengkap) ?>
              </h3>
            </div>
            
            <?= form_open('admin/users/edit/' . $edit_user->id) ?>
            <div class="card-body">
              
              <div class="form-group">
                <label for="role">Role Pengguna <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-control <?= form_error('role') ? 'is-invalid' : '' ?>" required>
                  <option value="">Pilih Role</option>
                  <option value="super_admin" <?= set_select('role', 'super_admin', $edit_user->role == 'super_admin') ?>>Super Admin</option>
                  <option value="guru" <?= set_select('role', 'guru', $edit_user->role == 'guru') ?>>Guru</option>
                  <option value="siswa" <?= set_select('role', 'siswa', $edit_user->role == 'siswa') ?>>Siswa</option>
                </select>
                <?= form_error('role', '<div class="invalid-feedback">', '</div>') ?>
              </div>

              <div class="form-group">
                <label for="nisn_nip">NISN/NIP <span class="text-danger">*</span></label>
                <input type="text" name="nisn_nip" id="nisn_nip" 
                       class="form-control <?= form_error('nisn_nip') ? 'is-invalid' : '' ?>" 
                       placeholder="Masukkan NISN untuk siswa atau NIP untuk guru/admin"
                       value="<?= set_value('nisn_nip', $edit_user->nisn_nip) ?>" required>
                <?= form_error('nisn_nip', '<div class="invalid-feedback">', '</div>') ?>
              </div>

              <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" 
                       class="form-control <?= form_error('nama_lengkap') ? 'is-invalid' : '' ?>" 
                       placeholder="Masukkan nama lengkap"
                       value="<?= set_value('nama_lengkap', $edit_user->nama_lengkap) ?>" required>
                <?= form_error('nama_lengkap', '<div class="invalid-feedback">', '</div>') ?>
              </div>

            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
              </button>
              <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
              </a>
            </div>
            <?= form_close() ?>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info-circle"></i> Informasi Pengguna
              </h3>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-4">Status:</dt>
                <dd class="col-sm-8">
                  <?php if ($edit_user->is_active): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Nonaktif</span>
                  <?php endif; ?>
                </dd>
                
                <dt class="col-sm-4">Dibuat:</dt>
                <dd class="col-sm-8">
                  <small><?= date('d/m/Y H:i', strtotime($edit_user->created_at)) ?></small>
                </dd>
                
                <?php if ($edit_user->updated_at): ?>
                <dt class="col-sm-4">Diupdate:</dt>
                <dd class="col-sm-8">
                  <small><?= date('d/m/Y H:i', strtotime($edit_user->updated_at)) ?></small>
                </dd>
                <?php endif; ?>
                
                <dt class="col-sm-4">Force Change:</dt>
                <dd class="col-sm-8">
                  <?php if ($edit_user->force_change_password): ?>
                    <span class="badge badge-warning">Ya</span>
                  <?php else: ?>
                    <span class="badge badge-success">Tidak</span>
                  <?php endif; ?>
                </dd>
              </dl>
              
              <hr>
              
              <div class="text-center">
                <a href="<?= base_url('admin/users/reset-password/' . $edit_user->id) ?>" 
                   class="btn btn-warning btn-sm"
                   onclick="return confirm('Yakin ingin mereset password pengguna ini?')">
                  <i class="fas fa-key"></i> Reset Password
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
