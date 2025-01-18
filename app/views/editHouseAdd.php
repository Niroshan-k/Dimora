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


require_once '../../app/controllers/HouseController.php';

$houseController = new HouseController();
$house_id = $_GET['house_id'] ?? null;

if (!$house_id) {
    header('Location: http://localhost/Dimora/App/views/sellerDashboardw.php');
    exit;
}

$advertisement = $houseController->fetchAdvertisementById($house_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($houseController->updateAdvertisement()) {
        header('Location: http://localhost/Dimora/App/views/sellerDashboard.php');
        exit;
    } else {
        echo "<p>Failed to update advertisement. Please try again.</p>";
    }
}

if (isset($_SESSION['error']['general'])) {
    echo "<p class='error'>" . $_SESSION['error']['general'] . "</p>";
    unset($_SESSION['error']['general']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Advertisement</title>
</head>
<body>
    <?php include './layout/customerHeader.php'; ?>
    <main class="p-10">
        <h1 class="text-2xl font-bold mb-6">Edit Advertisement <?php echo $house_id; ?></h1>
        <form action="http://localhost/Dimora/App/views/editHouseAdd.php?house_id=<?php echo $house_id; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="house_id" value="<?php echo htmlspecialchars($advertisement['house_id']); ?>">
            
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($advertisement['name']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Price:</label>
            <input type="number" name="price" value="<?php echo htmlspecialchars($advertisement['price']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Type:</label>
            <input type="text" name="type" value="<?php echo htmlspecialchars($advertisement['type']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Description:</label>
            <textarea name="description" class="block w-full mb-4 p-2 border"><?php echo htmlspecialchars($advertisement['description']); ?></textarea>
            
            <label>Location:</label>
            <input type="text" name="location" value="<?php echo htmlspecialchars($advertisement['location']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Bedrooms:</label>
            <input type="number" name="bedroom" value="<?php echo htmlspecialchars($advertisement['bedroom']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Bathrooms:</label>
            <input type="number" name="bathroom" value="<?php echo htmlspecialchars($advertisement['bathroom']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Carpark:</label>
            <input type="number" name="carpark" value="<?php echo htmlspecialchars($advertisement['carpark']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Area:</label>
            <input type="number" name="area" step="0.01" value="<?php echo htmlspecialchars($advertisement['area']); ?>" class="block w-full mb-4 p-2 border">
            
            <label>Pool:</label>
            <select name="pool" class="block w-full mb-4 p-2 border">
                <option value="1" <?php echo $advertisement['pool'] ? 'selected' : ''; ?>>Yes</option>
                <option value="0" <?php echo !$advertisement['pool'] ? 'selected' : ''; ?>>No</option>
            </select>

            <label>Image:</label>
            <input type="file" name="image" class="block w-full mb-4 p-2 border">

            <button type="submit" class="bg-brown-150 text-white font-bold py-2 px-4 rounded">Save Changes</button>
        </form>
    </main>
    <?php include './layout/footer.php'; ?>
</body>
</html>
