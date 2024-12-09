
create schema innjoy_1_0_0;
use innjoy_1_0_0;

CREATE TABLE itemfrigobar (
iditemfrigobar int auto_increment PRIMARY KEY,
idfrigobar int,
iditem int,
quantidade int,
ativo enum('s','n')
);

CREATE TABLE acomodacao (
idacomodacao int auto_increment PRIMARY KEY,
idtipoacomodacao int,
nome varchar(255),
numero int,
valor DECIMAL(11,2),
capacidade int,
descricao varchar(255),
ativo enum('s','n'),
datag date,
horag time,
cor varchar(255)
);

CREATE TABLE frigobar (
idfrigobar int auto_increment PRIMARY KEY,
idacomodacao int,
modelo varchar(255),
patrimonio varchar(255),
ativo enum('s','n'),
FOREIGN KEY(idacomodacao) REFERENCES acomodacao (idacomodacao)
);

CREATE TABLE estacionamento (
idvaga int auto_increment PRIMARY KEY,
idacomodacao int,
numero int,
ativo enum('s','n')
);

CREATE TABLE log (
idlog int auto_increment PRIMARY KEY,
iduser varchar(255),
acao varchar(255),
obs varchar(255),
tabela varchar(255),
idtabela varchar(255),
json varchar(8000),
datag date,
horag time
);

CREATE TABLE logacesso (
idlogacesso int auto_increment PRIMARY KEY,
idusuario int,
ultimologin datetime,
ip varchar(255)
);

CREATE TABLE tipoacomodacao (
idtipoac int auto_increment PRIMARY KEY,
nome varchar(255),
ativo enum('s','n')
);

CREATE TABLE estoque (
iditem int auto_increment PRIMARY KEY,
item varchar(255),
categoria varchar(255),
quantidade int,
valorunitario DECIMAL(11,2),
ativo enum('s','n')
);

CREATE TABLE categoriaestoque (
idcategoria int auto_increment PRIMARY KEY,
nome varchar(255),
ativo enum('s','n')
);

CREATE TABLE reserva (
idreserva int auto_increment PRIMARY KEY,
idacomodacao int,
idcliente int,
quantidadehospedes int,
entradaprevista date,
saidaprevista date,
datacheckin date,
horacheckin time,
datacheckout date,
horacheckout time,
datag date,
horag time,
obs varchar(255),
status enum('p','c', 'i', 'f'),
valordiaria DECIMAL(11,2),
FOREIGN KEY(idacomodacao) REFERENCES acomodacao (idacomodacao)
);

CREATE TABLE consumo (
idconsumo int auto_increment PRIMARY KEY,
idreserva int,
valorestadia DECIMAL(11,2),
valoritens DECIMAL(11,2),
valoradicional DECIMAL(11,2),
status enum('pendente','concluido'),
datafechamento date,
horafechamento time,
valorfinal DECIMAL(11,2),
totaldesconto DECIMAL(11,2),
formapagamento varchar(255),
comprovantepagamento varchar(255),
pago DECIMAL(11,2),
FOREIGN KEY(idreserva) REFERENCES reserva (idreserva)
);

CREATE TABLE itensconsumidos (
iditemconsumido int auto_increment PRIMARY KEY,
idconsumo int,
iditem int,
nome varchar(255),
quantidade int,
valortotal DECIMAL(11,2),
datag date,
horag time,
FOREIGN KEY(idconsumo) REFERENCES consumo (idconsumo)
);

CREATE TABLE movestoque (
idmov int auto_increment PRIMARY KEY,
iditem int,
tipo enum('entrada','saida'),
quantidade int,
usuario varchar(255),
motivo enum('compra', 'venda',  'perda'),
datag date,
horag time,
FOREIGN KEY(iditem) REFERENCES estoque (iditem)
);

CREATE TABLE funcionario (
idlogin int auto_increment PRIMARY KEY,
nome varchar(255),
login varchar(255),
senha varchar(255),
dtnascimento date,
cpf varchar(255),
nivel int,
ativo enum('s','n')
);

CREATE TABLE cliente (
idcliente int auto_increment PRIMARY KEY,
nome varchar(255),
cpf varchar(255),
dtnasc date,
email varchar(255),
telefone varchar(255),
estado varchar(255),
cidade varchar(255),
datag date,
ativo enum('s','n')
);

CREATE TABLE adicionalconsumo (
idadicional int auto_increment PRIMARY KEY,
idconsumo int,
motivo varchar(255),
valor DECIMAL(11,2),
FOREIGN KEY(idconsumo) REFERENCES consumo (idconsumo)
);

CREATE TABLE formapagamento (
idformapagamento int auto_increment PRIMARY KEY,
nome varchar(255),
ativo enum('s','n')
);

CREATE TABLE estabelecimento (
idestabelecimento int auto_increment PRIMARY KEY,
cnpj varchar(255),
razaosocial varchar(255),
website varchar(255),
email varchar(255),
telefone varchar(255),
celular varchar(255)
);

CREATE TABLE enderecoestabelecimento (
idenderecoestabelecimento int auto_increment PRIMARY KEY,
logradouro varchar(255),
numero varchar(255),
complemento varchar(255),
cidade varchar(255),
bairro varchar(255),
cep varchar(255)
);

CREATE TABLE logo (
idlogo int auto_increment PRIMARY KEY,
logoserver varchar(255),
logonome varchar(255)
);

CREATE TABLE pagamento (
idpagamento int auto_increment PRIMARY KEY,
idconsumo int,
valor DECIMAL(11,2),
comprovante varchar(255),
observacao text,
formapagamento varchar(255),
datag date,
horag time,
FOREIGN KEY(idconsumo) REFERENCES consumo (idconsumo)
);


ALTER TABLE itemfrigobar ADD FOREIGN KEY(idfrigobar) REFERENCES frigobar (idfrigobar);
ALTER TABLE itemfrigobar ADD FOREIGN KEY(iditem) REFERENCES estoque (iditem);
ALTER TABLE acomodacao ADD FOREIGN KEY(idtipoacomodacao) REFERENCES tipoacomodacao (idtipoac);
ALTER TABLE reserva ADD FOREIGN KEY(idcliente) REFERENCES cliente (idcliente);

insert into funcionario values (null, 'Administrador', 'admin', '$2y$10$/67uF2RsL.HfvIobnoecQ.6je/pI9LI/wLvzvp0cv7hq1RwHhlag.', '', '000.000.000-1','1', 's');
insert into logo values (null, 'logo.png', 'logo.png');
