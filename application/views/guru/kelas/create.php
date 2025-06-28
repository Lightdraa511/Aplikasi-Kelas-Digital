<?php
// =============================================================================
// 8. VIEW GURU - application/views/guru/kelas/create.php
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
            <li class="breadcrumb-item"><a href="<?= base_url('guru/kelas') ?>">Kelola Kelas</a></li>
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
                <i class="fas fa-chalkboard"></i> Form Buat Kelas Baru
              </h3>
            </div>
            
            <?= form_open('guru/kelas/create') ?>
            <div class="card-body">
              
              <div class="form-group">
                <label for="nama_kelas">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" name="nama_kelas" id="nama_kelas" 
                       class="form-control <?= form_error('nama_kelas') ? 'is-invalid' : '' ?>" 
                       placeholder="Contoh: XII IPA 1, X Akuntansi A"
                       value="<?= set_value('nama_kelas') ?>" required>
                <?= form_error('nama_kelas', '<div class="invalid-feedback">', '</div>') ?>
                <small class="form-text text-muted">Nama yang mudah dikenali siswa</small>
              </div>

              <div class="form-group">
                <label for="mata_pelajaran">Mata Pelajaran <span class="text-danger">*</span></label>
                <input type="text" name="mata_pelajaran" id="mata_pelajaran" 
                       class="form-control <?= form_error('mata_pelajaran') ? 'is-invalid' : '' ?>" 
                       placeholder="Contoh: Matematika, Fisika, Bahasa Indonesia"
                       value="<?= set_value('mata_pelajaran') ?>" required>
                <?= form_error('mata_pelajaran', '<div class="invalid-feedback">', '</div>') ?>
              </div>

              <div class="form-group">
                <label for="deskripsi">Deskripsi Kelas</label>
                <textarea name="deskripsi" id="deskripsi" 
                          class="form-control <?= form_error('deskripsi') ? 'is-invalid' : '' ?>" 
                          rows="4" 
                          placeholder="Deskripsi singkat tentang kelas ini (opsional)"><?= set_value('deskripsi') ?></textarea>
                <?= form_error('deskripsi', '<div class="invalid-feedback">', '</div>') ?>
                <small class="form-text text-muted">Jelaskan tujuan pembelajaran atau informasi penting lainnya</small>
              </div>

            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Buat Kelas
              </button>
              <a href="<?= base_url('guru/kelas') ?>" class="btn btn-secondary">
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
              <h6><strong>Setelah Membuat Kelas:</strong></h6>
              <ol class="text-sm">
                <li>Kelas akan otomatis aktif</li>
                <li>Tambahkan siswa ke kelas</li>
                <li>Buat tugas untuk siswa</li>
                <li>Monitor progress pembelajaran</li>
              </ol>
              
              <hr>
              
              <h6><strong>Tips:</strong></h6>
              <ul class="text-sm text-muted">
                <li>Gunakan nama yang jelas dan mudah dipahami</li>
                <li>Deskripsi membantu siswa memahami tujuan kelas</li>
                <li>Anda dapat edit informasi kelas kapan saja</li>
              </ul>
              
              <div class="alert alert-info">
                <small>
                  <i class="fas fa-calendar"></i>
                  Kelas akan terikat dengan periode akademik yang sedang aktif.
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>