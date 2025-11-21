<!doctype html>
<html>
<head><meta charset="utf-8"><title>Relatório Financeiro</title></head>
<body>
    <h1>Relatório Financeiro</h1>
    <p>Total de Vendas: R$ {{ number_format($sales_total, 2, ',', '.') }}</p>
    <p>Total de OS: R$ {{ number_format($order_total, 2, ',', '.') }}</p>
</body>
</html>
