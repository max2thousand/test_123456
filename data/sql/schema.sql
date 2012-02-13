CREATE TABLE banner_configuration (id BIGINT AUTO_INCREMENT, page_routing VARCHAR(255), banner_text LONGTEXT, is_default TINYINT(1), position VARCHAR(255), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE game (id BIGINT AUTO_INCREMENT, game_configuration_id BIGINT, title VARCHAR(255), scheduled_time VARCHAR(255), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX game_configuration_id_idx (game_configuration_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE game_configuration (id BIGINT AUTO_INCREMENT, title VARCHAR(255), class_to_use VARCHAR(255), configurations_steps LONGTEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_profile (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, first_name VARCHAR(30), last_name VARCHAR(30), facebook_uid VARCHAR(20), email VARCHAR(255), INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE superenalotto_bet (id BIGINT AUTO_INCREMENT, numbers_played VARCHAR(255), superstar BIGINT, contest_id VARCHAR(255), game_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by BIGINT, updated_by BIGINT, INDEX game_id_idx (game_id), INDEX created_by_idx (created_by), INDEX updated_by_idx (updated_by), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE superenalotto_win (id BIGINT AUTO_INCREMENT, numbers_played VARCHAR(255), contest_id VARCHAR(255), superstar BIGINT, game_id BIGINT, user_id BIGINT, INDEX user_id_idx (user_id), INDEX game_id_idx (game_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE user_statistic (id BIGINT AUTO_INCREMENT, num_bets BIGINT, num_accesses BIGINT, num_wins BIGINT, user_id BIGINT, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE net7_email_template_translation (id BIGINT, email_subject VARCHAR(255), email_body LONGTEXT, email_from VARCHAR(255), lang CHAR(2), PRIMARY KEY(id, lang)) ENGINE = INNODB;
CREATE TABLE net7_email_template (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_forgot_password (id BIGINT AUTO_INCREMENT, user_id BIGINT NOT NULL, unique_key VARCHAR(255), expires_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_group_permission (group_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(group_id, permission_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_permission (id BIGINT AUTO_INCREMENT, name VARCHAR(255) UNIQUE, description TEXT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_remember_key (id BIGINT AUTO_INCREMENT, user_id BIGINT, remember_key VARCHAR(32), ip_address VARCHAR(50), created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX user_id_idx (user_id), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user (id BIGINT AUTO_INCREMENT, first_name VARCHAR(255), last_name VARCHAR(255), email_address VARCHAR(255) NOT NULL UNIQUE, username VARCHAR(128) NOT NULL UNIQUE, algorithm VARCHAR(128) DEFAULT 'sha1' NOT NULL, salt VARCHAR(128), password VARCHAR(128), is_active TINYINT(1) DEFAULT '1', is_super_admin TINYINT(1) DEFAULT '0', last_login DATETIME, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX is_active_idx_idx (is_active), PRIMARY KEY(id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_group (user_id BIGINT, group_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, group_id)) ENGINE = INNODB;
CREATE TABLE sf_guard_user_permission (user_id BIGINT, permission_id BIGINT, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(user_id, permission_id)) ENGINE = INNODB;
ALTER TABLE game ADD CONSTRAINT game_game_configuration_id_game_configuration_id FOREIGN KEY (game_configuration_id) REFERENCES game_configuration(id);
ALTER TABLE sf_guard_user_profile ADD CONSTRAINT sf_guard_user_profile_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE superenalotto_bet ADD CONSTRAINT superenalotto_bet_updated_by_sf_guard_user_id FOREIGN KEY (updated_by) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE superenalotto_bet ADD CONSTRAINT superenalotto_bet_game_id_game_id FOREIGN KEY (game_id) REFERENCES game(id);
ALTER TABLE superenalotto_bet ADD CONSTRAINT superenalotto_bet_created_by_sf_guard_user_id FOREIGN KEY (created_by) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE superenalotto_win ADD CONSTRAINT superenalotto_win_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id);
ALTER TABLE superenalotto_win ADD CONSTRAINT superenalotto_win_game_id_game_id FOREIGN KEY (game_id) REFERENCES game(id);
ALTER TABLE user_statistic ADD CONSTRAINT user_statistic_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id);
ALTER TABLE net7_email_template_translation ADD CONSTRAINT net7_email_template_translation_id_net7_email_template_id FOREIGN KEY (id) REFERENCES net7_email_template(id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE sf_guard_forgot_password ADD CONSTRAINT sf_guard_forgot_password_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_group_permission ADD CONSTRAINT sf_guard_group_permission_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_remember_key ADD CONSTRAINT sf_guard_remember_key_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_group ADD CONSTRAINT sf_guard_user_group_group_id_sf_guard_group_id FOREIGN KEY (group_id) REFERENCES sf_guard_group(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_user_id_sf_guard_user_id FOREIGN KEY (user_id) REFERENCES sf_guard_user(id) ON DELETE CASCADE;
ALTER TABLE sf_guard_user_permission ADD CONSTRAINT sf_guard_user_permission_permission_id_sf_guard_permission_id FOREIGN KEY (permission_id) REFERENCES sf_guard_permission(id) ON DELETE CASCADE;
