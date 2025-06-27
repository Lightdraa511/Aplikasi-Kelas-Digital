<?php
// =============================================================================
// 12. VIEW TEMPLATE - application/views/templates/sidebar.php
// =============================================================================
?>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
      <img src="https://via.placeholder.com/33x33/007bff/ffffff?text=KD" alt="Kelas Digital" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light"><b>Kelas</b>Digital</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="https://via.placeholder.com/35x35/28a745/ffffff?text=<?= strtoupper(substr($user['nama_lengkap'], 0, 1)) ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?= $user['nama_lengkap'] ?></a>
          <small class="text-light"><?= ucwords(str_replace('_', ' ', $user['role'])) ?></small>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <!-- Dashboard -->
          <li class="nav-item">
            <a href="<?= base_url('dashboard') ?>" class="nav-link <?= ($this->uri->segment(1) == 'dashboard' || $this->uri->segment(1) == '') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <?php if($user['role'] == 'super_admin'): ?>
          <!-- Admin Menu -->
          <li class="nav-header">ADMINISTRATOR</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>Kelola Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Periode Akademik</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>Laporan</p>
            </a>
          </li>
          
          <?php elseif($user['role'] == 'guru'): ?>
          <!-- Guru Menu -->
          <li class="nav-header">GURU</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-chalkboard"></i>
              <p>Kelola Kelas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tasks"></i>
              <p>Kelola Tugas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-star"></i>
              <p>Penilaian</p>
            </a>
          </li>
          
          <?php elseif($user['role'] == 'siswa'): ?>
          <!-- Siswa Menu -->
          <li class="nav-header">SISWA</li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-book"></i>
              <p>Kelas Saya</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-clipboard-list"></i>
              <p>Tugas</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-trophy"></i>
              <p>Nilai</p>
            </a>
          </li>
          <?php endif; ?>

          <!-- Common Menu -->
          <li class="nav-header">AKUN</li>
          <li class="nav-item">
            <a href="<?= base_url('change-password') ?>" class="nav-link">
              <i class="nav-icon fas fa-key"></i>
              <p>Ubah Password</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('logout') ?>" class="nav-link">
              <i class="nav-icon fas fa-sign-out-alt"></i>
              <p>Logout</p>
            </a>
          </li>
        </ul>
      </nav>
    </div>
  </aside>