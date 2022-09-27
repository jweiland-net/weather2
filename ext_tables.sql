#
# Table structure for table 'tx_weather2_domain_model_currentweather'
#
CREATE TABLE tx_weather2_domain_model_currentweather (
	name varchar(255) DEFAULT '' NOT NULL,
	measure_timestamp int(11) DEFAULT '0' NOT NULL,
	temperature_c double(4,2) DEFAULT '0.0' NOT NULL,
	pressure_hpa int(11) DEFAULT '0' NOT NULL,
	humidity_percentage int(11) unsigned DEFAULT '0' NOT NULL,
	min_temp_c double(4,2) DEFAULT '0.0' NOT NULL,
	max_temp_c double(4,2) DEFAULT '0.0' NOT NULL,
	wind_speed_m_p_s double(4,2) DEFAULT '0.0' NOT NULL,
	wind_direction_deg int(11) DEFAULT '0' NOT NULL,
	pop_percentage int(11) unsigned DEFAULT '0' NOT NULL,
	rain_volume double (4,2) DEFAULT '0.0' NOT NULL,
	snow_volume double(4,2) DEFAULT '0.0' NOT NULL,
	clouds_percentage int(11) unsigned DEFAULT '0' NOT NULL,
	serialized_array varchar(255) DEFAULT '' NOT NULL,
	icon varchar(30) DEFAULT '' NOT NULL,
	condition_code int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_weather2_domain_model_weatheralert'
#
CREATE TABLE tx_weather2_domain_model_weatheralert (
	dwd_warn_cell int(11) NOT NULL,
	level int(11) DEFAULT '0' NOT NULL,
	type int(11) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	description text DEFAULT '' NOT NULL,
	instruction text DEFAULT '' NOT NULL,
	response_timestamp int(11) DEFAULT '0' NOT NULL,
	start_date int(11) unsigned DEFAULT '0' NOT NULL,
	end_date int(11) unsigned DEFAULT '0' NOT NULL,
	comparison_hash varchar(32) DEFAULT '' NOT NULL,
	preliminary_information tinyint(4) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_weather2_domain_model_dwdwarncell'
#
CREATE TABLE tx_weather2_domain_model_dwdwarncell (
	warn_cell_id varchar(30) DEFAULT '' NOT NULL,
	name varchar(60) DEFAULT '' NOT NULL,
	short_name varchar(30) DEFAULT '' NOT NULL,
	sign varchar(10) DEFAULT '' NOT NULL
);
