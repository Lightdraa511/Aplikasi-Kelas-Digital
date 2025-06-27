<?php
// =============================================================================
// 5. VIEW - application/views/admin/users/create.php
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
                <i class="fas fa-user-plus"></i> Form Tambah Pengguna
              </h3>
            </div>
            
            <?= form_open('admin/users/create') ?>
            <div class="card-body">
              
              <div class="form-group">
                <label for="role">Role Pengguna <span class="text-danger">*</span></label>
                <select name="role" id="role" class="form-control <?= form_error('role') ? 'is-invalid' : '' ?>" required>
                  <option value="">Pilih Role</option>
                  <option value="super_admin" <?= set_select('role', 'super_admin') ?>>Super Admin</option>
                  <option value="guru" <?= set_select('role', 'guru') ?>>Guru</option>
                  <option value="siswa" <?= set_select('role', 'siswa') ?>>Siswa</option>
                </select>
                <?= form_error('role', '<div class="invalid-feedback">', '</div>') ?>
                <small class="form-text text-muted">Pilih role untuk menentukan hak akses pengguna</small>
              </div>

              <div class="form-group">
                <label for="nisn_nip">NISN/NIP <span class="text-danger">*</span></label>
                <input type="text" name="nisn_nip" id="nisn_nip" 
                       class="form-control <?= form_error('nisn_nip') ? 'is-invalid' : '' ?>" 
                       placeholder="Masukkan NISN untuk siswa atau NIP untuk guru/admin"
                       value="<?= set_value('nisn_nip') ?>" required>
                <?= form_error('nisn_nip', '<div class="invalid-feedback">', '</div>') ?>
                <small class="form-text text-muted">
                  <strong>Format:</strong> NISN untuk siswa (10 digit), NIP untuk guru (18 digit), username untuk admin
                </small>
              </div>

              <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                <input type="text" name="nama_lengkap" id="nama_lengkap" 
                       class="form-control <?= form_error('nama_lengkap') ? 'is-invalid' : '' ?>" 
                       placeholder="Masukkan nama lengkap"
                       value="<?= set_value('nama_lengkap') ?>" required>
                <?= form_error('nama_lengkap', '<div class="invalid-feedback">', '</div>') ?>
              </div>

            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
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
                <i class="fas fa-info-circle"></i> Informasi
              </h3>
            </div>
            <div class="card-body">
              <h6><strong>Password Default:</strong></h6>
              <p class="text-muted">Password akan dibuat otomatis dengan format:</p>
              <ul class="text-sm">
                <li><strong>Siswa:</strong> [Tahun][NISN]<br><small class="text-muted">Contoh: 20240123456789</small></li>
                <li><strong>Guru:</strong> [Tahun][NIP]<br><small class="text-muted">Contoh: 2024196501011990032001</small></li>
                <li><strong>Admin:</strong> [Tahun]admin<br><small class="text-muted">Contoh: 2024admin</small></li>
              </ul>
              
              <hr>
              
              <h6><strong>Catatan:</strong></h6>
              <ul class="text-sm text-muted">
                <li>Pengguna wajib mengubah password saat login pertama</li>
                <li>NISN/NIP harus unik dalam sistem</li>
                <li>Password default akan ditampilkan setelah berhasil menambah pengguna</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
$(document).ready(function() {
    // Auto format NISN/NIP based on role selection
    $('#role').change(function() {
        var role = $(this).val();
        var $nisnNip = $('#nisn_nip');
        
        if (role === 'siswa') {
            $nisnNip.attr('placeholder', 'Masukkan NISN siswa (10 digit)');
            $nisnNip.attr('maxlength', '10');
        } else if (role === 'guru') {
            $nisnNip.attr('placeholder', 'Masukkan NIP guru (18 digit)');
            $nisnNip.attr('maxlength', '18');
        } else if (role === 'super_admin') {
            $nisnNip.attr('placeholder', 'Masukkan username admin');
            $nisnNip.attr('maxlength', '20');
        } else {
            $nisnNip.attr('placeholder', 'Masukkan NISN untuk siswa atau NIP untuk guru/admin');
            $nisnNip.removeAttr('maxlength');
        }
    });
    
    // Only allow numbers for NISN/NIP when role is siswa or guru
    $('#nisn_nip').on('input', function() {
        var role = $('#role').val();
        if (role === 'siswa' || role === 'guru') {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });
});
</script>

<?php $this->load->view('templates/footer'); ?>
