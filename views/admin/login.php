<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - Cafe Pub La Luna</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>css/style.css">

    <style>
        body {
            background: #000;
            color: #fff;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .login-screen {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-logo {
            width: 220px;
            margin-bottom: 60px;
        }

        .admin-card {
            width: 430px;
            max-width: 90%;
            background: #111;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 45px;
            box-sizing: border-box;
        }

        .admin-card h2 {
            text-align: center;
            font-size: 2.2rem;
            letter-spacing: 8px;
            margin-bottom: 45px;
        }

        .admin-card input {
            width: 100%;
            padding: 18px 22px;
            margin-bottom: 25px;
            background: #000;
            color: white;
            border: 1px solid #555;
            font-size: 1.2rem;
            box-sizing: border-box;
        }

        .admin-card button {
            width: 100%;
            padding: 18px;
            background: white;
            color: black;
            border: none;
            font-size: 1.1rem;
            font-weight: bold;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 6px;
        }

        .error {
            color: #ff3131;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="login-screen">

    <a href="<?= BASE_URL ?>">
        <img src="<?= BASE_URL ?>img/logo/logo Luna.png" class="login-logo" alt="Logo Luna">
    </a>

    <form method="POST" action="<?= BASE_URL ?>login" class="admin-card">

    <h2>ACCESO ADMIN</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <input type="text" name="usuario" placeholder="Usuario" autocomplete="username">

    <input type="password" name="password" placeholder="Contraseña" autocomplete="current-password">

    <button type="submit">ENTRAR</button>
</form>

</div>

</body>
</html>