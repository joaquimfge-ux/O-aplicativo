    </div>
</main>

<footer class="bg-white border-top py-4 mt-auto">
    <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
        <div class="d-flex align-items-center gap-2">
            <span class="fw-bold text-dark">Custom<span class="text-primary">Storage</span></span>
            <span class="text-muted small">&copy; <?= date('Y') ?> - Todos os direitos reservados.</span>
        </div>
        <div class="text-muted small">
            <span>Dispositivos Físicos Personalizados para Jogos, Fotos, Vídeos e Memórias.</span>
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-light text-secondary border">PHP 8.3 &bull; MySQL &bull; Apache</span>
        </div>
    </div>
</footer>

<!-- Bootstrap 5.3 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Scripts da Aplicação -->
<script src="<?= View::url('/public/js/main.js') ?>"></script>
</body>
</html>
