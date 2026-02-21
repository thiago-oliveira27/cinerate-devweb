// js/adicionar.js
const editId = new URLSearchParams(window.location.search).get('editar');

// Load existing data if editing
if (editId) {
    document.getElementById('form-heading').textContent = 'EDITAR TÍTULO';
    document.getElementById('btn-submit').textContent   = 'Salvar Alterações';
    document.getElementById('titulo-edit-id').value     = editId;

    fetch(`php/titulos_api.php?id=${editId}`)
        .then(r => r.json())
        .then(item => {
            document.getElementById('titulo').value     = item.titulo || '';
            document.getElementById('tipo').value       = item.tipo || '';
            document.getElementById('genero').value     = item.genero || '';
            document.getElementById('ano').value        = item.ano || '';
            document.getElementById('sinopse').value    = item.sinopse || '';
            document.getElementById('poster_url').value = item.poster_url || '';
        });
}

document.getElementById('titulo-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    if (!Validate.tituloForm(form)) return;

    const btn  = document.getElementById('btn-submit');
    btn.disabled = true;
    btn.textContent = 'Salvando...';

    const body = {
        id:         editId || undefined,
        titulo:     document.getElementById('titulo').value,
        tipo:       document.getElementById('tipo').value,
        genero:     document.getElementById('genero').value,
        ano:        document.getElementById('ano').value,
        sinopse:    document.getElementById('sinopse').value,
        poster_url: document.getElementById('poster_url').value,
    };

    try {
        const method = editId ? 'PUT' : 'POST';
        const res    = await fetch('php/titulos_api.php', {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body)
        });
        const data = await res.json();

        if (data.success) {
            CineRate.toast(editId ? 'Título atualizado!' : 'Título adicionado!', 'success');
            const targetId = editId || data.id;
            setTimeout(() => window.location.href = `detalhe.php?id=${targetId}`, 900);
        } else {
            const err = document.getElementById('alert-error');
            err.textContent = data.msg || 'Erro ao salvar.';
            err.classList.add('show');
        }
    } catch {
        const err = document.getElementById('alert-error');
        err.textContent = 'Erro de conexão.';
        err.classList.add('show');
    } finally {
        btn.disabled = false;
        btn.textContent = editId ? 'Salvar Alterações' : 'Adicionar';
    }
});
