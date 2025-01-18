<?php

    session_start();

    if (isset($_SESSION['user'])) {
        $username = $_SESSION['user']['username'];
        $role = $_SESSION['user']['role'];
        $user_id = $_SESSION['user']['user_id'] ?? null;
    } else {
        // Redirect to signin page if session is not set
        header('Location: http://localhost/Dimora/App/views/signin.php');
        exit;
    }

    if($role === "seller"){
        header('Location: http://localhost/Dimora/App/views/sellerDashboard.php');
    }
 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>
    <?php include './layout/customerHeader.php' ?>
    <main>
        <br>
        <?php include './layout/search.php' ?>
        <?php include './layout/houseComp.php' ?>
    </main>
    <?php include './layout/footer.php' ?>
</body>
</html>