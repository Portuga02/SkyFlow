# 🌤️ SkyFlow

Uma solução moderna para gestão de fluxos de produtividade pessoal — parte da **Coleção Sky**, uma série de aplicações modulares desenvolvidas para demonstrar competências em arquitetura de software, segurança e escalabilidade no ecossistema Laravel.

---

## 🛠️ Stack Tecnológica

| Camada | Tecnologia |
|---|---|
| **Backend** | Laravel 10.x (PHP 8.1+) |
| **Frontend** | Blade + TailwindCSS + Alpine.js (Stack Laravel Breeze) |
| **Banco de Dados** | PostgreSQL |
| **Bundler** | Vite ⚡ |
| **Ícones** | Font Awesome (via NPM) |
| **Autenticação** | Laravel Breeze (login, registro, "lembrar-me", recuperação de senha) |

> ℹ️ O projeto **não usa Livewire** apesar de mencionado em versões antigas da documentação — a interatividade (filtros, dropdowns, cores dinâmicas, sidebar responsiva) é feita com **Alpine.js**, que já vem junto no stack padrão do Breeze.

---

## 🎨 Identidade Visual

- Paleta de cor customizada `brand` (tons de azul "sky"), definida em `tailwind.config.js`
- Componentes Blade reutilizáveis (`x-primary-button`, `x-text-input`, `x-input-label`, etc.) já estilizados com o tema
- Sidebar de navegação fixa (estilo Notion), responsiva com menu off-canvas no mobile
- Cards com sombra suave (`shadow-card`), bordas arredondadas e microanimações (`fade-in`, `pop-in`)

---

## ✅ Funcionalidades implementadas

### 📋 Tarefas (`Todo`)
- CRUD completo (criar, listar, editar, ver detalhes, excluir)
- Toggle rápido de status (concluída/pendente) direto na listagem, sem precisar abrir o formulário
- Filtros client-side (Todas / Pendentes / Concluídas) via Alpine.js
- Estatísticas no topo da lista (total, pendentes, concluídas, % de progresso)
- Estado vazio ilustrado quando não há tarefas

### 📁 Categorias (`Category`)
- CRUD completo com suporte a **subcategorias** (auto-relacionamento via `parent_id`)
- Cor e ícone customizáveis por categoria (color picker + seletor de ícone Font Awesome)
- Pré-visualização em tempo real no formulário
- Contagem de tarefas por categoria na listagem

### 🔐 Autenticação (Laravel Breeze)
- Login, registro, logout
- Recuperação e redefinição de senha
- Confirmação de e-mail
- **"Lembrar-me"** funcional (estende o cookie de sessão via `Auth::attempt($credentials, $remember)`)

### 🧭 Navegação
- Sidebar fixa com seções "Principal" (Dashboard, Tarefas, Nova Tarefa) e "Organização" (Categorias)
- Dropdown de usuário com avatar, acesso ao perfil e logout
- Totalmente responsiva (mobile: menu off-canvas)

---

## 🚧 Roadmap (cards em andamento)

| Card | Descrição | Status |
|---|---|---|
| SKY-FLOW-I | Sidebar de navegação | ✅ Concluído |
| SKY-FLOW-II | Model + Migration de Categorias/Subcategorias | ✅ Concluído |
| SKY-FLOW-III | CRUD de Categorias | ✅ Concluído |
| SKY-FLOW-IV | Vincular tarefas às categorias (UI) | 🔜 Pendente |
| SKY-FLOW-V | Migration: `priority` e `due_date` em `todos` | 🔜 Pendente |
| SKY-FLOW-VI | Formulário de tarefa com prioridade e prazo | 🔜 Pendente |
| SKY-FLOW-VII | Badges de prioridade/atraso nos cards | 🔜 Pendente |
| SKY-FLOW-VIII | Toggle Lista ↔ Kanban | 🔜 Pendente |
| SKY-FLOW-IX | Visão Kanban (colunas por status) | 🔜 Pendente |
| SKY-FLOW-X | Drag & drop entre colunas do Kanban | 🔜 Pendente |
| SKY-FLOW-XI | Upload de avatar do usuário | 🔜 Pendente |
| SKY-FLOW-XII | Tema de cor personalizável | 🔜 Pendente |
| SKY-FLOW-XIII | Streak/estatísticas no perfil | 🔜 Pendente |
| SKY-FLOW-XIV | Busca global (⌘K) | 🔜 Pendente |
| SKY-FLOW-XV | Notificações de tarefas vencendo | 🔜 Pendente |
| SKY-FLOW-XVI | Times/colaboração (convidar membros) | 🔜 Pendente |
| SKY-FLOW-XVII | Agenda/Calendário (tarefas + eventos) | 🔜 Pendente |
| SKY-FLOW-XVIII | Isolamento de tarefas por usuário (`user_id` em `todos`) | ⚠️ Pendente (bug de privacidade conhecido) |

> ⚠️ **Nota de segurança:** atualmente a tabela `todos` não possui coluna `user_id`, então todas as tarefas são compartilhadas entre todos os usuários autenticados. Correção prevista no card **SKY-FLOW-XVIII**.

---

## 🚀 Instalação e Configuração

### 1. Requisitos do Sistema
- PHP >= 8.1 (com a extensão `pdo_pgsql` habilitada no `php.ini`)
- Composer
- Node.js (versão LTS atual)
- PostgreSQL

### 2. Instalar dependências
```bash
composer install
npm install
```

### 3. Configurar o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

Edite o `.env` com os dados do banco:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=skyflowdb
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

### 4. Rodar as migrations
```bash
php artisan migrate
```

### 5. Compilar os assets
```bash
npm run dev      # desenvolvimento (watch mode)
npm run build    # produção
```

### 6. Subir o servidor
```bash
php artisan serve
```

---

## 📂 Estrutura relevante do projeto
app/
├── Http/
│ ├── Controllers/
│ │ ├── TodoController.php
│ │ ├── CategoryController.php
│ │ └── ProfileController.php
│ └── Requests/
│ ├── TodoRequest.php
│ └── CategoryRequest.php
└── Models/
├── Todo.php
├── Category.php
└── User.php

resources/views/
├── layouts/
│ ├── app.blade.php
│ ├── guest.blade.php
│ └── sidebar.blade.php
├── auth/
│ ├── todo.blade.php
│ ├── create-todo.blade.php
│ ├── edit-todo.blade.php
│ └── showTodo.blade.php
├── categories/
│ ├── index.blade.php
│ ├── create.blade.php
│ └── edit.blade.php
├── components/ # x-primary-button, x-text-input, etc.
└── dashboard.blade.php

database/migrations/
├── create_todos_table.php
├── create_categories_table.php
└── add_category_id_to_todos_table.php


---

## 💻 Ferramentas recomendadas

- **VS Code** com as extensões:
  - PHP Intelephense
  - Laravel Blade Snippets
  - Tailwind CSS IntelliSense
  - Laravel Extra Intellisense