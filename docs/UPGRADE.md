# Guia de Atualização

1. **Backup**: gere backup dos bancos e do `.env` antes de aplicar qualquer release.
2. **Atualize o código**: faça pull da nova versão e execute `composer install --no-dev --optimize-autoloader` e `npm install && npm run build` quando houver mudanças de front-end.
3. **Migrations versionadas**: execute `php artisan migrate --force`. As migrations seguem carimbo de data para versionamento incremental.
4. **Seeds opcionais**: para atualizar permissões ou dados de apoio, rode `php artisan db:seed --class=DatabaseSeeder --force`.
5. **Cache/Config**: limpe caches com `php artisan optimize:clear` após alterar variáveis de ambiente.
6. **Verificações finais**: acesse `/insights` para confirmar KPIs, `/status` para healthcheck e valide jobs agendados com `php artisan schedule:run --verbose`.

## Rollback
- Utilize `php artisan migrate:rollback --step=1` para desfazer a última migration.
- Em caso de falha, restaure o backup do banco e `.env` e reexecute o instalador se necessário.
