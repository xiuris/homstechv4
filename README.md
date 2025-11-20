# Homstech OS Suite

Plataforma SaaS construída com Laravel 11 para gestão de ordens de serviço, PDV, clientes/revendedores, financeiro e integrações (WhatsApp, NF-e/NFC-e). O objetivo é disponibilizar uma base sólida com autenticação, perfis RBAC (Spatie Permission), dados de demonstração e testes automatizados com Pest.

## Requisitos

- PHP 8.2+
- Composer 2.6+
- MySQL 8+
- Node.js 20+ (para assets opcionais com Vite)
- Extensões PHP recomendadas: fileinfo, mbstring, xml (para PDF e Excel)

## Configuração inicial

### Instalador web (recomendado)
1. Suba o projeto localmente com `composer install` e `php artisan serve`.
2. Acesse `http://localhost:8000/install` e preencha os dados de aplicação, banco MySQL e o usuário Administrador.
3. O instalador verifica requisitos, testa conexão, gera `APP_KEY`, grava `.env`, executa migrations/seeders e cria o admin informado.

### Configuração manual
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
- 1 documento fiscal pendente para simulação de emissão
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
- Executar agendador (expiração de orçamentos e recorrência de despesas):
  ```bash
  php artisan schedule:run
  ```

### Rotas base

- `GET /` — página inicial (pública)
- `GET /status` — requer autenticação HTTP Basic + permissão `view platform status`
- `GET /api/status` — idem, expõe resposta JSON com timestamp
- `GET /customers` — CRUD completo de clientes (RBAC `manage customers`)
- `GET /products` — CRUD completo de produtos (RBAC `manage products`)
- `GET /services` — CRUD completo de serviços (RBAC `manage services`)
- `GET /fiscal-documents` — status e reimpressão de documentos fiscais simulados (RBAC `manage integrations`)
- `GET /install` — instalador web (disponível apenas quando `APP_INSTALLED=false`)

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
| `APP_ENV`, `APP_DEBUG`, `APP_INSTALLED` | Ambiente, modo de depuração e flag de instalação |
| `GLOBAL_RATE_LIMIT` | Limite global de requisições por minuto (throttle padrão) |
| `UPLOAD_MAX_MB` | Tamanho máximo de upload (responde 413 acima do limite) |
| `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` | Configuração do MySQL |
| `CACHE_STORE`, `QUEUE_CONNECTION`, `SESSION_DRIVER` | Drivers padrão para cache, fila e sessão |
| `MAIL_*` | Configuração do transporte de e-mails |
| `SANCTUM_STATEFUL_DOMAINS`, `SESSION_DOMAIN` | Domínios confiáveis para autenticação baseada em cookie |
| `WHATSAPP_TOKEN`, `WHATSAPP_PHONE_ID`, `WHATSAPP_BASE_URL`, `WHATSAPP_RETRIES`, `WHATSAPP_BACKOFF` | Credenciais da Cloud API e parâmetros de retry/log para envio de mensagens |
| `FISCAL_DRIVER` | Driver de emissão fiscal (mock padrão) |

## Documentação complementar
- [Manual do Administrador](docs/ADMIN_GUIDE.md)
- [Troubleshooting](docs/TROUBLESHOOTING.md)
- [Guia de Atualização](docs/UPGRADE.md)

## Contribuição

- Padrões: PSR-12, Conventional Commits, testes com Pest.
- Execute `php artisan pint` antes de abrir PRs.
- Toda alteração deve incluir migrações/seeders/testes quando aplicável e atualizar esta documentação se necessário.
