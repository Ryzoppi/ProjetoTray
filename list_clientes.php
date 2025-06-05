<?php
include 'conexao.php';

// Ativar erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Consulta clientes
$sql = "SELECT c.idCli, c.nomeCli, c.empresaCli, c.telefoneCli, l.emailLogin 
        FROM cliente c
        INNER JOIN login l ON c.login_idLogin = l.idLogin
        ORDER BY c.idCli";
$comando = $pdo->query($sql);
$lista_cliente = $comando->fetchAll(PDO::FETCH_ASSOC);

// Título com botão
echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
        <h2 style="margin: 0;">Lista de Clientes</h2>
        <button onclick="document.getElementById(\'modal-novo-cliente\').style.display=\'block\'" 
                style="padding: 4px 10px; font-size: 12px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
            + Novo Cliente
        </button>
      </div>';

// Lista
if (count($lista_cliente) > 0) {
    echo "<ul>";
    foreach ($lista_cliente as $cliente) {
        $id = $cliente['idCli'];
        $nome = $cliente['nomeCli'];
        $empresa = $cliente['empresaCli'];
        $telefone = $cliente['telefoneCli'];
        $email = $cliente['emailLogin'];

        echo "<li>
                <span class='nome'>{$nome}</span> - {$empresa} - {$telefone} - <span class='email'>({$email})</span>
                <button onclick=\"abrirModalCliente({$id}, '{$nome}', '{$empresa}', '{$telefone}', '{$email}')\">Editar</button>
              </li>";
    }
    echo "</ul>";
} else {
    echo "Nenhum cliente encontrado.";
}
?>

<!-- Modal de Novo Cliente -->
<div id="modal-novo-cliente" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3>Novo Cliente</h3>
    <form method="POST" action="salvar_cliente.php">
        <label>Nome:</label><br>
        <input type="text" name="nomeCli" required><br>
        <label>Empresa:</label><br>
        <input type="text" name="empresaCli" required><br>
        <label>Telefone:</label><br>
        <input type="text" name="telefoneCli" required><br>
        <label>Email:</label><br>
        <input type="email" name="emailLogin" required><br>
        <label>Senha:</label><br>
        <input type="password" name="senhaLogin" required><br><br>
        <button type="submit">Salvar</button>
        <button type="button" onclick="document.getElementById('modal-novo-cliente').style.display='none'">Cancelar</button>
    </form>
</div> 

<!-- Modal de Edição -->
<div id="modal-cliente" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%); background:#fff; padding:20px; border:1px solid #ccc; z-index:1000;">
    <h3>Editar Cliente</h3>
    <form method="POST" action="editar_cliente.php" style="margin-bottom: 10px;">
        <input type="hidden" name="idCli" id="edit-idCli">
        <label>Nome:</label><br>
        <input type="text" name="nomeCli" id="edit-nomeCli" required><br>
        <label>Empresa:</label><br>
        <input type="text" name="empresaCli" id="edit-empresaCli" required><br>
        <label>Telefone:</label><br>
        <input type="text" name="telefoneCli" id="edit-telefoneCli" required><br>
        <label>Email:</label><br>
        <input type="email" name="emailLogin" id="edit-emailCli" required><br><br>
        <button type="submit">Salvar</button>
    </form>

    <!-- Botão de Deletar -->
    <form method="POST" action="deletar_cliente.php" onsubmit="return confirm('Tem certeza que deseja deletar este cliente?');">
        <input type="hidden" name="idCli" id="delete-idCli">
        <button type="submit" style="background-color:red; color:white;">Deletar</button>
        <button type="button" onclick="fecharModalCliente()">Cancelar</button>
    </form>
</div>

<script>
    function abrirModalCliente(id, nome, empresa, telefone, email) {
        document.getElementById('edit-idCli').value = id;
        document.getElementById('edit-nomeCli').value = nome;
        document.getElementById('edit-empresaCli').value = empresa;
        document.getElementById('edit-telefoneCli').value = telefone;
        document.getElementById('edit-emailCli').value = email;
        document.getElementById('delete-idCli').value = id;
        document.getElementById('modal-cliente').style.display = 'block';
    }

    function fecharModalCliente() {
        document.getElementById('modal-cliente').style.display = 'none';
    }
</script>
