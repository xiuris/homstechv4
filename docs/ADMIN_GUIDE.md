# Manual do Administrador

## Primeiros passos
1. Acesse `/install` em um ambiente limpo e siga o instalador web para gerar o `.env`, rodar migrations/seeders e criar o usuário Administrador.
2. Após o login (auth básica), revise as permissões em **Usuários/Perfis** (Spatie Permission) conforme os papéis padrão: Administrador, Vendedor, Técnico e Financeiro.
3. Atualize a empresa e revendedores gerados pelo seed para refletir seus dados reais.

## Rotinas diárias
- **PDV**: `pos/` cria orçamentos ou vendas com múltiplas formas de pagamento e baixa de estoque automática.
- **OS**: crie ordens de serviço com checklist, anexos, itens de produtos/serviços e acompanhe o fluxo de status até entrega e faturamento parcial.
- **Financeiro**: menus de Contas a Receber/Pagar, parcelamentos e recorrências; relatórios com exportação PDF/Excel em `reports/`.
- **Integrações**: telas de WhatsApp e Fiscal em `fiscal-documents/` para disparos mock/Cloud e emissão fiscal.
- **Insights & Agenda**: `insights/` apresenta KPIs/alertas configuráveis e `appointments/` permite agendamentos por técnico com lembretes.

## Segurança e auditoria
- Todas as rotas usam policies/permissões; ajuste via Spatie Permission.
- Logs estruturados são gravados no stack `daily,structured` com retenção configurável (`LOG_DAILY_DAYS`).
- Rate limiting global configurável (`GLOBAL_RATE_LIMIT`) e cabeçalhos de segurança ativados por middleware.
- Limite de upload configurável por variável `UPLOAD_MAX_MB` (retorna 413 quando excedido).

## Operações de manutenção
- **Backups/alertas**: jobs agendados já habilitados no scheduler (`artisan schedule:run`).
- **Reset de senha**: altere via artisan tinker ou atualize o usuário administrador pela tela de usuários.
- **Reemissão fiscal**: utilize a página de detalhes do documento fiscal para reimprimir XML/PDF.
