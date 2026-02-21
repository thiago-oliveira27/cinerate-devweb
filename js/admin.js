// js/admin.js
function esc(s) {
    if (!s) return '';
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

// Tab switching
document.querySelectorAll('[data-tab]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('[data-tab]').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        const tab = btn.dataset.tab;
        document.getElementById('tab-usuarios').style.display = tab === 'usuarios' ? '' : 'none';
        document.getElementById('tab-titulos').style.display  = tab === 'titulos'  ? '' : 'none';
        if (tab === 'titulos') loadTitulos();
    });
});

// ---- USERS ----
async function loadUsers() {
    const res   = await fetch('php/admin_api.php');
    const users = await res.json();

    document.getElementById('users-loader').style.display      = 'none';
    document.getElementById('users-table-wrap').style.display  = '';

    const tbody = document.getElementById('users-tbody');
    tbody.innerHTML = users.map(u => `
        <tr id="user-row-${u.id}">
            <td>${u.id}</td>
            <td>${esc(u.nome)}</td>
            <td>${esc(u.email)}</td>
            <td>${u.admin ? '⭐ Sim' : 'Não'}</td>
            <td>${u.titulos_cadastrados}</td>
            <td>${u.avaliacoes}</td>
            <td>${CineRate.formatDate(u.criado_em)}</td>
            <td>
                <button class="btn-delete-user" data-id="${u.id}"
                    style="background:none;border:1px solid var(--danger);color:var(--danger);
                           padding:4px 10px;border-radius:4px;cursor:pointer;font-size:0.8rem">
                    Excluir
                </button>
            </td>
        </tr>`).join('');

    tbody.querySelectorAll('.btn-delete-user').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Excluir este usuário?')) return;
            const res  = await fetch(`php/admin_api.php?id=${btn.dataset.id}`, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) {
                document.getElementById(`user-row-${btn.dataset.id}`)?.remove();
                CineRate.toast('Usuário excluído.', 'success');
            } else {
                CineRate.toast(data.msg, 'error');
            }
        });
    });
}

// ---- TITLES ----
async function loadTitulos() {
    document.getElementById('titulos-loader').style.display     = '';
    document.getElementById('titulos-table-wrap').style.display = 'none';

    const res    = await fetch('php/titulos_api.php');
    const items  = await res.json();

    document.getElementById('titulos-loader').style.display     = 'none';
    document.getElementById('titulos-table-wrap').style.display = '';

    const tipoLabel = { filme: 'Filme', serie: 'Série', documentario: 'Documentário' };
    const tbody = document.getElementById('titulos-tbody');
    tbody.innerHTML = items.map(t => `
        <tr id="titulo-row-${t.id}">
            <td>${t.id}</td>
            <td><a href="detalhe.php?id=${t.id}" style="color:var(--accent)">${esc(t.titulo)}</a></td>
            <td>${tipoLabel[t.tipo] || t.tipo}</td>
            <td>${esc(t.genero)}</td>
            <td>${t.ano}</td>
            <td>${t.nota_media ? Number(t.nota_media).toFixed(1) : '—'}</td>
            <td style="display:flex;gap:6px">
                <a href="adicionar.php?editar=${t.id}" class="btn-outline-cinerate" style="padding:4px 10px;font-size:0.8rem">Editar</a>
                <button class="btn-delete-titulo" data-id="${t.id}"
                    style="background:none;border:1px solid var(--danger);color:var(--danger);
                           padding:4px 10px;border-radius:4px;cursor:pointer;font-size:0.8rem">
                    Excluir
                </button>
            </td>
        </tr>`).join('');

    tbody.querySelectorAll('.btn-delete-titulo').forEach(btn => {
        btn.addEventListener('click', async () => {
            if (!confirm('Excluir este título e todas suas avaliações?')) return;
            const res  = await fetch(`php/titulos_api.php?id=${btn.dataset.id}`, { method: 'DELETE' });
            const data = await res.json();
            if (data.success) {
                document.getElementById(`titulo-row-${btn.dataset.id}`)?.remove();
                CineRate.toast('Título excluído.', 'success');
            } else {
                CineRate.toast(data.msg, 'error');
            }
        });
    });
}

// Init
loadUsers();
