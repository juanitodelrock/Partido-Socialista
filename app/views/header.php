<!-- app/views/header.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?php echo APP_NAME; ?>
    </title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">

    <!-- Tu hoja de estilos personalizada (opcional) -->
    <link rel="stylesheet" href="<?php echo APP_ROOT; ?>/assets/css/custom.css">
</head>

<body>

    <?php if ( isset($_SESSION['user_name']) && $action !== 'login' && $action !== 'register'): ?>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Political Monitor</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_link('dashboard'); ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo generate_link('topics_list'); ?>">Mis tópicos</a>
                    </li>
                    <?php if (is_super_admin($_SESSION['user_id'])): ?>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo generate_link('topics_list'); ?>">Crear nuevo tópico</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo generate_link('user_manage'); ?>">Activar/desactivar usuarios</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo generate_link('topics_assign'); ?>">Asignar tópicos a usuarios</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <form class="form-inline my-2 my-lg-0">
                    <span class="mr-sm-2">
                        <?php echo $_SESSION['user_name']; ?>
                    </span>
                    <a class="btn btn-outline-danger my-2 my-sm-0" href="<?php echo generate_link('logout'); ?>">Cerrar sesión</a>
                </form>
            </div>
        </nav>
    <?php endif; ?>