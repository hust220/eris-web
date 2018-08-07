--drop tables jobs2;
--drop tables pdbfiles2;
create table jobs3(
id int NOT NULL auto_increment, 
created_by int NOT NULL,
email varchar(40) NOT NULL,
pdbid varchar(12) NOT NULL, 
mutation varchar(200) NOT NULL, 
ddg float NOT NULL, 
flex boolean NOT NULL,
relax boolean NOT NULL,
pdbfileid int NOT NULL,
tsubmit datetime NOT NULL,
tprocess datetime NOT NULL,
tfinish datetime NOT NULL,
ip varchar(20) NOT NULL,
status int default '0', 
message text NOT NULL,
tmp text NOT NULL,
PRIMARY KEY (id),
-- delete flag
flag int NOT NULL default 0,
-- email flag, 0:no email, 1:need email, 2:email sent
emailFlag int NOT NULL default 0
);

create table pdbfiles3(
id int NOT NULL auto_increment,
pdbid varchar(12) NOT NULL,
hash varchar(255) NOT NULL default '',
structure mediumblob NOT NULL,
E0 float NOT NULL,
stdE0 float NOT NULL,
E1 float NOT NULL,
stdE1 float NOT NULL,
rstructure mediumblob NOT NULL,
rE0 float NOT NULL,
rstdE0 float NOT NULL,
rE1 float NOT NULL,
rstdE1 float NOT NULL,
save text NOT NULL,
tmp text NOT NULL,
PRIMARY KEY (id)
);


