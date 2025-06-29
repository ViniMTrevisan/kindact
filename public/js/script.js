document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('message');
    if (message) {
        const messageContainer = document.getElementById('message-container');
        if (messageContainer) {
            messageContainer.textContent = decodeURIComponent(message).replace(/\+/g, ' ');
            if (message.toLowerCase().includes('sucesso') || message.toLowerCase().includes('aprovada')) {
                messageContainer.className = 'success';
            } else {
                messageContainer.className = 'error';
            }
            messageContainer.style.display = 'block';
        }
    }
    const removeButtons = document.querySelectorAll(".remover");
    removeButtons.forEach(button => {
        button.addEventListener("click", function(event) {
            if (!confirm("Tem certeza que deseja remover este item? Esta ação é irreversível.")) {
                event.preventDefault();
            }
        });
    });

    const listsToSearch = document.querySelectorAll(".ong-list, .voluntario-list, .event-list, .opportunity-list");
    listsToSearch.forEach(list => {
        if (list) {
            const searchInput = document.createElement("input");
            searchInput.type = "text";
            searchInput.placeholder = "Pesquisar na lista...";
            searchInput.className = "search-input";
            
            list.parentElement.insertBefore(searchInput, list);

            const items = list.querySelectorAll(".ong-item, .voluntario-item, .event-item");

            searchInput.addEventListener("input", function() {
                const filter = this.value.toLowerCase();
                items.forEach(item => {
                    const textContent = item.textContent.toLowerCase();
                    if (textContent.includes(filter)) {
                        item.style.display = ""; 
                    } else {
                        item.style.display = "none"; 
                    }
                });
            });
        }
    });

});