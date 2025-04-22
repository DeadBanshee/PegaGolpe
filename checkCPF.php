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
    <title>Validador de CPF</title>
    <link href="/assets/css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="flex flex-col min-h-screen bg-gray-400">
    <main class="flex-grow flex flex-col items-center justify-center p-4">

    <!-- LOGO E FORM -->
    <div class="flex flex-col items-center text-center w-full max-w-lg space-y-4" x-data="cpfValidator()">
        <a href="index.php"><img src="/assets/img/PegaGolpeLogoTexto.png" class="mt-6 mb-3 w-32 md:w-40"></a>

        <div class="p-6 bg-gray-800 shadow-md rounded-lg w-full text-white">
            <h2 class="text-xl font-bold mb-4">Validador de CPF</h2>

            <div class="space-y-4">
                <div class="flex flex-col">
                    <label class="text-sm mb-1">Digite o CPF</label>
                    <input type="text" x-model="cpf" placeholder="Ex: 123.456.789-09"
                        class="rounded px-3 py-2 text-black" maxlength="14" @input="formatCPF" />
                </div>

                <button @click="validateCPF"
                    class="w-full py-2 bg-green-500 hover:bg-green-600 rounded-lg font-semibold text-white">
                    Validar CPF
                </button>

                <template x-if="result !== null">
                    <div class="mt-4 text-lg font-semibold"
                        :class="result ? 'text-green-400' : 'text-red-400'">
                        <span x-text="result ? 'CPF válido ✅' : 'CPF inválido ❌'"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>
    </main>

    <?php

    include("footer.php");

    ?>
</body>
</html>

<script>
function cpfValidator() {
    return {
        cpf: '',
        result: null,

        formatCPF() {
            this.cpf = this.cpf
                .replace(/\D/g, '')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d)/, '$1.$2')
                .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        },

        validateCPF() {
            const cleanCPF = this.cpf.replace(/\D/g, '');

            if (cleanCPF.length !== 11 || /^(\d)\1{10}$/.test(cleanCPF)) {
                this.result = false;
                return;
            }

            const digits = cleanCPF.split('').map(Number);

            const calcCheckDigit = (slice, factor) => {
                const sum = slice.reduce((acc, val) => acc + val * factor--, 0);
                const mod = (sum * 10) % 11;
                return mod === 10 ? 0 : mod;
            };

            const firstDigit = calcCheckDigit(digits.slice(0, 9), 10);
            const secondDigit = calcCheckDigit(digits.slice(0, 10), 11);

            this.result = firstDigit === digits[9] && secondDigit === digits[10];
        }
    };
}
</script>
