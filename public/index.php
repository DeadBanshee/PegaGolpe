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

<body class="flex flex-col min-h-screen bg-gray-400">
    <main class="flex-grow flex flex-col items-center justify-center p-4">

    <!-- LOGO E FORM -->
    <div class="flex flex-col items-center text-center w-full max-w-lg space-y-4">
        <img src="assets/img/PegaGolpeLogoTexto.png" class="mt-6 mb-3 w-32 md:w-40">
        
        <div class="p-6 bg-gray-800 shadow-md rounded-lg w-full">
            <p id="result" class="mt-2 mb-2 text-sm font-semibold text-red-500"></p> <!-- Message output -->
            
            <form id="urlForm" action="../routes.php" method="POST" class="w-full flex flex-col sm:flex-row gap-3">

                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                
                <input type="hidden" name="function" value="generateLink">
                
                <input type="text" name="urlInput" id="urlInput" name="valor" placeholder="Digite o link aqui..." 
                    class="flex-grow h-12 p-3 rounded-lg bg-gray-200 text-gray-900 focus:outline-none focus:ring-2 focus:ring-green-600 w-full text-lg">
                
                <button type="submit"
                    class="h-12 px-8 min-w-[140px] rounded-lg bg-green-500 text-white font-semibold text-lg hover:bg-green-600 transition whitespace-nowrap">
                    Criar Link
                </button>
            </form>

        </div>
    </div>

    <!-- INSTRUÇÕES -->
    <div class="flex flex-col items-center text-center w-full max-w-lg mt-6 space-y-4">
        <div class="p-6 bg-gray-800 shadow-md rounded-lg w-full">
            <h2 class="text-white font-bold font-mono"> Instruções </h2>
            <p class="text-white font-mono text-sm">
                Coloque acima o link de qualquer site, criaremos um link falso para enviar para o suspeito.<br>
                Links devem estar no seguinte formato: https://exemplo.com.br
            </p>
        </div>
    </div>
    </main>

    <!-- OUTRAS FERRAMENTAS -->
    <div class="flex flex-col items-center text-center space-y-4 bg-gray-800 mt-8 w-full py-10 px-4">
        <h1 class="text-white font-mono font-bold text-3xl"> Outras Ferramentas </h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 w-full max-w-7xl">
            <!-- Ferramenta 1 -->
            <a href="https://www.google.com">
                <div class="flex flex-col items-center bg-gray-700 rounded-lg p-4 shadow-md">
                    <img src="/assets/img/personal-information.png" class="w-16 md:w-20 m-auto">
                    <h2 class="mt-4 text-gray-100 font-bold">Conferir CPF</h2>
                    <p class="text-gray-300 mb-4">Confira se um CPF é válido com base no cálculo dos dígitos verificadores.</p>
                </div>
            </a>

            <a href="https://www.google.com">
                <div class="flex flex-col items-center bg-gray-700 rounded-lg p-4 shadow-md">
                    <img src="/assets/img/office-building.png" class="w-16 md:w-20 m-auto">
                    <h2 class="mt-4 text-gray-100 font-bold">Conferir CNPJ</h2>
                    <p class="text-gray-300 mb-4">Confira se um CPF é válido com base no cálculo dos dígitos verificadores.</p>
                </div>
            </a>

            <!-- Ferramenta 2 -->
            <a href="https://www.google.com">
                <div class="flex flex-col items-center bg-gray-700 rounded-lg p-4 shadow-md">
                    <img src="/assets/img/list.png" class="w-16 md:w-20 m-auto">
                    <h2 class="mt-4 text-gray-100 font-bold">Lista de Golpes</h2>
                    <p class="text-gray-300 mb-4">Confira se um CPF é válido com base no cálculo dos dígitos verificadores.</p>
                </div>
            </a>

            <!-- Ferramenta 3 -->
            <a href="https://www.google.com">
                <div class="flex flex-col items-center bg-gray-700 rounded-lg p-4 shadow-md">
                    <img src="/assets/img/contacts.png" class="w-16 md:w-20 m-auto">
                    <h2 class="mt-4 text-gray-100 font-bold">Números de Golpistas/SPAM</h2>
                    <p class="text-gray-300 mb-4">Confira se um CPF é válido com base no cálculo dos dígitos verificadores.</p>
                </div>
            </a>

            <a href="https://www.google.com">
                <div class="flex flex-col items-center bg-gray-700 rounded-lg p-4 shadow-md">
                    <img src="/assets/img/padlock.png" class="w-16 md:w-20 m-auto">
                    <h2 class="mt-4 text-gray-100 font-bold">Gerador de Senhas</h2>
                    <p class="text-gray-300 mb-4">Crie senhas fortes e seguras com o gerador do Pega Golpe.</p>
                </div>
            </a>

            <a href="https://www.google.com">
                <div class="flex flex-col items-center bg-gray-700 rounded-lg p-4 shadow-md">
                    <img src="/assets/img/contacts.png" class="w-16 md:w-20 m-auto">
                    <h2 class="mt-4 text-gray-100 font-bold">Números de Golpistas/SPAM</h2>
                    <p class="text-gray-300 mb-4">Confira se um CPF é válido com base no cálculo dos dígitos verificadores.</p>
                </div>
            </a>

            
        </div>



    </div>

    
    
    <script>
document.getElementById("urlForm").addEventListener("submit", function(event) {
    const input = document.getElementById("urlInput").value.trim();
    const result = document.getElementById("result");

    if (input === "" || !isValidURL(input)) {
        result.textContent = "❌ Insira um link válido.";
        result.style.color = "red";
        event.preventDefault(); // Prevent form submission
    } else {
        result.textContent = "✅ Tudo Certo!";
        result.style.color = "green";
        // Form will submit normally
    }
});

function isValidURL(string) {
    try {
        const url = new URL(string);
        return url.protocol === "http:" || url.protocol === "https:";
    } catch (_) {
        return false;
    }
}
</script>


</body>
</html>
