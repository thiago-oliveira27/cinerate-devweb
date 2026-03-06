document
  .getElementById("cadastro-form")
  .addEventListener("submit", async (e) => {
    e.preventDefault();
    const form = e.target;
    if (!Validate.cadastroForm(form)) return;

    const btn = document.getElementById("btn-cad");
    btn.textContent = "Criando conta...";
    btn.disabled = true;

    const body = new FormData(form);

    try {
      const res = await fetch("php/cadastro_action.php", {
        method: "POST",
        body,
      });
      const data = await res.json();

      if (data.success) {
        CineRate.toast("Conta criada! Bem-vindo(a) ao CineRate!", "success");
        setTimeout(() => (window.location.href = "index.php"), 1200);
      } else {
        const err = document.getElementById("alert-error");
        err.textContent = data.msg;
        err.classList.add("show");
      }
    } catch {
      const err = document.getElementById("alert-error");
      err.textContent = "Erro de conexão. Tente novamente.";
      err.classList.add("show");
    } finally {
      btn.textContent = "Criar Conta";
      btn.disabled = false;
    }
  });
