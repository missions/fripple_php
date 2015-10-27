CREATE TABLE IF NOT EXISTS user (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
first_name      VARCHAR(50) NOT NULL,
last_name       VARCHAR(50) NOT NULL,
username        VARCHAR(32) NOT NULL COMMENT 'REQUIRED, username is shown instead of real name',
password        VARCHAR(255) COMMENT 'OPTIONAL, password is not supplied when signing in using fb',
role            TINYINT(5) NOT NULL DEFAULT '0' COMMENT 'hardcoded value depending on user role',
facebook_id     VARCHAR(255) COMMENT 'facebook id supplied by client when signing in using fb',
email_address   VARCHAR(255) NOT NULL,
location        VARCHAR(255) COMMENT 'WIP, need to clarify first with client side',
rebate_points   DECIMAL(10,2) NOT NULL DEFAULT '0' COMMENT 'total rebate points of user, always 0 for non-regular users',
is_active       TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'turns 1 if account is activated',
created         datetime NOT NULL,
updated         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
UNIQUE INDEX (email_address),
UNIQUE INDEX (username),
INDEX name (username, first_name, last_name),
INDEX account_info (username, password),
INDEX active_users (is_active, created)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS account_session (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
user_id         INT(10) UNSIGNED NOT NULL,
device_id       VARCHAR(255) NOT NULL COMMENT 'device id supplied by client when logging in',
date_start      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
date_end        datetime NOT NULL COMMENT 'end of session is after 1 yr of date_start',
PRIMARY KEY (user_id, device_id),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS restaurant (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
name            VARCHAR(30) NOT NULL,
description     TEXT COMMENT 'Brief description or introduction of the restaurant and what they serve',
contact_number  VARCHAR(50) COMMENT 'For documentation and transaction client purposes',
contact_person  VARCHAR(50) COMMENT 'Person in charge or owner',
longhitude      DECIMAL(12,8) NOT NULL DEFAULT '0' COMMENT 'Used to pinpoint the restaurant longhitude in G-Maps',
latitude        DECIMAL(12,8) NOT NULL DEFAULT '0' COMMENT 'Used to pinpoint the restaurant latitude in G-Maps',
created         datetime NOT NULL,
updated         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX location (longhitude, latitude),
INDEX contact (contact_person, contact_number),
INDEX (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS restaurant_tags (
restaurant_id   INT(10) UNSIGNED NOT NULL,
tag             VARCHAR(30) NOT NULL COMMENT 'tags used for searching restaurants',
PRIMARY KEY (restaurant_id, tag)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS menu (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
restaurant_id   INT(10) UNSIGNED NOT NULL,
food_name       VARCHAR(30) NOT NULL,
price           DECIMAL(10,2) UNSIGNED NOT NULL,
description     TEXT COMMENT 'Brief explanation of how food is made, ingredients, etc.',
created         datetime NOT NULL,
updated         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX price_from_resto (restaurant_id, price),
INDEX price_for_food (food_name, price),
INDEX (restaurant_id),
INDEX (price)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS menu_feedback (
menu_id         INT(10) UNSIGNED NOT NULL,
user_id         INT(10) UNSIGNED NOT NULL,
is_liked        TINYINT(1) NOT NULL COMMENT 'Value will be 1 for like, 0 for dislike'
PRIMARY KEY (menu_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS menu_comment (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,    
menu_id         INT(10) UNSIGNED NOT NULL,
user_id         INT(10) UNSIGNED NOT NULL,
comment         TEXT NOT NULL,
created         datetime NOT NULL,
updated         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX (menu_id),
INDEX user_comments (menu_id, user_id),
INDEX date_sort (menu_id, updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS follower (
restaurant_id   INT(10) UNSIGNED NOT NULL,
user_id         INT(10) UNSIGNED NOT NULL,
PRIMARY KEY (restaurant_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS advertisement (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
restaurant_id   INT(10) UNSIGNED NOT NULL,
main_image_url  TEXT NOT NULL,
thumb_image_url TEXT NOT NULL,
date_start      datetime NOT NULL,
date_end        datetime NOT NULL,
created         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX duration_per_resto (restaurant_id, date_start, date_end),
INDEX duration (date_start, date_end),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS advertisement_feedback (
advertisement_id    INT(10) UNSIGNED NOT NULL,
user_id             INT(10) UNSIGNED NOT NULL,
is_liked            TINYINT(1) NOT NULL COMMENT 'Value will be 1 for like, 0 for dislike'
PRIMARY KEY (advertisement_id, user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS advertisement_comment (
id                  INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,    
advertisement_id    INT(10) UNSIGNED NOT NULL,
user_id             INT(10) UNSIGNED NOT NULL,
comment             TEXT NOT NULL,
created             datetime NOT NULL,
updated             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX (advertisement_id),
INDEX user_comments (advertisement_id, user_id),
INDEX date_sort (advertisement_id, updated)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;