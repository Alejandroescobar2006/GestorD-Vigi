<?php
// app/views/auth/login.php
$pageTitle = 'Login - VigiTecol';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="/css/login.css">
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>Bienvenido al SENA</h1>
            <p>Ingresa a tu cuenta</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="error-message" style="display: block; background: #fee; border: 1px solid #fcc; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                <?php foreach ($errors as $error): ?>
                    <p style="margin: 5px 0; color: #c00;"><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="/login">
            <div class="inptUser">
                <input type="email" 
                       name="email" 
                       placeholder="Ingrese su correo electrónico"
                       value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                       required>
            </div>

            <div class="inptPass">
                <input type="password" 
                       name="password" 
                       placeholder="Ingrese su contraseña"
                       required>
            </div>

            <div class="remember-forgot">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Recordarme</label>
                </div>
                <a href="#" class="forgot-password">¿Olvidó su contraseña?</a>
            </div>

            <button type="submit" class="login-button">Iniciar Sesión</button>
        </form>

        <div class="register-link">
            <p>¿No tienes una cuenta? <a href="#">Solicitar acceso</a></p>
        </div>
    </div>

    <script>
        // Validación básica del lado del cliente
        document.querySelector('form').addEventListener('submit', function(e) {
            const email = document.querySelector('input[name="email"]').value;
            const password = document.querySelector('input[name="password"]').value;
            let errors = [];

            if (!email) {
                errors.push('El correo electrónico es requerido');
            } else if (!/\S+@\S+\.\S+/.test(email)) {
                errors.push('El formato del correo no es válido');
            }

            if (!password) {
                errors.push('La contraseña es requerida');
            }

            if (errors.length > 0) {
                e.preventDefault();
                alert('Errores:\n' + errors.join('\n'));
            }
        });
    </script>
</body>
</html>