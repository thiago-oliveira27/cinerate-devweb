// js/login.js
document.getElementById('login-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    if (!Validate.loginForm(form)) return;

    const btn = document.getElementById('btn-login');
    btn.textContent = 'Entrando...';
    btn.disabled = true;

    const body = new FormData(form);

    try {
        const res  = await fetch('php/login_action.php', { method: 'POST', body });
        const data = await res.json();

        if (data.success) {
            window.location.href = 'index.php';
        } else {
            const err = document.getElementById('alert-error');
            err.textContent = data.msg;
            err.classList.add('show');
        }
    } catch {
        const err = document.getElementById('alert-error');
        err.textContent = 'Erro de conexão. Tente novamente.';
        err.classList.add('show');
    } finally {
        btn.textContent = 'Entrar';
        btn.disabled = false;
    }
});
