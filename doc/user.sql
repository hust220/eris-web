
CREATE TABLE `users3` (
  `id` int(11) NOT NULL auto_increment,
  `created_on` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `username` varchar(60) NOT NULL default '',
  `password` varchar(60) NOT NULL default '',
  `namefirst` varchar(60) NOT NULL default '',
  `namelast` varchar(60) NOT NULL default '',
  `level` tinyint(4) NOT NULL default '1',
  `email` varchar(100) NOT NULL default '',
  `organization` varchar(100) NOT NULL default '',
  `numtasks` int(10) NOT NULL default '10',
  `cpuHours` int(11) NOT NULL default '100',
  `emailConfirmed` tinyint(1) unsigned NOT NULL default '0',
  `approved` tinyint(1) unsigned NOT NULL default '0',
  `tlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  `tlastlogin` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username` (`username`)
) ;
-- ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;
