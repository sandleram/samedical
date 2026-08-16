-- --------------------------------------------------------
-- Servidor:                     samed_pro.vpshost9932.mysql.dbaas.com.br
-- Versão do servidor:           5.7.32-35-log - Percona Server (GPL), Release 35, Revision 5688520
-- OS do Servidor:               Linux
-- HeidiSQL Versão:              12.21.0.7344
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Copiando estrutura para tabela samed_pro.absenteismo
CREATE TABLE IF NOT EXISTS `absenteismo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `importacao_id` bigint(20) DEFAULT NULL,
  `beneficiario_id` int(11) NOT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `matricula` varchar(45) DEFAULT NULL,
  `documento_id` varchar(100) DEFAULT NULL,
  `motivo_id` varchar(100) DEFAULT NULL,
  `hospital_clinica` varchar(45) DEFAULT NULL,
  `nome_colaborador` varchar(255) DEFAULT NULL,
  `data_saida` date DEFAULT NULL,
  `data_retorno` date DEFAULT NULL,
  `dias_calculados` int(11) DEFAULT NULL,
  `hora_saida` time DEFAULT NULL,
  `hora_retorno` time DEFAULT NULL,
  `horas_calculadas` time DEFAULT NULL,
  `qtde_dias_atestado` int(11) DEFAULT NULL,
  `cid` varchar(45) DEFAULT NULL,
  `cid_id` int(11) DEFAULT NULL,
  `especialidade_id` varchar(100) DEFAULT NULL,
  `emissor_id` varchar(100) DEFAULT NULL,
  `profissional` varchar(45) DEFAULT NULL,
  `num_crm` varchar(45) DEFAULT NULL,
  `tipo_absenteismo_id` varchar(100) DEFAULT NULL,
  `departamento_id` varchar(100) DEFAULT NULL,
  `cargo_id` varchar(100) DEFAULT NULL,
  `setor_id` varchar(100) DEFAULT NULL,
  `parte_corpo_id` int(11) DEFAULT NULL,
  `observacao` text,
  `arquivo` varchar(255) DEFAULT NULL,
  `situacao` varchar(150) DEFAULT NULL,
  `data_atualizacao` date DEFAULT NULL,
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_beneficio_previdenciario_importacao1_idx` (`importacao_id`),
  KEY `fk_beneficio_previdenciario_beneficiario1_idx` (`beneficiario_id`),
  KEY `fk_beneficio_previdenciario_empresa1_idx` (`empresa_id`),
  CONSTRAINT `fk_absenteismo_beneficiario` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiario` (`id`),
  CONSTRAINT `fk_absenteismo_importacao` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.afastado
CREATE TABLE IF NOT EXISTS `afastado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `importacao_id` bigint(20) DEFAULT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `beneficiario_id` int(11) NOT NULL,
  `data_inicio_afastamento` date DEFAULT NULL,
  `data_fim_afastamento` date DEFAULT NULL,
  `cid` varchar(45) DEFAULT NULL,
  `tipo_afastamento` varchar(45) DEFAULT NULL COMMENT 'Tipo Afastamento (se doença, acidente, licença maternidade ou aposentadoria)\n',
  `assistencia_medica` varchar(45) DEFAULT NULL COMMENT 'Assistência Médica (nome)\n',
  `plano_assistencia_medica` varchar(45) DEFAULT NULL COMMENT 'Plano assistencia médica (nome)\n',
  `situacao` char(2) NOT NULL DEFAULT 'A' COMMENT '- Afastado (A)\n- Retorno ao trabalho (RT)',
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `usuario_criador_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `anexo` varchar(255) DEFAULT NULL,
  `blob_id` int(11) DEFAULT NULL,
  `acao_trabalhista` int(11) DEFAULT NULL,
  `acao_inss` int(11) DEFAULT NULL,
  `limbo_previdenciario` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_afastado_importacao1_idx` (`importacao_id`),
  KEY `fk_afastado_beneficiario1_idx` (`beneficiario_id`),
  KEY `fk_afastado_empresa1_idx` (`empresa_id`),
  CONSTRAINT `fk_afastado_beneficiario1` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiario` (`id`),
  CONSTRAINT `fk_afastado_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  CONSTRAINT `fk_afastado_importacao1` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13124 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.agendamento
CREATE TABLE IF NOT EXISTS `agendamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data_hora` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `usuario_id` bigint(20) NOT NULL,
  `usuario_agendamento_id` bigint(20) NOT NULL,
  `tarefa_id` int(11) DEFAULT NULL,
  `atendimento_id` int(11) NOT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_atualizacao_id` bigint(20) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=101417 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.amb_proced
CREATE TABLE IF NOT EXISTS `amb_proced` (
  `id` bigint(20) NOT NULL,
  `cod_amb` char(12) NOT NULL,
  `termo_busca` varchar(70) DEFAULT NULL,
  `descricao` varchar(2000) DEFAULT NULL,
  `tipo_procedimento` varchar(2000) DEFAULT NULL,
  `subgrupo` varchar(2000) DEFAULT NULL,
  `termo_ca` varchar(2000) DEFAULT NULL,
  `classificacao_tiss` varchar(100) DEFAULT NULL,
  `cod_tiss` char(3) DEFAULT NULL,
  `operadora_id` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tuss` (`cod_tiss`),
  KEY `cod_amb` (`cod_amb`),
  KEY `operadora_id` (`operadora_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.atendimento
CREATE TABLE IF NOT EXISTS `atendimento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` text,
  `descricao_origin` text,
  `tipo_atendimento` int(11) DEFAULT NULL,
  `hora_inicio` datetime DEFAULT NULL,
  `hora_fim` datetime DEFAULT NULL,
  `tempo_trabalho` int(11) DEFAULT NULL,
  `cid` varchar(6) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `forma_atendimento` int(11) DEFAULT NULL,
  `beneficiario_id` int(11) NOT NULL,
  `status_atendimento` int(11) NOT NULL,
  `at_horas` int(11) DEFAULT '0',
  `at_minutos` int(11) DEFAULT '0',
  `data_conclusao` datetime DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `anexo` varchar(255) DEFAULT NULL,
  `blob_id` int(11) DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_atualizacao_id` bigint(20) DEFAULT NULL,
  `descricao_agendamento` text,
  PRIMARY KEY (`id`),
  KEY `fk_atendimento_beneficiario1_idx` (`beneficiario_id`),
  KEY `fk_atendimento_usuario1_idx` (`usuario_id`),
  CONSTRAINT `fk_atendimento_beneficiario1` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiario` (`id`),
  CONSTRAINT `fk_atendimento_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=121190 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.beneficiario
CREATE TABLE IF NOT EXISTS `beneficiario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `nome_social` varchar(60) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `pis` varchar(45) DEFAULT NULL,
  `nome_mae` varchar(100) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `beneficio` varchar(150) DEFAULT NULL,
  `valor_do_seguro` decimal(10,2) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `tipo_de_seguro` varchar(150) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(10) DEFAULT NULL,
  `conta` varchar(20) DEFAULT NULL,
  `tipo_de_conta` varchar(20) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `numero` varchar(100) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(150) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `telefone_tipo` varchar(10) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `telefone1_tipo` varchar(10) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2_tipo` varchar(10) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `telefone3_tipo` varchar(10) DEFAULT NULL,
  `telefone3` varchar(15) DEFAULT NULL,
  `telefone4_tipo` varchar(10) DEFAULT NULL,
  `telefone4` varchar(15) DEFAULT NULL,
  `telefone5_tipo` varchar(10) DEFAULT NULL,
  `telefone5` varchar(15) DEFAULT NULL,
  `telefone6_tipo` varchar(10) DEFAULT NULL,
  `telefone6` varchar(15) DEFAULT NULL,
  `telefone7_tipo` varchar(10) DEFAULT NULL,
  `telefone7` varchar(15) DEFAULT NULL,
  `telefone8_tipo` varchar(10) DEFAULT NULL,
  `telefone8` varchar(15) DEFAULT NULL,
  `telefone9_tipo` varchar(10) DEFAULT NULL,
  `telefone9` varchar(15) DEFAULT NULL,
  `sexo` varchar(15) DEFAULT NULL,
  `altura` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `imc` double(4,2) DEFAULT NULL,
  `profissao` varchar(50) DEFAULT NULL,
  `ocupacao` varchar(50) DEFAULT NULL,
  `pessoa_politicamente_exposta` varchar(60) DEFAULT NULL,
  `realiza_alguma_atividade_perigosa_na_profissao` varchar(60) DEFAULT NULL,
  `possui_deficiencia` varchar(60) DEFAULT NULL,
  `beneficiario1` varchar(150) DEFAULT NULL,
  `parentesco1` varchar(60) DEFAULT NULL,
  `beneficiario2` varchar(150) DEFAULT NULL,
  `parentesco2` varchar(60) DEFAULT NULL,
  `beneficiario3` varchar(150) DEFAULT NULL,
  `parentesco3` varchar(60) DEFAULT NULL,
  `beneficiario4` varchar(150) DEFAULT NULL,
  `parentesco4` varchar(60) DEFAULT NULL,
  `observacao` text,
  `situacao` varchar(150) DEFAULT NULL,
  `importacao_id` bigint(20) DEFAULT NULL,
  `competencia` date DEFAULT NULL COMMENT 'Sempre iniciar a data com dia 01',
  `chave_beneficiario` varchar(250) DEFAULT NULL,
  `grupo_familiar_id` varchar(100) DEFAULT NULL,
  `cod_matricula` varchar(100) DEFAULT NULL,
  `dt_inclusao` date DEFAULT NULL,
  `dt_exclusao` date DEFAULT NULL,
  `dt_admissao` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `faixa_etaria_ans_id` int(11) DEFAULT NULL,
  `grau_parentesco_id` varchar(50) DEFAULT NULL,
  `ds_grau_parentesco` varchar(50) DEFAULT NULL,
  `nome_titular` varchar(250) DEFAULT NULL,
  `cpf_titular` varchar(11) DEFAULT NULL,
  `estado_civil_id` int(11) DEFAULT NULL,
  `ds_estado_civil` varchar(250) DEFAULT NULL,
  `plano_id` varchar(100) DEFAULT NULL,
  `ds_plano` varchar(100) DEFAULT NULL,
  `elegibilidade` varchar(50) DEFAULT NULL,
  `cod_cns` varchar(50) DEFAULT NULL,
  `numero_nascido_vivo` varchar(50) DEFAULT NULL,
  `cod_operadora` int(11) DEFAULT NULL,
  `operadora` varchar(100) DEFAULT NULL,
  `ds_faixa_etaria_ans` varchar(100) DEFAULT NULL,
  `ds_tipo_acomodacao` varchar(250) DEFAULT NULL,
  `tipo_movimentacao` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `cod_u_seg` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `codigo_empresa` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `relacao_dep` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `relacao_dep_digito` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `lotacao_do_funcionario` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `motivo_exclusao` varchar(45) DEFAULT NULL,
  `cod_empresa` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `num_contrato` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `carteirinha` varchar(45) DEFAULT NULL,
  `carteirinha_titular` varchar(45) DEFAULT NULL,
  `cod_permanencia` varchar(10) DEFAULT NULL,
  `desc_permanencia` varchar(60) DEFAULT NULL,
  `remido` varchar(3) DEFAULT NULL,
  `cod_subfatura` varchar(50) DEFAULT NULL,
  `processo` varchar(50) NOT NULL DEFAULT '1',
  `vl_ambulatorio` decimal(10,2) NOT NULL DEFAULT '1.00',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_criador_id` int(11) NOT NULL DEFAULT '1',
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Ativo\n2 - excluido\n0 - inativo\n\n',
  PRIMARY KEY (`id`),
  KEY `fk_beneficiario_cliente1_idx` (`cliente_id`),
  KEY `fk_beneficiario_usuario1_idx` (`usuario_criador_id`),
  KEY `fk_beneficiario_usuario2_idx` (`usuario_atualizacao_id`),
  KEY `fk_beneficiario_importacao1_idx` (`importacao_id`),
  KEY `fk_beneficiario_empresa1_idx` (`empresa_id`),
  KEY `fk_beneficiario_operadora1_idk` (`operadora`),
  KEY `fk_beneficiario_chave_beneficiario1_idk` (`chave_beneficiario`),
  CONSTRAINT `fk_beneficiario_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_beneficiario_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  CONSTRAINT `fk_beneficiario_importacao1` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_beneficiario_usuario1` FOREIGN KEY (`usuario_criador_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `fk_beneficiario_usuario2` FOREIGN KEY (`usuario_atualizacao_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=549635 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.beneficiario_plano
CREATE TABLE IF NOT EXISTS `beneficiario_plano` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `beneficiario_id` int(11) NOT NULL,
  `plano_id` int(11) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_beneficiario_plano_beneficiario1_idx` (`beneficiario_id`),
  KEY `fk_beneficiario_plano_plano1_idx` (`plano_id`),
  CONSTRAINT `fk_beneficiario_plano_beneficiario1` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiario` (`id`),
  CONSTRAINT `fk_beneficiario_plano_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.beneficio
CREATE TABLE IF NOT EXISTS `beneficio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(100) NOT NULL,
  `breakeven` int(11) DEFAULT NULL,
  `contrato` varchar(50) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cancelamento` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `cliente_id` int(11) NOT NULL,
  `operadora_id` int(11) NOT NULL,
  `tipo_beneficio_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_beneficio_cliente1_idx` (`cliente_id`),
  KEY `fk_beneficio_operadora1_idx` (`operadora_id`),
  KEY `fk_beneficio_tipo_beneficio1_idx` (`tipo_beneficio_id`),
  CONSTRAINT `fk_beneficio_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_beneficio_operadora1` FOREIGN KEY (`operadora_id`) REFERENCES `operadora` (`id`),
  CONSTRAINT `fk_beneficio_tipo_beneficio1` FOREIGN KEY (`tipo_beneficio_id`) REFERENCES `tipo_beneficio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.beneficio_previdenciario
CREATE TABLE IF NOT EXISTS `beneficio_previdenciario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `importacao_id` bigint(20) DEFAULT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `beneficiario_id` int(11) NOT NULL,
  `data_proxima_pericia` date DEFAULT NULL,
  `num_requerimento` bigint(20) DEFAULT NULL,
  `nb` bigint(20) DEFAULT NULL COMMENT 'NB -  Numero benefício previdenciário\n',
  `nit` bigint(20) DEFAULT NULL COMMENT 'NIT (PIS)',
  `especie` varchar(200) DEFAULT NULL COMMENT 'Espécie Benefício previdênciario\n',
  `especie_bp_id` int(11) NOT NULL,
  `situacao` varchar(45) DEFAULT NULL COMMENT 'Situação Benefício no INSS\n',
  `data_entrada_requerimento` date DEFAULT NULL COMMENT 'Dt. Entrada Requerimento benefício no INSS\n',
  `data_inicio` date DEFAULT NULL COMMENT 'Dt. Início beneficio do beneficio\n',
  `data_despacho` date DEFAULT NULL COMMENT 'Dt. Despacho (não preciso deste campo)\n',
  `data_realizacao_pericia` date DEFAULT NULL COMMENT 'Data de realização da perícia',
  `conclusao_pericia_medica` text,
  `data_limite` date DEFAULT NULL,
  `data_indeferimento` date DEFAULT NULL,
  `data_cessacao` date DEFAULT NULL,
  `nexo_tecnico` text,
  `data_atualizacao` date DEFAULT NULL,
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `contestado` int(11) DEFAULT NULL,
  `contestado_protocolo` varchar(30) DEFAULT NULL,
  `cat` int(11) DEFAULT NULL,
  `cat_tipo_acidente` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_beneficio_previdenciario_importacao1_idx` (`importacao_id`),
  KEY `fk_beneficio_previdenciario_beneficiario1_idx` (`beneficiario_id`),
  KEY `fk_beneficio_previdenciario_especie_bp1_idx` (`especie_bp_id`),
  KEY `fk_beneficio_previdenciario_empresa1_idx` (`empresa_id`),
  CONSTRAINT `fk_beneficio_previdenciario_beneficiario1` FOREIGN KEY (`beneficiario_id`) REFERENCES `beneficiario` (`id`),
  CONSTRAINT `fk_beneficio_previdenciario_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  CONSTRAINT `fk_beneficio_previdenciario_especie_bp1` FOREIGN KEY (`especie_bp_id`) REFERENCES `especie_bp` (`id`),
  CONSTRAINT `fk_beneficio_previdenciario_importacao1` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31304 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.bi
CREATE TABLE IF NOT EXISTS `bi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_empresarial_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `titulo` varchar(20) NOT NULL,
  `subtitulo` varchar(60) DEFAULT NULL,
  `link` varchar(255) NOT NULL,
  `observacao` text,
  `ordem` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_bi_usuario_idx` (`usuario_id`),
  KEY `fk_bi_cliente_idx` (`cliente_id`),
  KEY `fk_bi_grupo_empresarial_idx` (`grupo_empresarial_id`) USING BTREE,
  CONSTRAINT `fk_bi_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_bi_grupo_empresarial` FOREIGN KEY (`grupo_empresarial_id`) REFERENCES `grupo_empresarial` (`id`),
  CONSTRAINT `fk_bi_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.blob
CREATE TABLE IF NOT EXISTS `blob` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `table` varchar(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipo` varchar(100) NOT NULL,
  `tamanho` int(11) NOT NULL,
  `extensao` varchar(6) NOT NULL,
  `blob` longblob NOT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL,
  `usuario_id_atualizacao` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cid
CREATE TABLE IF NOT EXISTS `cid` (
  `id` int(11) NOT NULL,
  `cid` varchar(10) DEFAULT NULL,
  `descricao` varchar(150) DEFAULT NULL,
  `cid_grupos_id` int(11) NOT NULL,
  `cid_ponto` varchar(10) DEFAULT NULL,
  `termo_busca` varchar(20) DEFAULT NULL,
  `atencao_primaria` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cid__cid` (`cid`),
  KEY `fk_cid__cid_grupos_idx` (`cid_grupos_id`),
  CONSTRAINT `fk_cid__cid_grupos` FOREIGN KEY (`cid_grupos_id`) REFERENCES `cid_grupos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cid_grupo
CREATE TABLE IF NOT EXISTS `cid_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `descricao` varchar(110) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cid_grupos
CREATE TABLE IF NOT EXISTS `cid_grupos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `cod_cid_inicial` int(11) NOT NULL DEFAULT '0',
  `cod_cid_final` varchar(45) NOT NULL DEFAULT '0',
  `descricao` varchar(110) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cidade
CREATE TABLE IF NOT EXISTS `cidade` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `estado_id` int(11) NOT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `uf` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cidade_estado1_idx` (`estado_id`),
  CONSTRAINT `fk_cidade_estado1` FOREIGN KEY (`estado_id`) REFERENCES `estado` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5567 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cliente
CREATE TABLE IF NOT EXISTS `cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_empresarial_id` int(11) NOT NULL,
  `nome` varchar(450) NOT NULL,
  `img_logo` varchar(255) DEFAULT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `inscricao_estadual` varchar(25) DEFAULT NULL,
  `inscricao_municipal` varchar(25) DEFAULT NULL,
  `numero_funcionarios` int(11) DEFAULT NULL,
  `descricao` varchar(1024) DEFAULT NULL,
  `porte` varchar(10) DEFAULT NULL COMMENT '<select name="id_porte">\\n          <option value=""></option>\\n                        <option value="3">\\n              Grande (3)              </option>\\n                        <option value="2">\\n              Média (2)              </option>\\n                        <option value="1">\\n              Pequena (1)              </option>\\n                      </select>',
  `faturamento` varchar(50) DEFAULT NULL COMMENT '<select name="id_faturamento">\\n          <option value=""></option>\\n                        <option value="1">\\n              até 120.000,00 (10)</option>\\n                        <option value="2">\\n              120.001,00 a 720.000,00 (20)</option>\\n                        <option value="3">\\n              720.001,00 a 5.000.000,00 (30)</option>\\n                        <option value="4">\\n              mais de 5.000.000,00 (40)</option>\\n                        <option value="5">\\n              Não informado (50)</option>\\n                      </select>',
  `tipo` varchar(15) DEFAULT NULL COMMENT '<select name="id_tipo_empresa">\\n          <option value=""></option>\\n                        <option value="3">\\n              Multinacional (3)              </option>\\n                        <option value="2">\\n              Nacional (2)              </option>\\n                        <option value="1">\\n              RH (1)              </option>\\n                      </select>',
  `endereco` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site` varchar(250) DEFAULT NULL,
  `observacao` text,
  `bi_rh` varchar(255) DEFAULT NULL,
  `acesso_rh` varchar(255) DEFAULT NULL,
  `bi_medico` varchar(255) DEFAULT NULL,
  `token_tv` text,
  `token_apresentacao` text,
  `usuario_criador_id` int(11) DEFAULT NULL COMMENT 'Quem Criou',
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_cancelamento` date DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cliente_conta1_idx` (`grupo_empresarial_id`),
  CONSTRAINT `fk_cliente_grupo_empresarial1` FOREIGN KEY (`grupo_empresarial_id`) REFERENCES `grupo_empresarial` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=309 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cliente_desligamento
CREATE TABLE IF NOT EXISTS `cliente_desligamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_empresarial_id` int(11) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `file_info` varchar(255) NOT NULL,
  `files` varchar(255) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cliente_desligamento_grupo_empresarial1_idx` (`grupo_empresarial_id`),
  KEY `fk_cliente_desligamento_cliente1_idx` (`cliente_id`),
  KEY `fk_cliente_desligamento_usuario1_idx` (`usuario_id`),
  CONSTRAINT `fk_cliente_desligamento_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cliente_desligamento_grupo_empresarial1` FOREIGN KEY (`grupo_empresarial_id`) REFERENCES `grupo_empresarial` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_cliente_desligamento_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.cronicos
CREATE TABLE IF NOT EXISTS `cronicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_tuss` varchar(255) DEFAULT NULL,
  `cod_bradesco` varchar(255) DEFAULT NULL,
  `ds_exame` varchar(255) DEFAULT NULL,
  `frequencia` int(11) DEFAULT NULL,
  `especialidade` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=911 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_beneficiario
CREATE TABLE IF NOT EXISTS `dw_beneficiario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `rg` varchar(20) DEFAULT NULL,
  `cpf` varchar(20) DEFAULT NULL,
  `pis` varchar(45) DEFAULT NULL,
  `nome_mae` varchar(100) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `beneficio` varchar(150) DEFAULT NULL,
  `valor_do_seguro` decimal(10,2) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `tipo_de_seguro` varchar(150) DEFAULT NULL,
  `banco` varchar(50) DEFAULT NULL,
  `agencia` varchar(10) DEFAULT NULL,
  `conta` varchar(20) DEFAULT NULL,
  `tipo_de_conta` varchar(20) DEFAULT NULL,
  `endereco` varchar(200) DEFAULT NULL,
  `numero` varchar(100) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(150) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `telefone_tipo` varchar(10) DEFAULT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `telefone1_tipo` varchar(10) DEFAULT NULL,
  `telefone1` varchar(15) DEFAULT NULL,
  `telefone2_tipo` varchar(10) DEFAULT NULL,
  `telefone2` varchar(15) DEFAULT NULL,
  `telefone3_tipo` varchar(10) DEFAULT NULL,
  `telefone3` varchar(15) DEFAULT NULL,
  `telefone4_tipo` varchar(10) DEFAULT NULL,
  `telefone4` varchar(15) DEFAULT NULL,
  `telefone5_tipo` varchar(10) DEFAULT NULL,
  `telefone5` varchar(15) DEFAULT NULL,
  `telefone6_tipo` varchar(10) DEFAULT NULL,
  `telefone6` varchar(15) DEFAULT NULL,
  `telefone7_tipo` varchar(10) DEFAULT NULL,
  `telefone7` varchar(15) DEFAULT NULL,
  `telefone8_tipo` varchar(10) DEFAULT NULL,
  `telefone8` varchar(15) DEFAULT NULL,
  `telefone9_tipo` varchar(10) DEFAULT NULL,
  `telefone9` varchar(15) DEFAULT NULL,
  `sexo` varchar(15) DEFAULT NULL,
  `altura` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `imc` double(4,2) DEFAULT NULL,
  `profissao` varchar(50) DEFAULT NULL,
  `ocupacao` varchar(50) DEFAULT NULL,
  `pessoa_politicamente_exposta` varchar(60) DEFAULT NULL,
  `realiza_alguma_atividade_perigosa_na_profissao` varchar(60) DEFAULT NULL,
  `possui_deficiencia` varchar(60) DEFAULT NULL,
  `beneficiario1` varchar(150) DEFAULT NULL,
  `parentesco1` varchar(60) DEFAULT NULL,
  `beneficiario2` varchar(150) DEFAULT NULL,
  `parentesco2` varchar(60) DEFAULT NULL,
  `beneficiario3` varchar(150) DEFAULT NULL,
  `parentesco3` varchar(60) DEFAULT NULL,
  `beneficiario4` varchar(150) DEFAULT NULL,
  `parentesco4` varchar(60) DEFAULT NULL,
  `observacao` text,
  `situacao` varchar(150) DEFAULT NULL,
  `importacao_id` bigint(20) DEFAULT NULL,
  `competencia` date DEFAULT NULL COMMENT 'Sempre iniciar a data com dia 01',
  `chave_beneficiario` varchar(250) DEFAULT NULL,
  `grupo_familiar_id` varchar(100) DEFAULT NULL,
  `cod_matricula` varchar(100) DEFAULT NULL,
  `dt_inclusao` date DEFAULT NULL,
  `dt_exclusao` date DEFAULT NULL,
  `dt_admissao` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `faixa_etaria_ans_id` int(11) DEFAULT NULL,
  `grau_parentesco_id` varchar(50) DEFAULT NULL,
  `ds_grau_parentesco` varchar(50) DEFAULT NULL,
  `nome_titular` varchar(250) DEFAULT NULL,
  `cpf_titular` varchar(11) DEFAULT NULL,
  `estado_civil_id` int(11) DEFAULT NULL,
  `ds_estado_civil` varchar(250) DEFAULT NULL,
  `plano_id` varchar(100) DEFAULT NULL,
  `ds_plano` varchar(100) DEFAULT NULL,
  `elegibilidade` varchar(50) DEFAULT NULL,
  `cod_cns` varchar(50) DEFAULT NULL,
  `numero_nascido_vivo` varchar(50) DEFAULT NULL,
  `cod_operadora` int(11) DEFAULT NULL,
  `operadora` varchar(100) DEFAULT NULL,
  `ds_faixa_etaria_ans` varchar(100) DEFAULT NULL,
  `ds_tipo_acomodacao` varchar(250) DEFAULT NULL,
  `tipo_movimentacao` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `cod_u_seg` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `codigo_empresa` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `relacao_dep` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `relacao_dep_digito` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `lotacao_do_funcionario` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `motivo_exclusao` varchar(45) DEFAULT NULL,
  `cod_empresa` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `num_contrato` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `carteirinha` varchar(45) DEFAULT NULL,
  `carteirinha_titular` varchar(45) DEFAULT NULL,
  `cod_permanencia` varchar(10) DEFAULT NULL,
  `desc_permanencia` varchar(60) DEFAULT NULL,
  `remido` varchar(3) DEFAULT NULL,
  `cod_subfatura` varchar(50) DEFAULT NULL,
  `processo` varchar(50) NOT NULL DEFAULT '1',
  `vl_ambulatorio` decimal(10,2) NOT NULL DEFAULT '1.00',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_criador_id` int(11) NOT NULL DEFAULT '1',
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1' COMMENT '1 - Ativo\n2 - excluido\n0 - inativo\n\n',
  PRIMARY KEY (`id`),
  KEY `fk_dw_beneficiario_cliente1_idx` (`cliente_id`),
  KEY `fk_dw_beneficiario_usuario1_idx` (`usuario_criador_id`),
  KEY `fk_dw_beneficiario_usuario2_idx` (`usuario_atualizacao_id`),
  KEY `fk_dw_beneficiario_importacao1_idx` (`importacao_id`),
  KEY `fk_dw_beneficiario_empresa1_idx` (`empresa_id`),
  KEY `fk_dw_beneficiario_operadora1_idk` (`operadora`),
  KEY `fk_dw_beneficiario_chave_beneficiario1_idk` (`chave_beneficiario`),
  CONSTRAINT `fk_dw_beneficiario_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_dw_beneficiario_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  CONSTRAINT `fk_dw_beneficiario_importacao1` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_dw_beneficiario_usuario1` FOREIGN KEY (`usuario_criador_id`) REFERENCES `usuario` (`id`),
  CONSTRAINT `fk_dw_beneficiario_usuario2` FOREIGN KEY (`usuario_atualizacao_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_fatura_mes
CREATE TABLE IF NOT EXISTS `dw_fatura_mes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(750) DEFAULT NULL,
  `nome_operadora` varchar(600) DEFAULT NULL,
  `nome_plano` varchar(100) DEFAULT NULL,
  `acomodacao` varchar(200) DEFAULT NULL,
  `nome_subfatura` varchar(100) DEFAULT NULL,
  `subfatura` varchar(100) DEFAULT NULL,
  `competencia` date DEFAULT NULL,
  `elegibilidade` varchar(1) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `valor` decimal(65,2) DEFAULT NULL,
  `valor_sem_iof` decimal(65,2) DEFAULT NULL,
  `valor_com_iof` decimal(65,2) DEFAULT NULL,
  `chave` varchar(45) NOT NULL,
  `cliente_id` int(11) NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `plano_id` int(11) NOT NULL,
  `subfatura_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `importacao_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dw_fatura_mes_cliente1_idx` (`cliente_id`),
  KEY `fk_dw_fatura_mes_beneficio1_idx` (`beneficio_id`),
  KEY `fk_dw_fatura_mes_plano1_idx` (`plano_id`),
  KEY `fk_dw_fatura_mes_subfatura1_idx` (`subfatura_id`),
  KEY `fk_dw_fatura_mes_importacao_id_idx` (`importacao_id`),
  CONSTRAINT `fk_dw_fatura_mes_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`),
  CONSTRAINT `fk_dw_fatura_mes_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_dw_fatura_mes_importacao_id` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_dw_fatura_mes_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  CONSTRAINT `fk_dw_fatura_mes_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31070 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_populacao
CREATE TABLE IF NOT EXISTS `dw_populacao` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `nome_cliente` varchar(750) DEFAULT NULL,
  `beneficio_id` int(11) NOT NULL,
  `plano_id` int(11) NOT NULL,
  `nome_plano` varchar(100) DEFAULT NULL,
  `subfatura_id` int(11) NOT NULL,
  `subfatura` varchar(150) DEFAULT NULL,
  `nome_operadora` varchar(600) DEFAULT NULL,
  `acomodacao` varchar(20) DEFAULT NULL,
  `nome_subfatura` varchar(100) DEFAULT NULL,
  `competencia` date NOT NULL,
  `elegibilidade` varchar(1) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `total_vidas` int(11) DEFAULT NULL,
  `chaves` longtext,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `importacao_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dw_populacao_plano1_idx` (`plano_id`),
  KEY `fk_dw_populacao_cliente1_idx` (`cliente_id`),
  KEY `fk_dw_populacao_beneficio1_idx` (`beneficio_id`),
  KEY `fk_dw_populacao_subfatura1_idx` (`subfatura_id`),
  KEY `fk_dw_populacao_importacao_id_idx` (`importacao_id`),
  CONSTRAINT `fk_dw_populacao_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`),
  CONSTRAINT `fk_dw_populacao_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_dw_populacao_importacao_id` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_dw_populacao_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  CONSTRAINT `fk_dw_populacao_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27812 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_robo_atualizacao
CREATE TABLE IF NOT EXISTS `dw_robo_atualizacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(20) NOT NULL,
  `ultima_atualizacao` datetime NOT NULL,
  `status` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_sinistro_evento
CREATE TABLE IF NOT EXISTS `dw_sinistro_evento` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(750) DEFAULT NULL,
  `nome_operadora` varchar(600) DEFAULT NULL,
  `nome_plano` varchar(100) DEFAULT NULL,
  `nome_subfatura` varchar(100) DEFAULT NULL,
  `subfatura` varchar(30) DEFAULT NULL,
  `competencia` date DEFAULT NULL,
  `descricao_evento` varchar(400) DEFAULT NULL,
  `valor` decimal(65,2) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `senha` varchar(530) DEFAULT NULL,
  `cod_cid_grupos` int(11) DEFAULT NULL,
  `cid` varchar(100) DEFAULT NULL,
  `reembolso` tinyint(1) DEFAULT NULL,
  `dia_semana` int(11) DEFAULT NULL,
  `elegibilidade` varchar(1) DEFAULT NULL,
  `carteira_20` varchar(50) DEFAULT NULL,
  `chave` varchar(20) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `data_referencia` varchar(20) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `subfatura_id` int(11) NOT NULL,
  `plano_id` int(11) NOT NULL,
  `sinistro_evento_id` int(11) NOT NULL,
  `cid_grupo_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `importacao_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dw_sinistro_evento_cliente1_idx` (`cliente_id`),
  KEY `fk_dw_sinistro_evento_beneficio1_idx` (`beneficio_id`),
  KEY `fk_dw_sinistro_evento_subfatura1_idx` (`subfatura_id`),
  KEY `fk_dw_sinistro_evento_plano1_idx` (`plano_id`),
  KEY `fk_dw_sinistro_evento_sinistro_evento1_idx` (`sinistro_evento_id`),
  KEY `fk_dw_sinistro_evento_cid_grupo1_idx` (`cid_grupo_id`),
  KEY `fk_dw_sinistro_evento_importacao_id_idx` (`importacao_id`),
  CONSTRAINT `fk_dw_sinistro_evento_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`),
  CONSTRAINT `fk_dw_sinistro_evento_cid_grupo1` FOREIGN KEY (`cid_grupo_id`) REFERENCES `cid_grupo` (`id`),
  CONSTRAINT `fk_dw_sinistro_evento_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_dw_sinistro_evento_importacao_id` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_dw_sinistro_evento_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  CONSTRAINT `fk_dw_sinistro_evento_sinistro_evento1` FOREIGN KEY (`sinistro_evento_id`) REFERENCES `sinistro_evento` (`id`),
  CONSTRAINT `fk_dw_sinistro_evento_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=85129 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_sinistro_paciente
CREATE TABLE IF NOT EXISTS `dw_sinistro_paciente` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(750) DEFAULT NULL,
  `nome_operadora` varchar(100) DEFAULT NULL,
  `nome_plano` varchar(100) DEFAULT NULL,
  `nome_subfatura` varchar(100) DEFAULT NULL,
  `subfatura` varchar(30) DEFAULT NULL,
  `competencia` date NOT NULL,
  `paciente` varchar(500) DEFAULT NULL,
  `titular` varchar(500) DEFAULT NULL,
  `elegibilidade` varchar(1) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `carteira_20` varchar(50) DEFAULT NULL,
  `carteira` varchar(50) DEFAULT NULL,
  `carteira_complemento` varchar(50) DEFAULT NULL,
  `chave` varchar(100) DEFAULT NULL,
  `situacao` varchar(50) DEFAULT NULL,
  `reembolso` tinyint(1) DEFAULT NULL,
  `valor_sinistro_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_ps_consultas_data_pagamento` int(11) DEFAULT NULL,
  `valor_ps_consultas_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_consultas_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_consultas_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_ps_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_ps_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_exames_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_exames_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_procedimento_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_procedimento_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_terapia_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_terapia_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_gestao_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_gestao_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_consulta_odonto_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_consulta_odonto_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_proc_odonto_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_proc_odonto_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_exame_odonto_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_exame_odonto_data_pagamento` decimal(65,2) DEFAULT NULL,
  `qtd_sem_classific_data_pagamento` int(11) DEFAULT NULL,
  `valor_sinistro_sem_classific_data_pagamento` decimal(65,2) DEFAULT NULL,
  `valor_sinistro_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_ps_consultas_data_evento` int(11) DEFAULT NULL,
  `valor_ps_consultas_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_consultas_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_consultas_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_ps_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_ps_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_exames_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_exames_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_procedimento_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_procedimento_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_terapia_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_terapia_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_gestao_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_gestao_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_consulta_odonto_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_consulta_odonto_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_proc_odonto_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_proc_odonto_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_exame_odonto_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_exame_odonto_data_evento` decimal(65,2) DEFAULT NULL,
  `qtd_sem_classific_data_evento` int(11) DEFAULT NULL,
  `valor_sinistro_sem_classific_data_evento` decimal(65,2) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `plano_id` int(11) NOT NULL,
  `subfatura_id` int(11) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `importacao_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dw_sinistro_paciente_cliente1_idx` (`cliente_id`),
  KEY `fk_dw_sinistro_paciente_beneficio1_idx` (`beneficio_id`),
  KEY `fk_dw_sinistro_paciente_plano1_idx` (`plano_id`),
  KEY `fk_dw_sinistro_paciente_subfatura1_idx` (`subfatura_id`),
  KEY `fk_dw_sinistro_paciente_importacao_id_idx` (`importacao_id`),
  CONSTRAINT `fk_dw_sinistro_paciente_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`),
  CONSTRAINT `fk_dw_sinistro_paciente_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_dw_sinistro_paciente_importacao_id` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_dw_sinistro_paciente_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  CONSTRAINT `fk_dw_sinistro_paciente_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41713 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.dw_sinistro_prestador_evento
CREATE TABLE IF NOT EXISTS `dw_sinistro_prestador_evento` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(750) DEFAULT NULL,
  `nome_operadora` varchar(600) DEFAULT NULL,
  `nome_plano` varchar(100) DEFAULT NULL,
  `nome_subfatura` varchar(100) DEFAULT NULL,
  `subfatura` varchar(100) DEFAULT NULL,
  `competencia` date DEFAULT NULL,
  `descricao_evento` varchar(100) DEFAULT NULL,
  `valor` decimal(65,2) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `senha` varchar(530) DEFAULT NULL,
  `cod_cid_grupos` int(11) DEFAULT NULL,
  `cid` varchar(500) DEFAULT NULL,
  `reembolso` tinyint(1) DEFAULT NULL,
  `dia_semana` int(11) DEFAULT NULL,
  `elegibilidade` varchar(1) DEFAULT NULL,
  `carteira_20` varchar(50) DEFAULT NULL,
  `chave` varchar(20) DEFAULT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `data_referencia` varchar(20) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `subfatura_id` int(11) NOT NULL,
  `plano_id` int(11) NOT NULL,
  `sinistro_evento_id` int(11) NOT NULL,
  `cid_grupo_id` int(11) DEFAULT NULL,
  `prestador` varchar(2000) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `importacao_id` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_dw_sinistro_prestador_evento_cliente1_idx` (`cliente_id`),
  KEY `fk_dw_sinistro_prestador_evento_beneficio1_idx` (`beneficio_id`),
  KEY `fk_dw_sinistro_prestador_evento_subfatura1_idx` (`subfatura_id`),
  KEY `fk_dw_sinistro_prestador_evento_plano1_idx` (`plano_id`),
  KEY `fk_dw_sinistro_prestador_evento_sinistro_evento1_idx` (`sinistro_evento_id`),
  KEY `fk_dw_sinistro_prestador_evento_cid_grupo1_idx` (`cid_grupo_id`),
  KEY `fk_dw_sinistro_prestador_evento_importacao_id_idx` (`importacao_id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_cid_grupo1` FOREIGN KEY (`cid_grupo_id`) REFERENCES `cid_grupo` (`id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_importacao_id` FOREIGN KEY (`importacao_id`) REFERENCES `importacao` (`id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_sinistro_evento1` FOREIGN KEY (`sinistro_evento_id`) REFERENCES `sinistro_evento` (`id`),
  CONSTRAINT `fk_dw_sinistro_prestador_evento_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90386 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.empresa
CREATE TABLE IF NOT EXISTS `empresa` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nome` varchar(45) NOT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `nome_fantasia` varchar(100) DEFAULT NULL,
  `cnpj` varchar(14) NOT NULL,
  `inscricao_estadual` varchar(25) DEFAULT NULL,
  `inscricao_municipal` varchar(25) DEFAULT NULL,
  `numero_funcionarios` int(11) DEFAULT NULL,
  `descricao` varchar(1024) DEFAULT NULL,
  `porte` varchar(10) DEFAULT NULL COMMENT '<select name="id_porte">\n          <option value=""></option>\n                        <option value="3">\n              Grande (3)              </option>\n                        <option value="2">\n              Média (2)              </option>\n             /* comment truncated */ /*            <option value="1">\n              Pequena (1)              </option>\n                      </select>*/',
  `faturamento` varchar(50) DEFAULT NULL COMMENT '<select name="id_faturamento">\n          <option value=""></option>\n                        <option value="1">\n              até 120.000,00 (10)</option>\n                        <option value="2">\n              120.001,00 a 720.000,00 (20)</option>\n       /* comment truncated */ /*                  <option value="3">\n              720.001,00 a 5.000.000,00 (30)</option>\n                        <option value="4">\n              mais de 5.000.000,00 (40)</option>\n                        <option value="5">\n              Não informado (50)</option>\n                      </select>*/',
  `tipo` varchar(15) DEFAULT NULL COMMENT '<select name="id_tipo_empresa">\n          <option value=""></option>\n                        <option value="3">\n              Multinacional (3)              </option>\n                        <option value="2">\n              Nacional (2)              </opt /* comment truncated */ /*ion>\n                        <option value="1">\n              RH (1)              </option>\n                      </select>*/',
  `endereco` varchar(100) DEFAULT NULL,
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `bairro` varchar(50) DEFAULT NULL,
  `cidade` varchar(50) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `telefone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `site` varchar(250) DEFAULT NULL,
  `token_tv` text,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_criador_id` int(11) DEFAULT NULL COMMENT 'Quem Criou',
  `status` int(11) NOT NULL DEFAULT '1',
  `cliente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa_cliente1_idx` (`cliente_id`),
  CONSTRAINT `fk_empresa_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11665 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.empresa_subfatura
CREATE TABLE IF NOT EXISTS `empresa_subfatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) NOT NULL,
  `subfatura_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_empresa_subfatura_subfatura1_idx` (`subfatura_id`),
  KEY `fk_empresa_subfatura_empresa1_idx` (`empresa_id`),
  CONSTRAINT `fk_empresa_subfatura_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.especie_bp
CREATE TABLE IF NOT EXISTS `especie_bp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(200) NOT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.estado
CREATE TABLE IF NOT EXISTS `estado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cod_uf` varchar(45) DEFAULT NULL,
  `nome` varchar(45) DEFAULT NULL,
  `uf` varchar(2) DEFAULT NULL,
  `regiao` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.faixa_etaria
CREATE TABLE IF NOT EXISTS `faixa_etaria` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cod_faixa_etaria_ans` int(11) DEFAULT NULL,
  `ds_faixa_etaria_ans` varchar(250) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.fatura
CREATE TABLE IF NOT EXISTS `fatura` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `importacao_id` bigint(20) NOT NULL,
  `cliente_id` varchar(50) NOT NULL,
  `empresa_id` varchar(50) DEFAULT NULL,
  `subfatura_id` varchar(50) DEFAULT NULL COMMENT '(Não usado)',
  `chave_beneficiario` varchar(250) NOT NULL,
  `matricula` varchar(100) DEFAULT NULL,
  `beneficio_id` int(11) NOT NULL,
  `numero_carteira_titular` varchar(50) DEFAULT NULL,
  `numero_carteira_titular_complemento` varchar(50) DEFAULT NULL,
  `cpf_titular` varchar(11) DEFAULT NULL,
  `nome_titular` varchar(500) DEFAULT NULL,
  `beneficiario_id` bigint(20) NOT NULL,
  `numero_carteira` varchar(50) NOT NULL,
  `numero_carteira_complemento` varchar(50) DEFAULT NULL,
  `cpf_beneficiario` varchar(11) DEFAULT NULL,
  `nome_beneficiario` varchar(500) DEFAULT NULL,
  `sexo` varchar(2) NOT NULL COMMENT 'M ou F',
  `elegibilidade` varchar(50) NOT NULL COMMENT 'A = AMBULATORIO I = INTERNACAO',
  `data_nascimento` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `parentesco_id` int(11) DEFAULT NULL COMMENT '(Não usado)',
  `cod_faixa_etaria_ans` varchar(50) DEFAULT NULL,
  `plano_id` varchar(50) DEFAULT NULL,
  `ds_plano` varchar(100) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `valor_coparticipacao` decimal(10,2) DEFAULT NULL,
  `competencia` date NOT NULL,
  `operadora` varchar(100) DEFAULT NULL,
  `usuario_id` int(11) NOT NULL DEFAULT '1',
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `ds_empresa` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.faturamento
CREATE TABLE IF NOT EXISTS `faturamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `competencia_referencia` date DEFAULT NULL,
  `competencia` date NOT NULL COMMENT 'Sempre iniciar a data com dia 01',
  `codigo_operadora` varchar(20) DEFAULT NULL,
  `operadora` varchar(70) DEFAULT NULL,
  `valor_fatura` decimal(10,2) DEFAULT NULL,
  `qtd_vidas` int(11) DEFAULT NULL,
  `reembolso` decimal(10,2) DEFAULT NULL,
  `rede` decimal(10,2) DEFAULT NULL,
  `coparticipacao` decimal(10,2) DEFAULT NULL,
  `revisao` decimal(10,2) DEFAULT NULL,
  `recuperacao` decimal(10,2) DEFAULT NULL COMMENT 'RECUPERACAO DE SINISTRO',
  `valor_sinistro` decimal(10,2) DEFAULT NULL,
  `saldo` decimal(10,2) DEFAULT NULL,
  `percentual` float DEFAULT NULL,
  `total_sinistro` decimal(10,2) DEFAULT NULL,
  `qtd_beneficiarios_atendidos` int(11) DEFAULT NULL,
  `data_cadastro_robo` datetime DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_faturamento_cliente1_idx_idx` (`cliente_id`),
  CONSTRAINT `fk_faturamento_cliente1_idx` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8280 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.grupo_empresarial
CREATE TABLE IF NOT EXISTS `grupo_empresarial` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  `img_logo` varchar(255) DEFAULT NULL,
  `bi` varchar(255) DEFAULT NULL,
  `cor` varchar(10) DEFAULT NULL,
  `data_cadastro` datetime NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `data_cancelamento` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.grupo_estatistico
CREATE TABLE IF NOT EXISTS `grupo_estatistico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sigla` varchar(255) DEFAULT NULL,
  `descricao` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.importacao
CREATE TABLE IF NOT EXISTS `importacao` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo_importacao` varchar(45) NOT NULL COMMENT 'fatura ou sinistro',
  `arquivo_importado` varchar(255) DEFAULT NULL,
  `avisos` text,
  `data_cadastro` datetime NOT NULL,
  `usuario_criador_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_importacao_cliente1_idx` (`cliente_id`),
  KEY `fk_importacao_usuario1_idx` (`usuario_criador_id`),
  CONSTRAINT `fk_importacao_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_importacao_usuario1` FOREIGN KEY (`usuario_criador_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1282 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.importacao_nova
CREATE TABLE IF NOT EXISTS `importacao_nova` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `nome_arquivo` varchar(60) NOT NULL,
  `tipo_importacao` varchar(45) NOT NULL COMMENT 'fatura ou sinistro',
  `arquivo_importado` varchar(255) NOT NULL,
  `avisos` text,
  `linhas_totais` varchar(45) DEFAULT '0',
  `linhas_processadas` varchar(45) DEFAULT '0',
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_criador_id` int(11) NOT NULL,
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `status_processo` int(11) NOT NULL DEFAULT '0' COMMENT '(pending, processing, done, done_whitch_error, error)0, 1, 2, 3, 4',
  `status` int(1) NOT NULL DEFAULT '1' COMMENT '(pending, processing, done, done_whitch_error, error)0, 1, 2, 3, 4',
  PRIMARY KEY (`id`),
  KEY `fk_importacao_nova_cliente1_idx` (`cliente_id`),
  KEY `fk_importacao_nova_usuario1_idx` (`usuario_criador_id`),
  CONSTRAINT `fk_importacao_nova_cliente10` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_importacao_nova_usuario10` FOREIGN KEY (`usuario_criador_id`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.indice
CREATE TABLE IF NOT EXISTS `indice` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` varchar(45) CHARACTER SET latin1 NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `sinistro_evento_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_indices_sinistro_evento1_idx` (`sinistro_evento_id`),
  CONSTRAINT `fk_indices_sinistro_evento1` FOREIGN KEY (`sinistro_evento_id`) REFERENCES `sinistro_evento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.log
CREATE TABLE IF NOT EXISTS `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` varchar(100) DEFAULT NULL,
  `mensagem` text,
  `description` text,
  `server_description` text,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Quem Criou',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=84564 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.log_erro
CREATE TABLE IF NOT EXISTS `log_erro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `log` varchar(100) DEFAULT NULL,
  `mensagem` text,
  `description` text,
  `server_description` text,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) DEFAULT NULL COMMENT 'Quem Criou',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=604 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mensagem
CREATE TABLE IF NOT EXISTS `mensagem` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `tipo` int(11) DEFAULT NULL,
  `sms` varchar(255) DEFAULT NULL,
  `email` text,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_criador_id` int(11) DEFAULT NULL COMMENT 'Quem Criou',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_mensagem_cliente1_idx` (`cliente_id`),
  CONSTRAINT `fk_mensagem_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mh_critico
CREATE TABLE IF NOT EXISTS `mh_critico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mh_prestador_id` int(11) NOT NULL COMMENT 'mh_prestador',
  `mh_prestador_principal_id` int(11) NOT NULL,
  `principal` int(11) NOT NULL DEFAULT '1',
  `nome` varchar(150) DEFAULT NULL,
  `opcao` int(11) NOT NULL COMMENT 'opção 0,1,2,3\n',
  `ciclo` int(11) NOT NULL COMMENT 'Prospecção \\ncontato\\nmapeameno\\nNegociação\\nInssucesso',
  `status_ciclo` int(11) NOT NULL COMMENT 'nao iniciada, em andamento, concluida',
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_id` varchar(45) NOT NULL,
  `usuario_atualizacao_id` varchar(45) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mh_critico_historico
CREATE TABLE IF NOT EXISTS `mh_critico_historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mh_critico_id` int(11) NOT NULL,
  `ciclo` int(11) NOT NULL COMMENT 'Prospecção contato, mapeamento, negociação, inssucesso',
  `status_ciclo` int(11) NOT NULL COMMENT 'nao iniciada, em andamento, concluida',
  `descricao` text NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mh_critico_status
CREATE TABLE IF NOT EXISTS `mh_critico_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mh_prestador_id` int(11) NOT NULL,
  `ciclo` int(11) NOT NULL,
  `status_ciclo` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Status geral dos mh_criticos';

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mh_negociacao
CREATE TABLE IF NOT EXISTS `mh_negociacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mh_prestador_id` int(11) NOT NULL,
  `tipo_negocio` int(11) NOT NULL,
  `usuario_negociador_id` int(11) NOT NULL COMMENT 'usuario',
  `usuario_id` int(11) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `usuario_negociador_id_old` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mh_negociacao_historico
CREATE TABLE IF NOT EXISTS `mh_negociacao_historico` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ciclo` int(11) NOT NULL,
  `status_proposta` int(11) NOT NULL COMMENT 'Proposta - Aguardando\\nProposta - Análise \\nContraproposta - Elaboração \\nContraproposta - Revisão\\nContraproposta - Validação \\nContraproposta - Enviada Operadora\\nContraproposta - Retornada\\nContraproposta - Enviada Prestador\\nContraproposta - Aceita\\nContraproposta - Recusada\n\nquando tiver Contraproposta - Revisão entra um revisor\n',
  `usuario_revisor_id` int(11) DEFAULT NULL COMMENT 'Cleber, Rogério, vitor\n\nContraproposta - Revisão\nContraproposta - Validação ',
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.mh_prestador
CREATE TABLE IF NOT EXISTS `mh_prestador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_hubspot` varchar(45) DEFAULT NULL,
  `nome` varchar(150) DEFAULT NULL,
  `cidade` varchar(60) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `praca` varchar(100) DEFAULT NULL,
  `atividade` varchar(255) DEFAULT NULL,
  `descricao` text,
  `data_cadastro` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2189 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.modulo
CREATE TABLE IF NOT EXISTS `modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo_id` int(11) DEFAULT NULL,
  `nome` varchar(45) NOT NULL COMMENT 'controller',
  `controller` varchar(45) NOT NULL,
  `action` varchar(45) DEFAULT NULL,
  `menu` int(11) NOT NULL DEFAULT '0' COMMENT '0 - Não tem\\n1 - Nível pai com submenu\\n2 - Existe Menu (CRUD)',
  `order` int(11) DEFAULT NULL,
  `icon` varchar(35) DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL DEFAULT '1',
  `status` int(11) NOT NULL DEFAULT '1' COMMENT 'Status \\n0 - inativo\\n1 - ativo\\n2 - excluido\\n3 - permissao root',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=178 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.notificacao
CREATE TABLE IF NOT EXISTS `notificacao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tabela` varchar(60) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `id_referencia` int(11) DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  `descricao` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `data_cadastro` datetime DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notificacao_cliente1_idx` (`cliente_id`),
  CONSTRAINT `fk_notificacao_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.notificacao_usuario
CREATE TABLE IF NOT EXISTS `notificacao_usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `notificacao_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `data_cadastro` datetime DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notificacao_id_idx` (`notificacao_id`),
  KEY `fk_notificacao_usuario_usuario1_idx` (`usuario_id`),
  CONSTRAINT `fk_notificacao_id` FOREIGN KEY (`notificacao_id`) REFERENCES `notificacao` (`id`),
  CONSTRAINT `fk_notificacao_usuario_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.operadora
CREATE TABLE IF NOT EXISTS `operadora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(450) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_cancelamento` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.parametro
CREATE TABLE IF NOT EXISTS `parametro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `valor` varchar(100) NOT NULL,
  `ordenacao` int(11) NOT NULL DEFAULT '1',
  `tipo` varchar(20) NOT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_id` int(11) NOT NULL COMMENT 'Quem Criou',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.patologia_clf_proced
CREATE TABLE IF NOT EXISTS `patologia_clf_proced` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patologia` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `cod_procedimento` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `des_procedimento` varchar(45) CHARACTER SET latin1 DEFAULT NULL,
  `classificacao` varchar(1) CHARACTER SET latin1 DEFAULT NULL,
  `evento_confirmatorio` varchar(3) CHARACTER SET latin1 DEFAULT NULL,
  `realizado` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cod_procedimento` (`cod_procedimento`)
) ENGINE=InnoDB AUTO_INCREMENT=265 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.perfil
CREATE TABLE IF NOT EXISTS `perfil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) DEFAULT NULL,
  `tipo` int(11) DEFAULT NULL,
  `descricao` text,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `usuario_criador` int(11) NOT NULL COMMENT 'Quem Criou',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.perfil_modulo
CREATE TABLE IF NOT EXISTS `perfil_modulo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `permissao` int(11) NOT NULL DEFAULT '0' COMMENT '0 - Sem Acesso\n1 - Visualizar\n2 - Visualizar / cadastrar / editar\n3 - gerenciar completo',
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL DEFAULT '1',
  `modulo_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_perfil_modulo_modulo1_idx` (`modulo_id`),
  KEY `fk_perfil_modulo_perfil1_idx` (`perfil_id`),
  CONSTRAINT `fk_perfil_modulo_modulo1` FOREIGN KEY (`modulo_id`) REFERENCES `modulo` (`id`),
  CONSTRAINT `fk_perfil_modulo_perfil1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=801 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.plano
CREATE TABLE IF NOT EXISTS `plano` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `codigo_operadora` varchar(50) NOT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `cliente_id` int(11) DEFAULT NULL,
  `operadora_id` int(11) DEFAULT NULL,
  `tipo_beneficio_id` int(11) DEFAULT NULL,
  `ordem` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_plano_cliente1_idx` (`cliente_id`),
  KEY `fk_plano_operadora1_idx` (`operadora_id`),
  KEY `fk_plano_tipo_beneficio1_idx` (`tipo_beneficio_id`),
  CONSTRAINT `fk_plano_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_plano_operadora1` FOREIGN KEY (`operadora_id`) REFERENCES `operadora` (`id`),
  CONSTRAINT `fk_plano_tipo_beneficio1` FOREIGN KEY (`tipo_beneficio_id`) REFERENCES `tipo_beneficio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5502 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.procedimento
CREATE TABLE IF NOT EXISTS `procedimento` (
  `id` bigint(20) NOT NULL DEFAULT '0',
  `cod_procedimento` varchar(50) DEFAULT NULL,
  `ds_procedimento` varchar(300) DEFAULT NULL,
  `Grupo` varchar(100) NOT NULL,
  `Subgrupo` varchar(250) NOT NULL,
  `Grupo de Exames` varchar(100) NOT NULL,
  `usuario_id` int(11) NOT NULL DEFAULT '1',
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `tipo_procedimento` varchar(200) DEFAULT NULL,
  `tipo_servico` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.procedimento_old
CREATE TABLE IF NOT EXISTS `procedimento_old` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sinistro_evento_id` int(11) DEFAULT NULL,
  `operadora_id` int(11) DEFAULT NULL,
  `tuss` varchar(20) NOT NULL,
  `descricao` varchar(2000) NOT NULL,
  `data_cadastro` datetime NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `data_cancelamento` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_procedimento_sinistro_evento1_idx` (`sinistro_evento_id`),
  KEY `fk_procedimento_operadora1_idx` (`operadora_id`),
  CONSTRAINT `fk_procedimento_operadora1` FOREIGN KEY (`operadora_id`) REFERENCES `operadora` (`id`),
  CONSTRAINT `fk_procedimento_sinistro_evento1` FOREIGN KEY (`sinistro_evento_id`) REFERENCES `sinistro_evento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11686 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.sinistro
CREATE TABLE IF NOT EXISTS `sinistro` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `importacao_id` bigint(20) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `empresa_id` bigint(20) DEFAULT NULL,
  `subfatura_id` int(11) DEFAULT NULL COMMENT '(Não usado)',
  `cod_subfatura` varchar(50) DEFAULT NULL,
  `chave_beneficiario` varchar(100) DEFAULT NULL,
  `matricula` varchar(100) DEFAULT NULL,
  `beneficio_id` int(11) DEFAULT NULL,
  `cod_grupo_familiar` varchar(100) DEFAULT NULL,
  `numero_carteira_titular` varchar(50) DEFAULT NULL,
  `numero_carteira_titular_complemento` varchar(50) DEFAULT NULL,
  `cpf_titular` varchar(11) DEFAULT NULL,
  `nome_titular` varchar(500) DEFAULT NULL,
  `beneficiario_id` bigint(20) DEFAULT NULL,
  `numero_carteira` varchar(50) DEFAULT NULL,
  `numero_carteira_complemento` varchar(50) DEFAULT NULL,
  `cpf_beneficiario` varchar(11) DEFAULT NULL,
  `nome_beneficiario` varchar(500) DEFAULT NULL,
  `sexo` varchar(2) DEFAULT NULL COMMENT 'M ou F',
  `elegibilidade` varchar(50) DEFAULT NULL COMMENT 'A = AMBULATORIO I = INTERNACAO',
  `data_nascimento` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL,
  `parentesco_id` int(11) DEFAULT NULL COMMENT '(Não usado)',
  `tipo_reembolso` varchar(20) DEFAULT NULL COMMENT ' Unimed(rede reembolso ou intercambio) antigo tipo_conta_id',
  `cod_prestador` varchar(20) DEFAULT NULL,
  `nome_prestador` varchar(100) DEFAULT NULL,
  `cidade_prestador` varchar(100) DEFAULT NULL,
  `uf_prestador` varchar(15) DEFAULT NULL,
  `cod_faixa_etaria_ans` varchar(40) DEFAULT NULL,
  `plano_id` int(11) DEFAULT NULL,
  `cod_plano` varchar(50) DEFAULT NULL,
  `ds_plano` varchar(100) DEFAULT NULL,
  `nro_conta_medica` varchar(200) DEFAULT NULL,
  `procedimento_id` int(11) DEFAULT NULL COMMENT '(incluso no futuro)',
  `cod_procedimento` varchar(50) DEFAULT NULL,
  `ds_procedimento` varchar(300) DEFAULT NULL,
  `qtde_procedimento` int(11) DEFAULT NULL,
  `tipo_servico` varchar(1) DEFAULT NULL COMMENT 'A = AMBULATORIO I = INTERNACAO',
  `conta_medica` varchar(200) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `valor_coparticipacao` decimal(10,2) DEFAULT NULL,
  `senha` varchar(50) DEFAULT NULL,
  `nr_autorizacao` varchar(50) DEFAULT NULL,
  `prestador_tipo` varchar(1) DEFAULT NULL COMMENT 'J = Jurídico F = Físico',
  `local_atendimento` varchar(50) DEFAULT NULL,
  `cod_especialidade` int(11) DEFAULT NULL,
  `ds_especialidade` varchar(200) DEFAULT NULL,
  `data_evento` date DEFAULT NULL,
  `data_pagamento` date DEFAULT NULL,
  `cid` varchar(20) DEFAULT NULL,
  `ds_cid` varchar(100) DEFAULT NULL,
  `operadora` varchar(100) DEFAULT NULL,
  `tipo_servico_operadora` varchar(50) DEFAULT NULL COMMENT 'Ambulatorio Consulta etc..',
  `tipo_internacao` varchar(50) DEFAULT NULL COMMENT 'obstetricia clinica cirurgica (vai no token)',
  `tipo_entrada` varchar(50) DEFAULT NULL COMMENT 'Emergencia Eletivo (vai no token)',
  `campo_1_coluna` varchar(100) DEFAULT NULL COMMENT 'Ex: cod_beneficio',
  `campo_1_dado` varchar(100) DEFAULT NULL COMMENT 'Ex: 15165',
  `campo_2_coluna` varchar(100) DEFAULT NULL COMMENT 'Ex: cod_beneficio',
  `campo_2_dado` varchar(100) DEFAULT NULL COMMENT 'Ex: 15165',
  `campo_3_coluna` varchar(100) DEFAULT NULL COMMENT 'Ex: cod_beneficio',
  `campo_3_dado` varchar(100) DEFAULT NULL COMMENT 'Ex: 15165',
  `campo_4_coluna` varchar(100) DEFAULT NULL COMMENT 'Ex: cod_beneficio',
  `campo_4_dado` varchar(100) DEFAULT NULL COMMENT 'Ex: 15165',
  `competencia_robo` date DEFAULT NULL,
  `ds_parentesco` varchar(20) DEFAULT NULL,
  `cod_origem_prestador` int(11) DEFAULT NULL,
  `num_contrato` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `nome_contrato` varchar(155) DEFAULT NULL COMMENT 'novo (myralis)',
  `apolice` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `lotacao_titular` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `endereco_titular` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `titular_cidade` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `titular_uf` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `titular_cep` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `procedimento_tipo_tabela` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `codigo_beneficio` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `data_final_servico` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `co_particiacao_perc` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `tipo_sinistro` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `atendimento_emergencia` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `tipo_paciente` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `provedor_codigo` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `estipulante_cnpj` varchar(20) DEFAULT NULL COMMENT 'novo (myralis)',
  `estipulante_endereco` varchar(150) DEFAULT NULL COMMENT 'novo (myralis)',
  `estipulante_cidade` varchar(45) DEFAULT NULL COMMENT 'novo (myralis)',
  `estipulante_uf` varchar(2) DEFAULT NULL,
  `estipulante_cep` varchar(9) DEFAULT NULL COMMENT 'novo (myralis)',
  `origem_pagamento` text COMMENT 'novo (myralis)',
  `tabela_grupo` varchar(10) DEFAULT NULL,
  `codigo_grupo` varchar(10) DEFAULT NULL,
  `descricao_grupo` varchar(100) DEFAULT NULL,
  `codigo_subgrupo` varchar(10) DEFAULT NULL,
  `descricao_subgrupo` varchar(100) DEFAULT NULL,
  `data_alta` date DEFAULT NULL,
  `uf_registro` varchar(2) DEFAULT NULL,
  `numero_registro` varchar(20) DEFAULT NULL,
  `cnpj_prestador` varchar(100) DEFAULT NULL COMMENT 'Ex: cod_beneficio',
  `nome_hash` varchar(100) DEFAULT NULL,
  `nome_prestador_hash` varchar(100) DEFAULT NULL,
  `chave_sinistro` varchar(500) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT '1',
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `data_cadastro_robo` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_cliente_id_sinistro1` (`cliente_id`),
  KEY `fk_operadora_sinistro1` (`operadora`),
  KEY `fk_numero_carteira_sinistro1` (`numero_carteira`),
  KEY `fk_data_evento_sinistro1` (`data_evento`),
  KEY `fk_ds_procedimento_sinistro1` (`ds_procedimento`(255)),
  KEY `fk_chave_beneficiario_sinistro1` (`chave_beneficiario`),
  KEY `fk_chave_sinitro1` (`chave_sinistro`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.sinistro_evento
CREATE TABLE IF NOT EXISTS `sinistro_evento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) NOT NULL,
  `data_cadastro` date NOT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_cancelamento` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.sinistro_internacao_alta
CREATE TABLE IF NOT EXISTS `sinistro_internacao_alta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `beneficiario_id` bigint(20) NOT NULL,
  `data_internacao` date DEFAULT NULL,
  `data_ultimo_dia` date DEFAULT NULL,
  `data_alta` date DEFAULT NULL,
  `usuario_id` int(11) NOT NULL DEFAULT '1',
  `usuario_atualizacao_id` int(11) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.sinistro_old
CREATE TABLE IF NOT EXISTS `sinistro_old` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `empresa_id` bigint(20) DEFAULT NULL,
  `cliente_id` int(11) NOT NULL,
  `prestador` varchar(100) NOT NULL,
  `codigo_prestador` varchar(20) DEFAULT NULL,
  `doc_prestador` varchar(45) DEFAULT NULL,
  `quantidade` decimal(10,2) NOT NULL,
  `data_pagamento` date NOT NULL,
  `data_evento` date NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `elegibilidade` varchar(1) NOT NULL,
  `carteira` varchar(50) NOT NULL,
  `carteira_complemento` varchar(50) DEFAULT NULL,
  `carteira_titular` varchar(50) DEFAULT NULL,
  `carteira_titular_complemento` varchar(50) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `idade` int(11) DEFAULT NULL COMMENT 'idade e calculada através da data de pagamento',
  `idade_calculada` int(11) DEFAULT NULL,
  `paciente` varchar(500) NOT NULL,
  `titular` varchar(500) NOT NULL,
  `matricula` varchar(45) DEFAULT NULL,
  `reembolso` tinyint(4) NOT NULL DEFAULT '0',
  `sexo` varchar(1) NOT NULL,
  `parentesco` int(11) DEFAULT NULL,
  `tipo_servico` varchar(1) NOT NULL COMMENT 'A = AMBULATORIO\nI = INTERNACAO',
  `tipo_internacao` int(11) DEFAULT NULL,
  `dias_internados` int(11) DEFAULT NULL,
  `conta_medica` varchar(100) DEFAULT NULL,
  `cid` varchar(20) DEFAULT NULL,
  `cnpj_cliente` varchar(14) DEFAULT NULL,
  `numero_conta` varchar(50) DEFAULT NULL,
  `grupo_estatistico` varchar(45) DEFAULT NULL,
  `alta` tinyint(4) DEFAULT NULL,
  `valor_empresa` decimal(10,2) DEFAULT NULL,
  `valor_paciente` decimal(10,2) DEFAULT NULL,
  `data_atualizacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `chave` varchar(45) DEFAULT NULL COMMENT 'hash sera md5 do primeiro nome + espaco + primeira letra do segundo nome + data_nascimento',
  `cpf` varchar(11) DEFAULT NULL,
  `prestador_tipo` varchar(45) DEFAULT NULL COMMENT 'J = Juridica F fisica',
  `plano_id` int(11) NOT NULL,
  `procedimento_id` int(11) NOT NULL,
  `subfatura_id` int(11) DEFAULT NULL,
  `importacao_id` bigint(20) NOT NULL,
  `beneficio_id` int(11) NOT NULL,
  `senha` varchar(50) DEFAULT NULL,
  `desc_plano` varchar(100) DEFAULT NULL,
  `desc_procedimento` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sinistro_elegibilidade1_idx` (`elegibilidade`),
  KEY `fk_sinistro_cliente1_idx` (`cliente_id`),
  KEY `fk_sinistro_plano1_idx` (`plano_id`),
  KEY `fk_sinistro_procedimento1_idx` (`procedimento_id`),
  KEY `fk_sinistro_subfatura1_idx` (`subfatura_id`),
  KEY `fk_sinistro_empresa1_idx` (`empresa_id`),
  KEY `fk_sinistro_beneficio_idx` (`beneficio_id`),
  CONSTRAINT `fk_sinistro_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`),
  CONSTRAINT `fk_sinistro_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_sinistro_empresa1` FOREIGN KEY (`empresa_id`) REFERENCES `empresa` (`id`),
  CONSTRAINT `fk_sinistro_plano1` FOREIGN KEY (`plano_id`) REFERENCES `plano` (`id`),
  CONSTRAINT `fk_sinistro_procedimento1` FOREIGN KEY (`procedimento_id`) REFERENCES `procedimento` (`id`),
  CONSTRAINT `fk_sinistro_subfatura1` FOREIGN KEY (`subfatura_id`) REFERENCES `subfatura` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.subfatura
CREATE TABLE IF NOT EXISTS `subfatura` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `beneficio_id` int(11) NOT NULL,
  `descricao` varchar(450) NOT NULL,
  `codigo` varchar(45) NOT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `data_cancelamento` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subfatura_beneficio1_idx` (`beneficio_id`),
  CONSTRAINT `fk_subfatura_beneficio1` FOREIGN KEY (`beneficio_id`) REFERENCES `beneficio` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=181 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.subfaturas
CREATE TABLE IF NOT EXISTS `subfaturas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subfatura_id` varchar(255) DEFAULT NULL,
  `ds_subfatura` varchar(255) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=106 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.tipo_beneficio
CREATE TABLE IF NOT EXISTS `tipo_beneficio` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(45) NOT NULL,
  `data_cadastro` datetime DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cancelamento` date DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.tipo_conta
CREATE TABLE IF NOT EXISTS `tipo_conta` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cod_tipo_conta` int(11) DEFAULT NULL,
  `ds_tipo_conta` varchar(250) DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `data_atualizacao` datetime DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.usuario
CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_empresarial_id` int(11) NOT NULL,
  `perfil_id` int(11) NOT NULL,
  `nome` varchar(65) NOT NULL,
  `apelido` varchar(10) DEFAULT NULL,
  `usuario` varchar(60) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_gestao` varchar(255) DEFAULT NULL,
  `token_forgot` varchar(255) DEFAULT NULL,
  `qtd_forgot` int(11) DEFAULT '0',
  `cor` varchar(10) DEFAULT NULL,
  `tel1_tipo` varchar(17) DEFAULT NULL,
  `tel1` varchar(17) DEFAULT NULL,
  `tel2_tipo` varchar(17) DEFAULT NULL,
  `tel2` varchar(17) DEFAULT NULL,
  `tel3_tipo` varchar(17) DEFAULT NULL,
  `tel3` varchar(17) DEFAULT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `observacao` text,
  `data_nascimento` date DEFAULT NULL,
  `cidade_nascimento` varchar(60) DEFAULT NULL,
  `estado_nascimento` varchar(60) DEFAULT NULL,
  `nascionalidade` varchar(60) DEFAULT NULL,
  `estado_civil` varchar(20) DEFAULT NULL,
  `sexo` varchar(10) DEFAULT NULL COMMENT 'M, F',
  `numero` varchar(10) DEFAULT NULL,
  `complemento` varchar(45) DEFAULT NULL,
  `rg` varchar(45) DEFAULT NULL,
  `cpf` varchar(45) DEFAULT NULL,
  `certidao_militar` varchar(20) DEFAULT NULL,
  `titulo_eleitoral` varchar(20) DEFAULT NULL,
  `passaporte` varchar(20) DEFAULT NULL,
  `data_passaporte` date DEFAULT NULL,
  `certidao_nascimento` varchar(45) DEFAULT NULL,
  `data_atualizacao` datetime DEFAULT NULL,
  `data_cadastro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usuario_criador_id` int(11) DEFAULT NULL COMMENT 'Quem Criou',
  `status` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_usuario_perfil1_idx` (`perfil_id`),
  KEY `fk_usuario_grupo_empresarial1_idx` (`grupo_empresarial_id`),
  CONSTRAINT `fk_usuario_grupo_empresarial2` FOREIGN KEY (`grupo_empresarial_id`) REFERENCES `grupo_empresarial` (`id`),
  CONSTRAINT `fk_usuario_perfil1` FOREIGN KEY (`perfil_id`) REFERENCES `perfil` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=449 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.usuario_bi
CREATE TABLE IF NOT EXISTS `usuario_bi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bi_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_table1_bi1_idx` (`bi_id`),
  KEY `fk_table1_usuario2_idx` (`usuario_id`),
  CONSTRAINT `fk_table1_bi1` FOREIGN KEY (`bi_id`) REFERENCES `bi` (`id`),
  CONSTRAINT `fk_table1_usuario2` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela samed_pro.usuario_cliente
CREATE TABLE IF NOT EXISTS `usuario_cliente` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_table1_cliente1_idx` (`cliente_id`),
  KEY `fk_table1_usuario1_idx` (`usuario_id`),
  CONSTRAINT `fk_table1_cliente1` FOREIGN KEY (`cliente_id`) REFERENCES `cliente` (`id`),
  CONSTRAINT `fk_table1_usuario1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10712 DEFAULT CHARSET=utf8;

-- Exportação de dados foi desmarcado.

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_afastado` AS select `ge`.`nome` AS `grupo_nome`,`c`.`nome` AS `cliente_nome`,`b`.`id` AS `id`,`b`.`nome` AS `beneficiario_nome`,`b`.`cpf` AS `cpf`,`b`.`situacao` AS `situacao_beneficiario`,`b`.`data_cadastro` AS `data_cadastro` from (((`beneficiario` `b` join `afastado` `a` on((`a`.`beneficiario_id` = `b`.`id`))) join `cliente` `c` on((`c`.`id` = `b`.`cliente_id`))) join `grupo_empresarial` `ge` on((`ge`.`id` = `c`.`grupo_empresarial_id`))) where (`c`.`id` = 6)
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_afastado_detalhado` AS select `ge`.`nome` AS `grupo_nome`,`c`.`nome` AS `cliente_nome`,`b`.`id` AS `id_beneficiario`,`b`.`nome` AS `beneficiario_nome`,`b`.`cpf` AS `cpf`,`b`.`situacao` AS `situacao_beneficiario`,date_format(`a`.`data_inicio_afastamento`,'%d/%m/%Y') AS `data_inicio_afastamento`,date_format(`a`.`data_fim_afastamento`,'%d/%m/%Y') AS `data_fim_afastamento`,`a`.`cid` AS `cid`,`a`.`tipo_afastamento` AS `tipo_afastamento`,`a`.`assistencia_medica` AS `assistencia_medica`,`a`.`plano_assistencia_medica` AS `plano_assistencia_medica`,`a`.`situacao` AS `situacao_afastado`,date_format(`a`.`data_cadastro`,'%d/%m/%Y %H:%i:%s') AS `data_cadastro`,date_format(`a`.`data_atualizacao`,'%d/%m/%Y %H:%i:%s') AS `data_atualizacao` from (((`beneficiario` `b` join `afastado` `a` on((`a`.`beneficiario_id` = `b`.`id`))) join `cliente` `c` on((`c`.`id` = `b`.`cliente_id`))) join `grupo_empresarial` `ge` on((`ge`.`id` = `c`.`grupo_empresarial_id`))) where (`c`.`grupo_empresarial_id` = 10)
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_agendamento_aberto` AS select `ge`.`nome` AS `nome_grupo_empresarial`,`c`.`nome` AS `nome_cliente`,`ag`.`id` AS `id`,concat('https://samed.app.br/admin/beneficiario/view/',`b`.`id`) AS `beneficiario`,`b`.`cpf` AS `cpf`,`u`.`nome` AS `quem_criou`,`ug`.`nome` AS `usuario_responsavel`,date_format(`ag`.`data_cadastro`,'%d/%m/%Y %H:%i:%s') AS `data_hora_cadastro`,date_format(`ag`.`data_hora`,'%d/%m/%Y %H:%i:%s') AS `hora_agendamento` from ((((((`agendamento` `ag` left join `atendimento` `at` on((`ag`.`atendimento_id` = `at`.`id`))) left join `usuario` `u` on((`ag`.`usuario_id` = `u`.`id`))) left join `usuario` `ug` on((`ag`.`usuario_agendamento_id` = `ug`.`id`))) left join `beneficiario` `b` on((`at`.`beneficiario_id` = `b`.`id`))) left join `cliente` `c` on((`c`.`id` = `b`.`cliente_id`))) left join `grupo_empresarial` `ge` on((`ge`.`id` = `c`.`grupo_empresarial_id`))) where ((`ag`.`status` = 0) and (`ge`.`id` <> 1)) order by `ag`.`data_hora`
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_atend_por_usuario_por_cliente` AS select `c`.`nome` AS `nome`,`u`.`nome` AS `usuario_responsavel`,count(`a`.`id`) AS `total_atendimentos` from ((((`beneficiario` `b` join `atendimento` `a` on((`a`.`beneficiario_id` = `b`.`id`))) join `cliente` `c` on((`b`.`cliente_id` = `c`.`id`))) left join `empresa` `e` on((`e`.`id` = `b`.`empresa_id`))) join `usuario` `u` on((`a`.`usuario_id` = `u`.`id`))) group by `c`.`id`,`u`.`id`
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_atendim_por_benef` AS select `b`.`id` AS `id`,`b`.`nome` AS `nome`,`e`.`nome` AS `empresa`,`a`.`data_cadastro` AS `data_cadastro`,`a`.`data_conclusao` AS `data_conclusao`,count(`b`.`id`) AS `total_atendimentos` from ((`beneficiario` `b` join `atendimento` `a` on((`a`.`beneficiario_id` = `b`.`id`))) left join `empresa` `e` on((`e`.`id` = `b`.`empresa_id`))) where (`b`.`cliente_id` = 81) group by `b`.`id`
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_atendim_por_usuario` AS select `u`.`id` AS `id`,`u`.`nome` AS `usuario_responsavel`,count(`u`.`id`) AS `total_atendimentos` from (((`beneficiario` `b` join `atendimento` `a` on((`a`.`beneficiario_id` = `b`.`id`))) left join `empresa` `e` on((`e`.`id` = `b`.`empresa_id`))) join `usuario` `u` on((`a`.`usuario_id` = `u`.`id`))) where (`b`.`cliente_id` = 6) group by `u`.`id`
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_atendimento_por_usuario_por_data` AS select `c`.`nome` AS `nome`,`u`.`nome` AS `usuario_responsavel`,`a`.`descricao` AS `descricao`,date_format(`a`.`data_cadastro`,'%d/%m/%Y') AS `date_format(a.data_cadastro,'%d/%m/%Y')` from ((((`beneficiario` `b` join `atendimento` `a` on((`a`.`beneficiario_id` = `b`.`id`))) join `cliente` `c` on((`b`.`cliente_id` = `c`.`id`))) left join `empresa` `e` on((`e`.`id` = `b`.`empresa_id`))) join `usuario` `u` on((`a`.`usuario_id` = `u`.`id`))) where ((`u`.`id` = 111) and (`a`.`data_cadastro` > '2024-09-01 00:00:00'))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_atendimentos_usuario_por_cliente` AS select `c`.`nome` AS `nome`,`u`.`id` AS `id`,`u`.`nome` AS `usuario_responsavel`,count(`a`.`id`) AS `total_atendimentos` from ((((`beneficiario` `b` join `atendimento` `a` on((`a`.`beneficiario_id` = `b`.`id`))) join `cliente` `c` on((`b`.`cliente_id` = `c`.`id`))) left join `empresa` `e` on((`e`.`id` = `b`.`empresa_id`))) join `usuario` `u` on((`a`.`usuario_id` = `u`.`id`))) where ((`c`.`grupo_empresarial_id` = 7) and (`u`.`id` not in (1,3))) group by `c`.`id`,`u`.`id`
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_benef_ativos` AS select `ge`.`nome` AS `grupo_nome`,`c`.`nome` AS `cliente_nome`,`b`.`id` AS `id`,`b`.`nome` AS `beneficiario_nome`,`b`.`cpf` AS `cpf`,`b`.`situacao` AS `situacao` from ((`beneficiario` `b` join `cliente` `c` on((`c`.`id` = `b`.`cliente_id`))) join `grupo_empresarial` `ge` on((`ge`.`id` = `c`.`grupo_empresarial_id`))) where ((`ge`.`id` = 4) and (`b`.`status` = 1))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_benef_ativos_com_cnpj` AS select `ge`.`nome` AS `grupo_nome`,`c`.`nome` AS `cliente_nome`,`e`.`nome` AS `empresa_nome`,`e`.`cnpj` AS `cnpj`,`b`.`id` AS `id`,`b`.`nome` AS `beneficiario_nome`,`b`.`cpf` AS `cpf`,`b`.`situacao` AS `situacao`,`b`.`data_cadastro` AS `data_cadastro` from (((`beneficiario` `b` join `cliente` `c` on((`c`.`id` = `b`.`cliente_id`))) join `grupo_empresarial` `ge` on((`ge`.`id` = `c`.`grupo_empresarial_id`))) left join `empresa` `e` on((`e`.`id` = `b`.`empresa_id`))) where ((`c`.`id` = 10) or `c`.`id` in (select `cliente`.`id` from `cliente` where (`cliente`.`grupo_empresarial_id` = 10)))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_benef_por_casa` AS select `c`.`nome` AS `nome_cliente`,`b`.`id` AS `id`,`b`.`nome` AS `nome_beneficiario`,`b`.`cpf` AS `cpf`,`b`.`data_nascimento` AS `data_nascimento`,`b`.`situacao` AS `situacao`,`b`.`status` AS `status` from (`beneficiario` `b` join `cliente` `c` on((`c`.`id` = `b`.`cliente_id`))) where ((`c`.`grupo_empresarial_id` = 7) or (`b`.`cliente_id` in (6,10)))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_clientes_por_grupo_emp` AS select `ge`.`nome` AS `nome_grupo`,`c`.`id` AS `cliente_id`,`c`.`nome` AS `cliente` from (`cliente` `c` join `grupo_empresarial` `ge` on((`ge`.`id` = `c`.`grupo_empresarial_id`))) where ((`ge`.`status` = 1) and (`c`.`status` = 1) and (`ge`.`id` not in (6,8,9)))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_cpf_distinct_por_cliente` AS select distinct `beneficiario`.`cpf` AS `cpf` from `beneficiario` where ((`beneficiario`.`cliente_id` = 10) and (`beneficiario`.`status` = 1))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_usuario_por_clientes` AS select `u`.`id` AS `usuario_id`,`u`.`nome` AS `usuario_nome`,`u`.`email` AS `usuario_email`,`c`.`id` AS `id_cliente`,`c`.`nome` AS `nome_cliente` from ((`usuario` `u` join `usuario_cliente` `uc` on((`u`.`id` = `uc`.`usuario_id`))) join `cliente` `c` on((`c`.`id` = `uc`.`cliente_id`))) where ((`c`.`grupo_empresarial_id` = 10) and (`u`.`id` not in (1,167,338,3)) and (`u`.`status` = 1))
;

-- Removendo tabela temporária e criando a estrutura VIEW final
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `report_usuario_por_ge` AS select `u`.`id` AS `usuario_id`,`u`.`nome` AS `usuario_nome`,`u`.`email` AS `usuario_email`,`c`.`id` AS `id_cliente`,`c`.`nome` AS `nome_cliente` from ((`usuario` `u` join `usuario_cliente` `uc` on((`u`.`id` = `uc`.`usuario_id`))) join `cliente` `c` on((`c`.`id` = `uc`.`cliente_id`))) where (`c`.`grupo_empresarial_id` = 10)
;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
