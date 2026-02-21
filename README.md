# CineRate — Plataforma de Avaliação de Filmes e Séries

## Instalação

### 1. Banco de Dados
1. Abra o phpMyAdmin ou MySQL Workbench
2. Execute o arquivo `bd_cinerate.sql`
3. O banco `cinerate` será criado com tabelas e dados de exemplo

### 2. Configuração do Banco
Edite o arquivo `php/db.php` e ajuste as credenciais:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cinerate');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
```

### 3. Servidor Web
- Coloque a pasta do projeto em `htdocs` (XAMPP) ou `www` (WAMP)
- Acesse: `http://localhost/cinerate/`

---

## Credenciais de Teste

| Perfil | E-mail | Senha |
|--------|--------|-------|
| Admin  | admin@cinerate.com | admin123 |
| Usuário | maria@exemplo.com | usuario123 |

> **Nota:** As senhas acima usam hash `bcrypt` padrão do PHP. Para criar novos usuários use a página de Cadastro.

---

## Estrutura do Projeto

```
cinerate/
├── index.php           — Página pública (listagem + busca)
├── login.php           — Login
├── cadastro.php        — Cadastro de usuário
├── detalhe.php         — Detalhe do título + avaliações
├── adicionar.php       — Adicionar/editar título (privado)
├── perfil.php          — Perfil do usuário (privado)
├── admin.php           — Painel administrativo (admin)
│
├── css/
│   └── style.css       — Estilos principais (Bootstrap + custom)
│
├── js/
│   ├── main.js         — Utilitários e navbar dinâmica
│   ├── validate.js     — Validação de formulários
│   ├── index.js        — Listagem e busca AJAX
│   ├── login.js        — Login AJAX
│   ├── cadastro.js     — Cadastro AJAX
│   ├── detalhe.js      — Detalhe e avaliações AJAX
│   ├── adicionar.js    — CRUD de títulos AJAX
│   ├── perfil.js       — Edição de perfil AJAX
│   └── admin.js        — Painel admin AJAX
│
└── php/
    ├── db.php              — Conexão PDO
    ├── auth.php            — Helpers de autenticação
    ├── session.php         — Status de sessão (JSON)
    ├── navbar.php          — Navbar HTML reutilizável
    ├── footer.php          — Footer HTML reutilizável
    ├── login_action.php    — Action de login
    ├── cadastro_action.php — Action de cadastro
    ├── logout.php          — Logout
    ├── titulos_api.php     — API REST de títulos
    ├── avaliacoes_api.php  — API REST de avaliações
    ├── perfil_api.php      — API REST de perfil
    ├── minhas_avaliacoes_api.php — Avaliações do usuário logado
    └── admin_api.php       — API REST admin (usuários)
```

---

## Tecnologias Utilizadas
- **Frontend:** HTML5, CSS3, JavaScript (ES6+), Bootstrap 5
- **Backend:** PHP 8+ com PDO
- **Banco:** MySQL (utf8mb4)

## Funcionalidades Implementadas

### Visitante (público)
- ✅ Listagem de filmes/séries/documentários
- ✅ Detalhe com nota média e avaliações
- ✅ Busca por título (AJAX)
- ✅ Filtro por tipo (AJAX)

### Usuário Registrado
- ✅ Cadastro e login/logout
- ✅ Edição de perfil e exclusão de conta
- ✅ Cadastrar e editar títulos
- ✅ Avaliar com nota (1–5 estrelas) e comentário

### Administrador
- ✅ Todas as funções do usuário
- ✅ Remover qualquer título
- ✅ Excluir comentários de usuários
- ✅ Listar e excluir usuários

### Extras
- ✅ Busca AJAX funcional
- ✅ Todas as chamadas de CRUD via AJAX/Fetch API
- ✅ Validação client-side em todos os formulários
- ✅ Responsivo com Bootstrap 5
