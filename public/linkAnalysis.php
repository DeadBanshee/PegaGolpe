<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pega Golpe</title>
    <link href="/assets/css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-400 p-4">

    <!-- LOGO E FORM -->
    <div class="flex flex-col items-center text-center w-full max-w-lg space-y-4">
        <img src="/assets/img/PegaGolpeLogoTexto.png" class="mt-6 mb-3 w-32 md:w-40">
        

            <div class="relative overflow-x-auto rounded-xl">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded">
                    <tbody>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Link de Destino:
                            </th>
                            <td class="px-6 py-4">
                                Silver
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>



    </div>


</body>
</html>
