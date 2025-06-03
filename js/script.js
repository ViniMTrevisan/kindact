document.addEventListener("DOMContentLoaded", function() {
    // expande e recolhe seções de informações
    const sections = document.querySelectorAll(".about, .tela, .ajudar");
    sections.forEach(function(section) {
        const content = section.querySelector("p, ul");
        if (content) {
            content.style.display = "none";
            section.addEventListener("click", function() {
                if (content.style.display === "none") {
                    content.style.display = "block";
                    section.classList.add("expanded");
                } else {
                    content.style.display = "none";
                    section.classList.remove("expanded");
                }
            });
        }
    });

// Valida os Formulários
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
//confirma a remoção de itens
    const removeButtons = document.querySelectorAll(".remover");
    removeButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            if (!confirm("Tem certeza que deseja remover?")) {
                event.preventDefault();
            }
        });
    });
// barra de pesquisa para ONGs
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