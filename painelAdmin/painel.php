<?php
/* session_start();
include_once '../config/conn.php';

if (!isset($_SESSION['email'])) {
    header('Location: ../login/login.html');
    exit();
}

$email = $_SESSION['email'];

$logado = $_SESSION['email'];
*/ 
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Painel Administrativo</title>
  <link rel="stylesheet" href="estilo.css" />
</head>
<body>
  <div class="wrapper">
    <header class="topo">
      <div class="container">
        <h1>DESENVOLVIMENTO<br><span>COMUNITÁRIO</span></h1>
        <p>Empoderando as comunidades do entorno</p>
        <button class="btn-admin">Admin</button>
      </div>
    </header>

    <main class="painel">
      <div class="container">
        <div class="cabecalho-painel">
          <h2>Painel Administrativo</h2>
          <button class="btn-logout"><a style="text-decoration: none; color:white;" href="../config/logout.php">Logout</a></button>
        </div>

        <div class="filtro-cidade">
          <label for="estado">Estado:</label>
          <select id="estado" onchange="atualizarCidades(); filtrarTabela();">
            <option value="todos">Todos</option>
            <option value="RJ">RJ</option>
            <option value="SP">SP</option>
            <option value="BA">BA</option>
            <option value="CE">CE</option>
          </select>

          <label for="cidade">Cidade:</label>
          <select id="cidade" onchange="filtrarTabela()">
            <option value="todas">Todas</option>
          </select>
        </div>

        <div class="tabela">
          <h3>Comunidades Cadastradas</h3>
          <table id="tabela-comunidades">
            <thead>
              <tr>
                <th>Comunidade</th>
                <th>Cidade</th>
                <th>Estado</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>Vila Esperança</td>
                <td>Rio de Janeiro</td>
                <td>RJ</td>
                <td><button class="btn-detalhes">Ver Detalhes</button></td>
              </tr>
              <tr>
                <td>Nova Aliança</td>
                <td>São Paulo</td>
                <td>SP</td>
                <td><button class="btn-detalhes">Ver Detalhes</button></td>
              </tr>
              <tr>
                <td>Morada Nova</td>
                <td>Salvador</td>
                <td>BA</td>
                <td><button class="btn-detalhes">Ver Detalhes</button></td>
              </tr>
              <tr>
                <td>Jardim das Flores</td>
                <td>Fortaleza</td>
                <td>CE</td>
                <td><button class="btn-detalhes">Ver Detalhes</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>

    <footer>
      <div class="container">
        <p>Administrar Petro</p>
        <img src="logo-br.png" alt="Logo BR" />
      </div>
    </footer>
  </div>

  <script>
    const cidadesPorEstado = {
      RJ: ['Rio de Janeiro'],
      SP: ['São Paulo'],
      BA: ['Salvador'],
      CE: ['Fortaleza']
    };

    function atualizarCidades() {
      const estado = document.getElementById('estado').value;
      const cidadeSelect = document.getElementById('cidade');

      cidadeSelect.innerHTML = '<option value="todas">Todas</option>';

      if (estado !== 'todos') {
        cidadesPorEstado[estado].forEach(cidade => {
          const option = document.createElement('option');
          option.value = cidade;
          option.textContent = cidade;
          cidadeSelect.appendChild(option);
        });
      }
    }

    function filtrarTabela() {
      const estado = document.getElementById('estado').value.toLowerCase();
      const cidade = document.getElementById('cidade').value.toLowerCase();
      const linhas = document.querySelectorAll("#tabela-comunidades tbody tr");

      linhas.forEach(linha => {
        const cidadeLinha = linha.children[1].textContent.toLowerCase();
        const estadoLinha = linha.children[2].textContent.toLowerCase();

        const estadoValido = estado === 'todos' || estado === estadoLinha;
        const cidadeValida = cidade === 'todas' || cidade === cidadeLinha;

        linha.style.display = (estadoValido && cidadeValida) ? '' : 'none';
      });
    }
  </script>
</body>
</html>
