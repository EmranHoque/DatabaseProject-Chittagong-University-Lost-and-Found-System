<?php
require 'includes/db.php';
session_start();
include 'templates/header.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];


$sql = "SELECT * FROM user WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("User not found.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    
    $sql_update = "UPDATE user SET name = :name, email = :email, phone_number = :phone WHERE user_id = :user_id";
    $stmt_update = $pdo->prepare($sql_update);

    try {
        $stmt_update->execute([
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'user_id' => $user_id
        ]);
        $success = "Profile updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating profile: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Chittagong University Lost & Found</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="bg-gray-200">
    <div class="max-w-md mx-auto py-12 px-4">
        <h1 class="text-5xl font-extrabold text-gray-900 mb-6 text-center">Edit Profile</h1>
        <form action="edit_profile.php" method="POST" class="bg-white p-8 rounded-lg shadow-md">
            <?php if (isset($success)): ?>
                <div class="mb-4 text-green-500 text-sm">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="mb-4 text-red-500 text-sm">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" required value="<?= htmlspecialchars($user['name']) ?>" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" required value="<?= htmlspecialchars($user['email']) ?>" 
                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <div class="mb-6">
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" id="phone" name="phone" required value="<?= htmlspecialchars($user['phone_number']) ?>" 
                    pattern="01[3-9][0-9]{8}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
            </div>

            <div class="flex justify-center items-center">
                <button type="submit" class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-2 rounded-lg hover:opacity-90 transition duration-300">Save Changes</button>
            </div>
        </form>
    </div>
    
</body>
</html>

<?php include 'templates/footer.php'; ?>
