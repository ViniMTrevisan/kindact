// Toggle sections on click (e.g., in index.html, usuario.html)
document.addEventListener("DOMContentLoaded", function() {
    const sections = document.querySelectorAll(".about h2, .tela h2, .ajudar h2, .publicar h3, .analisar h3");
    sections.forEach(section => {
        section.addEventListener("click", function() {
            const content = this.nextElementSibling;
            content.style.display = content.style.display === "none" ? "block" : "none";
        });
    });

    // Form validation
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function(event) {
            const inputs = form.querySelectorAll("input[required], textarea[required]");
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.style.borderColor = "red";
                } else {
                    input.style.borderColor = "";
                }
            });

            if (!valid) {
                event.preventDefault();
                alert("Por favor, preencha todos os campos obrigatórios.");
            }
        });
    });

    // Confirmation prompts for removal actions
    const removeButtons = document.querySelectorAll(".remover");
    removeButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            if (!confirm("Tem certeza que deseja remover?")) {
                event.preventDefault();
            }
        });
    });

    // Search filter for ONG list (in voluntarios_selecionando_ongs.php)
    const ongList = document.querySelector(".ong-list");
    if (ongList) {
        const searchInput = document.createElement("input");
        searchInput.type = "text";
        searchInput.placeholder = "Pesquisar ONGs...";
        searchInput.className = "search-input";
        ongList.parentElement.insertBefore(searchInput, ongList);

        searchInput.addEventListener("input", function() {
            const filter = this.value.toLowerCase();
            const items = ongList.querySelectorAll(".ong-item");

            items.forEach(item => {
                const name = item.querySelector("h3").textContent.toLowerCase();
                item.style.display = name.includes(filter) ? "block" : "none";
            });
        });
    }
});