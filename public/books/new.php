<?php

use App\Database\AuthorRepository;
use App\Database\BookRepository;
use App\Utils\BookValidator;
use App\Utils\ImageConstants;
use App\Utils\InputValidator;
use App\Utils\Navigation;
use App\Utils\SessionErrorDisplay;

session_start();

require __DIR__ . "/../../vendor/autoload.php";

if (isset($_POST["title"])) {
    $title = InputValidator::sanitize($_POST["title"]);
    $synopsis = InputValidator::sanitize($_POST["synopsis"]);
    $authorId = (int) InputValidator::sanitize($_POST["authorId"]);
    $imageData = $_FILES["cover"];
    $imageCodeError = $imageData["error"];
    $imageTmpName = $imageData["tmp_name"];
    $imageName = $imageData["name"];
    $cover = ImageConstants::DEFAULT_BOOK_COVER;
    $hasErrors = false;

    if (!BookValidator::isValidTitle($title)) $hasErrors = true;
    if (!$hasErrors && !BookValidator::isTitleUnique($title)) $hasErrors = true;
    if (!BookValidator::isValidSynopsis($synopsis)) $hasErrors = true;
    if (!BookValidator::isValidAuthorId($authorId)) $hasErrors = true;
    if (BookValidator::isFileUploaded($imageCodeError) && !BookValidator::isValidCover($imageData)) $hasErrors = true;

    if ($hasErrors) Navigation::refreshPage();

    if (
        BookValidator::isFileUploaded($imageCodeError) &&
        !BookValidator::moveNewCover($imageTmpName, $cover = BookValidator::generateUniqueCoverName($imageName))
    ) Navigation::refreshPage();

    (new BookRepository)
        ->setBookTitle($title)
        ->setBookSynopsis($synopsis)
        ->setBookCover("img/" . basename($cover))
        ->setAuthorId($authorId)
        ->save();

    $_SESSION["message"] = "Book created successfully.";
    Navigation::redirectTo("books.php");
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Ángel Martínez Otero">
    <title>New Book</title>
    <script src="../js/handlerPreviewImage.js"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Add a New Book</h2>
            <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter book title" required>
                        <?= SessionErrorDisplay::displayError("title") ?>
                    </div>
                    <div>
                        <div class="flex items-center space-x-6 pt-2">
                            <div class="shrink-0">
                                <img id="preview_cover" class="h-16 w-16 object-cover rounded-full" src="<?= "img/" . ImageConstants::DEFAULT_BOOK_COVER ?>" alt="Cover preview">
                            </div>
                            <label class="block">
                                <span class="sr-only">Choose cover photo</span>
                                <input type="file" accept="image/*" id="cover" name="cover" oninput="displayInPreview(this, 'preview_cover');" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                            </label>
                        </div>
                        <?= SessionErrorDisplay::displayError("image") ?>
                    </div>
                    <div>
                        <label for="authorId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Author</label>
                        <select id="authorId" name="authorId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            <option value="" selected>Select an author</option>
                            <?php foreach (AuthorRepository::fetchAll() as $author): ?>
                                <option value="<?= $author["id"] ?>"><?= $author["name"] . " " . $author["surname"] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= SessionErrorDisplay::displayError("authorId") ?>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="synopsis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Synopsis</label>
                        <textarea id="synopsis" name="synopsis" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Synopsis here"></textarea>
                        <?= SessionErrorDisplay::displayError("synopsis") ?>
                    </div>
                </div>
                <div class="mt-12">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"><i class="fa-solid fa-user-plus mr-2"></i>Add Book</button>
                    <button type="reset" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900 hover:bg-yellow-800 ml-4"><i class="fa-regular fa-window-restore mr-2"></i>Reset</button>
                    <a href="books.php" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800 ml-4"><i class="fa-solid fa-backward mr-2"></i>Back</a>
                </div>
            </form>
        </div>
    </section>
</body>

</html>