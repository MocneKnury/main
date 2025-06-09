<?php
session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';

    $loginLower = strtolower($login);
    $passwordLower = strtolower($password);

    if (
        ($loginLower === 'suv' && $password === 'user') ||
        ($loginLower === 'wayfer' && $password === 'user') ||
        ($loginLower === 'wikok' && $password === 'user') ||
        ($loginLower === 'divsero' && $password === 'user') ||
        (($loginLower === 'kuba' && ($password === 'Admin' || $password === 'admin')) ||
         ($loginLower === 'wiktor' && $passwordLower === 'admin'))
    ) {
        // Set session user for example purpose (optional)
        $_SESSION['user'] = $login;

        // Redirect accordingly
        if ($loginLower === 'suv') {
            header('Location: suv.php');
            exit;
        } elseif ($loginLower === 'wayfer') {
            header('Location: wayfer.php');
            exit;
        } elseif ($loginLower === 'wikok') {
            header('Location: wikok.php');
            exit;
        } elseif ($loginLower === 'divsero') {
            header('Location: divsero.php');
            exit;
        } elseif ($loginLower === 'kuba' || $loginLower === 'wiktor') {
            header('Location: admin.php');
            exit;
        }
    } else {
        $error = 'Nieprawidłowy login lub hasło.';
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Logowanie - Mocne Knury Esport</title>
<style>
  /* Reset */
  *, *::before, *::after {
    box-sizing: border-box;
  }
  body {
    margin: 0;
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, #0a0a0a 0%, #121212 100%);
    color: #e5e5e5;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
  }
  .login-card {
    background: #1f1f1f;
    padding: 40px 40px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(34, 197, 94, 0.7);
    width: 100%;
    max-width: 400px;
    color: #d1d5db;
  }
  h1 {
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 24px;
    color: #22c55e;
    text-align: center;
    text-shadow: 0 0 8px #22c55eaa;
  }
  form {
    display: flex;
    flex-direction: column;
    gap: 18px;
  }
  label {
    font-weight: 600;
    color: #86efac;
    margin-bottom: 6px;
  }
  input[type="text"],
  input[type="password"] {
    padding: 12px 16px;
    border: 1.5px solid #22c55e;
    border-radius: 0.75rem;
    font-size: 1rem;
    background: #121212;
    color: #cbd5e1;
    transition: border-color 0.3s ease, background-color 0.3s ease;
  }
  input[type="text"]::placeholder,
  input[type="password"]::placeholder {
    color: #6b7280;
  }
  input[type="text"]:focus,
  input[type="password"]:focus {
    border-color: #86efac;
    outline: none;
    background: #1f1f1f;
  }
  button {
    background-color: #22c55e;
    color: #111;
    font-size: 1.1rem;
    font-weight: 700;
    padding: 14px 0;
    border: none;
    border-radius: 0.75rem;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(34, 197, 94, 0.8);
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
  }
  button:hover,
  button:focus {
    background-color: #16a34a;
    box-shadow: 0 6px 15px rgba(22, 163, 74, 1);
    outline: none;
  }
  .error-message {
    background-color: #991b1b;
    color: #fee2e2;
    font-weight: 600;
    border-radius: 0.5rem;
    padding: 12px 16px;
    user-select: none;
    text-align: center;
    box-shadow: 0 0 12px #b91c1ccc;
  }
  @media (max-width: 480px) {
    .login-card {
      padding: 32px 24px;
      width: 100%;
      max-width: 100%;
    }
  }
</style>
</head>
<body>
  <main class="login-card" role="main" aria-labelledby="login-title">
    <h1 id="login-title">Logowanie</h1>

    <?php if ($error): ?>
      <div class="error-message" role="alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" action="logowanie.php" novalidate>
      <div>
        <label for="login">Login</label>
        <input type="text" id="login" name="login" placeholder="Wpisz login" required autocomplete="username" aria-required="true" />
      </div>
      <div>
        <label for="password">Hasło</label>
        <input type="password" id="password" name="password" placeholder="Wpisz hasło" required autocomplete="current-password" aria-required="true" />
      </div>
      <button type="submit">Zaloguj się</button>
    </form>
  </main>
</body>
</html>
