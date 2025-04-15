<?php

session_start();

include ("functions.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/classes/Database.php';

use Classes\Database; 

$db = new Database();

$id = $_GET["id"];

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
        
        <?php


            // Verificar se a conexão foi estabelecida
            if ($db->getConnection()) {
                    
                $dadosSite = Database::select("SELECT * FROM generatedlinks WHERE id = ?", [$id]);

            } else {
                echo "Falha na conexão!";
            }
            ?>


            <div class="relative overflow-x-auto rounded-xl">
                <table class="w-full bg-gray-800 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded">
                    <tbody>
                    <?php


                        echo '
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center border-gray-200">

                        <th scope="row" colspan="4" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Informações dos Acessos:
                        </th>
                        </tr>';


                        echo '
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center border-gray-200">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Data
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            IP / Provedor de Internet
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Cidade / País 
                        </th>
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            Dispositivo
                        </th>
                        </tr>';

                                
                    // Verificar se a conexão foi estabelecida
                    if ($db->getConnection()) {
                            
                        $dadosAcessos = Database::select("SELECT * FROM linkaccess WHERE id = ?", [$id]);

                    } else {
                        echo "Falha na conexão!";
                    }

                    foreach ($dadosAcessos as $dado):
                        $countryCode = strtolower(countryNameToCode($dado["access_country"]));
                        $dataFormatada = date('d/m/Y H:i', strtotime($dado['access_datetime']));
                    ?>
                        <tr class="border-t border-gray-600 text-sm text-white text-left">
                            <!-- Data -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <?= $dataFormatada ?>
                            </td>
                    
                            <!-- IP -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <div class="leading-tight items-center justify-center text-center p-2">
                                    <p class="text-white"> <?= $dado['access_ip'] ?></p>
                                    <p class="text-white"> <?= $dado['access_provider'] ?></p>
                                    </div>
                                </div>
                            </td>
                    
                            <!-- Cidade / País -->
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <img src="/assets/img/w40/<?= $countryCode ?>.png" class="w-6 h-4 object-cover rounded">
                                    <div class="leading-tight p-2">
                                        <p class="text-white"><?= $dado['access_city'] ?></p>
                                        <p class="text-gray-400 text-xs"><?= $dado['access_country'] ?></p>
                                    </div>
                                </div>
                            </td>
                    
                            <!-- Dispositivo -->
                            <td class="px-4 py-3 text-gray-300 whitespace-nowrap">-</td>
                    
                        </tr>
                    <?php endforeach; ?>

                    </tbody>
                </table>
            </div>

            <div class="relative overflow-x-auto rounded-xl">

            <iframe 
                width="800" 
                height="600" 
                frameborder="0" 
                style="border:0" 
                allowfullscreen 
                src="https://www.google.com/maps?q=<?= $dado['access_lat'] ?>,<?= $dado['access_lon'] ?>&hl=pt-BR&z=14&output=embed">
            </iframe>
            
            </div>


        </div>

                    </tbody>
                </table>
            </div>





    </div>
</body>
</html>
