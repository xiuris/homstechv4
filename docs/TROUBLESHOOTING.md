# Troubleshooting

## Erros de instalação
- **Requisito pendente**: verifique extensões PHP listadas no topo do instalador e permissões de escrita em `storage/` e `.env`.
- **Falha ao conectar no banco**: confirme host/porta/usuário e se o banco permite conexões externas. Para diagnóstico rápido, teste com driver `sqlite` local e depois ajuste para MySQL.
- **404 ao acessar `/install`**: garanta que `APP_INSTALLED=false` no `.env` e que não exista o arquivo `storage/app/installed`.

## Performance e limites
- **429 Too Many Requests**: rate limit global excedido; aumente `GLOBAL_RATE_LIMIT` ou distribua chamadas.
- **413 Upload excede limite**: aumente `UPLOAD_MAX_MB` e reinicie a aplicação.

## Logs e backups
- Logs ficam em `storage/logs/` com rotação diária (`LOG_DAILY_DAYS`).
- Backups e alertas agendados registram eventos no log estruturado e no banco; valide o scheduler com `php artisan schedule:run --verbose`.

## Atualização de dependências
- Rode `composer install` e `npm install` antes de testar novas releases.
- Execute `php artisan config:clear` e `php artisan route:clear` após mudanças em env/rotas.
