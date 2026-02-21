// js/index.js - Home page content loader

let currentTipo = "";
let searchQuery = "";

// Read URL params on load
const params = new URLSearchParams(window.location.search);
if (params.get("tipo")) {
  currentTipo = params.get("tipo");
}
if (params.get("busca")) {
  searchQuery = params.get("busca");
  document.getElementById("search-input").value = searchQuery;
}

async function loadContent() {
  const area = document.getElementById("content-area");
  area.innerHTML =
    '<div class="loader"><div class="spinner"></div> Carregando...</div>';

  // Update section heading
  const headingMap = {
    "": "TODOS OS",
    filme: "FILMES",
    serie: "SÉRIES",
    documentario: "DOCUMENTÁRIOS",
  };
  const headingLabel = {
    "": "<span>TÍTULOS</span>",
    filme: "",
    serie: "",
    documentario: "",
  };
  const h = document.getElementById("section-heading");
  if (h)
    h.innerHTML = `${headingMap[currentTipo] || ""} <span>${headingLabel[currentTipo] === "" ? currentTipo.toUpperCase() : "TÍTULOS"}</span>`;

  // Update active filter tab
  document.querySelectorAll(".filter-tab").forEach((btn) => {
    btn.classList.toggle("active", btn.dataset.tipo === currentTipo);
  });

  try {
    let url = `php/titulos_api.php?tipo=${encodeURIComponent(currentTipo)}`;
    if (searchQuery) url += `&busca=${encodeURIComponent(searchQuery)}`;
    const res = await fetch(url);
    const items = await res.json();

    if (!items.length) {
      area.innerHTML = `<div class="loader" style="flex-direction:column;gap:10px;">
                <p style="color:var(--text-muted);font-size:1.1rem;">Nenhum título encontrado.</p>
                <a href="adicionar.php" class="btn-cinerate">Adicionar o primeiro</a>
            </div>`;
      return;
    }

    area.innerHTML = `<div class="content-grid">${items.map(cardHTML).join("")}</div>`;
  } catch (e) {
    area.innerHTML = '<div class="loader">Erro ao carregar conteúdo.</div>';
  }
}

function cardHTML(item) {
  const nota = item.nota_media || 0;
  const stars = CineRate.starsHTML(nota);
  const posterEl = item.poster_url
    ? `<img class="card-poster" src="${escHTML(item.poster_url)}" alt="${escHTML(item.titulo)}" loading="lazy">`
    : `<div class="card-poster-placeholder">🎬</div>`;

  const tipoLabel = {
    filme: "Filme",
    serie: "Série",
    documentario: "Documentário",
  };

  return `
    <a class="content-card" href="detalhe.php?id=${item.id}">
        ${posterEl}
        <div class="card-body">
            <div class="card-title">${escHTML(item.titulo)}</div>
            <div class="card-meta">${escHTML(item.genero)} · ${item.ano}</div>
            <span class="card-badge">${tipoLabel[item.tipo] || item.tipo}</span>
            <div class="card-rating">
                <span class="stars-display">${stars}</span>
                <span class="rating-count">${nota ? 1 : "—"} (${item.total_avaliacoes})</span>
            </div>
        </div>
    </a>`;
}

function escHTML(str) {
  if (!str) return "";
  return str
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;");
}

// Filter tabs
document.querySelectorAll(".filter-tab").forEach((btn) => {
  btn.addEventListener("click", () => {
    currentTipo = btn.dataset.tipo;
    loadContent();
  });
});

// Set correct active tab from URL param
document.querySelectorAll(".filter-tab").forEach((btn) => {
  btn.classList.toggle("active", btn.dataset.tipo === currentTipo);
});

// Search
function doSearch() {
  searchQuery = document.getElementById("search-input").value.trim();
  loadContent();
}

document.getElementById("search-btn").addEventListener("click", doSearch);
document.getElementById("search-input").addEventListener("keydown", (e) => {
  if (e.key === "Enter") doSearch();
});

// Carrega o conteúdo inicial
loadContent();
