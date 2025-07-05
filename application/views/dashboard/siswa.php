<?php
// =============================================================================
// 16. VIEW DASHBOARD - application/views/dashboard/siswa.php
// =============================================================================
$this->load->view('templates/header', ['title' => 'Dashboard Siswa', 'user' => $user]);
$this->load->view('templates/sidebar', ['user' => $user]);
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard Siswa</h1>
          <p class="text-muted">Selamat datang, <?= $user['nama_lengkap'] ?>!</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      
      <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <i class="fas fa-check"></i> <?= $this->session->flashdata('success') ?>
        </div>
      <?php endif; ?>

		<!-- Info boxes -->
		<div class="row">
			<div class="col-12 col-sm-6 col-md-3">
				<div class="info-box">
					<span class="info-box-icon bg-info elevation-1"><i class="fas fa-book"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Kelas Diikuti</span>
						<span class="info-box-number"><?= $stats['total_kelas'] ?></span>
					</div>
				</div>
			</div>
			
			<div class="col-12 col-sm-6 col-md-3">
				<div class="info-box mb-3">
					<span class="info-box-icon bg-success elevation-1"><i class="fas fa-clock"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Tugas Mendatang</span>
						<span class="info-box-number"><?= $stats['tugas_mendatang'] ?></span>
					</div>
				</div>
			</div>
			
			<div class="col-12 col-sm-6 col-md-3">
				<div class="info-box mb-3">
					<span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Tugas Terlambat</span>
						<span class="info-box-number"><?= $stats['tugas_terlambat'] ?></span>
					</div>
				</div>
			</div>
			
			<div class="col-12 col-sm-6 col-md-3">
				<div class="info-box mb-3">
					<span class="info-box-icon bg-warning elevation-1"><i class="fas fa-trophy"></i></span>
					<div class="info-box-content">
						<span class="info-box-text">Nilai Terbaru</span>
						<span class="info-box-number">
							<?= $stats['nilai_terbaru'] ? $stats['nilai_terbaru']->nilai : '-' ?>
						</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Main row -->
		<div class="row">
			<!-- Tugas Mendatang -->
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="fas fa-calendar-check mr-1"></i>
							Tugas Mendatang
						</h3>
					</div>
					<div class="card-body">
						<?php if ($stats['tugas_mendatang'] > 0): ?>
							<div class="text-center">
								<h4 class="text-warning"><?= $stats['tugas_mendatang'] ?></h4>
								<p class="text-muted">tugas harus dikerjakan</p>
								<a href="<?= base_url('siswa/tugas') ?>" class="btn btn-warning btn-sm">
									<i class="fas fa-eye"></i> Lihat Semua Tugas
								</a>
							</div>
						<?php else: ?>
							<div class="text-center py-4">
								<i class="fas fa-clipboard-check fa-2x text-muted mb-3"></i>
								<p class="text-muted">Tidak ada tugas mendatang</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<!-- Nilai Terbaru -->
			<div class="col-md-6">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="fas fa-star mr-1"></i>
							Nilai Terbaru
						</h3>
					</div>
					<div class="card-body">
						<?php if ($stats['nilai_terbaru']): ?>
							<div class="text-center">
								<h4 class="text-primary">
									<?= $stats['nilai_terbaru']->nilai ?>/<?= $stats['nilai_terbaru']->max_poin ?>
								</h4>
								<p class="text-muted"><?= htmlspecialchars($stats['nilai_terbaru']->judul) ?></p>
								<a href="<?= base_url('siswa/tugas') ?>" class="btn btn-primary btn-sm">
									<i class="fas fa-trophy"></i> Lihat Semua Nilai
								</a>
							</div>
						<?php else: ?>
							<div class="text-center py-4">
								<i class="fas fa-medal fa-2x text-muted mb-3"></i>
								<p class="text-muted">Belum ada nilai</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Kelas Yang Diikuti -->
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h3 class="card-title">
							<i class="fas fa-graduation-cap mr-1"></i>
							Kelas Yang Diikuti
						</h3>
					</div>
					<div class="card-body">
						<?php if ($stats['total_kelas'] > 0): ?>
							<div class="text-center">
								<h4 class="text-primary"><?= $stats['total_kelas'] ?></h4>
								<p class="text-muted">Kelas aktif</p>
								<a href="<?= base_url('siswa/kelas') ?>" class="btn btn-primary btn-sm">
									<i class="fas fa-eye"></i> Lihat Semua Kelas
								</a>
							</div>
						<?php else: ?>
							<div class="text-center py-3">
								<i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
								<p class="text-muted">Belum terdaftar di kelas manapun</p>
								<small class="text-muted">Hubungi guru untuk mendaftarkan Anda</small>
							</div>
						<?php endif; ?>
            </div>
          </div>
        </div>
    </div>
  </section>
</div>

<?php $this->load->view('templates/footer'); ?>