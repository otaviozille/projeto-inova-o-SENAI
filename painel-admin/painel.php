  <?php
  session_start();
  include_once '../config/conn.php';

  if (!isset($_SESSION['email'])) {
    header('Location: ../login/login.html');
    exit();
  }

  $comunidades = [];
  $estados = [];
  $cidadesPorEstado = [];

  $sql = "SELECT id, comunidade, cidade, estado, educacao, agua, renda, saude, moradia, emprego FROM comunidades";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
      $comunidades[] = $row;
      $estado = $row['estado'];
      $cidade = $row['cidade'];

      if (!in_array($estado, $estados)) {
        $estados[] = $estado;
      }

      if (!isset($cidadesPorEstado[$estado])) {
        $cidadesPorEstado[$estado] = [];
      }

      if (!in_array($cidade, $cidadesPorEstado[$estado])) {
        $cidadesPorEstado[$estado][] = $cidade;
      }
    }
  }
  ?>

  <!DOCTYPE html>
  <html lang="pt-br">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="estilo.css" />
  </head>

  <body>
    <div class="wrapper">
      <header class="topo">
        <div class="container cabecalho-topo">
          <img src="petrobras.png" alt="Logo Petrobras" class="logo-petro" />
          <div class="titulo-topo">
            <h1>DESENVOLVIMENTO<br><span>COMUNITÁRIO</span></h1>
            <p>Empoderando as comunidades do entorno</p>
          </div>
          <a href="../page-admin/index.php" class="btn-add">Adicionar nova comunidade</a>
          <button class="btn-admin">Admin</button>
          <button class="btn-logout"><a style="text-decoration: none; color:white;" href="../config/logout.php">Logout</a></button>
        </div>
      </header>

      <main class="painel">
        <div class="container">
          <div class="cabecalho-painel">
            <h2>Painel Administrativo</h2>
          </div>

          <div class="filtro-cidade">
            <label for="estado">Estado:</label>
            <select id="estado">
              <option value="todos">Todos</option>
              <?php foreach ($estados as $uf): ?>
                <option value="<?= htmlspecialchars($uf) ?>"><?= htmlspecialchars($uf) ?></option>
              <?php endforeach; ?>
            </select>

            <label for="cidade">Cidade:</label>
            <select id="cidade">
              <option value="todas">Todas</option>
            </select>
          </div>

          <div class="tabela">
            <h3>Comunidades Cadastradas</h3>
            <div class="tabela-scroll">
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
                  <?php foreach ($comunidades as $com): ?>
                    <tr>
                      <td><?= htmlspecialchars($com['comunidade']) ?></td>
                      <td><?= htmlspecialchars($com['cidade']) ?></td>
                      <td><?= htmlspecialchars($com['estado']) ?></td>
                      <td><button class="btn-detalhes">Ver Detalhes</button></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal" style="display:none;">
      <div class="modal-content">
        <span class="close" onclick="fecharModal()">&times;</span>
        <h2 id="modal-titulo">Detalhes da Comunidade</h2>
        <div id="detalhes-comunidade" style="margin-top: 20px; font-size: 16px;"></div>
      </div>
    </div>

    <!-- JavaScript -->
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const comunidades = <?= json_encode($comunidades, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
        const cidadesPorEstado = <?= json_encode($cidadesPorEstado, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;

        function atualizarCidades() {
          const estado = document.getElementById('estado').value;
          const cidadeSelect = document.getElementById('cidade');
          cidadeSelect.innerHTML = '<option value="todas">Todas</option>';

          if (estado !== 'todos' && cidadesPorEstado[estado]) {
            cidadesPorEstado[estado].forEach(cidade => {
              const option = document.createElement('option');
              option.value = cidade;
              option.textContent = cidade;
              cidadeSelect.appendChild(option);
            });
          }
          filtrarTabela(); // filtrar tabela ao atualizar cidades
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

        function abrirModal(comunidade) {
          document.getElementById("modal").style.display = "flex";
          document.getElementById("modal-titulo").innerText = `Comunidade: ${comunidade.comunidade}`;
          const detalhes = `
        <p><strong>Educação:</strong> ${comunidade.educacao}</p>
        <p><strong>Acesso à Água e Esgoto:</strong> ${comunidade.agua}</p>
        <p><strong>Renda Média:</strong> R$ ${parseFloat(comunidade.renda).toFixed(2)}</p>
        <p><strong>Saúde:</strong> ${comunidade.saude}</p>
        <p><strong>Moradia:</strong> ${comunidade.moradia}</p>
        <p><strong>Emprego:</strong> ${comunidade.emprego}</p>
      `;
          document.getElementById("detalhes-comunidade").innerHTML = detalhes;
        }

        function fecharModal() {
          document.getElementById("modal").style.display = "none";
        }

        // Event listeners corretamente atribuídos após o DOM estar carregado
        document.getElementById('estado').addEventListener('change', atualizarCidades);
        document.getElementById('cidade').addEventListener('change', filtrarTabela);

        document.querySelectorAll(".btn-detalhes").forEach((btn, index) => {
          btn.addEventListener("click", () => {
            const comunidade = comunidades[index];
            abrirModal(comunidade);
          });
        });

        // Inicialização
        atualizarCidades();
        filtrarTabela();

        // Tornando acessíveis globalmente se necessário
        window.fecharModal = fecharModal;
      });
    </script>

  </body>

  </html>