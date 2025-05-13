<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $file = 'users.txt';

    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $authenticated = false;

        foreach ($lines as $line) {
            if (strpos($line, ',') === false) continue;

            list(, $storedUserPass) = explode(',', $line);
            if (strpos($storedUserPass, '=') === false) continue;

            list($storedUser, $storedPass) = explode('=', $storedUserPass);

            if ($storedUser === $username && password_verify($password, $storedPass)) {
                $authenticated = true;
                break;
            }
        }

        if ($authenticated) {
            // Sanitize username for output
            $safeUsername = htmlspecialchars($username, ENT_QUOTES, 'UTF-8');
            header("Location: welcome.php?username=$safeUsername");
            exit();
        } else {
            $message = "Wrong username/password ";
        }
    } else {
        $message = "User file not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
<div class="container mt-5">
<div class="mt-3">
        <?php if (isset($message)) : ?>
            <div class="alert alert-primary" role="alert">
            <?php if (isset($message)) echo $message; ?>
        </div>
        <?php endif; ?>
    </div>

    <h1 class="text-center mb-4">Login Form</h1>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" class="form-control" name="username" id="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
