<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dimora Header</title>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
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
                <a href="customerDashboard.php" class="text-gray-600 hover:text-brown-600 transition">Home</a>
                
                <button 
                    class="bg-green-50 text-white font-bold py-2 px-4 rounded hover:bg-green-100 transition">
                    <a href="userProfile.php">profile</a>
                </button>
            </nav>
        </div>
    </header>
</body>
</html>
