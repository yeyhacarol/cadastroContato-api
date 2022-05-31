show databases;

use dbcontatos;

show tables;

select * from tblcontatos;

desc tblcontatos;

insert into tblcontatos (nome, telefone, celular, email, obs, foto, idEstado)
			values ('carol', '11 961809076', '11 961809076', 'carol@gmail.com', 'blabla', 'foto.png', 1);
            
insert into tblcontatos (nome, telefone, celular, email, obs, idEstado)
			values ('lucas', '11 956785432', '11 956785432', 'lucas@gmail.com', 'blabla', 1);
            
insert into tblcontatos (nome, telefone, celular, email, obs, idEstado)
			values ('roberta', '11 956785432', '11 956785432', 'roberta@gmail.com', 'blabla', 1);
            
DESC 
