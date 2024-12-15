<?php

namespace App\Utils;

class SweetAlertDisplay
{
    public static function displayAlert(): void
    {
        if ($alertMessage = $_SESSION["message"] ?? false) {
            echo <<<HTML
                <script>
                    Swal.fire({
                        position: "center",
                        icon: "success",
                        title: "$alertMessage",
                        showConfirmButton: false,
                        timer: 2000
                    });
                </script>
            HTML;
            unset($_SESSION["message"]);
        }
    }
}
