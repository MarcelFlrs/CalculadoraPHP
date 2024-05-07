<?php
session_start();

function calcular($num1, $num2, $op){
    switch ($op) {
        case '+':
            return $num1 + $num2;
            break;
        case '-':
            return $num1 - $num2;
            break;
        case '*':
            return $num1 * $num2;
            break;
        case '/':
            if ($num2 != 0) {
                return $num1 / $num2;
            } else {
                return "Erro: Divisão por zero";
            }
            break;
        case '^':
            return pow($num1, $num2);
            break;
        default:
            return "Erro: Operação inválida";
    }
}

function adicionarHistorico($num1, $num2, $op, $resultado){
    $_SESSION["historico"][] = array($num1, $op, $num2, $resultado);
}

function limparHistorico(){
    unset($_SESSION["historico"]);
}

if ($_SERVER["REQUEST_METHOD"] == "GET"){
    if (isset($_GET["calcular"])) {
        $num1 = $_GET['num1'];
        $num2 = $_GET['num2'];
        $op = $_GET['op'];
        $resultado = calcular($num1, $num2, $op);
        adicionarHistorico($num1, $num2, $op, $resultado);
    } else if (isset($_GET["pegar"])){
        if (isset($_SESSION["memoria"])) {
            $num1 = $_SESSION["memoria"][0];
            $num2 = $_SESSION["memoria"][2];
            $op = $_SESSION["memoria"][1];
        }
    }else if (isset($_GET["m"])){
        if (isset($_GET['num1']) && isset($_GET['num2']) && isset($_GET['op'])) {
            $_SESSION["memoria"] = array($_GET['num1'], $_GET['op'], $_GET['num2']);
        }
    } else if (isset($_GET["apagar"])){
        limparHistorico();
    }
}

$historico = isset($_SESSION["historico"]) ? $_SESSION["historico"] : array();

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Calculadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-dark text-light">
    <div class="position-absolute top-50 start-50 translate-middle">
        <div class="container bg-black p-4 rounded">
            <h1 class="text-center mb-4">Calculadora PHP</h1>
            <form action="" method="GET">
                <div class="mb-3 d-flex align-items-center">
                <input type="text" aria-label="num1" class="form-control rounded-end me-2" name="num1" placeholder="Número 1" >
                    <select class="form-select me-2" aria-label="Default select example" name="op">
                         <option selected></option>
                        <option value="+" <?= isset($_GET['op']) && $_GET['op'] == '+' ? 'selected' : '' ?>>+</option>
                        <option value="-" <?= isset($_GET['op']) && $_GET['op'] == '-' ? 'selected' : '' ?>>-</option>
                        <option value="*" <?= isset($_GET['op']) && $_GET['op'] == '*' ? 'selected' : '' ?>>*</option>
                        <option value="/" <?= isset($_GET['op']) && $_GET['op'] == '/' ? 'selected' : '' ?>>/</option>
                        <option value="^" <?= isset($_GET['op']) && $_GET['op'] == '^' ? 'selected' : '' ?>>^</option>
                        <option value="!" <?= isset($_GET['op']) && $_GET['op'] == '!' ? 'selected' : '' ?>>!</option>
                    </select>
                    <input type="text" class="form-control me-2" id="num2" name="num2" placeholder="Número 2" value="<?= isset($num2) ? $num2 : '' ?>">
                    <button type="submit" class="btn btn-primary" name="calcular">Calcular</button>
                </div>
                <div class="mb-3">
                    <button type="submit" class="btn btn-success me-2" name="salvar">Salvar</button>
                    <button type="submit" class="btn btn-primary me-2" name="pegar">Pegar Valores</button>
                    <button type="submit" class="btn btn-primary me-2" name="m">M</button>
                    <button type="submit" class="btn btn-danger " name="apagar">Apagar Histórico</button>
                </div>
            </form>

            <div>
                <br>
                <div id="resultado" class="p-2 bg-white text-dark h-auto fs-6 ms-2 me-2 rounded"><?= isset($resultado) ? $resultado : '' ?></div>
                <br>
            </div>

            <div class="mb-3">
                <div><b>Histórico</b></div>
                <?php
                foreach ($historico as $item) {
                    echo "<p>" . implode(" ", $item) . "</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
