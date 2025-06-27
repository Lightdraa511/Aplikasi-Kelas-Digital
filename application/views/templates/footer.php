<?php
// =============================================================================
// 13. VIEW TEMPLATE - application/views/templates/footer.php
// =============================================================================
?>
  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2024 <a href="#">Kelas Digital</a>.</strong>
    Sistem Pembelajaran Digital untuk SMA.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    // Auto dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert:not(.alert-permanent)').fadeOut();
    }, 5000);
    
    // Confirm logout
    $('a[href*="logout"]').click(function(e) {
        if (!confirm('Yakin ingin logout?')) {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
