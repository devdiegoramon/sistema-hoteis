
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sistema_hoteis_prosync`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acomodacao`
--

CREATE TABLE `acomodacao` (
                              `idacomodacao` int(11) NOT NULL,
                              `idtipoacomodacao` int(11) DEFAULT NULL,
                              `nome` varchar(255) DEFAULT NULL,
                              `numero` int(11) DEFAULT NULL,
                              `valor` decimal(11,2) DEFAULT NULL,
                              `capacidade` int(11) DEFAULT NULL,
                              `descricao` varchar(255) DEFAULT NULL,
                              `ativo` enum('s','n') DEFAULT NULL,
                              `datag` date DEFAULT NULL,
                              `horag` time DEFAULT NULL,
                              `cor` varchar(255) DEFAULT NULL,
                              `tipo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `acomodacao`
--

INSERT INTO `acomodacao` (`idacomodacao`, `idtipoacomodacao`, `nome`, `numero`, `valor`, `capacidade`, `descricao`, `ativo`, `datag`, `horag`, `cor`, `tipo`) VALUES
                                                                                                                                                                  (1, 1, 'Suíte Sublime', 1, 200.00, 2, 'Quarto cama casal, frigobar, mesa pequena, ar-condicionado', 's', '2024-12-09', '10:53:37', '', ''),
                                                                                                                                                                  (2, 2, 'Suíte Deluxe', 2, 400.00, 4, 'Quarto cama casal, com cama solteiro elevada, frigobar, mesa  de cozinha, ar-condicionado, TV e banheiro.', 's', '2024-12-18', '08:30:36', '', ''),
                                                                                                                                                                  (3, 1, 'Quarto duplo', 3, 250.00, 4, 'Quarto duplo teste', 's', '2024-12-18', '08:41:39', '', '1'),
                                                                                                                                                                  (4, 2, 'Suíte Premium', 4, 500.00, 2, 'Suíte Premium', 's', '2024-12-18', '08:44:06', '', '2'),
                                                                                                                                                                  (5, 1, 'Teste', 5, 12312.00, 3, '2131232', 'n', '2024-12-18', '08:47:31', 'default', '1'),
                                                                                                                                                                  (6, 1, 'Testeteste', 123, 3123.00, 123, '123123', 'n', '2024-12-18', '08:50:36', 'default', '1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `adicionalconsumo`
--

CREATE TABLE `adicionalconsumo` (
                                    `idadicional` int(11) NOT NULL,
                                    `idconsumo` int(11) DEFAULT NULL,
                                    `motivo` varchar(255) DEFAULT NULL,
                                    `valor` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `adicionalconsumo`
--

INSERT INTO `adicionalconsumo` (`idadicional`, `idconsumo`, `motivo`, `valor`) VALUES
    (1, 1, 'Quebrou porta', 100.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoriaestoque`
--

CREATE TABLE `categoriaestoque` (
                                    `idcategoria` int(11) NOT NULL,
                                    `nome` varchar(255) DEFAULT NULL,
                                    `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoriaestoque`
--

INSERT INTO `categoriaestoque` (`idcategoria`, `nome`, `ativo`) VALUES
    (1, 'Bebidas', 's');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
                           `idcliente` int(11) NOT NULL,
                           `nome` varchar(255) DEFAULT NULL,
                           `cpf` varchar(255) DEFAULT NULL,
                           `dtnasc` date DEFAULT NULL,
                           `email` varchar(255) DEFAULT NULL,
                           `telefone` varchar(255) DEFAULT NULL,
                           `estado` varchar(255) DEFAULT NULL,
                           `cidade` varchar(255) DEFAULT NULL,
                           `datag` date DEFAULT NULL,
                           `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`idcliente`, `nome`, `cpf`, `dtnasc`, `email`, `telefone`, `estado`, `cidade`, `datag`, `ativo`) VALUES
                                                                                                                            (1, 'Mariazinha', '000.000.000-00', '2000-01-01', '00000@admin.com', '(00) 00000-000', 'PE', 'Recife', '2024-12-09', 's'),
                                                                                                                            (2, 'Joãozinho', '111.111.111-11', '1111-11-11', '11111@admin.com', '(11) 11111-1111', 'AC', 'Acrelândia', '2024-12-18', 's');

-- --------------------------------------------------------

--
-- Estrutura para tabela `consumo`
--

CREATE TABLE `consumo` (
                           `idconsumo` int(11) NOT NULL,
                           `idreserva` int(11) DEFAULT NULL,
                           `valorestadia` decimal(11,2) DEFAULT NULL,
                           `valoritens` decimal(11,2) DEFAULT NULL,
                           `valoradicional` decimal(11,2) DEFAULT NULL,
                           `status` enum('pendente','concluido') DEFAULT NULL,
                           `datafechamento` date DEFAULT NULL,
                           `horafechamento` time DEFAULT NULL,
                           `valorfinal` decimal(11,2) DEFAULT NULL,
                           `totaldesconto` decimal(11,2) DEFAULT NULL,
                           `formapagamento` varchar(255) DEFAULT NULL,
                           `comprovantepagamento` varchar(255) DEFAULT NULL,
                           `pago` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `consumo`
--

INSERT INTO `consumo` (`idconsumo`, `idreserva`, `valorestadia`, `valoritens`, `valoradicional`, `status`, `datafechamento`, `horafechamento`, `valorfinal`, `totaldesconto`, `formapagamento`, `comprovantepagamento`, `pago`) VALUES
    (7, 7, 0.00, NULL, NULL, 'pendente', NULL, NULL, 0.00, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecoestabelecimento`
--

CREATE TABLE `enderecoestabelecimento` (
                                           `idenderecoestabelecimento` int(11) NOT NULL,
                                           `logradouro` varchar(255) DEFAULT NULL,
                                           `numero` varchar(255) DEFAULT NULL,
                                           `complemento` varchar(255) DEFAULT NULL,
                                           `cidade` varchar(255) DEFAULT NULL,
                                           `bairro` varchar(255) DEFAULT NULL,
                                           `cep` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estabelecimento`
--

CREATE TABLE `estabelecimento` (
                                   `idestabelecimento` int(11) NOT NULL,
                                   `cnpj` varchar(255) DEFAULT NULL,
                                   `razaosocial` varchar(255) DEFAULT NULL,
                                   `website` varchar(255) DEFAULT NULL,
                                   `email` varchar(255) DEFAULT NULL,
                                   `telefone` varchar(255) DEFAULT NULL,
                                   `celular` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estabelecimento`
--

INSERT INTO `estabelecimento` (`idestabelecimento`, `cnpj`, `razaosocial`, `website`, `email`, `telefone`, `celular`) VALUES
    (1, '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estacionamento`
--

CREATE TABLE `estacionamento` (
                                  `idvaga` int(11) NOT NULL,
                                  `idacomodacao` int(11) DEFAULT NULL,
                                  `numero` int(11) DEFAULT NULL,
                                  `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estacionamento`
--

INSERT INTO `estacionamento` (`idvaga`, `idacomodacao`, `numero`, `ativo`) VALUES
                                                                               (1, 3, 1, 's'),
                                                                               (2, 2, 2, 's'),
                                                                               (3, 7, 3, 's'),
                                                                               (4, 7, 4, 's'),
                                                                               (5, NULL, 5, 's'),
                                                                               (6, NULL, 6, 's'),
                                                                               (7, NULL, 7, 's'),
                                                                               (8, NULL, 8, 's');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
                           `iditem` int(11) NOT NULL,
                           `item` varchar(255) DEFAULT NULL,
                           `categoria` varchar(255) DEFAULT NULL,
                           `quantidade` int(11) DEFAULT NULL,
                           `valorunitario` decimal(11,2) DEFAULT NULL,
                           `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`iditem`, `item`, `categoria`, `quantidade`, `valorunitario`, `ativo`) VALUES
                                                                                                  (1, 'Heineken 600ml Latão', 'Bebidas', 1, 12.00, 's'),
                                                                                                  (2, 'Coca-Cola 400ml', 'Bebidas', 3, 6.00, 's'),
                                                                                                  (3, 'Água Mineral 250ml Santa Joana', 'Bebidas', 9, 3.00, 's'),
                                                                                                  (4, 'Guaraná Antártica 2L', 'Bebidas', 4, 10.00, 's'),
                                                                                                  (5, 'Pizza Marguerita', 'Comidas', 3, 40.00, 's'),
                                                                                                  (6, 'Hambúrguer x-bacon', 'Comidas', 20, 20.00, 's'),
                                                                                                  (7, 'Batata Frita 500g', 'Comidas', 8, 15.00, 's'),
                                                                                                  (8, 'Água Tônica 250ml', 'Bebidas', 15, 6.00, 's'),
                                                                                                  (9, 'Sprite 400ml', 'Bebidas', 8, 6.00, 's'),
                                                                                                  (10, 'Fanta Laranja 1.5L', 'Bebidas', 7, 7.00, 's'),
                                                                                                  (11, 'Suco de Laranja 400ml', 'Bebidas', 10, 7.00, 's'),
                                                                                                  (12, 'Cerveja Skol 350ml', 'Bebidas', 12, 5.00, 's'),
                                                                                                  (13, 'Espetinho de Carne de Sol', 'Comidas', 5, 10.00, 's'),
                                                                                                  (14, 'Lasanha 400g', 'Comidas', 20, 15.00, 's'),
                                                                                                  (15, 'Hambúrguer X-Calabresa', 'Comidas', 15, 20.00, 's'),
                                                                                                  (16, 'Salada de Frutas 300g', 'Comidas', 8, 10.00, 's'),
                                                                                                  (17, 'Sopa de Legumes', 'Comidas', 9, 10.00, 's');

-- --------------------------------------------------------

--
-- Estrutura para tabela `formapagamento`
--

CREATE TABLE `formapagamento` (
                                  `idformapagamento` int(11) NOT NULL,
                                  `nome` varchar(255) DEFAULT NULL,
                                  `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `frigobar`
--

CREATE TABLE `frigobar` (
                            `idfrigobar` int(11) NOT NULL,
                            `idacomodacao` int(11) DEFAULT NULL,
                            `modelo` varchar(255) DEFAULT NULL,
                            `patrimonio` varchar(255) DEFAULT NULL,
                            `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `frigobar`
--

INSERT INTO `frigobar` (`idfrigobar`, `idacomodacao`, `modelo`, `patrimonio`, `ativo`) VALUES
    (1, 1, 'Eletrolux', '500', 's');

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
                               `idlogin` int(11) NOT NULL,
                               `nome` varchar(255) DEFAULT NULL,
                               `login` varchar(255) DEFAULT NULL,
                               `senha` varchar(255) DEFAULT NULL,
                               `dtnascimento` date DEFAULT NULL,
                               `cpf` varchar(255) DEFAULT NULL,
                               `nivel` int(11) DEFAULT NULL,
                               `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`idlogin`, `nome`, `login`, `senha`, `dtnascimento`, `cpf`, `nivel`, `ativo`) VALUES
                                                                                                             (1, 'Administrador', 'admin', '$2y$10$/67uF2RsL.HfvIobnoecQ.6je/pI9LI/wLvzvp0cv7hq1RwHhlag.', '0000-00-00', '000.000.000-1', 1, 's'),
                                                                                                             (2, 'prosync', 'prosync', '$2y$10$IEYFbJaNUeDw.NZk.YNGJOcKf/g56upWr9pEAbRWuDWt2Bfmt8BuC', '0001-01-01', '00.000.000-00', 1, 's'),
                                                                                                             (3, 'Witória Larissa Ferreira da Silva', 'witoria.larissa', '$2y$10$5j1pEEGAigEg0IvVJGXU7uZFqlfJ89w9voP3ZwtxUVr6xjGXtovDe', '2006-10-15', '135.456.314-00', 2, 's');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itemfrigobar`
--

CREATE TABLE `itemfrigobar` (
                                `iditemfrigobar` int(11) NOT NULL,
                                `idfrigobar` int(11) DEFAULT NULL,
                                `iditem` int(11) DEFAULT NULL,
                                `quantidade` int(11) DEFAULT NULL,
                                `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itensconsumidos`
--

CREATE TABLE `itensconsumidos` (
                                   `id` int(11) NOT NULL,
                                   `idpedido` int(11) NOT NULL,
                                   `iditem` int(11) NOT NULL,
                                   `quantidade` int(11) NOT NULL,
                                   `valorunitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itensconsumidos`
--

INSERT INTO `itensconsumidos` (`id`, `idpedido`, `iditem`, `quantidade`, `valorunitario`) VALUES
                                                                                              (1, 1, 1, 1, 12.00),
                                                                                              (2, 1, 2, 1, 6.00),
                                                                                              (8, 5, 1, 1, 12.00),
                                                                                              (9, 6, 2, 1, 6.00),
                                                                                              (10, 7, 2, 1, 6.00),
                                                                                              (11, 8, 1, 1, 12.00),
                                                                                              (12, 9, 1, 1, 12.00),
                                                                                              (13, 9, 5, 1, 40.00),
                                                                                              (14, 9, 6, 1, 20.00),
                                                                                              (15, 9, 13, 1, 10.00),
                                                                                              (16, 9, 15, 1, 20.00),
                                                                                              (17, 10, 1, 1, 12.00),
                                                                                              (18, 10, 5, 1, 40.00),
                                                                                              (19, 10, 6, 1, 20.00),
                                                                                              (20, 10, 13, 1, 10.00),
                                                                                              (21, 10, 15, 1, 20.00),
                                                                                              (22, 11, 17, 1, 10.00),
                                                                                              (23, 12, 1, 1, 12.00),
                                                                                              (24, 13, 17, 1, 10.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `log`
--

CREATE TABLE `log` (
                       `idlog` int(11) NOT NULL,
                       `iduser` varchar(255) DEFAULT NULL,
                       `acao` varchar(255) DEFAULT NULL,
                       `obs` varchar(255) DEFAULT NULL,
                       `tabela` varchar(255) DEFAULT NULL,
                       `idtabela` varchar(255) DEFAULT NULL,
                       `json` varchar(8000) DEFAULT NULL,
                       `datag` date DEFAULT NULL,
                       `horag` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `log`
--

INSERT INTO `log` (`idlog`, `iduser`, `acao`, `obs`, `tabela`, `idtabela`, `json`, `datag`, `horag`) VALUES
                                                                                                         (1, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma nova logo no dia <b>09/12/2024</b> às <b>09:48:48</b>', 'logo', '1', NULL, '2024-12-09', '09:48:48'),
                                                                                                         (2, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma nova logo no dia <b>09/12/2024</b> às <b>09:49:09</b>', 'logo', '1', NULL, '2024-12-09', '09:49:09'),
                                                                                                         (3, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma nova logo no dia <b>09/12/2024</b> às <b>09:49:44</b>', 'logo', '1', NULL, '2024-12-09', '09:49:44'),
                                                                                                         (4, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou um tipo de acomodação com nome: <b>Térreo</b>, no dia <b>09/12/2024</b> às <b>10:52:08</b>', 'tipoacomodacao', '1', NULL, '2024-12-09', '10:52:08'),
                                                                                                         (5, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou um tipo de acomodação com nome: <b>Quarto 101</b>, no dia <b>09/12/2024</b> às <b>10:52:21</b>', 'tipoacomodacao', '2', NULL, '2024-12-09', '10:52:21'),
                                                                                                         (6, '1', 'edição', 'Funcionário: <b>admin</b>, <b>editou</b> o tipo de acomodação de: <b>Quarto 101</b> para <b> 1° Andar </b>, no dia <b>09/12/2024</b> às <b>10:52:45</b>', 'tipoacomodacao', '2', NULL, '2024-12-09', '10:52:45'),
                                                                                                         (7, '1', 'deletar', 'Funcionário: <b>admin</b>, cadastrou uma acomodação com nome: <b>Quarto</b>, no dia <b>09/12/2024</b> às <b>10:53:37</b>', 'estacionamento', '1', NULL, '2024-12-09', '10:53:37'),
                                                                                                         (8, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou o cliente <b>Nº1</b> com nome: <b>Witória Larissa Ferreira da SIlva</b>, no dia <b>09/12/2024</b> às <b>10:54:34</b>', 'cliente', '1', NULL, '2024-12-09', '10:54:34'),
                                                                                                         (9, '1', 'desativar', 'Funcionário: <b>admin</b>, <b>Desativou</b> a acomodação: <b>Quarto</b>, no dia <b>09/12/2024</b> às <b>10:54:51</b>', 'acomodacao', '1', NULL, '2024-12-09', '10:54:51'),
                                                                                                         (10, '1', 'ativar', 'Funcionário: <b>admin</b>, <b>Ativou</b> a acomodação: <b>Quarto</b>, no dia <b>09/12/2024</b> às <b>10:54:52</b>', 'acomodacao', '1', NULL, '2024-12-09', '10:54:52'),
                                                                                                         (11, '1', 'edição', 'Funcionário: <b>admin</b>, <b>editou</b> a acomodação: <b>Quarto</b>, no dia <b>09/12/2024</b> às <b>10:55:19</b>', 'acomodacao', '1', NULL, '2024-12-09', '10:55:19'),
                                                                                                         (12, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 1</b> para o dia 09/12/2024 até 09/12/2024 na acomodação Nº1, no dia <b>09/12/2024</b> às <b>10:56:11</b>', 'reserva', '1', NULL, '2024-12-09', '10:56:11'),
                                                                                                         (13, '1', 'checkin', 'Funcionário: <b>admin</b>, realizou o Check-in na reserva <b>Nº 1</b>, no dia <b>09/12/2024</b> às <b>10:56:37</b>', 'reserva', '1', '{\"idreserva\":\"1\",\"acao\":\"check-in\",\"nomefuncionario\":\"Administrador\",\"datacheckin\":\"2024-12-09\",\"horacheckin\":\"10:56:37\"}', '2024-12-09', '10:56:37'),
                                                                                                         (14, '1', 'checkout', 'Funcionário: <b>admin</b>, realizou o Check-out na reserva <b>Nº 1</b>, no dia <b>09/12/2024</b> às <b>10:57:47</b>', 'reserva', '1', '{\"idreserva\":\"1\",\"acao\":\"check-out\",\"nomefuncionario\":\"Administrador\",\"datacheckout\":\"2024-12-09\",\"horacheckout\":\"10:57:47\"}', '2024-12-09', '10:57:47'),
                                                                                                         (15, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma nova logo no dia <b>09/12/2024</b> às <b>10:59:13</b>', 'logo', '1', NULL, '2024-12-09', '10:59:13'),
                                                                                                         (16, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma categoria com nome: <b>Bebidas</b>, no dia <b>11/12/2024</b> às <b>10:13:37</b>', 'categoriaestoque', '1', NULL, '2024-12-11', '10:13:37'),
                                                                                                         (17, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou um item com nome: <b>Heineken 600ml Latão</b>, no dia <b>11/12/2024</b> às <b>10:14:03</b>', 'estoque', '1', NULL, '2024-12-11', '10:14:03'),
                                                                                                         (18, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou um frigobar com patrimônio: <b>500</b>, no dia <b>11/12/2024</b> às <b>11:40:42</b>', 'frigobar', '1', NULL, '2024-12-11', '11:40:42'),
                                                                                                         (19, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou um item com nome: <b>Coca-Cola 400ml</b>, no dia <b>12/12/2024</b> às <b>09:58:14</b>', 'estoque', '2', NULL, '2024-12-12', '09:58:14'),
                                                                                                         (20, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>1</b>, no dia <b>18/12/2024</b> às <b>08:24:11</b>', 'estacionamento', '1', NULL, '2024-12-18', '08:24:11'),
                                                                                                         (21, '1', 'edição de cliente', 'Funcionário: <b>admin</b>, <b>editou</b> a cliente <b>Nº1</b> : <b>Mariazinha</b>, no dia <b>18/12/2024</b> às <b>08:24:47</b>', 'cliente', '1', NULL, '2024-12-18', '08:24:47'),
                                                                                                         (22, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou o cliente <b>Nº2</b> com nome: <b>Joãozinho</b>, no dia <b>18/12/2024</b> às <b>08:25:41</b>', 'cliente', '1', NULL, '2024-12-18', '08:25:41'),
                                                                                                         (23, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>2</b>, no dia <b>18/12/2024</b> às <b>08:25:47</b>', 'estacionamento', '2', NULL, '2024-12-18', '08:25:47'),
                                                                                                         (24, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 2</b> para o dia 18/12/2024 até 22/12/2024 na acomodação Nº1, no dia <b>18/12/2024</b> às <b>08:26:53</b>', 'reserva', '2', NULL, '2024-12-18', '08:26:53'),
                                                                                                         (25, '1', 'edição', 'Funcionário: <b>admin</b>, <b>editou</b> a acomodação: <b>Suíte Sublime</b>, no dia <b>18/12/2024</b> às <b>08:29:45</b>', 'acomodacao', '1', NULL, '2024-12-18', '08:29:45'),
                                                                                                         (26, '1', 'edição', 'Funcionário: <b>admin</b>, <b>editou</b> a acomodação: <b>Quarto duplo</b>, no dia <b>18/12/2024</b> às <b>08:46:38</b>', 'acomodacao', '3', NULL, '2024-12-18', '08:46:38'),
                                                                                                         (27, '1', 'edição', 'Funcionário: <b>admin</b>, <b>editou</b> a acomodação: <b>Suíte Premium</b>, no dia <b>18/12/2024</b> às <b>08:46:43</b>', 'acomodacao', '4', NULL, '2024-12-18', '08:46:43'),
                                                                                                         (28, '1', 'cadastro', 'Funcionário: <b>admin</b> cadastrou a acomodação <b>Testeteste</b> em 2024-12-18 às 08:50:36.', 'acomodacao', '6', NULL, '2024-12-18', '08:50:36'),
                                                                                                         (29, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>3</b>, no dia <b>18/12/2024</b> às <b>08:50:43</b>', 'estacionamento', '3', NULL, '2024-12-18', '08:50:43'),
                                                                                                         (30, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>4</b>, no dia <b>18/12/2024</b> às <b>08:50:44</b>', 'estacionamento', '4', NULL, '2024-12-18', '08:50:44'),
                                                                                                         (31, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>5</b>, no dia <b>18/12/2024</b> às <b>08:50:46</b>', 'estacionamento', '5', NULL, '2024-12-18', '08:50:46'),
                                                                                                         (32, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>6</b>, no dia <b>18/12/2024</b> às <b>08:50:47</b>', 'estacionamento', '6', NULL, '2024-12-18', '08:50:47'),
                                                                                                         (33, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>7</b>, no dia <b>18/12/2024</b> às <b>08:50:48</b>', 'estacionamento', '7', NULL, '2024-12-18', '08:50:48'),
                                                                                                         (34, '1', 'cadastro', 'Funcionário: <b>admin</b>, cadastrou uma vaga de estacionamento de número <b>8</b>, no dia <b>18/12/2024</b> às <b>08:50:49</b>', 'estacionamento', '8', NULL, '2024-12-18', '08:50:49'),
                                                                                                         (35, '1', 'cadastro', 'Funcionário: <b>admin</b> vinculou a vaga de número <b>3</b> à acomodação <b>testeteste</b> em 2024-12-18 às 08:51:24.', 'acomodacao', '7', NULL, '2024-12-18', '08:51:24'),
                                                                                                         (36, '1', 'cadastro', 'Funcionário: <b>admin</b> vinculou a vaga de número <b>4</b> à acomodação <b>testeteste</b> em 2024-12-18 às 08:51:24.', 'acomodacao', '7', NULL, '2024-12-18', '08:51:24'),
                                                                                                         (37, '1', 'cadastro', 'Funcionário: <b>admin</b> cadastrou a acomodação <b>testeteste</b> em 2024-12-18 às 08:51:24.', 'acomodacao', '7', NULL, '2024-12-18', '08:51:24'),
                                                                                                         (38, '1', 'desativar', 'Funcionário: <b>admin</b>, <b>Desativou</b> a acomodação: <b>Quarto duplo</b>, no dia <b>18/12/2024</b> às <b>08:52:01</b>', 'acomodacao', '3', NULL, '2024-12-18', '08:52:01'),
                                                                                                         (39, '1', 'ativar', 'Funcionário: <b>admin</b>, <b>Ativou</b> a acomodação: <b>Quarto duplo</b>, no dia <b>18/12/2024</b> às <b>08:52:20</b>', 'acomodacao', '3', NULL, '2024-12-18', '08:52:20'),
                                                                                                         (40, '1', 'desativar', 'Funcionário: <b>admin</b>, <b>Desativou</b> a acomodação: <b>Teste</b>, no dia <b>18/12/2024</b> às <b>08:52:22</b>', 'acomodacao', '5', NULL, '2024-12-18', '08:52:22'),
                                                                                                         (41, '1', 'desativar', 'Funcionário: <b>admin</b>, <b>Desativou</b> a acomodação: <b>Testeteste</b>, no dia <b>18/12/2024</b> às <b>08:52:22</b>', 'acomodacao', '6', NULL, '2024-12-18', '08:52:22'),
                                                                                                         (42, '1', 'desativar', 'Funcionário: <b>admin</b>, <b>Desativou</b> a acomodação: <b>testeteste</b>, no dia <b>18/12/2024</b> às <b>08:52:23</b>', 'acomodacao', '7', NULL, '2024-12-18', '08:52:23'),
                                                                                                         (43, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 3</b> para o dia 18/12/2024 até 20/12/2024 na acomodação Nº4, no dia <b>18/12/2024</b> às <b>09:00:42</b>', 'reserva', '3', NULL, '2024-12-18', '09:00:42'),
                                                                                                         (44, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 4</b> para o dia 19/12/2024 até 22/12/2024 na acomodação Nº2, no dia <b>18/12/2024</b> às <b>09:24:41</b>', 'reserva', '4', NULL, '2024-12-18', '09:24:41'),
                                                                                                         (45, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 5</b> para o dia 23/12/2024 até 24/12/2024 na acomodação Nº4, no dia <b>23/12/2024</b> às <b>10:12:06</b>', 'reserva', '5', NULL, '2024-12-23', '10:12:06'),
                                                                                                         (46, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 6</b> para o dia 26/12/2024 até 26/12/2024 na acomodação Nº2, no dia <b>26/12/2024</b> às <b>08:42:02</b>', 'reserva', '6', NULL, '2024-12-26', '08:42:02'),
                                                                                                         (47, '1', 'cadastro', 'Funcionário: <b>admin</b>, realizou a reserva de <b>Nº 7</b> para o dia 07/01/2025 até 08/01/2025 na acomodação Nº2, no dia <b>07/01/2025</b> às <b>19:17:29</b>', 'reserva', '7', NULL, '2025-01-07', '19:17:29');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logacesso`
--

CREATE TABLE `logacesso` (
                             `idlogacesso` int(11) NOT NULL,
                             `idusuario` int(11) DEFAULT NULL,
                             `ultimologin` datetime DEFAULT NULL,
                             `ip` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logacesso`
--

INSERT INTO `logacesso` (`idlogacesso`, `idusuario`, `ultimologin`, `ip`) VALUES
                                                                              (1, 1, '2024-12-09 08:56:45', '127.0.0.1'),
                                                                              (2, 2, '2024-12-09 08:58:35', '127.0.0.1'),
                                                                              (3, 3, '2024-12-09 08:59:40', '127.0.0.1'),
                                                                              (4, 1, '2024-12-09 09:26:07', '127.0.0.1'),
                                                                              (5, 1, '2024-12-09 09:33:12', '127.0.0.1'),
                                                                              (6, 1, '2024-12-09 09:34:54', '127.0.0.1'),
                                                                              (7, 1, '2024-12-09 09:43:18', '127.0.0.1'),
                                                                              (8, 1, '2024-12-09 10:58:02', '127.0.0.1'),
                                                                              (9, 1, '2024-12-11 09:38:34', '127.0.0.1'),
                                                                              (10, 1, '2024-12-11 09:39:04', '127.0.0.1'),
                                                                              (11, 1, '2024-12-11 10:12:27', '127.0.0.1'),
                                                                              (12, 1, '2024-12-11 10:13:26', '127.0.0.1'),
                                                                              (13, 1, '2024-12-11 11:02:08', '127.0.0.1'),
                                                                              (14, 1, '2024-12-12 08:41:37', '127.0.0.1'),
                                                                              (15, 1, '2024-12-12 09:57:45', '127.0.0.1'),
                                                                              (16, 1, '2024-12-12 11:13:28', '127.0.0.1'),
                                                                              (17, 1, '2024-12-12 11:17:44', '127.0.0.1'),
                                                                              (18, 1, '2024-12-12 11:20:25', '127.0.0.1'),
                                                                              (19, 1, '2024-12-12 11:21:32', '127.0.0.1'),
                                                                              (20, 1, '2024-12-12 11:29:36', '127.0.0.1'),
                                                                              (21, 1, '2024-12-12 11:34:25', '127.0.0.1'),
                                                                              (22, 1, '2024-12-12 11:40:23', '127.0.0.1'),
                                                                              (23, 1, '2024-12-12 11:40:55', '127.0.0.1'),
                                                                              (24, 1, '2024-12-13 09:10:29', '127.0.0.1'),
                                                                              (25, 1, '2024-12-13 09:14:32', '127.0.0.1'),
                                                                              (26, 1, '2024-12-13 09:56:58', '127.0.0.1'),
                                                                              (27, 1, '2024-12-13 11:25:17', '127.0.0.1'),
                                                                              (28, 1, '2024-12-16 08:10:55', '127.0.0.1'),
                                                                              (29, 1, '2024-12-16 08:26:33', '127.0.0.1'),
                                                                              (30, 1, '2024-12-16 10:33:48', '127.0.0.1'),
                                                                              (31, 1, '2024-12-18 08:23:33', '127.0.0.1'),
                                                                              (32, 1, '2024-12-18 09:03:21', '127.0.0.1'),
                                                                              (33, 1, '2024-12-18 10:23:42', '127.0.0.1'),
                                                                              (34, 1, '2024-12-18 10:23:49', '127.0.0.1'),
                                                                              (35, 1, '2024-12-19 08:40:58', '127.0.0.1'),
                                                                              (36, 1, '2024-12-19 11:39:06', '127.0.0.1'),
                                                                              (37, 1, '2024-12-23 09:13:18', '127.0.0.1'),
                                                                              (38, 1, '2024-12-23 10:54:43', '127.0.0.1'),
                                                                              (39, 1, '2024-12-26 08:08:33', '::1'),
                                                                              (40, 1, '2025-01-07 19:02:09', '::1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `logo`
--

CREATE TABLE `logo` (
                        `idlogo` int(11) NOT NULL,
                        `logoserver` varchar(255) DEFAULT NULL,
                        `logonome` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `movestoque`
--

CREATE TABLE `movestoque` (
                              `idmov` int(11) NOT NULL,
                              `iditem` int(11) DEFAULT NULL,
                              `tipo` enum('entrada','saida') DEFAULT NULL,
                              `quantidade` int(11) DEFAULT NULL,
                              `usuario` varchar(255) DEFAULT NULL,
                              `motivo` enum('compra','venda','perda') DEFAULT NULL,
                              `datag` date DEFAULT NULL,
                              `horag` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `movestoque`
--

INSERT INTO `movestoque` (`idmov`, `iditem`, `tipo`, `quantidade`, `usuario`, `motivo`, `datag`, `horag`) VALUES
                                                                                                              (1, 1, 'entrada', 10, 'admin', 'compra', '2024-12-11', '10:14:03'),
                                                                                                              (2, 2, 'entrada', 1, 'admin', 'compra', '2024-12-12', '09:58:14'),
                                                                                                              (3, 1, 'entrada', 10, 'admin', '', '2024-12-12', '11:13:47'),
                                                                                                              (4, 2, 'entrada', 10, 'admin', '', '2024-12-12', '11:13:47'),
                                                                                                              (5, 1, 'entrada', 10, 'admin', '', '2024-12-12', '11:17:54'),
                                                                                                              (6, 2, 'entrada', 10, 'admin', '', '2024-12-12', '11:17:54'),
                                                                                                              (7, 1, 'entrada', 7, 'admin', '', '2024-12-12', '11:20:48'),
                                                                                                              (8, 2, 'entrada', 10, 'admin', '', '2024-12-12', '11:20:48'),
                                                                                                              (9, 1, 'entrada', 9, 'admin', '', '2024-12-12', '11:21:40'),
                                                                                                              (10, 2, 'entrada', 4, 'admin', '', '2024-12-12', '11:21:40'),
                                                                                                              (11, 1, 'entrada', 4, 'admin', '', '2024-12-12', '11:29:45'),
                                                                                                              (12, 2, 'entrada', 10, 'admin', '', '2024-12-12', '11:29:45'),
                                                                                                              (13, 2, 'entrada', 6, 'admin', '', '2024-12-12', '11:34:30'),
                                                                                                              (14, 1, 'entrada', 10, 'admin', '', '2024-12-12', '11:40:29'),
                                                                                                              (15, 2, 'entrada', 10, 'admin', '', '2024-12-12', '11:40:29'),
                                                                                                              (16, 1, 'entrada', 2, 'admin', '', '2024-12-13', '09:57:13');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamento`
--

CREATE TABLE `pagamento` (
                             `idpagamento` int(11) NOT NULL,
                             `idconsumo` int(11) DEFAULT NULL,
                             `valor` decimal(11,2) DEFAULT NULL,
                             `comprovante` varchar(255) DEFAULT NULL,
                             `observacao` text DEFAULT NULL,
                             `formapagamento` varchar(255) DEFAULT NULL,
                             `datag` date DEFAULT NULL,
                             `horag` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
                           `id` int(11) NOT NULL,
                           `data_pedido` datetime NOT NULL,
                           `status` varchar(50) NOT NULL,
                           `idcliente` int(11) NOT NULL,
                           `idacomodacao` int(11) NOT NULL,
                           `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `data_pedido`, `status`, `idcliente`, `idacomodacao`, `valor_total`) VALUES
                                                                                                      (1, '2024-12-13 11:25:46', 'Concluído', 1, 1, 18.00),
                                                                                                      (5, '2024-12-13 11:55:06', 'Pendente', 1, 1, 12.00),
                                                                                                      (6, '2024-12-13 11:56:01', 'Pendente', 1, 1, 6.00),
                                                                                                      (7, '2024-12-13 11:56:06', 'Pendente', 1, 1, 6.00),
                                                                                                      (8, '2024-12-26 09:41:45', 'Pendente', 1, 1, 12.00),
                                                                                                      (9, '2024-12-26 09:42:20', 'Pendente', 1, 1, 102.00),
                                                                                                      (10, '2024-12-26 09:43:51', 'Pendente', 1, 1, 102.00),
                                                                                                      (11, '2024-12-26 09:53:56', 'Pendente', 1, 1, 10.00),
                                                                                                      (12, '2024-12-26 11:16:11', 'Pendente', 1, 1, 12.00),
                                                                                                      (13, '2024-12-26 11:39:30', 'Pendente', 1, 1, 10.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_historico`
--

CREATE TABLE `pedidos_historico` (
                                     `id` int(11) NOT NULL,
                                     `data_pedido` datetime NOT NULL,
                                     `data_conclusao` datetime NOT NULL,
                                     `status` varchar(50) NOT NULL,
                                     `idcliente` int(11) NOT NULL,
                                     `idacomodacao` int(11) NOT NULL,
                                     `valor_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos_historico`
--

INSERT INTO `pedidos_historico` (`id`, `data_pedido`, `data_conclusao`, `status`, `idcliente`, `idacomodacao`, `valor_total`) VALUES
                                                                                                                                  (1, '2024-12-13 11:25:46', '2024-12-13 11:35:58', 'Concluído', 1, 1, 18.00),
                                                                                                                                  (2, '2024-12-13 11:31:32', '2024-12-26 08:18:42', 'Concluído', 1, 1, 18.00),
                                                                                                                                  (3, '2024-12-13 11:38:16', '2024-12-13 11:38:36', 'Concluído', 1, 1, 18.00),
                                                                                                                                  (4, '2024-12-13 11:53:22', '2024-12-26 08:18:48', 'Concluído', 1, 1, 6.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `quartos`
--

CREATE TABLE `quartos` (
                           `id` int(11) NOT NULL,
                           `nome` varchar(100) NOT NULL,
                           `tipo` varchar(50) NOT NULL,
                           `capacidade` int(11) NOT NULL,
                           `descricao` text DEFAULT NULL,
                           `status` enum('Disponível','Ocupado','Reservado') NOT NULL,
                           `preco_diaria` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `reserva`
--

CREATE TABLE `reserva` (
                           `idreserva` int(11) NOT NULL,
                           `idacomodacao` int(11) DEFAULT NULL,
                           `idcliente` int(11) DEFAULT NULL,
                           `quantidadehospedes` int(11) DEFAULT NULL,
                           `entradaprevista` date DEFAULT NULL,
                           `saidaprevista` date DEFAULT NULL,
                           `datacheckin` date DEFAULT NULL,
                           `horacheckin` time DEFAULT NULL,
                           `datacheckout` date DEFAULT NULL,
                           `horacheckout` time DEFAULT NULL,
                           `datag` date DEFAULT NULL,
                           `horag` time DEFAULT NULL,
                           `obs` varchar(255) DEFAULT NULL,
                           `status` enum('p','c','i','f') DEFAULT NULL,
                           `valordiaria` decimal(11,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `reserva`
--

INSERT INTO `reserva` (`idreserva`, `idacomodacao`, `idcliente`, `quantidadehospedes`, `entradaprevista`, `saidaprevista`, `datacheckin`, `horacheckin`, `datacheckout`, `horacheckout`, `datag`, `horag`, `obs`, `status`, `valordiaria`) VALUES
    (7, 2, 1, 2, '2025-01-07', '2025-01-08', NULL, NULL, NULL, NULL, '2025-01-07', '19:17:28', '', 'p', 400.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `solicitacao_manutencao`
--

CREATE TABLE `solicitacao_manutencao` (
                                          `id` int(11) NOT NULL,
                                          `cliente_id` int(11) NOT NULL,
                                          `tipo_problema` varchar(255) NOT NULL,
                                          `descricao` text NOT NULL,
                                          `data_solicitacao` datetime DEFAULT current_timestamp(),
                                          `status` varchar(50) DEFAULT 'Pendente',
                                          `id_quarto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `solicitacao_manutencao`
--

INSERT INTO `solicitacao_manutencao` (`id`, `cliente_id`, `tipo_problema`, `descricao`, `data_solicitacao`, `status`, `id_quarto`) VALUES
                                                                                                                                       (1, 123, 'Elétrico', 'luz não liga', '2024-12-16 11:12:47', 'Pendente', 123),
                                                                                                                                       (2, 123, 'Mobília', 'teste', '2024-12-16 11:12:52', 'Pendente', 123);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tipoacomodacao`
--

CREATE TABLE `tipoacomodacao` (
                                  `idtipoac` int(11) NOT NULL,
                                  `nome` varchar(255) DEFAULT NULL,
                                  `ativo` enum('s','n') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `tipoacomodacao`
--

INSERT INTO `tipoacomodacao` (`idtipoac`, `nome`, `ativo`) VALUES
                                                               (1, 'Térreo', 's'),
                                                               (2, '1° Andar', 's');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acomodacao`
--
ALTER TABLE `acomodacao`
    ADD PRIMARY KEY (`idacomodacao`),
  ADD KEY `idtipoacomodacao` (`idtipoacomodacao`);

--
-- Índices de tabela `adicionalconsumo`
--
ALTER TABLE `adicionalconsumo`
    ADD PRIMARY KEY (`idadicional`),
  ADD KEY `idconsumo` (`idconsumo`);

--
-- Índices de tabela `categoriaestoque`
--
ALTER TABLE `categoriaestoque`
    ADD PRIMARY KEY (`idcategoria`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
    ADD PRIMARY KEY (`idcliente`);

--
-- Índices de tabela `consumo`
--
ALTER TABLE `consumo`
    ADD PRIMARY KEY (`idconsumo`),
  ADD KEY `idreserva` (`idreserva`);

--
-- Índices de tabela `enderecoestabelecimento`
--
ALTER TABLE `enderecoestabelecimento`
    ADD PRIMARY KEY (`idenderecoestabelecimento`);

--
-- Índices de tabela `estabelecimento`
--
ALTER TABLE `estabelecimento`
    ADD PRIMARY KEY (`idestabelecimento`);

--
-- Índices de tabela `estacionamento`
--
ALTER TABLE `estacionamento`
    ADD PRIMARY KEY (`idvaga`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
    ADD PRIMARY KEY (`iditem`);

--
-- Índices de tabela `formapagamento`
--
ALTER TABLE `formapagamento`
    ADD PRIMARY KEY (`idformapagamento`);

--
-- Índices de tabela `frigobar`
--
ALTER TABLE `frigobar`
    ADD PRIMARY KEY (`idfrigobar`),
  ADD KEY `idacomodacao` (`idacomodacao`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
    ADD PRIMARY KEY (`idlogin`);

--
-- Índices de tabela `itemfrigobar`
--
ALTER TABLE `itemfrigobar`
    ADD PRIMARY KEY (`iditemfrigobar`),
  ADD KEY `idfrigobar` (`idfrigobar`),
  ADD KEY `iditem` (`iditem`);

--
-- Índices de tabela `itensconsumidos`
--
ALTER TABLE `itensconsumidos`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idpedido` (`idpedido`),
  ADD KEY `iditem` (`iditem`);

--
-- Índices de tabela `log`
--
ALTER TABLE `log`
    ADD PRIMARY KEY (`idlog`);

--
-- Índices de tabela `logacesso`
--
ALTER TABLE `logacesso`
    ADD PRIMARY KEY (`idlogacesso`);

--
-- Índices de tabela `logo`
--
ALTER TABLE `logo`
    ADD PRIMARY KEY (`idlogo`);

--
-- Índices de tabela `movestoque`
--
ALTER TABLE `movestoque`
    ADD PRIMARY KEY (`idmov`),
  ADD KEY `iditem` (`iditem`);

--
-- Índices de tabela `pagamento`
--
ALTER TABLE `pagamento`
    ADD PRIMARY KEY (`idpagamento`),
  ADD KEY `idconsumo` (`idconsumo`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
    ADD PRIMARY KEY (`id`),
  ADD KEY `fk_cliente` (`idcliente`),
  ADD KEY `fk_acomodacao` (`idacomodacao`);

--
-- Índices de tabela `pedidos_historico`
--
ALTER TABLE `pedidos_historico`
    ADD PRIMARY KEY (`id`),
  ADD KEY `idcliente` (`idcliente`),
  ADD KEY `idacomodacao` (`idacomodacao`);

--
-- Índices de tabela `quartos`
--
ALTER TABLE `quartos`
    ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `reserva`
--
ALTER TABLE `reserva`
    ADD PRIMARY KEY (`idreserva`),
  ADD KEY `idacomodacao` (`idacomodacao`),
  ADD KEY `idcliente` (`idcliente`);

--
-- Índices de tabela `solicitacao_manutencao`
--
ALTER TABLE `solicitacao_manutencao`
    ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `tipoacomodacao`
--
ALTER TABLE `tipoacomodacao`
    ADD PRIMARY KEY (`idtipoac`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `acomodacao`
--
ALTER TABLE `acomodacao`
    MODIFY `idacomodacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `adicionalconsumo`
--
ALTER TABLE `adicionalconsumo`
    MODIFY `idadicional` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `categoriaestoque`
--
ALTER TABLE `categoriaestoque`
    MODIFY `idcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
    MODIFY `idcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `consumo`
--
ALTER TABLE `consumo`
    MODIFY `idconsumo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `enderecoestabelecimento`
--
ALTER TABLE `enderecoestabelecimento`
    MODIFY `idenderecoestabelecimento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estabelecimento`
--
ALTER TABLE `estabelecimento`
    MODIFY `idestabelecimento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `estacionamento`
--
ALTER TABLE `estacionamento`
    MODIFY `idvaga` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
    MODIFY `iditem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `formapagamento`
--
ALTER TABLE `formapagamento`
    MODIFY `idformapagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `frigobar`
--
ALTER TABLE `frigobar`
    MODIFY `idfrigobar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
    MODIFY `idlogin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `itemfrigobar`
--
ALTER TABLE `itemfrigobar`
    MODIFY `iditemfrigobar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itensconsumidos`
--
ALTER TABLE `itensconsumidos`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `log`
--
ALTER TABLE `log`
    MODIFY `idlog` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de tabela `logacesso`
--
ALTER TABLE `logacesso`
    MODIFY `idlogacesso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de tabela `logo`
--
ALTER TABLE `logo`
    MODIFY `idlogo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `movestoque`
--
ALTER TABLE `movestoque`
    MODIFY `idmov` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `pagamento`
--
ALTER TABLE `pagamento`
    MODIFY `idpagamento` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `pedidos_historico`
--
ALTER TABLE `pedidos_historico`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `quartos`
--
ALTER TABLE `quartos`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reserva`
--
ALTER TABLE `reserva`
    MODIFY `idreserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `solicitacao_manutencao`
--
ALTER TABLE `solicitacao_manutencao`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tipoacomodacao`
--
ALTER TABLE `tipoacomodacao`
    MODIFY `idtipoac` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `acomodacao`
--
ALTER TABLE `acomodacao`
    ADD CONSTRAINT `acomodacao_ibfk_1` FOREIGN KEY (`idtipoacomodacao`) REFERENCES `tipoacomodacao` (`idtipoac`);

--
-- Restrições para tabelas `consumo`
--
ALTER TABLE `consumo`
    ADD CONSTRAINT `consumo_ibfk_1` FOREIGN KEY (`idreserva`) REFERENCES `reserva` (`idreserva`);

--
-- Restrições para tabelas `frigobar`
--
ALTER TABLE `frigobar`
    ADD CONSTRAINT `frigobar_ibfk_1` FOREIGN KEY (`idacomodacao`) REFERENCES `acomodacao` (`idacomodacao`);

--
-- Restrições para tabelas `itemfrigobar`
--
ALTER TABLE `itemfrigobar`
    ADD CONSTRAINT `itemfrigobar_ibfk_1` FOREIGN KEY (`idfrigobar`) REFERENCES `frigobar` (`idfrigobar`),
  ADD CONSTRAINT `itemfrigobar_ibfk_2` FOREIGN KEY (`iditem`) REFERENCES `estoque` (`iditem`);

--
-- Restrições para tabelas `itensconsumidos`
--
ALTER TABLE `itensconsumidos`
    ADD CONSTRAINT `itensconsumidos_ibfk_1` FOREIGN KEY (`idpedido`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `itensconsumidos_ibfk_2` FOREIGN KEY (`iditem`) REFERENCES `estoque` (`iditem`);

--
-- Restrições para tabelas `movestoque`
--
ALTER TABLE `movestoque`
    ADD CONSTRAINT `movestoque_ibfk_1` FOREIGN KEY (`iditem`) REFERENCES `estoque` (`iditem`);

--
-- Restrições para tabelas `pagamento`
--
ALTER TABLE `pagamento`
    ADD CONSTRAINT `pagamento_ibfk_1` FOREIGN KEY (`idconsumo`) REFERENCES `consumo` (`idconsumo`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
    ADD CONSTRAINT `fk_acomodacao` FOREIGN KEY (`idacomodacao`) REFERENCES `acomodacao` (`idacomodacao`),
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`idacomodacao`) REFERENCES `acomodacao` (`idacomodacao`);

--
-- Restrições para tabelas `pedidos_historico`
--
ALTER TABLE `pedidos_historico`
    ADD CONSTRAINT `pedidos_historico_ibfk_1` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`),
  ADD CONSTRAINT `pedidos_historico_ibfk_2` FOREIGN KEY (`idacomodacao`) REFERENCES `acomodacao` (`idacomodacao`);

--
-- Restrições para tabelas `reserva`
--
ALTER TABLE `reserva`
    ADD CONSTRAINT `reserva_ibfk_1` FOREIGN KEY (`idacomodacao`) REFERENCES `acomodacao` (`idacomodacao`),
  ADD CONSTRAINT `reserva_ibfk_2` FOREIGN KEY (`idcliente`) REFERENCES `cliente` (`idcliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
