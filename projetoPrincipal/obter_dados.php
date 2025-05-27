<?php
// Configuração de conexão com o banco de dados
$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "comunidades";

// Cria a conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se há erro de conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Função para inicializar contadores
function inicializaContadores($opcoes) {
    $contadores = [];
    foreach ($opcoes as $opcao) {
        $contadores[$opcao] = 0;
    }
    return $contadores;
}

// Inicializa os dados para as métricas
$educacao = inicializaContadores(['bom', 'moderado', 'ruim']);
$agua = inicializaContadores(['bom', 'moderado', 'ruim']);
$saude = inicializaContadores(['bom', 'moderado', 'ruim']);
$moradia = inicializaContadores(['bom', 'moderado', 'ruim']);
$emprego = inicializaContadores(['alto', 'moderado', 'baixo']);
$renda_media = 0;
$total_renda = 0;
$total_registros = 0;

// Consulta para obter dados de educação
$sql_educacao = "SELECT educacao, COUNT(*) as quantidade FROM dados GROUP BY educacao";
$result_educacao = $conn->query($sql_educacao);
while ($row_educacao = $result_educacao->fetch_assoc()) {
    $educacao[$row_educacao['educacao']] = $row_educacao['quantidade'];
}

// Consulta para obter dados de acesso à água
$sql_agua = "SELECT agua, COUNT(*) as quantidade FROM dados GROUP BY agua";
$result_agua = $conn->query($sql_agua);
while ($row_agua = $result_agua->fetch_assoc()) {
    $agua[$row_agua['agua']] = $row_agua['quantidade'];
}

// Consulta para obter dados de saúde
$sql_saude = "SELECT saude, COUNT(*) as quantidade FROM dados GROUP BY saude";
$result_saude = $conn->query($sql_saude);
while ($row_saude = $result_saude->fetch_assoc()) {
    $saude[$row_saude['saude']] = $row_saude['quantidade'];
}

// Consulta para obter dados de moradia
$sql_moradia = "SELECT moradia, COUNT(*) as quantidade FROM dados GROUP BY moradia";
$result_moradia = $conn->query($sql_moradia);
while ($row_moradia = $result_moradia->fetch_assoc()) {
    $moradia[$row_moradia['moradia']] = $row_moradia['quantidade'];
}

// Consulta para obter dados de emprego
$sql_emprego = "SELECT emprego, COUNT(*) as quantidade FROM dados GROUP BY emprego";
$result_emprego = $conn->query($sql_emprego);
while ($row_emprego = $result_emprego->fetch_assoc()) {
    $emprego[$row_emprego['emprego']] = $row_emprego['quantidade'];
}

// Consulta para calcular a média de renda
$sql_renda = "SELECT renda FROM dados";
$result_renda = $conn->query($sql_renda);
while ($row_renda = $result_renda->fetch_assoc()) {
    $total_renda += $row_renda['renda'];
    $total_registros++;
}
if ($total_registros > 0) {
    $renda_media = $total_renda / $total_registros;
}

// Retorna os dados no formato JSON para o frontend
echo json_encode([
    'educacao' => $educacao,
    'agua' => $agua,
    'saude' => $saude,
    'moradia' => $moradia,
    'emprego' => $emprego,
    'renda' => ['media' => $renda_media]
]);

// Fecha a conexão com o banco de dados
$conn->close();
?>
