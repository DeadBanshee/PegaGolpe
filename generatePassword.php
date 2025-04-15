<?php

session_start();

include("functions.php");

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de Senhas</title>
    <link href="/assets/css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-400 p-4">

    <!-- LOGO E FORM -->
    <div class="flex flex-col items-center text-center w-full max-w-lg space-y-4" x-data="passwordGenerator()">
        <a href="index.php" ><img src="/assets/img/PegaGolpeLogoTexto.png" class="mt-6 mb-3 w-32 md:w-40"></a>

        <div class="p-6 bg-gray-800 shadow-md rounded-lg w-full text-white">
            <h2 class="text-xl font-bold mb-4">Gerador de Senhas</h2>

            <div class="space-y-4">
                <div class="flex flex-col">
                    <label class="text-sm mb-1">Número de caracteres</label>
                    <input type="number" min="4" max="50" x-model="length"
                        class="rounded px-3 py-2 text-black" />
                </div>

                <div class="grid grid-cols-2 gap-4 text-left">
                    <label><input type="checkbox" x-model="includeUppercase" class="mr-2"> Letras maiúsculas</label>
                    <label><input type="checkbox" x-model="includeLowercase" class="mr-2"> Letras minúsculas</label>
                    <label><input type="checkbox" x-model="includeNumbers" class="mr-2"> Números</label>
                    <label><input type="checkbox" x-model="includeSymbols" class="mr-2"> Símbolos</label>
                </div>

                <button @click="generatePassword"
                    class="w-full py-2 bg-green-500 hover:bg-green-600 rounded-lg font-semibold text-white">
                    Gerar Senha
                </button>

                <div class="mt-4">
                    <label class="block text-sm mb-1">Senha Gerada:</label>
                    <div class="bg-gray-700 p-3 rounded break-all select-all" x-text="password"></div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

<script>
function passwordGenerator() {
    return {
        length: 12,
        includeUppercase: true,
        includeLowercase: true,
        includeNumbers: true,
        includeSymbols: true,
        password: '',

        generatePassword() {
            let charset = '';
            const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const lower = 'abcdefghijklmnopqrstuvwxyz';
            const numbers = '0123456789';
            const symbols = '!@#$%^&*()_+{}[]|:;<>,.?/~`-=';

            if (this.includeUppercase) charset += upper;
            if (this.includeLowercase) charset += lower;
            if (this.includeNumbers) charset += numbers;
            if (this.includeSymbols) charset += symbols;

            if (charset === '') {
                this.password = 'Selecione pelo menos um tipo de caractere.';
                return;
            }

            this.password = Array.from({ length: this.length }, () =>
                charset[Math.floor(Math.random() * charset.length)]
            ).join('');
        }
    };
}
</script>
