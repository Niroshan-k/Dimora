
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
    //include the required controller
    require_once '../../app/controllers/HouseController.php';

    //create an instance of the cpntroller
    $controller = new HouseController();

    //call the create method to handle form submission
    $controller->createADD();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="/Dimora/public/css/tailwind.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/png" sizes="32x32" href="../../images/fav.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            height: 300px;
        }
    </style>
</head>
<?php include './layout/customerHeader.php' ?>
<br>
<body class="bg-white">
    <div class="container mx-auto p-8 bg-white rounded">
        <h1 class="text-3xl font-bold mb-6 text-center">Add Property</h1>
        <form id="propertyForm" enctype="multipart/form-data" action="http://localhost/Dimora/App/views/createAdvertisement.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Section -->
            <div>
                <!-- Name -->
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter property name">

                <!-- Price -->
                <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                <input type="text" id="price" name="price" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter price">

                <!-- Type -->
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <select id="type" name="type" class="w-full p-2 bg-brown-50 rounded mb-4">
                    <option value="luxury house">Luxury House</option>
                    <option value="residental house">Residental House</option>
                    <option value="traditional house">Traditional House</option>
                    <option value="modern house">Modern House</option>
                </select>

                <!-- Description -->
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" name="description" class="w-full p-2 bg-brown-50 rounded mb-4" rows="4" placeholder="Enter property description"></textarea>

                <!-- Image Upload -->
                <label for="image" class="block text-sm font-medium text-gray-700">Upload Image</label>
                <input type="file" id="image" name="image" class="w-full p-2 bg-brown-50 rounded mb-4">

                <!-- Map -->
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <p class="text-red-150 text-sm">Choose your location, click once and wait:</p>
                <div id="map" class="rounded mb-4"></div>
                <input type="hidden" id="location" name="location" class="w-full p-2 bg-brown-50 rounded" readonly>
            </div>

            <!-- Right Section -->
            <div>
                <!-- Bedroom -->
                <label for="bedroom" class="block text-sm font-medium text-gray-700">Bedroom</label>
                <input type="number" id="bedroom" name="bedroom" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter number of bedrooms">

                <!-- Bathroom -->
                <label for="bathroom" class="block text-sm font-medium text-gray-700">Bathroom</label>
                <input type="number" id="bathroom" name="bathroom" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter number of bathrooms">

                <!-- Carpark -->
                <label for="carpark" class="block text-sm font-medium text-gray-700">Carpark</label>
                <input type="number" id="carpark" name="carpark" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter number of carparks">

                <!-- Area -->
                <label for="area" class="block text-sm font-medium text-gray-700">Area (m²)</label>
                <input type="number" id="area" name="area" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter area in m²">

                <!-- Pool -->
                <label for="pool" class="block text-sm font-medium text-gray-700">Pool</label>
                <input type="number" id="pool" name="pool" class="w-full p-2 bg-brown-50 rounded mb-4" placeholder="Enter number of pools">

                <label for="user_id" class="block text-sm font-medium text-gray-700">User ID</label>
                <input type="text" id="user_id" name="user_id" class="w-full p-2 bg-brown-50 rounded mb-4" value="<?php echo htmlspecialchars($_SESSION['user']['user_id']); ?>" readonly>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-brown-150 text-white py-2 rounded font-bold">POST</button>
            </div>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        // Initialize Leaflet map
        const map = L.map('map').setView([5, 5], 2);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add click event to get location
        map.on('click', function (e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;

            // Reverse geocode to get readable address
            fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}`)
                .then(response => response.json())
                .then(data => {
                    const address = data.display_name;
                    document.getElementById('location').value = address;
                    alert(`Location selected: ${address}`);
                })
                .catch(error => console.error('Error fetching address:', error));
        });
    </script>
</body>
<br>
<?php include './layout/footer.php' ?>

</html>