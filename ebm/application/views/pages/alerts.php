<!-- ERROR ALERT -->
<?php if($this->session->flashdata('error')): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong class="text-center"><?=$this->session->flashdata('error')?></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<!-- SUCCESS ALERT -->
<?php if($this->session->flashdata('success')): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong class="text-center"><?=$this->session->flashdata('success')?></strong>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?> 

<!-- LOGGED IN ALERT -->
<?php if($this->session->flashdata('message')): ?>
  <script>
    alert("<?=$this->session->flashdata('message')?>");
  </script>
<?php endif; ?> 