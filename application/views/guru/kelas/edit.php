<?php
// =============================================================================
// 9. VIEW GURU - application/views/guru/kelas/edit.php
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
                <i class="fas fa-edit"></i> Edit Kelas: <?= htmlspecialchars($kelas->nama_kelas) ?>
              </h3>
            </div>
            
            <?= form_open('guru/kelas/edit/' . $kelas->id) ?>
            <div class="card-body">
              
              <div class="form-group">
                <label for="nama_kelas">Nama Kelas <span class="text-danger">*</span></label>
                <input type="text" name="nama_kelas" id="nama_kelas" 
                       class="form-control <?= form_error('nama_kelas') ? 'is-invalid' : '' ?>" 
                       placeholder="Contoh: XII IPA 1, X Akuntansi A"
                       value="<?= set_value('nama_kelas', $kelas->nama_kelas) ?>" required>
                <?= form_error('nama_kelas', '<div class="invalid-feedback">', '</div>') ?>
              </div>

              <div class="form-group">
                <label for="mata_pelajaran">Mata Pelajaran <span class="text-danger">*</span></label>
                <input type="text" name="mata_pelajaran" id="mata_pelajaran" 
                       class="form-control <?= form_error('mata_pelajaran') ? 'is-invalid' : '' ?>" 
                       placeholder="Contoh: Matematika, Fisika, Bahasa Indonesia"
                       value="<?= set_value('mata_pelajaran', $kelas->mata_pelajaran) ?>" required>
                <?= form_error('mata_pelajaran', '<div class="invalid-feedback">', '</div>') ?>
              </div>

              <div class="form-group">
                <label for="deskripsi">Deskripsi Kelas</label>
                <textarea name="deskripsi" id="deskripsi" 
                          class="form-control <?= form_error('deskripsi') ? 'is-invalid' : '' ?>" 
                          rows="4" 
                          placeholder="Deskripsi singkat tentang kelas ini (opsional)"><?= set_value('deskripsi', $kelas->deskripsi) ?></textarea>
                <?= form_error('deskripsi', '<div class="invalid-feedback">', '</div>') ?>
              </div>

            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Kelas
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
                <i class="fas fa-info-circle"></i> Informasi Kelas
              </h3>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-4">Status:</dt>
                <dd class="col-sm-8">
                  <?php if ($kelas->is_active): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Nonaktif</span>
                  <?php endif; ?>
                </dd>
                
                <dt class="col-sm-4">Periode:</dt>
                <dd class="col-sm-8">
                  <small><?= $kelas->tahun_ajaran ?> - <?= $kelas->semester ?></small>
                </dd>
                
                <dt class="col-sm-4">Dibuat:</dt>
                <dd class="col-sm-8">
                  <small><?= date('d/m/Y H:i', strtotime($kelas->created_at)) ?></small>
                </dd>
                
                <?php if ($kelas->updated_at): ?>
                <dt class="col-sm-4">Diupdate:</dt>
                <dd class="col-sm-8">
                  <small><?= date('d/m/Y H:i', strtotime($kelas->updated_at)) ?></small>
                </dd>
                <?php endif; ?>
              </dl>
              
              <hr>
              
              <?php 
              $stats = $this->Kelas_model->get_kelas_stats($kelas->id);
              ?>
              <h6><strong>Statistik:</strong></h6>
              <ul class="list-unstyled">
                <li><i class="fas fa-users text-info"></i> <strong><?= $stats['total_siswa'] ?></strong> siswa</li>
                <li><i class="fas fa-tasks text-success"></i> <strong><?= $stats['total_tugas'] ?></strong> tugas</li>
              </ul>
              
              <hr>
              
              <div class="text-center">
                <a href="<?= base_url('guru/kelas/students/' . $kelas->id) ?>" 
                   class="btn btn-info btn-sm mb-2">
                  <i class="fas fa-users"></i> Kelola Siswa
                </a><br>
                
                <button type="button" 
                        class="btn <?= $kelas->is_active ? 'btn-secondary' : 'btn-success' ?> btn-sm"
                        onclick="toggleStatus(<?= $kelas->id ?>, '<?= htmlspecialchars($kelas->nama_kelas) ?>', <?= $kelas->is_active ?>)">
                  <i class="fas <?= $kelas->is_active ? 'fa-ban' : 'fa-check' ?>"></i>
                  <?= $kelas->is_active ? 'Nonaktifkan' : 'Aktifkan' ?> Kelas
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
function toggleStatus(id, namaKelas, currentStatus) {
    var action = currentStatus ? 'menonaktifkan' : 'mengaktifkan';
    if (confirm('Yakin ingin ' + action + ' kelas "' + namaKelas + '"?')) {
        window.location.href = '<?= base_url("guru/kelas/toggle/") ?>' + id;
    }
}
</script>

<?php $this->load->view('templates/footer'); ?>