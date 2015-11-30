CREATE TABLE IF NOT EXISTS user (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
first_name      VARCHAR(50) NOT NULL,
last_name       VARCHAR(50) NOT NULL,
username        VARCHAR(32) NOT NULL COMMENT 'REQUIRED, username is shown instead of real name',
password        VARCHAR(255) COMMENT 'OPTIONAL, password is not supplied when signing in using fb',
role            TINYINT(5) NOT NULL DEFAULT '0' COMMENT 'hardcoded value depending on user role',
facebook_id     VARCHAR(255) COMMENT 'facebook id supplied by client when signing in using fb',
email_address   VARCHAR(255) NOT NULL COMMENT 'might need to send info about purchases/updates',
rebate_points   DECIMAL(10,2) NOT NULL DEFAULT '0' COMMENT 'total rebate points of user, always 0 for non-regular users',
is_active       TINYINT(1) NOT NULL DEFAULT '0' COMMENT 'turns 1 if account is activated',
created         datetime NOT NULL,
updated         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
UNIQUE INDEX (email_address),
UNIQUE INDEX (username),
INDEX name (username, first_name, last_name),
INDEX facebook_id (facebook_id),
INDEX active_users (is_active, created)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS account_session (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
user_id         INT(10) UNSIGNED NOT NULL,
device_id       VARCHAR(255) NOT NULL COMMENT 'device id supplied by client when logging in',
date_start      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
date_end        datetime NOT NULL COMMENT 'end of session is after 1 yr of date_start',
PRIMARY KEY (user_id, device_id),
UNIQUE INDEX (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS restaurant (
id                INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
name              VARCHAR(30) NOT NULL,
description       TEXT COMMENT 'Brief description or introduction of the restaurant and what they serve',
num_followers     INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'number of followers updated after a user follow/unfollow',
num_menu_likes    INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'number of menu likes updated after a user likes a menu item',
num_menu_dislikes INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'number of menu dislikes updated after a user dislikes a menu item',
contact_number    VARCHAR(50) COMMENT 'For documentation and transaction client purposes',
admin_id          INT(10) UNSIGNED NOT NULL COMMENT 'connected to user table, the id of the restaurant admin',
longitude         DECIMAL(12,8) NOT NULL DEFAULT '0' COMMENT 'Used to pinpoint the restaurant longitude in G-Maps',
latitude          DECIMAL(12,8) NOT NULL DEFAULT '0' COMMENT 'Used to pinpoint the restaurant latitude in G-Maps',
created           datetime NOT NULL,
updated           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (id),
UNIQUE INDEX (name),
UNIQUE INDEX (admin_id),
INDEX location (longitude, latitude)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS restaurant_tag (
restaurant_id   INT(10) UNSIGNED NOT NULL,
tag             VARCHAR(30) NOT NULL COMMENT 'tags used for searching restaurants',
PRIMARY KEY (restaurant_id, tag),
INDEX (restaurant_id)
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
restaurant_id   INT(10) UNSIGNED NOT NULL,
menu_id         INT(10) UNSIGNED NOT NULL,
user_id         INT(10) UNSIGNED NOT NULL,
is_liked        TINYINT(1) NOT NULL COMMENT 'Value will be 1 for like, 0 for dislike',
PRIMARY KEY (menu_id, user_id),
INDEX menu_likes (menu_id, is_liked),
INDEX restaurant_likes (restaurant_id, is_liked)
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
PRIMARY KEY (restaurant_id, user_id),
INDEX (restaurant_id),
INDEX (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS advertisement (
id              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
restaurant_id   INT(10) UNSIGNED NOT NULL,
num_likes       INT(10) UNSIGNED NOT NULL DEFAULT '0',
num_dislikes    INT(10) UNSIGNED NOT NULL DEFAULT '0',
date_start      datetime NOT NULL,
date_end        datetime NOT NULL,
created         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (id),
INDEX duration_per_resto (restaurant_id, date_start, date_end),
INDEX duration (date_start, date_end)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS advertisement_feedback (
advertisement_id    INT(10) UNSIGNED NOT NULL,
user_id             INT(10) UNSIGNED NOT NULL,
is_liked            TINYINT(1) NOT NULL COMMENT 'Value will be 1 for like, 0 for dislike',
PRIMARY KEY (advertisement_id, user_id),
INDEX advertisement_likes (advertisement_id, is_liked)
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