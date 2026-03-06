function esc(str) {
  if (!str) return "";
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

async function loadPerfil() {
  const res = await fetch("php/perfil_api.php");
  const user = await res.json();

  document.getElementById("profile-nome").textContent = user.nome;
  document.getElementById("profile-email").textContent = user.email;
  document.getElementById("avatar-initials").textContent = user.nome
    ?.charAt(0)
    .toUpperCase();
  if (user.admin) {
    document.getElementById("profile-badge").innerHTML =
      '<span class="card-badge" style="color:var(--accent);border-color:var(--accent)">Admin</span>';
  }

  document.getElementById("nome").value = user.nome;
  document.getElementById("email").value = user.email;
}

async function loadMyReviews() {
  const area = document.getElementById("my-reviews-area");

  const res = await fetch("php/minhas_avaliacoes_api.php");
  const items = await res.json();

  if (!items.length) {
    area.innerHTML =
      '<p style="color:var(--text-muted)">Você ainda não fez nenhuma avaliação.</p>';
    return;
  }

  area.innerHTML = items
    .map(
      (a) => `
        <div class="comment-card">
            <div class="comment-header">
                <a href="detalhe.php?id=${a.titulo_id}" style="color:var(--text-primary);font-weight:700;text-decoration:none">
                    ${esc(a.titulo)}
                </a>
                <span class="comment-date">${CineRate.formatDate(a.criado_em)}</span>
            </div>
            <div class="comment-stars">${CineRate.starsHTML(a.nota)} (${a.nota}/5)</div>
            <p class="comment-text">${esc(a.comentario)}</p>
        </div>`,
    )
    .join("");
}

document.getElementById("perfil-form").addEventListener("submit", async (e) => {
  e.preventDefault();
  const form = e.target;

  let valid = true;
  Validate.clearAll(form);
  const nome = form.querySelector("#nome");
  const email = form.querySelector("#email");
  const senha = form.querySelector("#senha");
  const confirmar = form.querySelector("#confirmar");

  if (!Validate.minLen(nome.value, 2)) {
    Validate.showError(nome, "Nome deve ter pelo menos 2 caracteres.");
    valid = false;
  }
  if (!Validate.email(email.value)) {
    Validate.showError(email, "E-mail inválido.");
    valid = false;
  }
  if (senha.value && !Validate.minLen(senha.value, 6)) {
    Validate.showError(senha, "Senha deve ter pelo menos 6 caracteres.");
    valid = false;
  }
  if (senha.value && confirmar.value !== senha.value) {
    Validate.showError(confirmar, "As senhas não coincidem.");
    valid = false;
  }
  if (!valid) return;

  const btn = document.getElementById("btn-salvar");
  btn.disabled = true;
  btn.textContent = "Salvando...";

  try {
    const res = await fetch("php/perfil_api.php", {
      method: "PUT",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({
        nome: nome.value,
        email: email.value,
        senha: senha.value,
      }),
    });
    const data = await res.json();
    if (data.success) {
      const s = document.getElementById("alert-success");
      s.textContent = "Perfil atualizado com sucesso!";
      s.classList.add("show");
      document.getElementById("profile-nome").textContent = nome.value;
      document.getElementById("avatar-initials").textContent = nome.value
        .charAt(0)
        .toUpperCase();
      senha.value = "";
      confirmar.value = "";
      setTimeout(() => s.classList.remove("show"), 3000);
    } else {
      const err = document.getElementById("alert-error");
      err.textContent = data.msg;
      err.classList.add("show");
    }
  } finally {
    btn.disabled = false;
    btn.textContent = "Salvar Alterações";
  }
});

document.getElementById("btn-excluir").addEventListener("click", async () => {
  if (
    !confirm(
      "Tem certeza que deseja excluir sua conta? Esta ação é irreversível.",
    )
  )
    return;
  const res = await fetch("php/perfil_api.php", { method: "DELETE" });
  const data = await res.json();
  if (data.success) {
    CineRate.toast("Conta excluída.", "success");
    setTimeout(() => (window.location.href = "index.php"), 1000);
  }
});

loadPerfil();
loadMyReviews();
