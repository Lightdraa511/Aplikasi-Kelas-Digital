<?php
// =============================================================================
// 10. VIEW GURU - application/views/guru/kelas/manage_students.php
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
          <p class="text-muted"><?= htmlspecialchars($kelas->nama_kelas) ?> - <?= htmlspecialchars($kelas->mata_pelajaran) ?></p>
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
      
      <div class="row">
        <!-- Current Students -->
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-users"></i> Siswa di Kelas (<?= count($students) ?>)
              </h3>
            </div>
            
            <div class="card-body">
              <?php if (empty($students)): ?>
                <div class="text-center py-4">
                  <i class="fas fa-user-plus fa-3x text-muted mb-3"></i>
                  <h5 class="text-muted">Belum ada siswa di kelas ini</h5>
                  <p class="text-muted">Tambahkan siswa dari daftar siswa yang tersedia</p>
                </div>
              <?php else: ?>
                <div class="table-responsive">
                  <table class="table table-striped">
                    <thead>
                      <tr>
                        <th style="width: 50px;">#</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Bergabung</th>
                        <th style="width: 80px;">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($students as $index => $s): ?>
                        <tr>
                          <td><?= $index + 1 ?></td>
                          <td><code><?= htmlspecialchars($s->nisn_nip) ?></code></td>
                          <td><?= htmlspecialchars($s->nama_lengkap) ?></td>
                          <td><small><?= date('d/m/Y', strtotime($s->joined_at)) ?></small></td>
                          <td>
                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    title="Hapus dari kelas"
                                    onclick="removeStudent(<?= $s->id ?>, '<?= htmlspecialchars($s->nama_lengkap) ?>')">
                              <i class="fas fa-times"></i>
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

        <!-- Available Students -->
        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-user-plus"></i> Tambah Siswa
              </h3>
            </div>
            
            <div class="card-body">
              <?php if (empty($available_students)): ?>
                <div class="text-center py-3">
                  <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                  <p class="text-muted">Semua siswa sudah terdaftar di kelas ini</p>
                </div>
              <?php else: ?>
                <div class="form-group">
                  <label for="select_student">Pilih Siswa:</label>
                  <select id="select_student" class="form-control form-control-sm">
                    <option value="">-- Pilih Siswa --</option>
                    <?php foreach ($available_students as $as): ?>
                      <option value="<?= $as->id ?>"><?= htmlspecialchars($as->nama_lengkap) ?> (<?= $as->nisn_nip ?>)</option>
                    <?php endforeach; ?>
                  </select>
                </div>
                
                <button type="button" class="btn btn-success btn-sm btn-block" onclick="addStudent()">
                  <i class="fas fa-plus"></i> Tambah ke Kelas
                </button>
                
                <hr>
                
                <small class="text-muted">
                  <strong>Tips:</strong> Siswa yang ditambahkan akan langsung bisa melihat kelas ini di dashboard mereka.
                </small>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Kelas Info -->
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <i class="fas fa-info-circle"></i> Info Kelas
              </h3>
            </div>
            <div class="card-body">
              <dl class="row">
                <dt class="col-sm-5">Periode:</dt>
                <dd class="col-sm-7"><small><?= $kelas->tahun_ajaran ?> - <?= $kelas->semester ?></small></dd>
                
                <dt class="col-sm-5">Status:</dt>
                <dd class="col-sm-7">
                  <?php if ($kelas->is_active): ?>
                    <span class="badge badge-success">Aktif</span>
                  <?php else: ?>
                    <span class="badge badge-secondary">Nonaktif</span>
                  <?php endif; ?>
                </dd>
                
                <dt class="col-sm-5">Total Siswa:</dt>
                <dd class="col-sm-7"><strong><?= count($students) ?></strong></dd>
              </dl>
              
              <a href="<?= base_url('guru/kelas') ?>" class="btn btn-secondary btn-sm btn-block">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Kelas
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>

<script>
function addStudent() {
    var studentId = $('#select_student').val();
    var studentName = $('#select_student option:selected').text();
    
    if (!studentId) {
        alert('Pilih siswa terlebih dahulu!');
        return;
    }
    
    if (confirm('Yakin ingin menambahkan ' + studentName + ' ke kelas ini?')) {
        $.ajax({
            url: '<?= base_url("guru/kelas/add-student") ?>',
            method: 'POST',
            data: {
                kelas_id: <?= $kelas->id ?>,
                siswa_id: studentId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menambahkan siswa');
            }
        });
    }
}

function removeStudent(studentId, studentName) {
    if (confirm('Yakin ingin menghapus ' + studentName + ' dari kelas ini?')) {
        $.ajax({
            url: '<?= base_url("guru/kelas/remove-student") ?>',
            method: 'POST',
            data: {
                kelas_id: <?= $kelas->id ?>,
                siswa_id: studentId
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function() {
                alert('Terjadi kesalahan saat menghapus siswa');
            }
        });
    }
}
</script>

<?php $this->load->view('templates/footer'); ?>