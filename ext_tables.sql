#
# Table structure for table 'tx_extensionporter_domain_model_portingprocess'
#
CREATE TABLE tx_extensionporter_domain_model_portingprocess (


	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	old_extension int(11) NOT NULL,
	new_extension int(11) NOT NULL,
	progresslogs int(11) NOT NULL,
	step int(11) unsigned DEFAULT '0',
	percent int(11) unsigned DEFAULT '0',

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);

#
# Table structure for table 'tx_extensionporter_domain_model_progresslog'
#
CREATE TABLE tx_extensionporter_domain_model_progresslog (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	porting_process int(11) NOT NULL,
	title varchar(255) DEFAULT '',
	type int(11) DEFAULT '0' NOT NULL,
	description text,

	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	cruser_id int(11) unsigned DEFAULT '0' NOT NULL,
	deleted tinyint(4) unsigned DEFAULT '0' NOT NULL,
	hidden tinyint(4) unsigned DEFAULT '0' NOT NULL,
	starttime int(11) unsigned DEFAULT '0' NOT NULL,
	endtime int(11) unsigned DEFAULT '0' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),

);
#
# Table structure for table 'tx_extensionporter_domain_model_extension'
#

CREATE TABLE tx_extensionporter_domain_model_extension (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	# type old
	copied_ext_folder varchar(255) DEFAULT '' NOT NULL,

	# type new

	# all types
	title varchar(255) DEFAULT '' NOT NULL,
	ext_folder varchar(255) DEFAULT '' NOT NULL,
	ext_key varchar(255) DEFAULT '' NOT NULL,
	description text,
	category varchar(255) DEFAULT '' NOT NULL,
	state varchar(255) DEFAULT '' NOT NULL,
	additional_emconf longtext,

	has_localization tinyint(4) unsigned DEFAULT '0' NOT NULL,
	has_database_definitions tinyint(4) unsigned DEFAULT '0' NOT NULL,
	has_plugins tinyint(4) unsigned DEFAULT '0' NOT NULL,
	has_modules tinyint(4) unsigned DEFAULT '0' NOT NULL,

	porting_process int(11) NOT NULL,

	crdate int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	deleted int(11) DEFAULT '0' NOT NULL,
	type varchar(255) DEFAULT '' NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);
