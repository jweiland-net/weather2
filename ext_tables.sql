#
# Table structure for table 'tx_weather2_domain_model_currentweather'
#
CREATE TABLE tx_weather2_domain_model_currentweather (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	measure_timestamp int(11) DEFAULT '0' NOT NULL,
	temperature_c int(11) DEFAULT '0' NOT NULL,
	pressure_hpa int(11) DEFAULT '0' NOT NULL,
	humidity_percentage int(11) unsigned DEFAULT '0' NOT NULL,
	min_temp_c int(11) DEFAULT '0' NOT NULL,
	max_temp_c int(11) DEFAULT '0' NOT NULL,
	wind_speed_m_p_s int(11) DEFAULT '0' NOT NULL,
	wind_direction_deg int(11) DEFAULT '0' NOT NULL,
	pop_percentage int(11) unsigned DEFAULT '0' NOT NULL,
	rain_volume int(11) DEFAULT '0' NOT NULL,
	snow_volume int(11) DEFAULT '0' NOT NULL,
	clouds_percentage int(11) unsigned DEFAULT '0' NOT NULL,
	serialized_array varchar(255) DEFAULT '' NOT NULL,
	icon varchar(30) DEFAULT '' NOT NULL,

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
