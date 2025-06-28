document.addEventListener("DOMContentLoaded", function() {
    // 1. Funcionalidade de expandir e recolher seções (Accordion)
    const sections = document.querySelectorAll(".about, .tela, .ajudar");
    sections.forEach(function(section) {
        const content = section.querySelector("p, ul, div, section"); // Adicionado 'section' para ser mais flexível
        if (content) {
            content.style.display = "none";
            
            const titleElement = section.querySelector('h2');
            if (titleElement) {
                const toggleIcon = document.createElement("span");
                toggleIcon.innerHTML = " &#x25BC;"; // Seta para baixo
                toggleIcon.style.float = "right";
                toggleIcon.style.transition = "transform 0.3s";
                titleElement.appendChild(toggleIcon);

                section.addEventListener("click", function() {
                    const isExpanded = content.style.display === "block";
                    content.style.display = isExpanded ? "none" : "block";
                    section.classList.toggle("expanded", !isExpanded);
                    toggleIcon.style.transform = isExpanded ? "rotate(0deg)" : "rotate(180deg)";
                });
            }
        }
    });

    // 2. Validação de Formulários Aprimorada
    const forms = document.querySelectorAll("form");
    forms.forEach(form => {
        form.addEventListener("submit", function(event) {
            const inputs = form.querySelectorAll("input[required], textarea[required]");
            let valid = true;

            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add("input-error");
                } else {
                    input.classList.remove("input-error");
                }
            });

            if (!valid) {
                event.preventDefault();
                const messageContainer = document.getElementById('message-container');
                if (messageContainer) {
                    messageContainer.textContent = "Por favor, preencha todos os campos obrigatórios.";
                    messageContainer.style.color = "red";
                    messageContainer.style.display = "block";
                } else {
                    alert("Por favor, preencha todos os campos obrigatórios.");
                }
            }
        });
    });

    // 3. Confirmação de remoção de itens
    const removeButtons = document.querySelectorAll(".remover");
    removeButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            if (!confirm("Tem certeza que deseja remover este item? Esta ação é irreversível.")) {
                event.preventDefault();
            }
        });
    });

    // 4. Barra de pesquisa dinâmica para ONGs e Oportunidades
    // Agora verifica tanto a classe .ong-list quanto .event-list
    const listsToSearch = document.querySelectorAll(".ong-list, .event-list");
    listsToSearch.forEach(list => {
        if (list) {
            const searchInput = document.createElement("input");
            searchInput.type = "text";
            searchInput.placeholder = "Pesquisar...";
            searchInput.className = "search-input";
            
            list.parentElement.insertBefore(searchInput, list);

            // Seleciona os itens da lista, que podem ser ONGs ou Eventos
            const items = list.querySelectorAll(".ong-item, .event-item");

            searchInput.addEventListener("input", function() {
                const filter = this.value.toLowerCase();
                items.forEach(item => {
                    const textContent = item.textContent.toLowerCase();
                    item.style.display = textContent.includes(filter) ? "flex" : "none"; // Alterado para 'flex' para manter o layout
                });
            });
        }
    });

    // 5. Exibição de mensagens de feedback da URL
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    if (message) {
        const messageContainer = document.getElementById('message-container');
        if (messageContainer) {
            messageContainer.textContent = decodeURIComponent(message).replace(/\+/g, ' ');
            
            // Define a cor da mensagem com base no conteúdo
            if (message.includes('sucesso') || message.includes('aprovada') || message.includes('cadastrado')) {
                messageContainer.style.color = 'green';
                messageContainer.style.borderColor = '#2ecc71';
            } else {
                messageContainer.style.color = 'red';
                messageContainer.style.borderColor = '#e74c3c';
            }
            messageContainer.style.display = 'block';
        }
    }
});