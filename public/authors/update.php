<?php

use App\Database\AuthorRepository;
use App\Utils\AuthorValidator;
use App\Utils\Country;
use App\Utils\InputValidator;
use App\Utils\Navigation;
use App\Utils\SessionErrorDisplay;

session_start();

require __DIR__ . "/../../vendor/autoload.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || !$author = AuthorRepository::fetchById($id)) {
    Navigation::redirectTo("authors.php");
}

if (isset($_POST["name"])) {
    $name = InputValidator::sanitize($_POST["name"]);
    $surname = InputValidator::sanitize($_POST["surname"]);
    $country = InputValidator::sanitize($_POST["country"]);
    $imageData = $_FILES["image"];
    $imageCodeError = $imageData["error"];
    $imageTmpName = $imageData["tmp_name"];
    $imageName = $imageData["name"];
    $image = $author["image"];
    $hasErrors = false;

    if (!AuthorValidator::isValidName($name)) $hasErrors = true;
    if (!AuthorValidator::isValidSurname($surname)) $hasErrors = true;
    if (!$hasErrors && !AuthorValidator::isUniqueFullName($name, $surname, $id)) $hasErrors = true;
    if (!AuthorValidator::isValidCountry($country)) $hasErrors = true;
    if (AuthorValidator::isFileUploaded($imageCodeError) && !AuthorValidator::isValidImage($imageData)) $hasErrors = true;

    if ($hasErrors) {
        Navigation::redirectTo("update.php?id=" . $author["id"]);
    }

    if (
        AuthorValidator::isFileUploaded($imageCodeError) &&
        !AuthorValidator::moveImage($imageTmpName, $image = AuthorValidator::generateUniqueImageName($imageName))
    ) {
        Navigation::redirectTo("update.php?id=" . $author["id"]);
    }

    (new AuthorRepository)
        ->setFirstName($name)
        ->setLastName($surname)
        ->setProfileImage("img/" . basename($image))
        ->setOriginCountry($country)
        ->updateById($id);

    if ($author["image"] !== $image) {
        AuthorValidator::deleteOldImage($author["image"]);
    }

    $_SESSION["message"] = "Author updated successfully.";
    Navigation::redirectTo("authors.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Ángel Martínez Otero">
    <title>Edit Author</title>
    <script src="../js/handlerPreviewImage.js"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Edit Author</h2>
            <form action="<?= $_SERVER["PHP_SELF"] . "?id=" . $id ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Name</label>
                        <input type="text" name="name" id="name" value="<?= $author["name"] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter author name" required>
                        <?= SessionErrorDisplay::displayError("name") ?>
                        <?= SessionErrorDisplay::displayError("fullName") ?>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="surname" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Surname</label>
                        <input type="text" name="surname" id="surname" value="<?= $author["surname"] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter author surname" required>
                        <?= SessionErrorDisplay::displayError("surname") ?>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="country" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Country</label>
                        <input list="countries" name="country" id="country" value="<?= $author["country"] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                        <datalist id="countries">
                            <?php foreach (Country::cases() as $country): ?>
                                <option value="<?= $country->toString() ?>"><?= $country->toString() ?></option>
                            <?php endforeach; ?>
                        </datalist>
                        <?= SessionErrorDisplay::displayError("country") ?>
                    </div>
                    <div>
                        <div class="flex items-center space-x-6 pt-2">
                            <div class="shrink-0">
                                <img id="preview_cover" class="h-16 w-16 object-cover rounded-full" src="<?= $author["image"] ?>" alt="Author preview">
                            </div>
                            <label class="block">
                                <span class="sr-only">Choose profile photo</span>
                                <input type="file" accept="image/*" id="image" name="image" oninput="displayInPreview(this, 'preview_cover');" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                            </label>
                        </div>
                        <?= SessionErrorDisplay::displayError("image") ?>
                    </div>
                </div>
                <div class="mt-12">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"><i class="fa-solid fa-user-plus mr-2"></i>Edit Author</button>
                    <button type="reset" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900 hover:bg-yellow-800 ml-4"><i class="fa-regular fa-window-restore mr-2"></i>Reset</button>
                    <a href="authors.php" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800 ml-4"><i class="fa-solid fa-backward mr-2"></i>Back</a>
                </div>
            </form>
        </div>
    </section>
</body>

</html>