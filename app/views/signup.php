<?php
//include the required controller
require_once '../../app/controllers/UserController.php';

//create an instance of the cpntroller
$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
// Check what is being sent in the request
    $controller->create();
}

//call the create method to handle form submission


session_start();
$usernameError = $_SESSION['error']['username'] ?? null; // Get username error if it exists
unset($_SESSION['error']); // Clear error messages after displaying
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <link rel="icon" type="image/png" sizes="32x32" href="../../images/fav.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
</head>
<script>
        // JavaScript Validation Function
        function validateForm(event) {
            let errors = [];
            const email = document.getElementById('email').value;
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;
            const nic = document.getElementById('NIC').value;

            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                errors.push("Please enter a valid email address.");
            }

            // Username validation
            if (username.trim().length < 3) {
                errors.push("Username must be at least 3 characters long.");
            }

            // Password validation
            if (password.length < 8) {
                errors.push("Password must be at least 8 characters long.");
            }

            // NIC validation
            const nicRegex = /^[0-9]{9}[Vv]$/; // Adjust regex based on your NIC format
            if (!nicRegex.test(nic)) {
                errors.push("NIC must follow the correct format.");
            }

            // Display errors
            if (errors.length > 0) {
                event.preventDefault();
                const errorContainer = document.getElementById('errorContainer');
                errorContainer.innerHTML = errors.map(err => `<p class="text-red-500">${err}</p>`).join('');
            }
        }
    </script>
<body>
    <!-- Header -->
    <header class="bg-yellow-50 shadow-md font-inter p-6">
        <div class="flex justify-between items-center max-w-7xl mx-auto">
            <!-- Logo -->
            <div>
                <img src="/Dimora/images/logo.png" alt="logo" class="w-24">
            </div>

            <!-- Hamburger Menu -->
            <div id="hamburger" class="cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16m-7 6h7" />
                </svg>
            </div>

            <!-- Close Icon -->
            <div id="close" class="hidden cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-nav" class="flex-col bg-yellow-50 space-y-4 py-4 px-6 hidden">
            <div class="flex justify-between items-center w-full">
                <a href="#aboutus" class="text-gray-600 hover:text-brown-600">About Us</a>|
                <a href="#contact" class="text-gray-600 hover:text-brown-600">Contact</a>|
                <a href="customerDashboard.php" class="text-gray-600 hover:text-brown-600 transition">Home</a>|
                <a href="../app/views/signup.php">
                    <button 
                        class="bg-green-50 text-white font-bold py-2 px-4 rounded hover:bg-green-100 transition">
                        <a href="userProfile.php">Sign UP</a>
                    </button>
                </a>
            </div>
        </div>
    </header>

    <!-- JavaScript for Toggling Menu -->
    <script>
        const hamburger = document.getElementById('hamburger');
        const close = document.getElementById('close');
        const mobileNav = document.getElementById('mobile-nav');
        const nav = document.getElementById('nav');

        // Toggle mobile menu visibility
        hamburger.addEventListener('click', () => {
            mobileNav.classList.remove('hidden');
            hamburger.classList.add('hidden');
            close.classList.remove('hidden');
        });

        // Close mobile menu
        close.addEventListener('click', () => {
            mobileNav.classList.add('hidden');
            hamburger.classList.remove('hidden');
            close.classList.add('hidden');
        });

        // If it's desktop, make sure the menu is hidden
        // if (window.innerWidth >= 1024) {
        //     mobileNav.classList.add('hidden');
        //     close.classList.add('hidden');
        //     hamburger.classList.remove('hidden');
        // }
    </script>
    <br>


    <main>
        
            <h1 class="text-7xl text-center font-bold font-serif mb-16">SIGN UP</h1>
            <form action="http://localhost/Dimora/app/views/signup.php" method="POST" enctype="multipart/form-data" class="space-y-4 max-w-xl mx-auto p-6 rounded-lg">
                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-3 py-2 bg-brown-50 rounded-md focus:outline-none focus:ring focus:ring-brown-300">
                    
                </div>

                <!-- Username Input -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" class="w-full px-3 py-2 bg-brown-50 rounded-md focus:outline-none focus:ring focus:ring-brown-300">
                    <?php if ($usernameError): ?>
                    <p class="text-xs text-red-500 mt-1"><?php echo $usernameError; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-3 py-2 bg-brown-50 rounded-md focus:outline-none focus:ring focus:ring-brown-300">
                    <p class="text-xs mt-1">* Password must be at least 8 characters</p>
                </div>

                <!-- User Type Radio Buttons -->
                <div class="mb-8">
                    <label class="block text-gray-700 font-bold mb-2">Are you:</label>
                    <div class="flex space-x-4">
                        <label class="inline-flex items-center">
                            <input type="radio" name="role" value="customer" class="form-radio text-green-600" />
                            <span class="ml-2">Customer</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" name="role" value="seller" class="form-radio text-green-600" />
                            <span class="ml-2">Seller</span>
                        </label>
                    </div>
                </div>

                <!-- NIC Input -->
                <div>
                    <label for="NIC" class="block text-sm font-medium text-gray-700">NIC</label>
                    <input type="text" id="NIC" name="NIC" class="w-full px-3 py-2 bg-brown-50 rounded-md focus:outline-none focus:ring focus:ring-brown-300">
                </div>

                <!-- Profile Picture Upload -->
                <div>
                    <label for="profilePicture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                    <input type="file" id="profilePicture" name="profilePicture" class=" px-3 py-2 rounded-md focus:outline-none focus:ring focus:ring-brown-300">
                </div>
                
                <!-- <label class="inline-flex items-center mb-4">
                    <input type="checkbox" class="form-checkbox text-green-600" />
                    <span class="ml-2 text-gray-700">I agree to the <a href="" class="text-blue-600 hover:underline">terms and conditions</a></span>
                </label> -->

                <!-- Submit Button -->
                <button type="submit" class=" w-full w-md bg-brown-150 font-bold text-white py-2 px-4 rounded">
                    Sign Up
                </button>
                <br>
                <div id="errorContainer" class="mb-4"></div>
            </form>
            <div class="p-10 flex flex-col items-center space-y-4">
                <!-- Sign In Link -->
                <p class="text-bold font-bold text-gray-700 mt-4">
                    Already have an account? 
                    <a href="./signin.php" class="text-blue-600 hover:underline">Sign In</a>
                </p>
            </div>     
    </main>
    <?php include './layout/footer.php' ?>
</body>
</html>

