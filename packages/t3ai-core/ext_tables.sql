#
# Table structure for table 'tx_t3aicore_domain_model_resource'
#
CREATE TABLE tx_t3aicore_domain_model_resource (
	title varchar(255) NOT NULL DEFAULT '',
    description text,
    resource varchar(255) NOT NULL DEFAULT '',
    configuration text
);

#
# Table structure for table 'sys_file_metadata'
#
CREATE TABLE sys_file_metadata (
    alternative_ai_generated smallint unsigned DEFAULT 0 NOT NULL,
    description_ai_generated smallint unsigned DEFAULT 0 NOT NULL,
);