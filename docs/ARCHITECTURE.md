# Arquitetura de Domínio

A fase inicial do projeto estabelece o núcleo de domínio e o modelo de permissões baseado em [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission). O diagrama abaixo resume as principais entidades e seus relacionamentos:

```text
Company
├─ Reseller (N)
│   └─ Customer (N)
├─ Customer (N)
│   ├─ OrderService (N)
│   │   ├─ AccountReceivable (N)
│   │   └─ Warranty (N)
│   └─ Sale (N)
│       ├─ AccountReceivable (N)
│       └─ Warranty (N)
├─ Product (N)
│   └─ StockMovement (N)
├─ Service (N)
├─ Sale (N)
├─ OrderService (N)
├─ AccountReceivable (N)
├─ AccountPayable (N)
│   └─ Payment (N)
└─ Warranty (N)

User (N) ── belongsTo ── Company
User (N) ── belongsTo ── Reseller (opcional)
User (N) ── HasRoles/Permissions (Spatie)
```

Principais observações:

- **RBAC**: Usuários são associados a `Company` e podem, opcionalmente, estar vinculados a um `Reseller`. As permissões são controladas por perfis (Administrador, Vendedor, Técnico, Financeiro) e seguem o middleware `permission` nas rotas web.
- **Clientes**: `Customer` centraliza dados cadastrais, relação com `Reseller` e possui vínculos com ordens de serviço, vendas, contas a receber e garantias.
- **Catálogo**: `Product` mantém preços de varejo/atacado, controle de estoque e categoria. `Service` guarda categoria, duração e status de ativação.
- **Operações**: `OrderService` e `Sale` representam ordens de serviço e vendas, respectivamente. Ambos alimentam `AccountReceivable`, `Warranty` e podem originar `Payment` através da relação polimórfica `payable`.
- **Faturamento de OS via PDV**: o PDV permite importar ordens de serviço aprovadas/prontas para faturar (`pdv.invoice_os`) e cria uma `Sale` vinculada (`order_service_id`), replicando itens e cliente. Os pagamentos e parcelas geradas carregam a referência da OS e o status é marcado como `invoiced` para impedir faturamentos duplicados.
- **Financeiro**: `AccountReceivable` e `AccountPayable` representam títulos a receber/pagar por empresa. Pagamentos são registrados via `Payment` (morph), permitindo associação a recebíveis, pagáveis ou outras entidades futuras. Recebíveis armazenam parcela/número total e preferências de notificação; pagáveis suportam recorrência (semanal/mensal), categoria e anexos de comprovantes.
- **Estoque e Garantias**: Movimentações de estoque são rastreadas em `StockMovement`, vinculadas a produtos e usuários responsáveis. Garantias (`Warranty`) podem se relacionar tanto a vendas quanto a ordens de serviço.

Este ERD simplificado orienta as próximas fases de expansão (PDV, integrações fiscais, automações) mantendo os vínculos necessários entre operações, financeiro e pós-venda.
