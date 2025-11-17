document.addEventListener('DOMContentLoaded', () => {
    const myModal = document.getElementById('myModal');
    const btnAdd = document.getElementById('btnAdd');
    const btnSave = document.getElementById('btnSave');
    const messageEl = document.getElementById('message');
    const modal = new bootstrap.Modal(myModal);

    btnAdd.addEventListener('click', () => {
        delete btnSave.dataset.id;
        messageEl.value = '';
        modal.show();
    });

    btnSave.addEventListener('click', async () => {
        const mensagem = messageEl.value.trim();
        const id = btnSave.dataset.id || null;
        const method = id ? 'PUT' : 'POST';

        if (!mensagem) {
            messageEl.focus();
            return;
        }

        btnSave.disabled = true;
        btnSave.textContent = 'Salvando...';

        try {
            const resp = await fetch('recados.php', {
                method,
                headers: { 'Content-Type': 'application/json' },
                body: id
                    ? JSON.stringify({ id, mensagem })
                    : JSON.stringify({ mensagem })
            });

            const data = await resp.json();
            if (data.ok) {
                modal.hide();
                location.reload();
            } else {
                alert(data.error);
            }
        } catch {
            alert('Erro de rede ao salvar');
        } finally {
            btnSave.disabled = false;
            btnSave.textContent = 'Salvar';
        }
    });

    document.querySelectorAll('#btnEdit, .btnEdit').forEach(btn => {
        btn.addEventListener('click', () => {
            modal.show();
            messageEl.value = btn.dataset.mensagem;
            btnSave.dataset.id = btn.dataset.id;
        });
    });

    document.querySelectorAll('.btnFavorite').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            let status = btn.dataset.status === '1' ? 0 : 1;

            btn.dataset.status = status;

            const img = btn.querySelector('img');
            img.src = status == 1 ? 'icons/star-fill.svg' : 'icons/star.svg';

            try {
                const resp = await fetch('recados.php', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id, status })
                });

                const data = await resp.json();
                if (!data.ok) {
                    alert(data.error);
                }

            } catch (e) {
                alert('Erro ao salvar favorito');
            }
        });
    });

    myModal.addEventListener('shown.bs.modal', () => messageEl.focus());
    myModal.addEventListener('hidden.bs.modal', () => {
        delete btnSave.dataset.id;
        messageEl.value = '';
    });
});
