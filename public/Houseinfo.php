<?php
require_once '../app/controllers/PublicController.php';

$PublicController = new PublicController();
$house_id = $_GET['house_id'] ?? null;

if (!$house_id) {
    //header('Location: http://localhost/Dimora/App/views/sellerDashboardw.php');
    exit;
}

$advertisement = $PublicController->fetchAdvertisementById($house_id);

function getCoordinates($location) {
    $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($location) . "&format=json&limit=1";

    // Create a context with a User-Agent header
    $context = stream_context_create([
        'http' => [
            'header' => "User-Agent: DimoraApp/1.0 (https://example.com; your-email@example.com)\r\n"
        ]
    ]);

    $response = file_get_contents($url, false, $context);
    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);
    if (!empty($data) && isset($data[0]['lat'], $data[0]['lon'])) {
        return [
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon']
        ];
    }

    return null;
}


$coordinates = getCoordinates($advertisement['location']);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Land Information</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- <link href="../../public/css/tailwind.css" rel="stylesheet"> -->
    <style>
        .slider img {
            width: 100%;
            height: 400px;
            object-fit: cover;
        }
    </style>
</head>
<body class="font-inter">
    <?php include '../app/views/layout/header.php'; ?>
    <main class="mx-[5rem] my-10">
        <h1></h1>
        <!-- Background Slider -->
        <div class="relative w-full max-w-4xl mt-8 mx-auto">
            <div class="overflow-hidden rounded-lg shadow-lg">
                <div class="flex transition-transform duration-500" id="slider">
                    <img src="data:image/jpg;base64,<?= base64_encode($advertisement['image']); ?>" alt="Slide 1" class="w-full">
                </div>
            </div>
            <!-- Navigation Buttons -->
            <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-gray-800 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                &larr;
            </button>
            <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-gray-800 text-white rounded-full w-10 h-10 flex items-center justify-center z-10">
                &rarr;
            </button>
        </div>

        <!-- House Details -->
        <section class="mt-11 px-8 py-4">
            <h1 class="text-4xl font-bold font-serif"><?php echo htmlspecialchars($advertisement['name']); ?></h1>
            <p class="text-gray-600"><?php echo htmlspecialchars($advertisement['location']); ?></p>
            <h2 class="text-3xl text-green-500 font-serif font-bold">$<?php echo htmlspecialchars($advertisement['price']); ?></h2>
        </section>
        <hr>
        <!-- Overview and Form Section -->
        <div class="grid grid-cols-2 gap-8 px-8 my-10">
            <!-- Left: Overview -->
            <div>
                <h3 class="text-xl font-semibold mb-2">Description</h3>
                <p class="text-gray-700"><?php echo htmlspecialchars($advertisement['description']); ?></p>
                <br>
                <hr>
                <br>
                <ul>
                    <li class="mb-2"><i class="fas fa- "></i> Name: <?php echo htmlspecialchars($advertisement['name']); ?></li>
                    <li class="mb-2"><i class="fas fa-home"></i> Type: <?php echo htmlspecialchars($advertisement['type']); ?></li>
                    <li class="mb-2"><i class="fas fa-bed"></i> Bedroom: <?php echo htmlspecialchars($advertisement['bedroom']); ?></li>
                    <li class="mb-2"><i class="fas fa-bath"></i> Bathroom: <?php echo htmlspecialchars($advertisement['bathroom']); ?></li>
                    <li class="mb-2"><i class="fas fa-car"></i> Car Park: <?php echo htmlspecialchars($advertisement['carpark']); ?></li>
                    <li class="mb-2"><i class="fas fa-swimmer"></i> Pool: <?php echo !empty($ad['pool']) ? 'Yes' : 'No'; ?></li>
                    <li class="mb-2"><i class="fas fa-ruler-combined"></i> Area: <?php echo htmlspecialchars($advertisement['area']); ?>sqft</li>
                </ul>

            </div>

            <!-- Right: Form -->
            <div>
                <h3 class="text-xl font-semibold font-serif mb-4">More About This Property</h3>
                <form action="/sendEmail.php" method="POST" class="space-y-4">
                    <input type="text" name="fullName" placeholder="Full Name" required class="w-full px-3 bg-brown-50 py-2 border rounded">
                    <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border bg-brown-50 rounded">
                    <input type="text" name="phone" placeholder="Phone" required class="w-full px-3 py-2 border bg-brown-50 rounded">
                    <textarea name="message" placeholder="Message" rows="4" required class="w-full px-3 py-2 bg-brown-50 border rounded"></textarea>
                    <button type="submit" class="bg-brown-150 text-white px-4 py-2 rounded ">Send Email</button>
                </form>
            </div>
        </div>
        <hr>
        <!-- Google Maps -->
        <div class="grid gap-8 px-8 py-6">
            <!-- Left: Map -->
            <div id="map" class="w-full h-96 rounded-md shadow-md"></div>
        </div>
    </main>
    <?php include '../app/views/layout/footer.php'; ?>

    <!-- JavaScript -->
    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('#slider img');

        function showSlide(index) {
            const slider = document.getElementById('slider');
            slider.style.transform = `translateX(-${index * 100}%)`;
        }

        document.getElementById('next').addEventListener('click', () => {
            currentSlide = (currentSlide + 1) % slides.length;
            showSlide(currentSlide);
        });

        document.getElementById('prev').addEventListener('click', () => {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(currentSlide);
        });

        const coordinates = <?php echo json_encode($coordinates); ?>;

        if (coordinates) {
            const map = L.map('map').setView([coordinates.lat, coordinates.lng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
            }).addTo(map);

            L.marker([coordinates.lat, coordinates.lng]).addTo(map)
                .bindPopup('<?php echo htmlspecialchars($advertisement['location']); ?>')
                .openPopup();
        } else {
            console.error('Failed to fetch coordinates for the location.');
        }
    </script>
</body>
</html>
