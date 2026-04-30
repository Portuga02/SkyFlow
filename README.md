# SkyFlow 🚀 

<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/PostgreSQL-4169E1?style=for-the-badge&logo=postgresql&logoColor=white" alt="Postgres">
  <img src="https://img.shields.io/badge/Vite-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
</p>

O **SkyFlow** é uma solução moderna e robusta para gestão de fluxos de produtividade pessoal. Este projeto integra a **Coleção Sky**, uma série de aplicações modulares desenvolvidas para demonstrar competências avançadas em arquitetura de software, segurança e escalabilidade no ecossistema Laravel.

## 🛠️ Tecnologias e Stack
* **Backend:** Laravel 10.x (PHP 8.1+)
* **Frontend:** Blade + TailwindCSS + Livewire (Stack Laravel Breeze)
* **Banco de Dados:** PostgreSQL (Identidade: **SkyFlowDB**)
* **Bundler:** Vite ⚡
* **Ícones:** Font Awesome (Integrado via NPM)

## 🌟 Diferenciais Técnicos (Nível Sênior)
* **Arquitetura de Segurança:** Autenticação completa e gestão de sessões via Laravel Breeze.
* **Persistência Profissional:** Configuração otimizada para PostgreSQL, utilizando drivers nativos para alta performance.
* **Gestão de Dependências:** Controle rigoroso de assets via NPM e Vite para carregamento otimizado.
* **Resiliência:** Preparado para implementação de Service Patterns e DTOs (Data Transfer Objects).

## 🚀 Instalação e Configuração

### 1. Requisitos do Sistema
* PHP >= 8.1 (Habilitar extensão `pdo_pgsql` no `php.ini`)
* Composer e Node.js (Versão LTS atual)

### 2. Passo a Passo Local
```bash
# Clone o repositório
git clone [https://github.com/portuga02/SkyFlow.git](https://github.com/portuga02/SkyFlow.git)

# Instale as dependências
composer install
npm install

# Configure o ambiente
cp .env.example .env
