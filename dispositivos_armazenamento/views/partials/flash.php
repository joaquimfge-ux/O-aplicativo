<?php
$flashes = View::getFlashes();
if (!empty($flashes)):
    foreach ($flashes as $flash):
?>
    <div class="alert alert-<?= htmlspecialchars($flash['type']) ?> alert-dismissible fade show shadow-sm d-flex align-items-center" role="alert">
        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
        <div class="flex-grow-1">
            <?= htmlspecialchars($flash['message']) ?>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
    </div>
<?php 
    endforeach;
endif;
?>
