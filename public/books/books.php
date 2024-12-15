<?php

use App\Database\BookRepository;
use App\Utils\SweetAlertDisplay;

session_start();

require __DIR__ . "/../../vendor/autoload.php";

$books = BookRepository::fetchAll();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Ángel Martínez Otero">
    <title>Books</title>
    <!-- CDN SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- CDN FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5 antialiased">
    <section>
        <div class="flex flex-col justify-center items-center mt-8 mb-4">
            <h2 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                <span class="text-indigo-600">Books</span>
            </h2>
            <a href="../authors/authors.php" class="text-xl font-extrabold text-indigo-600 sm:text-xl md:text-xl">
                Authors
            </a>
        </div>
        <div class="mx-auto max-w-screen-2xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex justify-end mx-4 py-4 border-t dark:border-gray-700">
                    <a href="new.php" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-blue-600 dark:hover:bg-blue-700">
                        <i class="fa-solid fa-plus mr-2"></i>Add Book
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-gray-500 dark:text-gray-400">
                        <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="p-4">Title</th>
                                <th class="p-4">Synopsis</th>
                                <th class="p-4">Author</th>
                                <th class="p-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                                <tr class="border-b dark:border-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <th class="px-4 py-3 flex items-center">
                                        <img class="h-12 w-12 rounded-full mr-3" src="<?= $book["cover"] ?>" alt="<?= $book["title"] ?> book picture">
                                        <?= $book["title"] ?>
                                    </th>
                                    <th class="px-4 py-3 max-w-md">
                                        <?= $book["synopsis"] ?>
                                    </th>
                                    <th class="px-4 py-3"><?= $book["name"] . " " . $book["surname"] ?></th>
                                    <th class="px-4 py-3 flex items-center justify-center space-x-4">
                                        <a href="update.php?id=<?= $book["id"] ?>" class="text-sm text-white bg-green-700 hover:bg-green-800 font-medium rounded-lg px-3 py-2">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="delete.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $book["id"] ?>">
                                            <button type="submit" class="text-sm text-red-700 hover:text-white border border-red-700 hover:bg-red-800 font-medium rounded-lg px-3 py-2">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </th>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</body>

<?= SweetAlertDisplay::displayAlert(); ?>

</html>