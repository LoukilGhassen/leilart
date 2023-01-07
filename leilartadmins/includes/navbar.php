 <!-- Sidebar -->
 <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
  <div class="sidebar-brand-icon rotate-n-15">
    <i class="fas fa-laugh-wink"></i>
  </div>
  <div class="sidebar-brand-text mx-3">Acceuil</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item active">
  <a class="nav-link" href="../index.php">
    <span>Leil art</span></a>
</li>

<!-- Divider -->

<!-- Heading -->


<!-- Nav Item - Pages Collapse Menu -->

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
  Option
</div>

<!-- Nav Item - Pages Collapse Menu -->

<!-- Nav Item - Charts -->
<li class="nav-item">
  <a class="nav-link" href="addimg.php">
    <i class="fas fa-fw fa-chart-area"></i>
    <span>Ajouter des photos</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="deletimg.php?page=1">
    <i class="fas fa-fw fa-chart-area"></i>
    <span>Supprimer des photos</span></a>
</li>
<li class="nav-item">
  <a class="nav-link" href="categmanag.php">
    <i class="fas fa-fw fa-chart-area"></i>
    <span>gestion des categories</span></a>
</li>

<!-- Nav Item - Tables -->
<?php 
if($_SESSION['role']==='admin'){?>
<li class="nav-item">
  <a class="nav-link" href="register.php">
    <i class="fas fa-fw fa-table"></i>
    <span>Admins</span></a>
</li>
<?php
}
?>
<!-- Divider -->
<hr class="sidebar-divider d-none d-md-block">

<!-- Sidebar Toggler (Sidebar) -->
<div class="text-center d-none d-md-inline">
  <button class="rounded-circle border-0" id="sidebarToggle"></button>
</div>

</ul>
<!-- End of Sidebar -->


 <!-- Scroll to Top Button-->
 <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

 <!-- Logout Modal-->
 <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">QUITTER ?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Voulez-vous vraiment quitter</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Annuler</button>
          <form action="logout.php" method="POST" >
          <input type="submit" class="btn btn-primary" name="logout_btn" value="QUITTER">
          </form>
        </div>
      </div>
    </div>
  </div>