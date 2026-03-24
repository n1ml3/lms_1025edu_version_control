<?php
/**
 * Admin Footer — Scripts + closing tags
 */
?>
    </div><!-- /.admin-wrapper -->

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Admin Global JS -->
    <script src="<?= $baseUrl ?? '/lms1025edu/admin' ?>/js/admin.js"></script>

    <?php if (!empty($extraScripts)):
        foreach ($extraScripts as $script): ?>
        <script src="<?= $script ?>"></script>
    <?php endforeach; endif; ?>

    <?php if (!empty($inlineScript)): ?>
    <script>
        <?= $inlineScript ?>
    </script>
    <?php endif; ?>
</body>
</html>
