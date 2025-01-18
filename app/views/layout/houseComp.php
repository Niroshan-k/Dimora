<?php

require_once '../../app/controllers/HouseController.php';

$Controller = new HouseController();
$Controller->loadAdvertisements();
$advertisements = $Controller->advertisements; // Access the advertisements
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-10">
        <!-- Advertisement Section -->
        <div>
            <!-- Section Heading -->
            <h1 class="text-4xl font-serif mb-6">Houses</h1>
            <!-- Grid for Ads -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($advertisements as $ad): ?>
                <!-- Individual Ad Card -->
                <div class="bg-yellow-50 shadow-lg rounded-sm">
                    <!-- Top Section: Image and Wishlist Icon -->
                    <div class="relative h-48">
                        <a href="./Houseinfo.php?house_id=<?php echo $ad['house_id']; ?>">
                        <img src="data:image/jpg;base64,<?= base64_encode($ad['image']); ?>" alt="House Image" class="w-full h-full object-cover">
                        </a>
                        <button class="absolute top-2 right-2" onclick="toggleBookmark(this)" data-saved="false">
                            <i class="fas fa-bookmark text-white text-2xl"></i>
                        </button>   
                    </div>

                    <!-- Bottom Section: Details -->
                    <a href="../app/views/Landinfo.php">
                    <div class="p-4">
                        <!-- Property Name -->
                        <h2 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($ad['name']); ?></h2>
                        <!-- Price -->
                        <p class="text-gray-600 mt-2">$<?php echo htmlspecialchars($ad['price']); ?></p>
                        <!-- Location -->
                        <p class="text-sm text-gray-500 mt-1"><?php echo htmlspecialchars($ad['location']); ?></p>
                    </div>
                    </a>

                    <!-- Bottom Icons -->
                    <div class="flex justify-between items-center p-4 border-t">
                        <!-- Left: Heart Icon -->
                        <button class="flex items-center space-x-1 text-gray-600" onclick="toggleHeart(this)">
                            <i class="fas fa-heart text-2xl text-brown-150"></i>
                            <span>Like</span>
                        </button>
                        <!-- Right: Share Icon -->
                        <button class="flex items-center space-x-1 text-gray-600 hover:text-blue-500">
                            <i class="fas fa-share text-2xl text-blue-500"></i>
                            <span>Share</span>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
            <!-- More Button -->
            <div class="flex justify-end mt-6">
                <button class="bg-green-50 text-white font-bold py-2 px-4 rounded-sm shadow-lg">
                    More
                </button>
            </div>
        </div>
    </div>

</body>
</html>
