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
            <li class="breadcrumb-item"><a href="<?= base_url('guru/tugas') ?>">Kelola Tugas</a></li>
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
                
                <dt class="col-sm-3">Status:</dt>
                <dd class="col-sm-9">
                  <?php
                  $badges = array(
                    'draft' => 'badge-secondary',
                    'published' => 'badge-success',
                    'closed' => 'badge-danger'
                  );
                  ?>
                  <span class="badge <?= $badges[$tugas->status] ?>"><?= ucfirst($tugas->status) ?></span>
                </dd>
                
                <dt class="col-sm-3">Deskripsi:</dt>
                <dd class="col-sm-9"><?= nl2br(htmlspecialchars($tugas->deskripsi)) ?></dd>
                
                <?php if ($tugas->file_materi): ?>
                <dt class="col-sm-3">File Materi:</dt>
                <dd class="col-sm-9">
                  <a href="<?= base_url('uploads/materi/' . $tugas->file_materi) ?>" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-download"></i> Download Materi
                  </a>
                </dd>
                <?php endif; ?>
              </dl>
            </div>
          </div>
          
          <!-- Submissions -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-upload"></i> Pengumpulan Tugas (<?= count($submissions) ?>)
              </h3>
            </div>
            
            <div class="card-body">
              <?php if (empty($submissions)): ?>
                <div class="text-center py-4">
                  <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                  <p class="text-muted">Belum ada yang mengumpulkan tugas</p>
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th>Siswa</th>
                        <th>File</th>
                        <th>Dikumpulkan</th>
                        <th>Status</th>
                        <th>Nilai</th>
                        <th>Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($submissions as $s): ?>
                        <tr>
                          <td>
                            <strong><?= htmlspecialchars($s->nama_lengkap) ?></strong><br>
                            <small><?= $s->nisn_nip ?></small>
                          </td>
                          <td>
                            <a href="<?= base_url('uploads/submissions/' . $s->file_jawaban) ?>" target="_blank">
                              <i class="fas fa-file"></i> <?= $s->file_jawaban ?>
                            </a>
                          </td>
                          <td>
                            <small><?= date('d/m/Y H:i', strtotime($s->submitted_at)) ?></small>
                          </td>
                          <td>
                            <?php if ($s->is_late): ?>
                              <span class="badge badge-warning">Terlambat</span>
                            <?php else: ?>
                              <span class="badge badge-success">Tepat Waktu</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php if ($s->nilai): ?>
                              <strong><?= $s->nilai ?></strong>/<?= $tugas->max_poin ?>
                            <?php else: ?>
                              <span class="text-muted">Belum dinilai</span>
                            <?php endif; ?>
                          </td>
                          <td>
                            <button type="button" class="btn btn-primary btn-sm" 
                                    data-toggle="modal" data-target="#gradeModal" 
                                    data-id="<?= $s->id ?>" 
                                    data-nama="<?= htmlspecialchars($s->nama_lengkap) ?>"
                                    data-nilai="<?= $s->nilai ?>"
                                    data-feedback="<?= htmlspecialchars($s->feedback) ?>">
                              <i class="fas fa-star"></i> Nilai
                            </button>
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

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-chart-bar"></i> Statistik
              </h3>
            </div>
            <div class="card-body">
              <div class="text-center">
                <h4><?= count($submissions) ?></h4>
                <p class="text-muted">Total Pengumpulan</p>
              </div>
              
              <hr>
              
              <div class="row text-center">
                <div class="col-6">
                  <?php 
                  $graded = 0;
                  foreach ($submissions as $s) {
                    if ($s->nilai !== null) $graded++;
                  }
                  ?>
                  <strong><?= $graded ?></strong><br>
                  <small class="text-muted">Sudah Dinilai</small>
                </div>
                <div class="col-6">
                  <?php 
                  $late = 0;
                  foreach ($submissions as $s) {
                    if ($s->is_late) $late++;
                  }
                  ?>
                  <strong><?= $late ?></strong><br>
                  <small class="text-muted">Terlambat</small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<!-- Simple Grade Form Modal -->
<div class="modal fade" id="gradeModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <?= form_open('guru/tugas/grade_simple', 'id="gradeForm"') ?>
      <div class="modal-header">
        <h4 class="modal-title">Beri Nilai</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="submission_id" id="submission_id">
        <input type="hidden" name="tugas_id" value="<?= $tugas->id ?>">
        
        <div class="form-group">
          <label>Siswa:</label>
          <p id="student_name" class="font-weight-bold"></p>
        </div>
        
        <div class="form-group">
          <label for="nilai">Nilai (0-<?= $tugas->max_poin ?>) <span class="text-danger">*</span></label>
          <input type="number" name="nilai" id="nilai" class="form-control" 
                 min="0" max="<?= $tugas->max_poin ?>" required>
        </div>
        
        <div class="form-group">
          <label for="feedback">Feedback:</label>
          <textarea name="feedback" id="feedback" class="form-control" rows="3" 
                    placeholder="Berikan feedback untuk siswa (opsional)"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Nilai</button>
      </div>
      <?= form_close() ?>
    </div>
  </div>
</div>

<script>
$('#gradeModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    $('#submission_id').val(button.data('id'));
    $('#student_name').text(button.data('nama'));
    $('#nilai').val(button.data('nilai') || '');
    $('#feedback').val(button.data('feedback') || '');
});

// Form validation
$('#gradeForm').on('submit', function(e) {
    var nilai = $('#nilai').val();
    var maxPoin = <?= $tugas->max_poin ?>;
    
    if (!nilai || nilai === '') {
        alert('Nilai wajib diisi!');
        e.preventDefault();
        return false;
    }
    
    if (parseInt(nilai) < 0 || parseInt(nilai) > maxPoin) {
        alert('Nilai harus antara 0 sampai ' + maxPoin + '!');
        e.preventDefault();
        return false;
    }
    
    return true;
});
</script>

<?php $this->load->view('templates/footer'); ?>