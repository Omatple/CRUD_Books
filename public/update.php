<?php

use App\Database\Author;
use App\Database\Book;
use App\Utils\BookValidator;
use App\Utils\InputValidator;
use App\Utils\Navigation;
use App\Utils\SessionErrorDisplay;

session_start();

require __DIR__ . "/../vendor/autoload.php";
$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id || !$book = Book::getBookById($id)) Navigation::redirectTo("books.php");
if (isset($_POST["title"])) {
    $title = InputValidator::sanitize($_POST["title"]);
    $synopsis = InputValidator::sanitize($_POST["synopsis"]);
    $authorId = (int) InputValidator::sanitize($_POST["authorId"]);
    $imageData = $_FILES["cover"];
    $imageCodeError = $imageData["error"];
    $imageTmpName = $imageData["tmp_name"];
    $imageName = $imageData["name"];
    $cover = $book["cover"];
    $hasErrors = false;
    if (!BookValidator::isValidTitle($title)) $hasErrors = true;
    if (!$hasErrors && !BookValidator::isTitleUnique($title, $id)) $hasErrors = true;
    if (!BookValidator::isValidSynopsis($synopsis)) $hasErrors = true;
    if (!BookValidator::isValidAuthorId($authorId)) $hasErrors = true;
    if (BookValidator::isSentAnyFile($imageCodeError) && !BookValidator::isValidCover($imageData)) $hasErrors = true;
    if ($hasErrors) Navigation::redirectTo("update.php?id=" . $book["id"]);
    if (
        BookValidator::isSentAnyFile($imageCodeError) &&
        !BookValidator::isMoveNewCover($imageTmpName, $cover = BookValidator::getUniqueCoverName($imageName))
    ) Navigation::redirectTo("update.php?id=" . $book["id"]);
    (new Book)
        ->setTitle($title)
        ->setSynopsis($synopsis)
        ->setCover("img/books/" . basename($cover))
        ->setAuthor_id($authorId)
        ->update($id);
    if (BookValidator::isSentAnyFile($imageCodeError)) BookValidator::deleteOldCover($book["cover"]);
    $_SESSION["message"] = "Book updated succesly.";
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
    <title>Edit Book</title>
    <script src="../scripts/handlerPreviewImage.js"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 antialiased">
    <section class="bg-white dark:bg-gray-900">
        <div class="px-4 mx-auto max-w-2xl lg:py-16">
            <h2 class="mb-4 text-2xl font-bold text-gray-900 dark:text-white">Edit a Book</h2>
            <form action="<?= $_SERVER["PHP_SELF"] . "?id=" . $id ?>" method="POST" enctype="multipart/form-data" novalidate>
                <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                    <div class="sm:col-span-2">
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" name="title" id="title" value="<?= $book["title"] ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="Enter title book" required>
                        <?= SessionErrorDisplay::showError("title") ?>
                    </div>
                    <div>
                        <div class="flex items-center space-x-6 pt-2">
                            <div class="shrink-0">
                                <img id="preview_cover" class="h-16 w-16 object-cover rounded-full" src="<?= $book["cover"] ?>" alt="Cover preview">
                            </div>
                            <label class="block">
                                <span class="sr-only">Choose profile photo</span>
                                <input type="file" accept="image/*" id="cover" name="cover" oninput="displayInPreview(this, 'preview_cover');" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100">
                            </label>
                        </div>
                        <?= SessionErrorDisplay::showError("image") ?>
                    </div>
                    <div>
                        <label for="authorId" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Author</label>
                        <select id="authorId" name="authorId" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                            <option value="" disabled>Select an author</option>
                            <?php
                            foreach (Author::read() as $author):
                                $selected = ($author["id"] === $book["author_id"]) ? ' selected' : "";
                            ?>
                                <option value="<?= $author["id"] ?>" <?= $selected ?>><?= $author["name"] . " " . $author["surname"] ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?= SessionErrorDisplay::showError("authorId") ?>
                    </div>
                    <div class="sm:col-span-2">
                        <label for="synopsis" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Synopsis</label>
                        <textarea id="synopsis" name="synopsis" rows="8" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Synopsis here"><?= $book["synopsis"] ?></textarea>
                        <?= SessionErrorDisplay::showError("synopsis") ?>
                    </div>
                </div>
                <div class="mt-12">
                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg focus:ring-4 focus:ring-blue-200 dark:focus:ring-blue-900 hover:bg-blue-800"><i class="fa-solid fa-user-plus mr-2"></i>Edit Book</button>
                    <button type="reset" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-yellow-700 rounded-lg focus:ring-4 focus:ring-yellow-200 dark:focus:ring-yellow-900 hover:bg-yellow-800 ml-4"><i class="fa-regular fa-window-restore mr-2"></i>Reset</button>
                    <a href="books.php" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-center text-white bg-red-700 rounded-lg focus:ring-4 focus:ring-red-200 dark:focus:ring-red-900 hover:bg-red-800 ml-4"><i class="fa-solid fa-backward mr-2"></i>Back</a>
                </div>
            </form>
        </div>
    </section>
</body>

</html>