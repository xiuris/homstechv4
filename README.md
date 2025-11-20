# Homstech OS Suite

Plataforma SaaS construída com Laravel 11 para gestão de ordens de serviço, PDV, clientes/revendedores, financeiro e integrações (WhatsApp, NF-e/NFC-e). O objetivo é disponibilizar uma base sólida com autenticação, perfis RBAC (Spatie Permission), dados de demonstração e testes automatizados com Pest.

## Requisitos

- PHP 8.2+
- Composer 2.6+
- MySQL 8+
- Node.js 20+ (para assets opcionais com Vite)

## Configuração inicial

1. Copie o arquivo de exemplo do ambiente:
   ```bash
   cp .env.example .env
   ```
2. Atualize as variáveis do `.env` conforme seu ambiente MySQL.
3. Instale as dependências:
   ```bash
   composer install
   npm install
   ```
4. Gere a chave da aplicação:
   ```bash
   php artisan key:generate
   ```
5. Execute as migrações e seeds de demonstração:
   ```bash
   php artisan migrate --seed
   ```

As seeds criam:
- 1 empresa principal com revendedores, clientes, contas a pagar/receber e garantias de exemplo
- 2 revendedores vinculados
- 5 clientes já relacionados à empresa e revendedores
- 3 produtos (com estoque inicial) e 3 serviços ativos
- 2 ordens de serviço, 2 vendas e movimentos financeiros associados
- Usuários demo com perfis Administrador, Vendedor, Técnico e Financeiro

Senhas padrão: `password` (alterar após o primeiro acesso).

## Executando o projeto

- Servidor HTTP local:
  ```bash
  php artisan serve
  ```
- Fila/Jobs (opcional):
  ```bash
  php artisan queue:work
  ```

### Rotas base

- `GET /` — página inicial (pública)
- `GET /status` — requer autenticação HTTP Basic + permissão `view platform status`
- `GET /api/status` — idem, expõe resposta JSON com timestamp
- `GET /customers` — CRUD completo de clientes (RBAC `manage customers`)
- `GET /products` — CRUD completo de produtos (RBAC `manage products`)
- `GET /services` — CRUD completo de serviços (RBAC `manage services`)

Use o usuário administrador (`admin@homstech.test` / `password`) para autenticar nas rotas protegidas ou associe permissões específicas aos demais perfis.

## Testes

Executar a suíte com Pest:
```bash
php artisan test
```
ou
```bash
./vendor/bin/pest
```

Os testes garantem o funcionamento das rotas de status, validam os dados semeados e asseguram que apenas usuários autorizados acessam os CRUDs de clientes, produtos e serviços.

## Variáveis de ambiente

Principais variáveis do `.env`:

| Variável | Descrição |
| --- | --- |
| `APP_NAME` | Nome da aplicação exibido nas respostas |
| `APP_ENV`, `APP_DEBUG` | Ambiente e modo de depuração |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Configuração do MySQL |
| `CACHE_STORE`, `QUEUE_CONNECTION`, `SESSION_DRIVER` | Drivers padrão para cache, fila e sessão |
| `MAIL_*` | Configuração do transporte de e-mails |
| `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN` | Domínios confiáveis para autenticação baseada em cookie |

## Contribuição

- Padrões: PSR-12, Conventional Commits, testes com Pest.
- Execute `php artisan pint` antes de abrir PRs.
- Toda alteração deve incluir migrações/seeders/testes quando aplicável e atualizar esta documentação se necessário.
