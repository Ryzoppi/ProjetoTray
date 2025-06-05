<?php
include 'conexao.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
}

// Listagem de Funcionários
$sql = "SELECT f.idFunc, f.nomeFunc, f.cargoFunc, l.emailLogin 
        FROM funcionario f
        JOIN login l ON f.login_idLogin = l.idLogin
        ORDER BY f.idFunc";
$comando = $pdo->query($sql);
$lista_funcionarios = $comando->fetchAll();

// Título com botão "Novo Funcionario"
echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h2 style="margin: 0;">Lista de Funcionários</h2>
        <button onclick="document.getElementById(\'modal-novo-funcionario\').style.display=\'block\'" 
                style="padding: 4px 10px; font-size: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
            + Novo Funcionario
        </button>
      </div>';

// Listagem de funcionários
if (count($lista_funcionarios) > 0) {
    echo "<ul>";
    foreach ($lista_funcionarios as $funcionario) {
        $id = $funcionario['idFunc'];
        $nome = $funcionario['nomeFunc'];
        $cargo = $funcionario['cargoFunc'];
        $email = $funcionario['emailLogin'];
        
        echo "<li>
                <span class='nome'>{$nome}</span> - {$cargo} - <span class='email'>({$email})</span>
                <button onclick=\"abrirModalFuncionario({$id}, '{$nome}', '{$cargo}', '{$email}')\">Editar</button>
              </li>";
    }
    echo "</ul>";
} else {
    echo "Nenhum funcionário encontrado.";
}
?>

<!-- Modal de Cadastro de Novo Funcionário -->
<div id="modal-novo-funcionario" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3>Novo Funcionário</h3>
    <form method="POST" action="salvar_funcionario.php">
        <label>Nome:</label><br>
        <input type="text" name="nomeFunc" required><br>
        <label>Cargo:</label><br>
        <input type="text" name="cargoFunc" required><br>
        <label>Email:</label><br>
        <input type="email" name="emailLogin" required><br>
        <label>Senha:</label><br>
        <input type="password" name="senhaLogin" required><br><br>
        <button type="submit">Salvar</button>
        <button type="button" onclick="document.getElementById('modal-novo-funcionario').style.display='none'">Cancelar</button>
    </form>
</div>

<!-- Modal de Edição de Funcionário -->
<div id="modal-funcionario" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3>Editar Funcionário</h3>
    <form method="POST" action="editar_funcionario.php" style="margin-bottom: 10px;">
        <input type="hidden" name="idFunc" id="edit-idFunc">
        <label>Nome:</label><br>
        <input type="text" name="nomeFunc" id="edit-nomeFunc"><br>
        <label>Cargo:</label><br>
        <input type="text" name="cargoFunc" id="edit-cargoFunc"><br>
        <label>Email:</label><br>
        <input type="email" name="emailLogin" id="edit-emailFunc"><br><br>
        <button type="submit">Salvar</button>
    </form>

    <!-- Botão de Deletar -->
    <form method="POST" action="delete_funcionario.php" onsubmit="return confirm('Tem certeza que deseja deletar este funcionário?');">
        <input type="hidden" name="idFunc" id="delete-idFunc">
        <button type="submit" style="background-color:red; color:white;">Deletar</button>
        <button type="button" onclick="fecharModalFuncionario()">Cancelar</button>
    </form>
</div>

<script>
    function abrirModalFuncionario(id, nome, cargo, email) {
        document.getElementById('edit-idFunc').value = id;
        document.getElementById('edit-nomeFunc').value = nome;
        document.getElementById('edit-cargoFunc').value = cargo;
        document.getElementById('edit-emailFunc').value = email;
        document.getElementById('delete-idFunc').value = id;
        document.getElementById('modal-funcionario').style.display = 'block';
    }

    function fecharModalFuncionario() {
        document.getElementById('modal-funcionario').style.display = 'none';
    }
</script>
