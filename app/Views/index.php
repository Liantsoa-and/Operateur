<?= $this->extend('layouts/operateur') ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h1 class="h3 mb-1"><i class="bi bi-people-fill me-2"></i>Point d'entre</h1>
        <p class="text-muted">Choisir entre operatuer ou client</p>
    </div>
    <a href="operateur" class="btn btn-primary">Operateur</a>
    <a href="client" class="btn btn-secondary">Client</a>
</div>