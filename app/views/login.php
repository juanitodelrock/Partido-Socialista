<!-- app/views/login.php -->
<?php require_once 'header.php'; ?>

<div class="container">
    <h1 class="mt-5">Iniciar sesión</h1>

    <?php if (!empty($errorMessage)): ?>
        <div class="alert alert-danger">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>

    <form action="/app/?action=login" method="post" class="mt-3">
        <div class="form-group">
            <label for="email">Correo electrónico</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Iniciar sesión</button>
    </form>
</div>

<?php require_once 'footer.php'; ?>
