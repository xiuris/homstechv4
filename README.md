# Homstech V4 — Starter

Base inicial para o repositório **homstechv4**. Este starter não é o código do framework em si, 
mas um *esqueleto* com documentação, governança de repositório e configuração mínima para você
começar e permitir que o Codex/ChatGPT faça *commits/PRs*.

> Recomendado: use **Laravel 11**, **PHP 8.2+**, **MySQL 8** (ou MariaDB 10.6+).

## Como usar

1. Faça o primeiro commit com estes arquivos:
   ```bash
   git init
   git add .
   git commit -m "chore: starter repo (docs + config)"
   git branch -M main
   git remote add origin https://github.com/xIuris/homstechv4.git
   git push -u origin main
   ```

2. Depois, gere um projeto Laravel (localmente ou via Codex) e mova os arquivos para dentro do repo:
   ```bash
   composer create-project laravel/laravel app
   # ou use o diretório raiz e copie / configure conforme preferir
   ```

3. Copie `.env.example` para `.env` e ajuste credenciais:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. (Opcional) Suba com Docker:
   ```bash
   docker compose up -d
   ```

## Estrutura
- `docs/` — arquitetura, roadmap, contribuição e segurança
- `.github/` — templates de issue/PR
- `docker-compose.yml` — ambiente simples (app + db) para desenvolvimento
- `.editorconfig`, `.gitattributes`, `.gitignore` — qualidade e padronização
- `LICENSE` — MIT

## Próximos passos sugeridos
- Ler `docs/ROADMAP.md` e quebrar em issues
- Criar a pasta `app/` do Laravel ou adicionar o monorepo com `src/`
- Configurar CI (GitHub Actions) para testes e lint
- Habilitar ambiente de staging na sua hospedagem

---

© 2025 Homstech Informática — MIT License.
