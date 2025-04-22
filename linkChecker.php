<div x-data="linkAnalyzer()" class="bg-gray-900 text-white p-6 rounded-xl shadow-lg w-full max-w-3xl mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4">🔍 Analisador de Links Suspeitos</h2>

    <form @submit.prevent="analisar" class="flex flex-col space-y-4">
        <input type="url" x-model="link" placeholder="Cole o link aqui" required class="p-3 rounded bg-gray-800 text-white placeholder-gray-400">
        <button type="submit" class="bg-red-600 hover:bg-red-700 transition p-3 rounded font-bold">Analisar</button>
    </form>

    <template x-if="resultado">
        <div class="bg-gray-800 mt-6 p-4 rounded space-y-2">
            <p><strong>🔗 URL Final:</strong> <span x-text="resultado.urlFinal"></span></p>
            <p><strong>📍 IP:</strong> <span x-text="resultado.ip"></span></p>
            <p><strong>🌎 País:</strong> <span x-text="resultado.pais"></span></p>
            <p><strong>💡 Provedor:</strong> <span x-text="resultado.isp"></span></p>
            <p><strong>🔒 SSL válido:</strong> <span x-text="resultado.ssl"></span></p>
            <p><strong>⚠️ Similaridade com domínios famosos:</strong> <span x-text="resultado.similaridade + '%'"></span></p>
        </div>
    </template>

    <template x-if="erro">
        <div class="bg-red-800 mt-6 p-4 rounded">
            <p x-text="erro"></p>
        </div>
    </template>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script>
function linkAnalyzer() {
    return {
        link: '',
        resultado: null,
        erro: null,
        async analisar() {
            this.resultado = null;
            this.erro = null;
            try {
                const form = new FormData();
                form.append('link', this.link);

                const res = await fetch('analisarLink.php', {
                    method: 'POST',
                    body: form
                });

                const data = await res.json();
                if (data.error) {
                    this.erro = data.error;
                } else {
                    this.resultado = data;
                }
            } catch (e) {
                this.erro = 'Erro ao conectar com o servidor.';
            }
        }
    }
}
</script>
