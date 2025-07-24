<?php
$this->load->view('templates/header', ['title' => $title, 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0"><?= htmlspecialchars($tugas->judul) ?></h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('siswa/tugas') ?>">Tugas Saya</a></li>
            <li class="breadcrumb-item active">Detail Tugas</li>
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
                <i class="fas fa-info-circle"></i> Detail Tugas
              </h3>
            </div>
            
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-3">Kelas:</dt>
                <dd class="col-sm-9"><?= htmlspecialchars($tugas->nama_kelas) ?> - <?= htmlspecialchars($tugas->mata_pelajaran) ?></dd>
                
                <dt class="col-sm-3">Deadline:</dt>
                <dd class="col-sm-9">
                  <?= date('d/m/Y H:i', strtotime($tugas->deadline)) ?>
                  <?php if (strtotime($tugas->deadline) < time()): ?>
                    <span class="badge badge-danger ml-2">Sudah Lewat</span>
                  <?php endif; ?>
                </dd>
                
                <dt class="col-sm-3">Nilai Maksimal:</dt>
                <dd class="col-sm-9"><?= $tugas->max_poin ?> poin</dd>
                
                <dt class="col-sm-3">Deskripsi:</dt>
                <dd class="col-sm-9"><?= nl2br(htmlspecialchars($tugas->deskripsi)) ?></dd>
                
                <?php if ($tugas->file_materi): ?>
                <dt class="col-sm-3">File Materi:</dt>
                <dd class="col-sm-9">
                  <a href="<?= base_url('siswa/tugas/download/' . $tugas->file_materi) ?>" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download"></i> Download Materi
                  </a>
                </dd>
                <?php endif; ?>
              </dl>
            </div>
          </div>
          
          <!-- Submission Section -->
          <?php if (!$submission): ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-upload"></i> Kumpulkan Tugas
                </h3>
              </div>
              
              <div class="card-body">
                <?php if (strtotime($tugas->deadline) < time()): ?>
                  <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Peringatan:</strong> Deadline sudah lewat. Pengumpulan akan dianggap terlambat.
                  </div>
                <?php endif; ?>
                
                <?= form_open_multipart('siswa/tugas/submit/' . $tugas->id) ?>
                  <div class="form-group">
                    <label for="file_jawaban">File Jawaban <span class="text-danger">*</span></label>
                    <input type="file" name="file_jawaban" id="file_jawaban" class="form-control" required>
                    <small class="form-text text-muted">Format: PDF, DOC, DOCX, ZIP, RAR. Max: 10MB</small>
                  </div>
                  
                  <button type="submit" class="btn btn-success">
                    <i class="fas fa-upload"></i> Kumpulkan Tugas
                  </button>
                <?= form_close() ?>
              </div>
            </div>
          <?php else: ?>
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-check-circle"></i> Status Pengumpulan
                </h3>
              </div>
              
              <div class="card-body">
                <div class="alert alert-success">
                  <i class="fas fa-check"></i>
                  <strong>Tugas sudah dikumpulkan!</strong>
                </div>
                
                <dl class="row">
                  <dt class="col-sm-3">File:</dt>
                  <dd class="col-sm-9">
                    <a href="<?= base_url('uploads/submissions/' . $submission->file_jawaban) ?>" target="_blank">
                      <i class="fas fa-file"></i> <?= $submission->file_jawaban ?>
                    </a>
                  </dd>
                  
                  <dt class="col-sm-3">Dikumpulkan:</dt>
                  <dd class="col-sm-9">
                    <?= date('d/m/Y H:i', strtotime($submission->submitted_at)) ?>
                    <?php if ($submission->is_late): ?>
                      <span class="badge badge-warning ml-2">Terlambat</span>
                    <?php else: ?>
                      <span class="badge badge-success ml-2">Tepat Waktu</span>
                    <?php endif; ?>
                  </dd>
                  
                  <?php if ($submission->nilai !== null): ?>
                  <dt class="col-sm-3">Nilai:</dt>
                  <dd class="col-sm-9">
                    <strong class="text-primary"><?= $submission->nilai ?>/<?= $tugas->max_poin ?></strong>
                  </dd>
                  <?php endif; ?>
                  
                  <?php if ($submission->feedback): ?>
                  <dt class="col-sm-3">Feedback:</dt>
                  <dd class="col-sm-9">
                    <div class="alert alert-info">
                      <?= nl2br(htmlspecialchars($submission->feedback)) ?>
                    </div>
                  </dd>
                  <?php endif; ?>
                </dl>
                
                <!-- Allow resubmission -->
                <hr>
                <h6>Kirim Ulang (Opsional)</h6>
                <?= form_open_multipart('siswa/tugas/submit/' . $tugas->id) ?>
                  <div class="form-group">
                    <label for="file_jawaban">File Jawaban Baru</label>
                    <input type="file" name="file_jawaban" id="file_jawaban" class="form-control">
                    <small class="form-text text-muted">File lama akan disimpan sebagai backup</small>
                  </div>
                  
                  <button type="submit" class="btn btn-warning btn-sm">
                    <i class="fas fa-redo"></i> Kirim Ulang
                  </button>
                <?= form_close() ?>
              </div>
            </div>
          <?php endif; ?>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info"></i> Informasi
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center">
                <?php if ($submission && $submission->nilai !== null): ?>
                  <h3 class="text-primary"><?= $submission->nilai ?>/<?= $tugas->max_poin ?></h3>
                  <p class="text-muted">Nilai Anda</p>
                <?php elseif ($submission): ?>
                  <h4 class="text-success">Sudah Dikumpulkan</h4>
                  <p class="text-muted">Menunggu penilaian</p>
                <?php else: ?>
                  <h4 class="text-warning">Belum Dikumpulkan</h4>
                  <p class="text-muted">Segera kumpulkan tugas</p>
                <?php endif; ?>
              </div>
              
              <hr>
              
              <div class="row text-center">
                <div class="col-12">
                  <?php 
                  $remaining = strtotime($tugas->deadline) - time();
                  if ($remaining > 0): 
                    $days = floor($remaining / (60 * 60 * 24));
                    $hours = floor(($remaining % (60 * 60 * 24)) / (60 * 60));
                  ?>
                    <strong><?= $days ?> hari <?= $hours ?> jam</strong><br>
                    <small class="text-muted">Sisa waktu</small>
                  <?php else: ?>
                    <strong class="text-danger">Deadline Lewat</strong><br>
                    <small class="text-muted">
                      <?= abs(floor($remaining / (60 * 60 * 24))) ?> hari yang lalu
                    </small>
                  <?php endif; ?>
                </div>
              </div>
              
              <hr>
              
              <a href="<?= base_url('siswa/tugas') ?>" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>