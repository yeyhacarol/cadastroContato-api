show databases;

use dbcontatos;

show tables;

desc tblContatos;

create table tblEstados(
	idEstado int not null auto_increment primary key,
    siga varchar(2) not null,
    nome varchar(30) not null
);

select * from tblContatos;

delete from tblContatos;

alter table tblcontatos
	add column idEstado int not null,
	add constraint FK_Estados_Contatos
    foreign key (idEstado)
	references tblEstados (idEstado);
    
insert into tblEstados (siga, nome)
			values ('SP', 'São Paulo'),
				   ('RJ', 'Rio de Janeiro');
                   
select * from tblEstados;

alter table tblEstados change column siga sigla varchar(2);

insert into tblEstados (sigla, nome)
			values ('SC', 'Santa Catarina'),
				   ('PR', 'Paraná'),
                   ('MG', 'Minas Gerais');
                   
select * from tblContatos;

select * from tblContatos where idEstado = 5;