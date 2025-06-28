<?php
// =============================================================================
// 12. VIEW TEMPLATE - application/views/templates/sidebar.php
// =============================================================================
?>
  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= base_url('dashboard') ?>" class="brand-link">
      <img src="https://i.pinimg.com/474x/f8/a5/7c/f8a57c4d62b9eb10ebcb3d46aeb29bd0.jpg" alt="Kelas Digital" class="brand-image img-circle elevation-3">
      <span class="brand-text font-weight-light"><b>Kelas</b>Digital</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
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
            <a href="<?= base_url('admin/users') ?>" class="nav-link <?= ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'users') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-users"></i>
              <p>Kelola Pengguna</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/periode') ?>" class="nav-link <?= ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'periode') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>Periode Akademik</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?= base_url('admin/kelas') ?>" class="nav-link <?= ($this->uri->segment(1) == 'admin' && $this->uri->segment(2) == 'kelas') ? 'active' : '' ?>">
              <i class="nav-icon fas fa-chalkboard"></i>
              <p>Monitoring Kelas</p>
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
            <a href="<?= base_url('guru/kelas') ?>" class="nav-link <?= ($this->uri->segment(1) == 'guru' && $this->uri->segment(2) == 'kelas') ? 'active' : '' ?>">
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
            <a href="<?= base_url('siswa/kelas') ?>" class="nav-link <?= ($this->uri->segment(1) == 'siswa' && $this->uri->segment(2) == 'kelas') ? 'active' : '' ?>">
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