<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Iniciar Sesión</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #f1f3f5, #e9ecef);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-card {
      width: 100%;
      max-width: 400px;
      border-radius: 15px;
    }

    .form-control:focus {
      box-shadow: none;
      border-color: #343a40;
    }

    .btn-dark {
      border-radius: 10px;
    }

    .social-btn i {
      font-size: 18px;
    }
  </style>
</head>

<body>

  <div class="card shadow login-card p-4">
    
    <h3 class="text-center mb-4">Iniciar Sesión</h3>

    <form method="POST" action="login.php?accion=login">

      <div class="mb-3">
        <label class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" name="correo" placeholder="tu_correo@example.com" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control" name="contrasena" placeholder="*********" required>
      </div>

      <div class="d-flex justify-content-end mb-3">
        <a href="login.php?accion=recuperar" class="small">¿Olvidaste tu contraseña?</a>
      </div>

      <button type="submit" class="btn btn-dark w-100 mb-3">
        Iniciar Sesión
      </button>

      <div class="text-center">
        <p class="mb-2">¿No tienes cuenta? <a href="#">Regístrate</a></p>

        <small class="text-muted">o inicia sesión con</small>

        <div class="mt-2">
          <button type="button" class="btn btn-outline-secondary btn-sm mx-1 social-btn">
            <i class="bi bi-facebook"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary btn-sm mx-1 social-btn">
            <i class="bi bi-google"></i>
          </button>
          <button type="button" class="btn btn-outline-secondary btn-sm mx-1 social-btn">
            <i class="bi bi-twitter"></i>
          </button>
        </div>
      </div>

    </form>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

</body>
</html>