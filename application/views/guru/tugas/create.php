<?php
// =============================================================================
// 5. CREATE VIEW - application/views/guru/tugas/create.php
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
            <li class="breadcrumb-item"><a href="<?= base_url('guru/tugas') ?>">Kelola Tugas</a></li>
            <li class="breadcrumb-item active"><?= $title ?></li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">
            <i class="fas fa-plus"></i> Form Buat Tugas Baru
          </h3>
        </div>
        
        <?= form_open_multipart('guru/tugas/create') ?>
        <div class="card-body">
          
          <div class="form-group">
            <label for="kelas_id">Kelas <span class="text-danger">*</span></label>
            <select name="kelas_id" id="kelas_id" class="form-control" required>
              <option value="">Pilih Kelas</option>
              <?php foreach ($kelas_options as $kelas): ?>
                <option value="<?= $kelas->id ?>" <?= set_select('kelas_id', $kelas->id) ?>>
                  <?= $kelas->nama_kelas ?> - <?= $kelas->mata_pelajaran ?>
                </option>
              <?php endforeach; ?>
            </select>
            <?= form_error('kelas_id', '<div class="text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="judul">Judul Tugas <span class="text-danger">*</span></label>
            <input type="text" name="judul" id="judul" class="form-control" 
                   value="<?= set_value('judul') ?>" required>
            <?= form_error('judul', '<div class="text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="deskripsi">Deskripsi <span class="text-danger">*</span></label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required><?= set_value('deskripsi') ?></textarea>
            <?= form_error('deskripsi', '<div class="text-danger">', '</div>') ?>
          </div>

          <div class="form-group">
            <label for="file_materi">File Materi (Opsional)</label>
            <input type="file" name="file_materi" id="file_materi" class="form-control">
            <small class="form-text text-muted">Format: PDF, DOC, DOCX, PPT, PPTX, XLS, XLSX. Max: 10MB</small>
          </div>

          <div class="row">
            <div class="col-md-6">
			<div class="form-group">
                <label for="deadline">Deadline <span class="text-danger">*</span></label>
                <input type="datetime-local" name="deadline" id="deadline" class="form-control" 
                       value="<?= set_value('deadline') ?>" required>
                <?= form_error('deadline', '<div class="text-danger">', '</div>') ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="max_poin">Nilai Maksimal <span class="text-danger">*</span></label>
                <input type="number" name="max_poin" id="max_poin" class="form-control" 
                       value="<?= set_value('max_poin', '100') ?>" min="1" max="1000" required>
                <?= form_error('max_poin', '<div class="text-danger">', '</div>') ?>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="status">Status <span class="text-danger">*</span></label>
            <select name="status" id="status" class="form-control" required>
              <option value="draft" <?= set_select('status', 'draft', true) ?>>Draft</option>
              <option value="published" <?= set_select('status', 'published') ?>>Published</option>
            </select>
            <?= form_error('status', '<div class="text-danger">', '</div>') ?>
            <small class="form-text text-muted">Draft: belum terlihat siswa. Published: siswa bisa lihat dan kerjakan</small>
          </div>

        </div>
        
        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Tugas
          </button>
          <a href="<?= base_url('guru/tugas') ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
          </a>
        </div>
        <?= form_close() ?>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
