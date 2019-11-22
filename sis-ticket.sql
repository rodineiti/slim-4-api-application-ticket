-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Tempo de geração: 14-Nov-2019 às 16:47
-- Versão do servidor: 5.6.35-81.0-log
-- versão do PHP: 7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sis-ticket`
--

USE HIfAvRRYxL;
-- --------------------------------------------------------

--
-- Estrutura da tabela `phinxlog`
--

CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `migration_name` varchar(100) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `breakpoint` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `phinxlog`
--

INSERT INTO `phinxlog` (`version`, `migration_name`, `start_time`, `end_time`, `breakpoint`) VALUES
(20180801120259, 'UsersTable', '2018-08-01 12:07:44', '2018-08-01 12:07:44', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbarea`
--

CREATE TABLE `tbarea` (
  `areId` int(11) NOT NULL,
  `areDescricao` varchar(100) DEFAULT NULL,
  `cor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbblog`
--

CREATE TABLE `tbblog` (
  `blogId` int(11) NOT NULL,
  `blogComentario` text,
  `blogUsuario` varchar(50) DEFAULT NULL,
  `blogDataCadastro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbcategoria`
--

CREATE TABLE `tbcategoria` (
  `catId` int(11) NOT NULL,
  `catDescricao` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `cor` varchar(255) COLLATE latin1_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tbchamados`
--

CREATE TABLE `tbchamados` (
  `chaId` int(11) NOT NULL,
  `areId` int(11) NOT NULL,
  `priId` int(11) NOT NULL,
  `staId` int(11) NOT NULL,
  `tipId` int(11) NOT NULL,
  `usuId` int(11) NOT NULL,
  `chaNomeCliente` varchar(255) DEFAULT NULL,
  `chaDescricao` varchar(255) DEFAULT NULL,
  `chaSlug` varchar(255) DEFAULT NULL,
  `chaConteudo` longtext,
  `chaConteudoHtml` longtext,
  `chaToken` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbchamados_copy`
--

CREATE TABLE `tbchamados_copy` (
  `chaId` int(11) NOT NULL,
  `areId` int(11) NOT NULL,
  `priId` int(11) DEFAULT NULL,
  `staId` int(11) DEFAULT NULL,
  `tipId` int(11) DEFAULT NULL,
  `usuId` int(11) DEFAULT NULL,
  `chaOS` varchar(10) DEFAULT NULL,
  `chaDescricao` varchar(255) DEFAULT NULL,
  `chaEnvolvidos` text,
  `chaObservacao` text,
  `chaDataCadastro` datetime DEFAULT NULL,
  `chaDataAlteracao` datetime DEFAULT NULL,
  `chaDataHoraInicio` datetime DEFAULT NULL,
  `chaDataHoraFim` datetime DEFAULT NULL,
  `chaCanal` char(1) DEFAULT NULL,
  `chaSolicitacao` char(1) DEFAULT NULL,
  `chaDataChamado` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbdemanda`
--

CREATE TABLE `tbdemanda` (
  `demId` int(11) NOT NULL,
  `areId` int(11) NOT NULL,
  `priId` int(11) DEFAULT NULL,
  `staId` int(11) DEFAULT NULL,
  `demOS` varchar(10) DEFAULT NULL,
  `demDescricao` varchar(255) DEFAULT NULL,
  `demEnvolvidos` text,
  `demObservacao` text,
  `demDataCadastro` datetime DEFAULT NULL,
  `demDataAlteracao` datetime DEFAULT NULL,
  `tipId` int(11) DEFAULT NULL,
  `demDataHoraInicio` datetime DEFAULT NULL,
  `demDataHoraFim` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbdetalhaequipamento`
--

CREATE TABLE `tbdetalhaequipamento` (
  `dteqpId` int(11) NOT NULL,
  `eqpId` int(11) DEFAULT NULL,
  `dteqpNome` varchar(150) DEFAULT NULL,
  `dteqpCaminhoImg` varchar(255) DEFAULT NULL,
  `dteqpStatus` char(1) DEFAULT NULL,
  `dteqpTipo` char(1) DEFAULT NULL,
  `dteqpMiniResumo` text,
  `dteqpDataCadastro` datetime DEFAULT NULL,
  `dteqpDataAtualizacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbdetalhaprodutos`
--

CREATE TABLE `tbdetalhaprodutos` (
  `dtproId` int(11) NOT NULL,
  `proId` int(11) DEFAULT NULL,
  `dtproNome` varchar(150) DEFAULT NULL,
  `dtproCaminhoImg` varchar(255) DEFAULT NULL,
  `dtproStatus` char(1) DEFAULT NULL,
  `dtproTipo` char(1) DEFAULT NULL,
  `dtproMiniResumo` text,
  `dtproDataCadastro` datetime DEFAULT NULL,
  `dtproDataAtualizacao` datetime DEFAULT NULL,
  `dtproEmbed` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbemails`
--

CREATE TABLE `tbemails` (
  `emaId` int(11) NOT NULL,
  `chaId` int(11) NOT NULL,
  `emaSuject` varchar(255) DEFAULT NULL,
  `emaAddress` longtext,
  `emaBody` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbequipamento`
--

CREATE TABLE `tbequipamento` (
  `eqpId` int(11) NOT NULL,
  `eqpNome` varchar(150) DEFAULT NULL,
  `eqpStatus` char(1) DEFAULT NULL,
  `eqpCaminhoImg` varchar(255) DEFAULT NULL,
  `eqpMiniResumo` text,
  `eqpDataCadastro` datetime DEFAULT NULL,
  `eqpDataAtualizacao` datetime DEFAULT NULL,
  `eqpNomeSlug` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbeventos`
--

CREATE TABLE `tbeventos` (
  `eveId` int(11) NOT NULL,
  `eveNome` varchar(255) DEFAULT NULL,
  `eveDataInicio` date DEFAULT NULL,
  `eveDataFim` date DEFAULT NULL,
  `eveCliente` varchar(255) DEFAULT NULL,
  `eveContato` varchar(255) DEFAULT NULL,
  `eveComercial` varchar(100) DEFAULT NULL,
  `eveOS` varchar(50) DEFAULT NULL,
  `eveDesenvolvedores` varchar(255) DEFAULT NULL,
  `eveTecnicos` varchar(255) DEFAULT NULL,
  `eveDataLiberacao` date DEFAULT NULL,
  `eveUrl` varchar(255) DEFAULT NULL,
  `eveDataCadastro` datetime DEFAULT NULL,
  `eveDataAlteracao` datetime DEFAULT NULL,
  `eveAno` varchar(10) DEFAULT NULL,
  `eveCor` varchar(10) DEFAULT NULL,
  `usuId` int(11) DEFAULT '1',
  `tecId` int(11) DEFAULT NULL,
  `desId` int(11) DEFAULT NULL,
  `eveToken` varchar(255) DEFAULT NULL,
  `eveObservacao` longtext,
  `eveHost` varchar(100) DEFAULT NULL,
  `eveBanco` varchar(100) DEFAULT NULL,
  `eveUser` varchar(50) DEFAULT NULL,
  `eveTemCertificado` char(1) DEFAULT 'N',
  `cntInscricao` char(1) DEFAULT 'N',
  `cntSecretaria` char(1) DEFAULT 'N',
  `cntAtividades` char(1) DEFAULT 'N',
  `cntHospedagem` char(1) DEFAULT 'N',
  `cntTrabalho` char(1) DEFAULT 'N',
  `cntProgramacao` char(1) DEFAULT 'N',
  `cntExpositor` char(1) DEFAULT 'N',
  `cntCertificado` char(1) DEFAULT 'N',
  `cntBoleto` char(1) DEFAULT 'N',
  `cntCieloWebservices` char(1) DEFAULT 'N',
  `cntVisaLetter` char(1) DEFAULT 'N',
  `cntFichaAvaliacao` char(1) DEFAULT 'N',
  `cntRSVPOnline` char(1) DEFAULT 'N',
  `cntPagSeguro` char(1) DEFAULT 'N',
  `cntPaypal` char(1) DEFAULT 'N',
  `cntPayU` char(1) DEFAULT 'N',
  `cntVoucher` char(1) DEFAULT 'N',
  `cntShopLine` char(1) DEFAULT 'N',
  `eveInscritosPago` varchar(10) DEFAULT NULL,
  `eveInscritosIsento` varchar(10) DEFAULT NULL,
  `eveInscritosGeral` varchar(10) DEFAULT NULL,
  `eveUltDataAtualizacaoSetup` datetime DEFAULT NULL,
  `webId` int(11) DEFAULT NULL,
  `eveTreinamento` char(1) DEFAULT 'N',
  `eveIDCliente` varchar(20) DEFAULT NULL,
  `eveEmailCliente` varchar(255) DEFAULT NULL,
  `eveTipoServico` char(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbimagens`
--

CREATE TABLE `tbimagens` (
  `imgId` int(11) NOT NULL,
  `demId` int(11) DEFAULT NULL,
  `imgTitle` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `imgPath` varchar(255) COLLATE latin1_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tblog`
--

CREATE TABLE `tblog` (
  `logId` int(11) NOT NULL,
  `usuId` int(11) DEFAULT NULL,
  `eveId` int(11) DEFAULT NULL,
  `logData` datetime DEFAULT NULL,
  `logSQL` text,
  `logIP` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Estrutura da tabela `tbocorrencia`
--

CREATE TABLE `tbocorrencia` (
  `tboId` int(11) NOT NULL,
  `eveId` int(11) DEFAULT NULL,
  `tipId` int(11) DEFAULT NULL,
  `tboDescricao` text COLLATE latin1_general_ci,
  `tboDataAlteracao` datetime DEFAULT NULL,
  `tboDataCadastro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbpermissoes`
--

CREATE TABLE `tbpermissoes` (
  `perId` int(11) NOT NULL,
  `usuId` int(11) NOT NULL,
  `perPermissoes` text,
  `perDataCadastro` datetime DEFAULT NULL,
  `perDataAtualizacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbpermissoes`
--

INSERT INTO `tbpermissoes` (`perId`, `usuId`, `perPermissoes`, `perDataCadastro`, `perDataAtualizacao`) VALUES
(1, 1, 'a:46:{s:8:\"vUsuario\";s:1:\"1\";s:8:\"aUsuario\";s:1:\"1\";s:8:\"eUsuario\";s:1:\"1\";s:8:\"dUsuario\";s:1:\"1\";s:5:\"vTipo\";s:1:\"1\";s:5:\"aTipo\";s:1:\"1\";s:5:\"eTipo\";s:1:\"1\";s:5:\"dTipo\";s:1:\"1\";s:5:\"vArea\";s:1:\"1\";s:5:\"aArea\";s:1:\"1\";s:5:\"eArea\";s:1:\"1\";s:5:\"dArea\";s:1:\"1\";s:11:\"vPrioridade\";s:1:\"1\";s:11:\"aPrioridade\";s:1:\"1\";s:11:\"ePrioridade\";s:1:\"1\";s:11:\"dPrioridade\";s:1:\"1\";s:7:\"vStatus\";s:1:\"1\";s:7:\"aStatus\";s:1:\"1\";s:7:\"eStatus\";s:1:\"1\";s:7:\"dStatus\";s:1:\"1\";s:8:\"vDemanda\";s:1:\"1\";s:8:\"aDemanda\";s:1:\"1\";s:8:\"eDemanda\";s:1:\"1\";s:8:\"dDemanda\";s:1:\"1\";s:8:\"vProduto\";s:1:\"1\";s:8:\"aProduto\";s:1:\"1\";s:8:\"eProduto\";s:1:\"1\";s:8:\"dProduto\";s:1:\"1\";s:12:\"vEquipamento\";s:1:\"1\";s:12:\"aEquipamento\";s:1:\"1\";s:12:\"eEquipamento\";s:1:\"1\";s:12:\"dEquipamento\";s:1:\"1\";s:7:\"vPlugin\";s:1:\"1\";s:7:\"aPlugin\";s:1:\"1\";s:7:\"ePlugin\";s:1:\"1\";s:7:\"dPlugin\";s:1:\"1\";s:7:\"vEvento\";s:1:\"1\";s:7:\"aEvento\";s:1:\"1\";s:7:\"eEvento\";s:1:\"1\";s:7:\"dEvento\";s:1:\"1\";s:7:\"tEvento\";s:1:\"1\";s:13:\"vProcedimento\";s:1:\"1\";s:13:\"aProcedimento\";s:1:\"1\";s:13:\"eProcedimento\";s:1:\"1\";s:13:\"dProcedimento\";s:1:\"1\";s:11:\"vCalendario\";s:1:\"1\";}', '2017-02-08 16:59:32', '2017-07-19 10:54:06');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbplugin`
--

CREATE TABLE `tbplugin` (
  `pluproId` int(11) NOT NULL,
  `proId` int(11) DEFAULT NULL,
  `pluproNome` varchar(150) DEFAULT NULL,
  `pluproStatus` char(2) DEFAULT NULL,
  `pluproFase` varchar(30) DEFAULT NULL,
  `pluproCaminhoArq` varchar(200) DEFAULT NULL,
  `pluproMiniResumo` text,
  `pluproDataCadastro` datetime DEFAULT NULL,
  `pluproDataAlteracao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbprioridade`
--

CREATE TABLE `tbprioridade` (
  `priId` int(11) NOT NULL,
  `priDescricao` varchar(100) DEFAULT NULL,
  `cor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbprocedimentos`
--

CREATE TABLE `tbprocedimentos` (
  `prcId` int(11) NOT NULL,
  `prcNome` varchar(150) DEFAULT NULL,
  `prcStatus` char(1) DEFAULT NULL,
  `prcCaminhoImg` varchar(255) DEFAULT NULL,
  `prcMiniResumo` text,
  `prcDataCadastro` datetime DEFAULT NULL,
  `prcDataAlteracao` datetime DEFAULT NULL,
  `prcNomeSlug` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbproduto`
--

CREATE TABLE `tbproduto` (
  `proId` int(11) NOT NULL,
  `proNome` varchar(150) DEFAULT NULL,
  `proStatus` char(1) DEFAULT NULL,
  `proCaminhoImg` varchar(255) DEFAULT NULL,
  `proMiniResumo` text,
  `proDataCadastro` datetime DEFAULT NULL,
  `proDataAlteracao` datetime DEFAULT NULL,
  `proNomeSlug` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbrespostas`
--

CREATE TABLE `tbrespostas` (
  `resId` int(11) NOT NULL,
  `chaId` int(11) NOT NULL,
  `usuId` int(11) NOT NULL,
  `resNome` varchar(255) DEFAULT NULL,
  `resConteudo` longtext,
  `resConteudoHtml` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbstatus`
--

CREATE TABLE `tbstatus` (
  `staId` int(11) NOT NULL,
  `staDescricao` varchar(100) DEFAULT NULL,
  `cor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbsubcategoria`
--

CREATE TABLE `tbsubcategoria` (
  `subId` int(11) NOT NULL,
  `subDescricao` varchar(100) COLLATE latin1_general_ci DEFAULT NULL,
  `catId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbtipo`
--

CREATE TABLE `tbtipo` (
  `tipId` int(11) NOT NULL,
  `tipDescricao` varchar(100) DEFAULT NULL,
  `cor` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbtokens`
--

CREATE TABLE `tbtokens` (
  `tokId` int(11) NOT NULL,
  `token_access` varchar(255) DEFAULT NULL,
  `token_validate` datetime DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `user` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tbusuario`
--

CREATE TABLE `tbusuario` (
  `usuId` int(11) NOT NULL,
  `usuNome` varchar(70) DEFAULT NULL,
  `usuEmail` varchar(70) DEFAULT NULL,
  `usuSenha` varchar(255) DEFAULT NULL,
  `usuDataCadastro` datetime DEFAULT NULL,
  `usuDataAtualizacao` datetime DEFAULT NULL,
  `usuDataUltAcesso` datetime DEFAULT NULL,
  `usuIP` varchar(50) DEFAULT NULL,
  `usuStatus` char(2) DEFAULT NULL,
  `usuAtivo` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tbusuario`
--

INSERT INTO `tbusuario` (`usuId`, `usuNome`, `usuEmail`, `usuSenha`, `usuDataCadastro`, `usuDataAtualizacao`, `usuDataUltAcesso`, `usuIP`, `usuStatus`, `usuAtivo`) VALUES
(1, 'Administrador', 'admin@admin.com', '5ebe2294ecd0e0f08eab7690d2a6ee69', '2016-04-09 00:44:24', '2017-07-24 17:04:31', '2019-10-15 10:19:59', '0.0.0.0', 'A', 'S');

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(250) NOT NULL,
  `password` varchar(100) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `phinxlog`
--
ALTER TABLE `phinxlog`
  ADD PRIMARY KEY (`version`);

--
-- Índices para tabela `tbarea`
--
ALTER TABLE `tbarea`
  ADD PRIMARY KEY (`areId`);

--
-- Índices para tabela `tbblog`
--
ALTER TABLE `tbblog`
  ADD PRIMARY KEY (`blogId`);

--
-- Índices para tabela `tbcategoria`
--
ALTER TABLE `tbcategoria`
  ADD PRIMARY KEY (`catId`);

--
-- Índices para tabela `tbchamados`
--
ALTER TABLE `tbchamados`
  ADD PRIMARY KEY (`chaId`);

--
-- Índices para tabela `tbchamados_copy`
--
ALTER TABLE `tbchamados_copy`
  ADD PRIMARY KEY (`chaId`);

--
-- Índices para tabela `tbdemanda`
--
ALTER TABLE `tbdemanda`
  ADD PRIMARY KEY (`demId`);

--
-- Índices para tabela `tbdetalhaequipamento`
--
ALTER TABLE `tbdetalhaequipamento`
  ADD PRIMARY KEY (`dteqpId`);

--
-- Índices para tabela `tbdetalhaprodutos`
--
ALTER TABLE `tbdetalhaprodutos`
  ADD PRIMARY KEY (`dtproId`);

--
-- Índices para tabela `tbemails`
--
ALTER TABLE `tbemails`
  ADD PRIMARY KEY (`emaId`);

--
-- Índices para tabela `tbequipamento`
--
ALTER TABLE `tbequipamento`
  ADD PRIMARY KEY (`eqpId`);

--
-- Índices para tabela `tbeventos`
--
ALTER TABLE `tbeventos`
  ADD PRIMARY KEY (`eveId`);

--
-- Índices para tabela `tbimagens`
--
ALTER TABLE `tbimagens`
  ADD PRIMARY KEY (`imgId`);

--
-- Índices para tabela `tblog`
--
ALTER TABLE `tblog`
  ADD PRIMARY KEY (`logId`);

--
-- Índices para tabela `tbocorrencia`
--
ALTER TABLE `tbocorrencia`
  ADD PRIMARY KEY (`tboId`);

--
-- Índices para tabela `tbpermissoes`
--
ALTER TABLE `tbpermissoes`
  ADD PRIMARY KEY (`perId`);

--
-- Índices para tabela `tbplugin`
--
ALTER TABLE `tbplugin`
  ADD PRIMARY KEY (`pluproId`);

--
-- Índices para tabela `tbprioridade`
--
ALTER TABLE `tbprioridade`
  ADD PRIMARY KEY (`priId`);

--
-- Índices para tabela `tbprocedimentos`
--
ALTER TABLE `tbprocedimentos`
  ADD PRIMARY KEY (`prcId`);

--
-- Índices para tabela `tbproduto`
--
ALTER TABLE `tbproduto`
  ADD PRIMARY KEY (`proId`);

--
-- Índices para tabela `tbrespostas`
--
ALTER TABLE `tbrespostas`
  ADD PRIMARY KEY (`resId`);

--
-- Índices para tabela `tbstatus`
--
ALTER TABLE `tbstatus`
  ADD PRIMARY KEY (`staId`);

--
-- Índices para tabela `tbsubcategoria`
--
ALTER TABLE `tbsubcategoria`
  ADD PRIMARY KEY (`subId`);

--
-- Índices para tabela `tbtipo`
--
ALTER TABLE `tbtipo`
  ADD PRIMARY KEY (`tipId`);

--
-- Índices para tabela `tbtokens`
--
ALTER TABLE `tbtokens`
  ADD PRIMARY KEY (`tokId`);

--
-- Índices para tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  ADD PRIMARY KEY (`usuId`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tbarea`
--
ALTER TABLE `tbarea`
  MODIFY `areId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbblog`
--
ALTER TABLE `tbblog`
  MODIFY `blogId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbcategoria`
--
ALTER TABLE `tbcategoria`
  MODIFY `catId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `tbchamados`
--
ALTER TABLE `tbchamados`
  MODIFY `chaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de tabela `tbchamados_copy`
--
ALTER TABLE `tbchamados_copy`
  MODIFY `chaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `tbdemanda`
--
ALTER TABLE `tbdemanda`
  MODIFY `demId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `tbdetalhaequipamento`
--
ALTER TABLE `tbdetalhaequipamento`
  MODIFY `dteqpId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbdetalhaprodutos`
--
ALTER TABLE `tbdetalhaprodutos`
  MODIFY `dtproId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbemails`
--
ALTER TABLE `tbemails`
  MODIFY `emaId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `tbequipamento`
--
ALTER TABLE `tbequipamento`
  MODIFY `eqpId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbeventos`
--
ALTER TABLE `tbeventos`
  MODIFY `eveId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=914;

--
-- AUTO_INCREMENT de tabela `tbimagens`
--
ALTER TABLE `tbimagens`
  MODIFY `imgId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `tblog`
--
ALTER TABLE `tblog`
  MODIFY `logId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- AUTO_INCREMENT de tabela `tbocorrencia`
--
ALTER TABLE `tbocorrencia`
  MODIFY `tboId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `tbpermissoes`
--
ALTER TABLE `tbpermissoes`
  MODIFY `perId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `tbplugin`
--
ALTER TABLE `tbplugin`
  MODIFY `pluproId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbprioridade`
--
ALTER TABLE `tbprioridade`
  MODIFY `priId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbprocedimentos`
--
ALTER TABLE `tbprocedimentos`
  MODIFY `prcId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `tbproduto`
--
ALTER TABLE `tbproduto`
  MODIFY `proId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `tbrespostas`
--
ALTER TABLE `tbrespostas`
  MODIFY `resId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT de tabela `tbstatus`
--
ALTER TABLE `tbstatus`
  MODIFY `staId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `tbsubcategoria`
--
ALTER TABLE `tbsubcategoria`
  MODIFY `subId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbtipo`
--
ALTER TABLE `tbtipo`
  MODIFY `tipId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `tbtokens`
--
ALTER TABLE `tbtokens`
  MODIFY `tokId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tbusuario`
--
ALTER TABLE `tbusuario`
  MODIFY `usuId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
