<?php
// $role and $user should be set before include
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="../dashboard.php">RMS</a>
    <div>
      <span class="me-3">Logged in as <b><?=htmlspecialchars($user)?></b> (<?=ucfirst($role)?>)</span>
      <a href="/logout.php" class="btn btn-sm btn-warning">Logout</a>
    </div>
  </div>
</nav>