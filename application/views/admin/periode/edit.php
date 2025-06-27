<?php
// =============================================================================
// 6. VIEW - application/views/admin/periode/edit.php
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
                <i class="fas fa-calendar-edit"></i> Edit Periode: <?= $period->tahun_ajaran ?> - <?= $period->semester ?>
              </h3>
            </div>
            
            <?= form_open('admin/periode/edit/' . $period->id) ?>
            <div class="card-body">
              
              <div class="form-group">
                <label for="tahun_ajaran">Tahun Ajaran <span class="text-danger">*</span></label>
                <select name="tahun_ajaran" id="tahun_ajaran" class="form-control <?= form_error('tahun_ajaran') ? 'is-invalid' : '' ?>" required>
                  <option value="">Pilih Tahun Ajaran</option>
                  <?php foreach ($year_options as $value => $label): ?>
                    <option value="<?= $value ?>" <?= set_select('tahun_ajaran', $value, $period->tahun_ajaran == $value) ?>><?= $label ?></option>
                  <?php endforeach; ?>
                </select>
                <?= form_error('tahun_ajaran', '<div class="invalid-feedback">', '</div>') ?>
              </div>

              <div class="form-group">
                <label for="semester">Semester <span class="text-danger">*</span></label>
                <select name="semester" id="semester" class="form-control <?= form_error('semester') ? 'is-invalid' : '' ?>" required>
                  <option value="">Pilih Semester</option>
                  <option value="Ganjil" <?= set_select('semester', 'Ganjil', $period->semester == 'Ganjil') ?>>Ganjil</option>
                  <option value="Genap" <?= set_select('semester', 'Genap', $period->semester == 'Genap') ?>>Genap</option>
                </select>
                <?= form_error('semester', '<div class="invalid-feedback">', '</div>') ?>
              </div>

            </div>
            
            <div class="card-footer">
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update
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
                <i class="fas fa-info-circle"></i> Informasi Periode
              </h3>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-4">Status:</dt>
                <dd class="col-sm-8">
                  <?php if ($period->is_active): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Nonaktif</span>
                  <?php endif; ?>
                </dd>
                
                <dt class="col-sm-4">Dibuat:</dt>
                <dd class="col-sm-8">
                  <small><?= date('d/m/Y H:i', strtotime($period->created_at)) ?></small>
                </dd>
                
                <?php if ($period->updated_at): ?>
                <dt class="col-sm-4">Diupdate:</dt>
                <dd class="col-sm-8">
                  <small><?= date('d/m/Y H:i', strtotime($period->updated_at)) ?></small>
                </dd>
                <?php endif; ?>
              </dl>
              
              <hr>
              
              <?php 
              $stats = $this->Periode_model->get_period_stats($period->id);
              ?>
              <h6><strong>Statistik:</strong></h6>
              <ul class="list-unstyled">
                <li><i class="fas fa-chalkboard text-info"></i> <strong><?= $stats['total_kelas'] ?></strong> kelas</li>
                <li><i class="fas fa-tasks text-success"></i> <strong><?= $stats['total_tugas'] ?></strong> tugas</li>
                <li><i class="fas fa-users text-warning"></i> <strong><?= $stats['total_siswa'] ?></strong> siswa</li>
              </ul>
              
              <?php if (!$period->is_active): ?>
              <hr>
              <div class="text-center">
                <a href="<?= base_url('admin/periode/activate/' . $period->id) ?>" 
                   class="btn btn-success btn-sm"
                   onclick="return confirm('Yakin ingin mengaktifkan periode ini?')">
                  <i class="fas fa-check"></i> Aktifkan Periode
                </a>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>
