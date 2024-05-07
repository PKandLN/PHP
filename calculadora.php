<?php
session_start();


$ultimoResultado = "";

// Função para calcular fatorial
function fatorial($n) {
    if ($n == 0) {
        return 1;
    } else {
        return $n * fatorial($n - 1);
    }
}

// Verifica se o formulário foi preenchido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtém os valores digitados pelo usuário
    $num1 = $_POST["num1"];
    $num2 = isset($_POST["num2"]) ? $_POST["num2"] : null;
    $operador = $_POST["operador"];

    // Calcula o resultado com base no operador selecionado
    switch ($operador) {
        case "+":
            $resultado = $num1 + $num2;
            break;
        case "-":
            $resultado = $num1 - $num2;
            break;
        case "*":
            $resultado = $num1 * $num2;
            break;
        case "/":
            if ($num2 != 0) {
                $resultado = $num1 / $num2;
            } else {
                $resultado = "Erro: divisão por zero";
            }
            break;
        case "^":
            $resultado = pow($num1, $num2);
            break;
        case "!":
            $resultado = fatorial($num1);
            break;
        default:
            $resultado = "Operador inválido";
    }

    // Adiciona a operação ao histórico
    if ($operador == "!") {
        $_SESSION["historico"][] = "$num1 $operador = $resultado";
    } else {
        $_SESSION["historico"][] = "$num1 $operador $num2 = $resultado";
    }

    // Define o último resultado
    $ultimoResultado = $_SESSION["historico"][count($_SESSION["historico"]) - 1];
}

// Limpa o histórico se o botão de limpar for clicado
if (isset($_POST["limpar_hist"])) {
    unset($_SESSION["historico"]);
}

// Verifica se tem um histórico de operações
if (isset($_SESSION["historico"])) {
    // Obtém o histórico sem o último resultado
    $historico = $_SESSION["historico"];
} else {
    $historico = array();
}

// Função para salvar os valores dos campos de entrada
if (isset($_POST["salvar_valores"])) {
    $_SESSION["valores_salvos"] = array(
        "num1" => $_POST["num1"],
        "num2" => isset($_POST["num2"]) ? $_POST["num2"] : null,
        "operador" => $_POST["operador"]
    );
}

// Função para recuperar os valores salvos
if (isset($_POST["recuperar_valores"]) && isset($_SESSION["valores_salvos"])) {
    $_POST["num1"] = $_SESSION["valores_salvos"]["num1"];
    $_POST["num2"] = $_SESSION["valores_salvos"]["num2"];
    $_POST["operador"] = $_SESSION["valores_salvos"]["operador"];
}

// Função para armazenar o último resultado na memória
if (isset($_POST["memoria"])) {
    $_SESSION["memoria"] = array(
        "num1" => isset($_POST["num1"]) ? $_POST["num1"] : null,
        "num2" => isset($_POST["num2"]) ? $_POST["num2"] : null,
        "operador" => isset($_POST["operador"]) ? $_POST["operador"] : null
    );
}

// Função para recuperar o valor da memória
if (isset($_POST["memoria"])) {
    if (isset($_SESSION["memoria"])) {
        $_POST["num1"] = $_SESSION["memoria"]["num1"];
        $_POST["num2"] = $_SESSION["memoria"]["num2"];
        $_POST["operador"] = $_SESSION["memoria"]["operador"];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Calculadora com Histórico</title>
    <link rel="stylesheet" href="Css.css">
</head>
<body>
    <h2>Calculadora</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="num1">Número 1:</label>
        <input type="number" name="num1" value="<?php echo isset($_POST['num1']) ? $_POST['num1'] : '' ?>" required><br><br>
        <label for="operador">Operador:</label>
        <select name="operador" required>
            <option value="+">+</option>
            <option value="-">-</option>
            <option value="*">*</option>
            <option value="/">/</option>
            <option value="^">^</option>
            <option value="!">!</option>
        </select><br><br>
        <label for="num2">Número 2:</label>
        <input type="number" name="num2" value="<?php echo isset($_POST['num2']) ? $_POST['num2'] : '' ?>"><br><br>
        <input type="submit" value="Calcular">
        
        <!-- Botões Salvar e Recuperar -->
        <input type="submit" name="salvar_valores" value="Salvar Valores">
        <input type="submit" name="recuperar_valores" value="Recuperar Valores">

        <!-- Botão de Memória -->
        <input type="submit" name="memoria" value="M">
    </form>

    <h2>Último Resultado</h2>
    <p><?php echo $ultimoResultado; ?></p>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="submit" name="limpar_hist" value="Limpar Histórico">
    </form>

    <h2>Histórico de Operações</h2>
    <ul>
        <?php foreach ($historico as $operacao) { ?>
            <li><?php echo $operacao; ?></li>
        <?php } ?>
    </ul>
</body>
</html>
