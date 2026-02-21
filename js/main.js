// ===== CineRate - main.js =====
// Shared utilities and navbar state management

const CineRate = {
    // Toast notification system
    toast(msg, type = 'success', duration = 3000) {
        let container = document.querySelector('.toast-container-cr');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container-cr';
            document.body.appendChild(container);
        }
        const t = document.createElement('div');
        t.className = `toast-cr ${type}`;
        t.textContent = msg;
        container.appendChild(t);
        setTimeout(() => {
            t.style.opacity = '0';
            t.style.transition = 'opacity 0.3s';
            setTimeout(() => t.remove(), 300);
        }, duration);
    },

    // Stars helper
    starsHTML(rating, max = 5) {
        rating = Math.round(rating * 2) / 2;
        let html = '';
        for (let i = 1; i <= max; i++) {
            if (rating >= i) html += '★';
            else if (rating >= i - 0.5) html += '½';
            else html += '☆';
        }
        return html;
    },

    // Format date
    formatDate(dateStr) {
        const d = new Date(dateStr);
        return d.toLocaleDateString('pt-BR', { day: '2-digit', month: 'short', year: 'numeric' });
    },

    // Check if user is logged in (from session via API)
    async checkSession() {
        try {
            const res = await fetch('php/session.php');
            return await res.json();
        } catch { return { logged: false }; }
    }
};

// ===== Navbar session state =====
document.addEventListener('DOMContentLoaded', async () => {
    const session = await CineRate.checkSession();
    const navRight = document.getElementById('nav-right');
    if (!navRight) return;

    if (session.logged) {
        navRight.innerHTML = `
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                    👤 ${session.nome}
                </a>
                <ul class="dropdown-menu dropdown-menu-dark dropdown-menu-end" style="background:var(--bg-surface);border-color:var(--border)">
                    <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                    <li><a class="dropdown-item" href="adicionar.php">Adicionar Título</a></li>
                    ${session.admin ? '<li><a class="dropdown-item" href="admin.php">Painel Admin</a></li>' : ''}
                    <li><hr class="dropdown-divider" style="border-color:var(--border)"></li>
                    <li><a class="dropdown-item text-danger" href="php/logout.php">Sair</a></li>
                </ul>
            </li>`;
    } else {
        navRight.innerHTML = `
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="btn-cinerate ms-2" href="cadastro.php">Cadastrar</a></li>`;
    }
});
