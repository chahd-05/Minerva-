<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
  
</head>
<body>
    <h2>Login</h2>

    <?php if(!empty($error)): ?>
        <p style="color: red"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="enter your email" required>
            <input type="password" name="password" placeholder="password" required>
            <input type="text" name="role" placeholder="enter your role" required>
            <button type="submit">Login</button>
        </form>
        
</body>
</html>