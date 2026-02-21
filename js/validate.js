// ===== CineRate - validate.js =====
// Form validation utilities

const Validate = {
    showError(inputEl, msg) {
        const errEl = inputEl.nextElementSibling;
        if (errEl && errEl.classList.contains('form-error')) {
            errEl.textContent = msg;
            errEl.classList.add('show');
        }
        inputEl.style.borderColor = 'var(--danger)';
    },

    clearError(inputEl) {
        const errEl = inputEl.nextElementSibling;
        if (errEl && errEl.classList.contains('form-error')) {
            errEl.classList.remove('show');
        }
        inputEl.style.borderColor = '';
    },

    clearAll(form) {
        form.querySelectorAll('.form-error').forEach(e => e.classList.remove('show'));
        form.querySelectorAll('.form-control-cr').forEach(e => e.style.borderColor = '');
    },

    email(val) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    },

    notEmpty(val) {
        return val.trim().length > 0;
    },

    minLen(val, n) {
        return val.trim().length >= n;
    },

    // Returns true if valid
    loginForm(form) {
        let valid = true;
        const email = form.querySelector('#email');
        const senha = form.querySelector('#senha');
        this.clearAll(form);
        if (!this.email(email.value)) {
            this.showError(email, 'Informe um e-mail válido.');
            valid = false;
        }
        if (!this.minLen(senha.value, 1)) {
            this.showError(senha, 'Informe a senha.');
            valid = false;
        }
        return valid;
    },

    cadastroForm(form) {
        let valid = true;
        const nome = form.querySelector('#nome');
        const email = form.querySelector('#email');
        const senha = form.querySelector('#senha');
        const confirmar = form.querySelector('#confirmar');
        this.clearAll(form);
        if (!this.minLen(nome.value, 2)) {
            this.showError(nome, 'Nome deve ter pelo menos 2 caracteres.');
            valid = false;
        }
        if (!this.email(email.value)) {
            this.showError(email, 'Informe um e-mail válido.');
            valid = false;
        }
        if (!this.minLen(senha.value, 6)) {
            this.showError(senha, 'A senha deve ter pelo menos 6 caracteres.');
            valid = false;
        }
        if (confirmar && confirmar.value !== senha.value) {
            this.showError(confirmar, 'As senhas não coincidem.');
            valid = false;
        }
        return valid;
    },

    tituloForm(form) {
        let valid = true;
        const titulo = form.querySelector('#titulo');
        const genero = form.querySelector('#genero');
        const ano = form.querySelector('#ano');
        const tipo = form.querySelector('#tipo');
        this.clearAll(form);
        if (!this.minLen(titulo.value, 1)) {
            this.showError(titulo, 'Informe o título.');
            valid = false;
        }
        if (!this.notEmpty(genero.value)) {
            this.showError(genero, 'Selecione um gênero.');
            valid = false;
        }
        const anoNum = parseInt(ano.value);
        if (isNaN(anoNum) || anoNum < 1888 || anoNum > new Date().getFullYear() + 2) {
            this.showError(ano, `Informe um ano válido (1888–${new Date().getFullYear() + 2}).`);
            valid = false;
        }
        if (!this.notEmpty(tipo.value)) {
            this.showError(tipo, 'Selecione o tipo.');
            valid = false;
        }
        return valid;
    },

    avaliacaoForm(form) {
        let valid = true;
        const nota = form.querySelector('input[name="nota"]:checked');
        const comentario = form.querySelector('#comentario');
        this.clearAll(form);
        const notaErr = form.querySelector('#nota-error');
        if (!nota) {
            if (notaErr) { notaErr.textContent = 'Selecione uma nota.'; notaErr.classList.add('show'); }
            valid = false;
        }
        if (!this.minLen(comentario.value, 5)) {
            this.showError(comentario, 'Escreva um comentário (mínimo 5 caracteres).');
            valid = false;
        }
        return valid;
    }
};
