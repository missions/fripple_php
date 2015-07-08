CREATE TABLE IF NOT EXISTS user (
id                 INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
first_name         VARCHAR(50) NOT NULL,
last_name          VARCHAR(50) NOT NULL,
username           VARCHAR(32) NOT NULL COMMENT 'REQUIRED, username is shown instead of real name',
password           VARCHAR(255) COMMENT 'OPTIONAL, password is not supplied when signing in using fb',
facebook_id        VARCHAR(255) COMMENT 'facebook id supplied by client when signing in using fb',
email_address      VARCHAR(255) NOT NULL,
location           VARCHAR(255) COMMENT 'WIP, need to clarify first with client side',
food_type_id_list  TEXT COMMENT 'comma separated IDs of the type of food that the user likes',
rebate_points      DECIMAL(10,2) NOT NULL DEFAULT '0' COMMENT 'total rebate points of user',
created            datetime NOT NULL,
updated            TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
UNIQUE INDEX (email_address),
INDEX name (first_name, last_name),
INDEX (facebook_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS account_session (
id              VARCHAR(12) NOT NULL COMMENT 'Format (1st 4 char of device id + Month + Year + Day, ex: 10QR06201506)',
user_id         INT(10) UNSIGNED NOT NULL,
device_id       VARCHAR(255) NOT NULL COMMENT 'device id supplied by client when logging in',
date_start      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
date_end        datetime NOT NULL COMMENT 'end of session is after 1 yr of date_start',
PRIMARY KEY (id, device_id),
UNIQUE INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;