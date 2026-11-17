# Estoque — Admin do Site

Módulo de gestão de estoque do painel administrativo (PHP + PDO/MySQL).

## Funções
- **Estoque** (`index.php`, `buscar.php`): lista e busca de produtos por secretaria.
- **Adicionar / Editar** (`adicionar.php`, `editar.php`): cadastro de produtos (tipo, marca, modelo, descrição, valor unitário, quantidade, secretaria).
- **Dispensar** (`dispensar.php`): saída de itens para um setor/solicitante, registrando o responsável.
- **Dispensados** (`dispensados.php`, `buscar_dispensados.php`): histórico das saídas.
- `actions/`: processamento dos formulários (adicionar, editar, apagar, dispensar).

## Dependências (fora deste repositório)
O módulo roda dentro de `admin/site/` e usa:
- `../assets/secure/acesso.php` — controle de login/sessão
- `../../config/pdo_database.php` — conexão com o banco (**contém credenciais, não versionar**)
- `../assets/includes/cabecalho.php` e `rodape.php` — layout
- `../assets/functions/tools.php` — funções auxiliares
