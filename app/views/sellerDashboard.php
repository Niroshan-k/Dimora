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

if($role === "customer"){
    header('Location: http://localhost/Dimora/App/views/customer.php');
}


require_once '../../app/controllers/HouseController.php';

$houseController = new HouseController();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $house_id = $_POST['house_id'];
    if ($houseController->deleteAdvertisement($house_id)) {
    } else {
        echo "<p class='text-red-500'>Failed to delete advertisement. Please try again.</p>";
    }
}

$advertisements = $houseController->fetchUserAdvertisements($user_id);  
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Dashboard</title>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../../images/fav.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <header class="bg-yellow-50 shadow-md font-inter">
        <div class="container mx-auto flex items-center justify-between py-4 px-6">
            <!-- Logo -->
            <div>
                <img src="/Dimora/images/logo.png" alt="logo" class="w-20" />
            </div>

            <!-- Navigation -->
            <nav class="flex items-center space-x-8">
                <a href="#aboutus" class="text-gray-600 hover:text-brown-600 transition">About Us</a>
                <a href="#contact" class="text-gray-600 hover:text-brown-600 transition">Contact</a>
                <a href="sellerDashboard.php" class="text-gray-600 hover:text-brown-600 transition">Home</a>
                
                <button 
                    class="bg-green-50 text-white font-bold py-2 px-4 rounded hover:bg-green-100 transition">
                    <a href="userProfile.php">profile</a>
                </button>
            </nav>
        </div>
    </header>


    <main class="p-10">
        <br>
        <a href="createAdvertisement.php"><i class="fa fa-plus-circle" aria-hidden="true"></i> Create Advertisement</a>
        <br>
        <br>
        <h1 class="text-2xl font-bold font-serif mb-6">Hello, <?php echo htmlspecialchars($username); ?>! Here are your advertisements</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($advertisements as $ad): ?>
            <div class="border rounded-lg shadow-lg overflow-hidden">
                <img src="data:image/jpg;base64,<?= base64_encode($ad['image']); ?>" alt="Property Image" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h2 class="text-2xl font-bold font-serif mb-2"><?php echo htmlspecialchars($ad['name']); ?></h2>
                    <p class="font-semibold mb-2">House Advertisement Id: <?php echo htmlspecialchars($ad['house_id']); ?></p>
                    <ul class="text-gray-700 text-sm">
                        <li><strong>Price:</strong> $<?php echo htmlspecialchars($ad['price']); ?></li>
                        <li><strong>Type:</strong> <?php echo htmlspecialchars($ad['type']); ?></li>
                        <li><strong>Location:</strong> <?php echo htmlspecialchars($ad['location']); ?></li>
                        <li><strong>Bedrooms:</strong> <?php echo htmlspecialchars($ad['bedroom']); ?></li>
                        <li><strong>Bathrooms:</strong> <?php echo htmlspecialchars($ad['bathroom']); ?></li>
                        <li><strong>Carpark:</strong> <?php echo htmlspecialchars($ad['carpark']); ?></li>
                        <li><strong>Pool:</strong> <?php echo !empty($ad['pool']) ? 'Yes' : 'No'; ?></li>
                    </ul>
                    <br>
                    <div class="flex gap-4">
                        <a href="editHouseAdd.php?house_id=<?php echo $ad['house_id']; ?>" class="text-xl bg-brown-150 font-bold rounded text-white p-2">Edit</a>
                        <form action="" method="POST" onsubmit="return confirm('Are you sure you want to delete this advertisement?');">
                            <input type="hidden" name="house_id" value="<?php echo $ad['house_id']; ?>">
                            <button type="submit" name="delete" class="text-xl bg-brown-100 font-bold rounded text-white p-2">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>

        <?php if (empty($advertisements)): ?>
            <p class="text-gray-500 mt-6">You have no advertisements yet. <a href="createAdvertisement.php" class="text-blue-500">Create one now!</a></p>
        <?php endif; ?>
    </main>
    <?php include './layout/footer.php'; ?>
</body>
</html>
