CREATE SCHEMA IF NOT EXISTS ProjetoTray DEFAULT CHARACTER SET utf8;
USE ProjetoTray;

-- -----------------------------------------------------
-- Tabela tipo (tipo de login, se é funcionario ou cliente)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.tipo (
  idTipo INT NOT NULL AUTO_INCREMENT,
  nomeTipo VARCHAR(45) NOT NULL,
  PRIMARY KEY (idTipo));

-- -----------------------------------------------------
-- Tabela login
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.login (
  idLogin INT NOT NULL AUTO_INCREMENT,
  emailLogin VARCHAR(45) NOT NULL,
  senhaLogin VARCHAR(45) NOT NULL,
  tipo_idTipo INT NOT NULL, -- Recebe da tabela tipo
  PRIMARY KEY (idLogin),
  INDEX fk_login_tipo1_idx (tipo_idTipo ASC) VISIBLE,
  CONSTRAINT fk_login_tipo1
    FOREIGN KEY (tipo_idTipo)
    REFERENCES ProjetoTray.tipo (idTipo)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela de funcionários
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.funcionario (
  idFunc INT NOT NULL AUTO_INCREMENT,
  nomeFunc VARCHAR(45) NOT NULL,
  cargoFunc VARCHAR(45) NOT NULL,
  login_idLogin INT NOT NULL, -- Recebe seu id de login
  PRIMARY KEY (idFunc),
  INDEX fk_funcionario_login1_idx (login_idLogin ASC) VISIBLE,
  CONSTRAINT fk_funcionario_login1
    FOREIGN KEY (login_idLogin)
    REFERENCES ProjetoTray.login (idLogin)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela cliente
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.cliente (
  idCli INT NOT NULL AUTO_INCREMENT,
  nomeCli VARCHAR(45) NOT NULL,
  empresaCli VARCHAR(45) NOT NULL,
  telefoneCli VARCHAR(45) NOT NULL,
  login_idLogin INT NOT NULL, -- Recebe seu id de login
  PRIMARY KEY (idCli),
  INDEX fk_cliente_login1_idx (login_idLogin ASC) VISIBLE,
  CONSTRAINT fk_cliente_login1
    FOREIGN KEY (login_idLogin)
    REFERENCES ProjetoTray.login (idLogin)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela de projetos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.projeto (
  idProj INT NOT NULL AUTO_INCREMENT,
  nomeProj VARCHAR(45) NOT NULL,
  descProj VARCHAR(100) NOT NULL,
  PRIMARY KEY (idProj));

-- -----------------------------------------------------
-- Tabela de notificações
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.notificacao (
  idNot INT NOT NULL AUTO_INCREMENT,
  assuntoNot VARCHAR(45) NOT NULL,
  conteudoNot VARCHAR(200) NOT NULL,
  remetenteNot INT NOT NULL, -- Vai salvar o id de quem enviou
  PRIMARY KEY (idNot),
  INDEX fk_notificacao_login1_idx (remetenteNot ASC) VISIBLE,
  CONSTRAINT fk_notificacao_login1
    FOREIGN KEY (remetenteNot)
    REFERENCES ProjetoTray.login (idLogin)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela entre funcionário e projeto (pois um funcionário pode estar em vários projetos e um projeto pode ter vários funcionários)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.funcionario_has_projeto (
  funcionario_idFunc INT NOT NULL,
  projeto_idProj INT NOT NULL,
  PRIMARY KEY (funcionario_idFunc, projeto_idProj),
  INDEX fk_funcionario_has_projeto_projeto1_idx (projeto_idProj ASC) VISIBLE,
  INDEX fk_funcionario_has_projeto_funcionario1_idx (funcionario_idFunc ASC) VISIBLE,
  CONSTRAINT fk_funcionario_has_projeto_funcionario1
    FOREIGN KEY (funcionario_idFunc)
    REFERENCES ProjetoTray.funcionario (idFunc)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_funcionario_has_projeto_projeto1
    FOREIGN KEY (projeto_idProj)
    REFERENCES ProjetoTray.projeto (idProj)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela entre cliente e projeto (pois um cliente pode ter vários projetos e um projeto pode ter vários cliente)
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.cliente_has_projeto (
  cliente_idCli INT NOT NULL,
  projeto_idProj INT NOT NULL,
  PRIMARY KEY (cliente_idCli, projeto_idProj),
  INDEX fk_cliente_has_projeto_projeto1_idx (projeto_idProj ASC) VISIBLE,
  INDEX fk_cliente_has_projeto_cliente1_idx (cliente_idCli ASC) VISIBLE,
  CONSTRAINT fk_cliente_has_projeto_cliente1
    FOREIGN KEY (cliente_idCli)
    REFERENCES ProjetoTray.cliente (idCli)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_cliente_has_projeto_projeto1
    FOREIGN KEY (projeto_idProj)
    REFERENCES ProjetoTray.projeto (idProj)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela das colunas dos projetos
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.coluna (
  idCol INT NOT NULL AUTO_INCREMENT,
  nomeCol VARCHAR(45) NOT NULL,
  PRIMARY KEY (idCol));

-- -----------------------------------------------------
-- Tabela das tarefas que ficam nas colunas
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.tarefa (
  idTarefa INT NOT NULL AUTO_INCREMENT,
  nomeTarefa VARCHAR(45) NOT NULL,
  descTarefa VARCHAR(100) NULL,
  PRIMARY KEY (idTarefa));

-- -----------------------------------------------------
-- Tabela entre projeto, coluna e tarefa
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.projeto_has_coluna_has_tarefa (
  projeto_idProj INT NOT NULL,
  coluna_idCol INT NOT NULL,
  tarefa_idTarefa INT NOT NULL,
  estado_tarefa INT NOT NULL,
  PRIMARY KEY (projeto_idProj, coluna_idCol, tarefa_idTarefa),
  INDEX fk_projeto_has_tarefa_tarefa1_idx (tarefa_idTarefa ASC) VISIBLE,
  INDEX fk_projeto_has_tarefa_projeto1_idx (projeto_idProj ASC) VISIBLE,
  INDEX fk_projeto_has_tarefa_coluna1_idx (coluna_idCol ASC) VISIBLE,
  CONSTRAINT fk_projeto_has_tarefa_projeto1
    FOREIGN KEY (projeto_idProj)
    REFERENCES ProjetoTray.projeto (idProj)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_projeto_has_tarefa_tarefa1
    FOREIGN KEY (tarefa_idTarefa)
    REFERENCES ProjetoTray.tarefa (idTarefa)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_projeto_has_tarefa_coluna1
    FOREIGN KEY (coluna_idCol)
    REFERENCES ProjetoTray.coluna (idCol)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela de destinatário das notificações
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS ProjetoTray.destinatario (
  notificacao_idNot INT NOT NULL, -- id da notificação que o destinatário vai receber
  login_idLogin INT NOT NULL, -- id de quem vai receber
  PRIMARY KEY (notificacao_idNot, login_idLogin),
  INDEX fk_notificacao_has_login_login1_idx (login_idLogin ASC) VISIBLE,
  INDEX fk_notificacao_has_login_notificacao1_idx (notificacao_idNot ASC) VISIBLE,
  CONSTRAINT fk_notificacao_has_login_notificacao1
    FOREIGN KEY (notificacao_idNot)
    REFERENCES ProjetoTray.notificacao (idNot)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT fk_notificacao_has_login_login1
    FOREIGN KEY (login_idLogin)
    REFERENCES ProjetoTray.login (idLogin)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);

-- -----------------------------------------------------
-- Tabela de destinatário das notificações
-- -----------------------------------------------------
CREATE TABLE sugestoes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  id_Cli INT NOT NULL,
  tarefa VARCHAR(255) NOT NULL,
  categoria ENUM('adicionar', 'incrementar', 'remover') NOT NULL,
  mensagem TEXT NOT NULL,
  status ENUM('em_analise', 'com_feedback') DEFAULT 'em_analise',
  resposta_funcionario TEXT,
  feedback ENUM('positivo', 'negativo'),
  data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (id_Cli) REFERENCES cliente(idCli)
);

-- -----------------------------------------------------
-- Tabela de histórico de tarefas
-- -----------------------------------------------------
CREATE TABLE historico_tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    projeto_id INT NOT NULL,
    tarefa_id INT,
    acao ENUM('Adicionada', 'Modificada', 'Removida') NOT NULL,
    nome_tarefa VARCHAR(255) NOT NULL,
    data_hora DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (projeto_id) REFERENCES projeto(idProj),
    FOREIGN KEY (tarefa_id) REFERENCES tarefa(idTarefa)
);



-- -----------------------------------------------------
-- Valores pré-setados
-- -----------------------------------------------------
INSERT INTO tipo (nomeTipo) VALUES ("funcionario"), ("cliente");

INSERT INTO login (emailLogin, senhaLogin, tipo_idTipo) VALUES 
("email@teste.com", "1234", 1),
("email2@teste.com", "5678", 1),
("email3@teste.com", "9012", 2),
("email4@teste.com", "3456", 2);

INSERT INTO funcionario (nomeFunc, cargoFunc, login_idLogin) VALUES
("Cleiton", "Desenvolvedor Junior", 1),
("Mateus", "Engenheiro de Software", 2);

INSERT INTO cliente (nomeCli, empresaCli, telefoneCli, login_idLogin) VALUES
("Rodolfo", "Varejo Marília", "14999990000", 3),
("Lucas", "Lojas Giga", "15000002222", 4);

INSERT INTO projeto (nomeProj, descProj) VALUES
("E-commerce Lojas Giga", "Site para vendas online"),
("E-commerce Varejo Marília", "Melhoria do último projeto");

INSERT INTO notificacao (assuntoNot, conteudoNot, remetenteNot) VALUES
("Modificações", "Adicionei mudanças que gostaria que fizessem no meu site", 3),
("Remoção", "Não gostei de algumas coisas que adicionaram, não combina com meu trabalho, segue mudanças", 4);

INSERT INTO funcionario_has_projeto (funcionario_idFunc, projeto_idProj) VALUES
(1, 1),
(2, 1),
(2, 2);

INSERT INTO cliente_has_projeto (cliente_idCli, projeto_idProj) VALUES
(1, 2),
(2, 1);

INSERT INTO coluna (nomeCol) VALUES
("Alinhamento"),
("Fase 1");

INSERT INTO tarefa (nomeTarefa, descTarefa) VALUES
("Diagramação", "Fazer diagramas para o desenvolvimento do site"),
("Fazer classes", "Implementar as classes previstas");


INSERT INTO projeto_has_coluna_has_tarefa (projeto_idProj, coluna_idCol, tarefa_idTarefa, estado_tarefa) VALUES
(1, 1, 1, 1),
(2, 1, 2, 0),
(1, 2, 1, 2),
(2, 2, 2, 0);

INSERT INTO destinatario (notificacao_idNot, login_idLogin) VALUES
(1, 1),
(1, 2),
(2, 2);
