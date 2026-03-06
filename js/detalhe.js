const urlId = new URLSearchParams(window.location.search).get("id");

function esc(str) {
  if (!str) return "";
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

async function loadDetail() {
  if (!urlId) {
    window.location.href = "index.php";
    return;
  }

  try {
    const [itemRes, sessionRes] = await Promise.all([
      fetch(`php/titulos_api.php?id=${urlId}`),
      fetch("php/session.php"),
    ]);
    const item = await itemRes.json();
    const session = await sessionRes.json();

    if (item.error) {
      window.location.href = "index.php";
      return;
    }

    document.title = `CineRate — ${item.titulo}`;

    const posterArea = document.getElementById("poster-area");
    posterArea.innerHTML = item.poster_url
      ? `<img class="detail-poster" src="${esc(item.poster_url)}" alt="${esc(item.titulo)}">`
      : `<div style="font-size:6rem;line-height:1">🎬</div>`;

    const tipoLabel = {
      filme: "Filme",
      serie: "Série",
      documentario: "Documentário",
    };
    document.getElementById("detail-badges").innerHTML = `
            <span class="badge-genre">${esc(item.genero)}</span>
            <span class="badge-type">${tipoLabel[item.tipo] || item.tipo}</span>
            <span class="badge-type">${item.ano}</span>`;

    document.getElementById("detail-title").textContent = item.titulo;
    document.getElementById("detail-sub").textContent =
      `Cadastrado por ${item.cadastrado_por || "Anônimo"}`;

    const nota = parseFloat(item.nota_media) || 0;
    document.getElementById("big-rating").textContent = nota
      ? nota.toFixed(1)
      : "—";
    document.getElementById("big-stars").textContent = nota
      ? CineRate.starsHTML(nota)
      : "";
    document.getElementById("review-count").textContent =
      `${item.total_avaliacoes} avaliação(ões)`;

    document.getElementById("synopsis").textContent =
      item.sinopse || "Sem sinopse disponível.";

    const actDiv = document.getElementById("action-btns");
    if (session.logged && (session.admin || item.usuario_id == session.id)) {
      actDiv.innerHTML += `
                <a href="adicionar.php?editar=${item.id}" class="btn-outline-cinerate">✏️ Editar</a>`;
    }
    if (session.logged && session.admin) {
      actDiv.innerHTML += `
                <button class="btn-outline-cinerate" style="border-color:var(--danger);color:var(--danger)"
                    id="btn-delete-titulo">🗑 Remover</button>`;
      document
        .getElementById("btn-delete-titulo")
        .addEventListener("click", async () => {
          if (!confirm("Remover este título permanentemente?")) return;
          const res = await fetch(`php/titulos_api.php?id=${urlId}`, {
            method: "DELETE",
          });
          const d = await res.json();
          if (d.success) {
            CineRate.toast("Título removido.", "success");
            setTimeout(() => (window.location.href = "index.php"), 1000);
          } else CineRate.toast(d.msg, "error");
        });
    }

    if (session.logged) {
      document.getElementById("review-section").style.display = "";
      document.getElementById("titulo-id").value = urlId;

      const myReview = item.avaliacoes?.find((a) => a.usuario_id == session.id);
      if (myReview) {
        const radio = document.querySelector(
          `input[name="nota"][value="${myReview.nota}"]`,
        );
        if (radio) radio.checked = true;
        document.getElementById("comentario").value = myReview.comentario;
        document.getElementById("btn-review").textContent =
          "Atualizar Avaliação";
      }
    }

    renderComments(item.avaliacoes, session);

    document.getElementById("page-loader").style.display = "none";
    document.getElementById("page-content").style.display = "";
  } catch (e) {
    document.getElementById("page-loader").innerHTML = "Erro ao carregar.";
  }
}

function renderComments(avaliacoes, session) {
  const area = document.getElementById("comments-area");
  if (!avaliacoes || !avaliacoes.length) {
    area.innerHTML =
      '<p style="color:var(--text-muted)">Nenhuma avaliação ainda. Seja o primeiro!</p>';
    return;
  }
  area.innerHTML = avaliacoes
    .map(
      (a) => `
        <div class="comment-card" id="comment-${a.id}">
            <div class="comment-header">
                <span class="comment-author">${esc(a.autor)}</span>
                <div class="d-flex align-items-center gap-2">
                    <span class="comment-date">${CineRate.formatDate(a.criado_em)}</span>
                    ${
                      session.admin ||
                      (session.logged && a.usuario_id == session.id)
                        ? `<button class="btn-delete-comment" data-id="${a.id}"
                            style="background:none;border:none;color:var(--danger);cursor:pointer;font-size:0.85rem">✕</button>`
                        : ""
                    }
                </div>
            </div>
            <div class="comment-stars">${CineRate.starsHTML(a.nota)} (${a.nota}/5)</div>
            <p class="comment-text">${esc(a.comentario)}</p>
        </div>`,
    )
    .join("");

  area.querySelectorAll(".btn-delete-comment").forEach((btn) => {
    btn.addEventListener("click", async () => {
      if (!confirm("Excluir este comentário?")) return;
      const res = await fetch(`php/avaliacoes_api.php?id=${btn.dataset.id}`, {
        method: "DELETE",
      });
      const d = await res.json();
      if (d.success) {
        document.getElementById(`comment-${btn.dataset.id}`)?.remove();
        CineRate.toast("Comentário removido.", "success");
      } else {
        CineRate.toast(d.msg || "Erro.", "error");
      }
    });
  });
}

document
  .getElementById("review-form")
  ?.addEventListener("submit", async (e) => {
    e.preventDefault();
    if (!Validate.avaliacaoForm(e.target)) return;

    const btn = document.getElementById("btn-review");
    btn.textContent = "Enviando...";
    btn.disabled = true;

    const nota = document.querySelector('input[name="nota"]:checked')?.value;
    const comentario = document.getElementById("comentario").value;

    try {
      const res = await fetch("php/avaliacoes_api.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ titulo_id: urlId, nota, comentario }),
      });
      const data = await res.json();

      if (data.success) {
        CineRate.toast("Avaliação enviada!", "success");
        setTimeout(() => location.reload(), 800);
      } else {
        const err = document.getElementById("review-alert-error");
        err.textContent = data.msg;
        err.classList.add("show");
      }
    } finally {
      btn.textContent = "Enviar Avaliação";
      btn.disabled = false;
    }
  });

loadDetail();
