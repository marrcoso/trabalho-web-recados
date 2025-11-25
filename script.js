class ModalRecado {
    constructor() {
        this.modalEl = document.getElementById('myModal');
        this.btnAdd = document.getElementById('btnAdd');
        this.btnSave = document.getElementById('btnSave');
        this.btnDelete = document.getElementById('btnDelete');
        this.messageEl = document.getElementById('message');

        this.modal = new bootstrap.Modal(this.modalEl);

        this.initEvents();
    }

    initEvents() {
        this.btnAdd.addEventListener('click', () => this.openForNew());
        this.btnSave.addEventListener('click', () => this.save());
        this.btnDelete.addEventListener('click', () => this.delete());
        document.querySelectorAll('#btnEdit, .btnEdit')
            .forEach(btn => btn.addEventListener('click', (e) => this.openForEdit(e.target)));
        this.modalEl.addEventListener('shown.bs.modal', () => this.messageEl.focus());
        this.modalEl.addEventListener('hidden.bs.modal', () => this.reset());
    }

    openForNew() {
        delete this.btnSave.dataset.id;
        this.messageEl.value = '';
        this.btnDelete.disabled = true;
        this.modal.show();
    }

    openForEdit(btn) {
        this.btnSave.dataset.id = btn.dataset.id;
        this.messageEl.value = btn.dataset.mensagem;
        this.btnDelete.disabled = false;
        this.modal.show();
    }

    async save() {
        const mensagem = this.messageEl.value.trim();
        const id = this.btnSave.dataset.id || null;

        if (!mensagem) {
            this.messageEl.focus();
            return;
        }

        const method = id ? "PUT" : "POST";

        this.btnSave.disabled = true;
        this.btnSave.textContent = "Salvando...";

        try {
            const resp = await fetch("recados.php", {
                method,
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(id ? { id, mensagem } : { mensagem })
            });

            const data = await resp.json();

            if (data.ok) {
                this.modal.hide();
                location.reload();
            } else {
                alert(data.error || "Erro ao salvar.");
            }
        } catch {
            alert("Erro de rede ao salvar.");
        } finally {
            this.btnSave.disabled = false;
            this.btnSave.textContent = "Salvar";
        }
    }

    async delete() {
        const id = this.btnSave.dataset.id;

        if (!id) {
            alert("Nenhum recado selecionado para excluir.");
            return;
        }

        this.btnDelete.disabled = true;
        this.btnDelete.textContent = "Excluindo...";

        try {
            const resp = await fetch("recados.php", {
                method: "DELETE",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ id })
            });

            const data = await resp.json();

            if (data.ok) {
                this.modal.hide();
                location.reload();
            } else {
                alert(data.error || "Erro ao excluir.");
            }
        } catch {
            alert("Erro de rede ao excluir.");
        } finally {
            this.btnDelete.disabled = false;
            this.btnDelete.textContent = "Excluir";
        }
    }

    reset() {
        delete this.btnSave.dataset.id;
        this.messageEl.value = '';
        this.btnDelete.disabled = true;
    }
}



class FavoritosManager {
    constructor() {
        this.favoritosSection = document.querySelector("#favoritosContainer");
        this.outrosSection = document.querySelector("#outrosContainer");

        this.initEvents();
    }

    initEvents() {
        document.querySelectorAll(".btnFavorite")
            .forEach(btn => btn.addEventListener("click", () => this.toggle(btn)));
    }

    async toggle(btn) {
        const id = btn.dataset.id;
        const oldStatus = btn.dataset.status;
        const newStatus = oldStatus === "1" ? 0 : 1;

        const ok = await this.updateStatus(id, newStatus);
        if (!ok) {
            alert("Erro ao atualizar favorito");
            return;
        }

        btn.dataset.status = newStatus;
        btn.querySelector("img").src = newStatus
            ? "icons/star-fill.svg"
            : "icons/star.svg";

        this.moveCard(btn, newStatus);
        this.cleanupEmptyFavorites();
    }

    async updateStatus(id, status) {
        const resp = await fetch("src/toggle_favorite.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `id=${id}&status=${status}`
        });

        return (await resp.text()) === "1";
    }

    moveCard(btn, newStatus) {
        const card = btn.closest(".col");

        if (!this.favoritosSection) this.createFavoritesSection();

        if (newStatus == 1) {
            this.favoritosSection.prepend(card);
        } else {
            this.outrosSection.prepend(card);
        }
    }

    createFavoritesSection() {
        const outrosTitle = this.outrosSection.previousElementSibling;
        const parent = outrosTitle.parentNode;

        const favTitle = document.createElement("div");
        favTitle.className = "mb-3 d-flex align-items-center justify-content-between";
        favTitle.innerHTML = "<h3 class='m-0'>Favoritos</h3>";

        this.favoritosSection = document.createElement("div");
        this.favoritosSection.id = "favoritosContainer";
        this.favoritosSection.className = "row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 mb-4";

        parent.insertBefore(favTitle, outrosTitle);
        parent.insertBefore(this.favoritosSection, outrosTitle);
    }

    cleanupEmptyFavorites() {
        if (!this.favoritosSection) return;

        if (this.favoritosSection.querySelectorAll(".col").length === 0) {
            const title = this.favoritosSection.previousElementSibling;
            if (title?.querySelector("h3")?.textContent === "Favoritos") {
                title.remove();
            }
            this.favoritosSection.remove();
            this.favoritosSection = null;
        }
    }
}



document.addEventListener("DOMContentLoaded", () => {
    new ModalRecado();
    new FavoritosManager();
});
