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
    <title>Validador de CNPJ</title>
    <link href="/assets/css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>

<body class="flex flex-col items-center justify-center min-h-screen bg-gray-400 p-4">

    <!-- LOGO E FORM -->
    <div class="flex flex-col items-center text-center w-full max-w-lg space-y-4" x-data="cnpjValidator()">
        <a href="index.php"><img src="/assets/img/PegaGolpeLogoTexto.png" class="mt-6 mb-3 w-32 md:w-40"></a>

        <div class="p-6 bg-gray-800 shadow-md rounded-lg w-full text-white">
            <h2 class="text-xl font-bold mb-4">Validador de CNPJ</h2>

            <div class="space-y-4">
                <div class="flex flex-col">
                    <label class="text-sm mb-1">Digite o CNPJ</label>
                    <input type="text" x-model="cnpj" placeholder="Ex: 12.345.678/0001-95"
                        class="rounded px-3 py-2 text-black" maxlength="18" @input="formatCNPJ" />
                </div>

                <button @click="validateCNPJ"
                    class="w-full py-2 bg-green-500 hover:bg-green-600 rounded-lg font-semibold text-white">
                    Validar CNPJ
                </button>

                <template x-if="result !== null">
                    <div class="mt-4 text-lg font-semibold"
                        :class="result ? 'text-green-400' : 'text-red-400'">
                        <span x-text="result ? 'CNPJ válido ✅' : 'CNPJ inválido ❌'"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

</body>
</html>

<script>
function cnpjValidator() {
    return {
        cnpj: '',
        result: null,

        formatCNPJ() {
            this.cnpj = this.cnpj
                .replace(/\D/g, '')
                .replace(/^(\d{2})(\d)/, '$1.$2')
                .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
                .replace(/\.(\d{3})(\d)/, '.$1/$2')
                .replace(/(\d{4})(\d)/, '$1-$2');
        },

        validateCNPJ() {
            const cnpj = this.cnpj.replace(/\D/g, '');

            if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) {
                this.result = false;
                return;
            }

            const calcCheckDigit = (base, multipliers) => {
                const sum = base.reduce((acc, val, i) => acc + val * multipliers[i], 0);
                const mod = sum % 11;
                return mod < 2 ? 0 : 11 - mod;
            };

            const digits = cnpj.split('').map(Number);
            const base = digits.slice(0, 12);

            const firstCheckDigit = calcCheckDigit(base, [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);
            const secondCheckDigit = calcCheckDigit([...base, firstCheckDigit], [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2]);

            this.result = firstCheckDigit === digits[12] && secondCheckDigit === digits[13];
        }
    };
}
</script>
