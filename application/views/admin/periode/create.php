<?php
// =============================================================================
// 5. VIEW - application/views/admin/periode/create.php
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
            <li class="breadcrumb-item"><a href="<?= base_url('admin/periode') ?>">Periode Akademik</a></li>
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
                <i class="fas fa-calendar-plus"></i> Form Tambah Periode Akademik
              </h3>
            </div>
            
            <?= form_open('admin/periode/create') ?>
            <div class="card-body">
              
              <div class="form-group">
                <label for="tahun_ajaran">Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="tahun_ajaran" id="tahun_ajaran" class="form-control <?= form_error('tahun_ajaran') ? 'is-invalid' : '' ?>" required>
                  <option value="">Pilih Tahun Ajaran</option>
                  <?php foreach ($year_options as $value => $label): ?>
                    <option value="<?= $value ?>" <?= set_select('tahun_ajaran', $value) ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('tahun_ajaran', '<div class="invalid-feedback">', '</div>') ?>
                <small class="form-text text-muted">Format: YYYY/YYYY (contoh: 2024/2025)</small>
              </div>

              <div class="form-group">
                <label for="semester">Semester <span class="text-danger">*</span></label>
                <select name="semester" id="semester" class="form-control <?= form_error('semester') ? 'is-invalid' : '' ?>" required>
                  <option value="">Pilih Semester</option>
                  <option value="Ganjil" <?= set_select('semester', 'Ganjil') ?>>Ganjil</option>
                  <option value="Genap" <?= set_select('semester', 'Genap') ?>>Genap</option>
                </select>
                <?= form_error('semester', '<div class="invalid-feedback">', '</div>') ?>
                <small class="form-text text-muted">Ganjil: Juli-Desember | Genap: Januari-Juni</small>
              </div>

            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan
              </button>
              <a href="<?= base_url('admin/periode') ?>" class="btn btn-secondary">
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
              <h6><strong>Tentang Periode Akademik:</strong></h6>
              <p class="text-muted">Periode akademik menentukan tahun ajaran dan semester yang sedang berjalan.</p>
              
              <hr>
              
              <h6><strong>Status Periode:</strong></h6>
              <ul class="text-sm">
                <li><strong>Aktif:</strong> Periode yang sedang digunakan sistem</li>
                <li><strong>Nonaktif:</strong> Periode yang tidak digunakan</li>
              </ul>
              
              <div class="alert alert-warning">
                <small>
                  <i class="fas fa-exclamation-triangle"></i>
                  <strong>Catatan:</strong> Hanya satu periode yang dapat aktif dalam satu waktu.
                </small>
              </div>
              
              <hr>
              
              <h6><strong>Contoh:</strong></h6>
              <ul class="text-sm text-muted">
                <li>2024/2025 - Ganjil</li>
                <li>2024/2025 - Genap</li>
                <li>2025/2026 - Ganjil</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>

