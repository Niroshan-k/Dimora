<?php
session_start();

if (isset($_SESSION['user'])) {
    $username = $_SESSION['user']['username'];
    $role = $_SESSION['user']['role'];
    $user_id = $_SESSION['user']['user_id'] ?? null;
} else {
    header('Location: http://localhost/Dimora/App/views/signin.php');
    exit;
}

require_once '../../app/controllers/UserController.php';

$userController = new UserController();
$userData = $userController->getProfile($user_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($userController->update($user_id)) {
        header('Location: http://localhost/Dimora/App/views/userprofile.php');
        exit;
    } else {
        echo "<p>Failed to update Profile. Please try again.</p>";
    }
}

if (!$userData) {
    echo "<p>User not found. Please check your details or contact support.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" sizes="32x32" href="../../images/fav.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <title>Profile</title>
</head>
<body>
    <?php include './layout/customerHeader.php'; ?>
    <br>
    <h1 class="text-center text-2xl font-bold mb-6">Your Profile Id: <?php echo htmlspecialchars($user_id); ?></h1>
    <form action="http://localhost/Dimora/App/views/userProfile.php" method="POST" enctype="multipart/form-data" class="max-w-xl mx-auto p-6 rounded-lg">
        <div class="text-center mb-6">
            <label for="profilePicture" class="font-semibold block mb-2">Profile Picture:</label>
            <?php if (!empty($userData['profilePicture'])): ?>
                <img src="data:image/jpg;base64,<?= base64_encode($userData['profilePicture']); ?>" alt="Profile Picture" class="w-36 h-36 rounded-full object-cover border-4 border-amber-500 mx-auto" />
            <?php else: ?>
                <div class="w-36 h-36 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 border-4 border-amber-500 mx-auto">No Image</div>
            <?php endif; ?>
            <input type="file" name="profilePicture" class="mt-4" accept="image/*">
        </div>

        <div class="mb-4">
            <label for="email" class="font-semibold block mb-1">Email:</label>
            <input type="email" name="email" value="<?= htmlspecialchars($userData['email'] ?? ''); ?>" class="p-3 bg-brown-50 rounded-md w-full" required>
        </div>

        <div class="mb-4">
            <label for="username" class="font-semibold block mb-1">Username:</label>
            <input type="text" name="username" value="<?= htmlspecialchars($userData['username'] ?? ''); ?>" class="p-3 bg-brown-50 rounded-md w-full" required>
        </div>

        <div class="mb-4">
            <label for="NIC" class="font-semibold block mb-1">NIC:</label>
            <input type="text" name="NIC" value="<?= htmlspecialchars($userData['NIC'] ?? ''); ?>" class="p-3 bg-brown-50 rounded-md w-full" required>
        </div>

        <div class="text-center justify-between items-center flex gap-4">
            <button type="submit" class="bg-brown-150 font-bold text-white py-2 px-4 rounded-md text-lg hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">Update Profile</button>
            <button class="bg-red-100 text-lg font-bold py-2 px-4 rounded-md"><a href="./signin.php">Log Out</a></button>
        </div>
    </form>
    
    <br>
    <?php include './layout/footer.php'; ?>
</body>
</html>
