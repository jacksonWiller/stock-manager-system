<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201224015315 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE api_token (id INT AUTO_INCREMENT NOT NULL, token VARCHAR(100) NOT NULL, permission LONGTEXT NOT NULL COMMENT \'(DC2Type:object)\', notes LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, expires DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7BA2F5EB5F37A13B (token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_global_config (name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, extra JSON DEFAULT NULL, PRIMARY KEY(name)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, salt VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', acl LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, status SMALLINT NOT NULL, UNIQUE INDEX UNIQ_88BDF3E9C05FB297 (confirmation_token), INDEX IDX_88BDF3E9217BBB47 (person_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_users_group (user_id INT NOT NULL, group_id INT NOT NULL, INDEX IDX_D352C630A76ED395 (user_id), INDEX IDX_D352C630FE54D947 (group_id), PRIMARY KEY(user_id, group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE app_user_group (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, ceated_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cidade (id INT AUTO_INCREMENT NOT NULL, uf_id INT NOT NULL, nome VARCHAR(255) NOT NULL, INDEX IDX_6A98335C705D2C5F (uf_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, date DATETIME NOT NULL, extra LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', show_date DATETIME NOT NULL, expiration_date DATETIME DEFAULT NULL, fixed TINYINT(1) NOT NULL, status SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification_user (id INT AUTO_INCREMENT NOT NULL, notification_id INT DEFAULT NULL, user_id INT DEFAULT NULL, date_show DATETIME DEFAULT NULL, date_click DATETIME DEFAULT NULL, INDEX IDX_35AF9D73EF1A9D84 (notification_id), INDEX IDX_35AF9D73A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE permission_module (module VARCHAR(50) NOT NULL, component VARCHAR(50) NOT NULL, action INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(module, component, action)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person (id INT AUTO_INCREMENT NOT NULL, registration_origin_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, name VARCHAR(100) NOT NULL, document VARCHAR(100) DEFAULT NULL, classification SMALLINT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, type SMALLINT NOT NULL, treatment VARCHAR(10) DEFAULT NULL, gender SMALLINT DEFAULT NULL, birth_date DATE DEFAULT NULL, status SMALLINT NOT NULL, ip_registration VARCHAR(255) DEFAULT NULL, nationality VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_34DCD176B5FE8DA9 (registration_origin_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_tagperson (person_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_2421C746217BBB47 (person_id), INDEX IDX_2421C746BAD26311 (tag_id), PRIMARY KEY(person_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_address (id INT AUTO_INCREMENT NOT NULL, purpose_id INT DEFAULT NULL, person_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, address VARCHAR(255) NOT NULL, complement VARCHAR(255) DEFAULT NULL, number VARCHAR(255) DEFAULT NULL, district VARCHAR(255) DEFAULT NULL, district_id INT DEFAULT NULL, zip VARCHAR(255) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, city_id INT DEFAULT NULL, state VARCHAR(100) DEFAULT NULL, state_id INT DEFAULT NULL, other_purpose VARCHAR(255) DEFAULT NULL, isDefault TINYINT(1) NOT NULL, latitude VARCHAR(255) DEFAULT NULL, longitude VARCHAR(255) DEFAULT NULL, INDEX IDX_2FD0DC087FC21131 (purpose_id), INDEX IDX_2FD0DC08217BBB47 (person_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_communication_history (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, user_id INT DEFAULT NULL, status SMALLINT NOT NULL, message_id INT DEFAULT NULL, INDEX IDX_3E898A51217BBB47 (person_id), INDEX IDX_3E898A51A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_contact_email (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, type_purpose_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, other_purpose VARCHAR(100) DEFAULT NULL, email VARCHAR(255) NOT NULL, valid TINYINT(1) DEFAULT NULL, isDefault TINYINT(1) NOT NULL, INDEX IDX_C2365FB6217BBB47 (person_id), INDEX IDX_C2365FB662C1C714 (type_purpose_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_contact_extra (id INT AUTO_INCREMENT NOT NULL, type_purpose_id INT DEFAULT NULL, person_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, name VARCHAR(100) NOT NULL, tag VARCHAR(20) DEFAULT NULL, value LONGTEXT NOT NULL, INDEX IDX_689B2EA762C1C714 (type_purpose_id), INDEX IDX_689B2EA7217BBB47 (person_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_contact_phone (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, type_purpose_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, other_purpose VARCHAR(100) DEFAULT NULL, area_code VARCHAR(5) NOT NULL, phone_number VARCHAR(50) NOT NULL, cellphone TINYINT(1) DEFAULT NULL, isDefault TINYINT(1) DEFAULT NULL, im LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_61EBB41F217BBB47 (person_id), INDEX IDX_61EBB41F62C1C714 (type_purpose_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_document (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, person_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, identifier VARCHAR(255) NOT NULL, agent_dispatcher VARCHAR(50) DEFAULT NULL, expedition VARCHAR(50) DEFAULT NULL, INDEX IDX_4C2F2F41C54C8C93 (type_id), INDEX IDX_4C2F2F41217BBB47 (person_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_extrafield (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(100) NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL, required TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_A416954877153098 (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_note (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, note LONGTEXT DEFAULT NULL, label VARCHAR(50) DEFAULT NULL, INDEX IDX_455B7E3F217BBB47 (person_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_note_history (id INT AUTO_INCREMENT NOT NULL, note_id INT DEFAULT NULL, person_id INT DEFAULT NULL, date DATETIME NOT NULL, old_data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_4512549C26ED0855 (note_id), INDEX IDX_4512549C217BBB47 (person_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_owner (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, owner_id INT DEFAULT NULL, date DATETIME NOT NULL, description_assignment LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_6356F9B8217BBB47 (person_id), INDEX IDX_6356F9B87E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_paper (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, super_person_id INT DEFAULT NULL, type_paper_id INT DEFAULT NULL, start DATE NOT NULL, end DATE DEFAULT NULL, INDEX IDX_E22C7FD2217BBB47 (person_id), INDEX IDX_E22C7FD28D189098 (super_person_id), INDEX IDX_E22C7FD22B8AAF9D (type_paper_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_personextrafield (id INT AUTO_INCREMENT NOT NULL, person_id INT DEFAULT NULL, extrafield_id INT DEFAULT NULL, value LONGTEXT DEFAULT NULL, INDEX IDX_47AE6217BBB47 (person_id), INDEX IDX_47AE6E8A4E12F (extrafield_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_registration_origin (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, extra LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_tag (id INT AUTO_INCREMENT NOT NULL, creator_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_80C4709861220EA6 (creator_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_type_address_purpose (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_type_contact_extra_purpose (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_type_document (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_type_email_purpose (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_type_paper (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, description VARCHAR(255) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE person_type_phone (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, code VARCHAR(100) DEFAULT NULL, name VARCHAR(100) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sm_message (id INT AUTO_INCREMENT NOT NULL, date_registry DATETIME NOT NULL, date_send DATETIME DEFAULT NULL, date_receiver DATETIME DEFAULT NULL, date_readed DATETIME DEFAULT NULL, schedule_for DATETIME DEFAULT NULL, sender_name VARCHAR(80) DEFAULT NULL, sender VARCHAR(100) NOT NULL, recipient_name VARCHAR(80) DEFAULT NULL, recipient VARCHAR(100) NOT NULL, ccs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', bccs LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', reply_to VARCHAR(100) DEFAULT NULL, priority SMALLINT DEFAULT NULL, status SMALLINT NOT NULL, subject VARCHAR(255) DEFAULT NULL, message LONGTEXT DEFAULT NULL, message_text LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, config LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', attempts INT NOT NULL, INDEX type (type), INDEX status (status), INDEX scheduleFor (schedule_for), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sm_message_attachment (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, file VARCHAR(255) DEFAULT NULL, file_size VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, size INT DEFAULT NULL, INDEX IDX_47C3B098537A1329 (message_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sm_message_log (id INT AUTO_INCREMENT NOT NULL, message_id INT DEFAULT NULL, date DATETIME NOT NULL, subject VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, extra LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', INDEX IDX_8D08C3C1537A1329 (message_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sm_message_type (id INT AUTO_INCREMENT NOT NULL, classname VARCHAR(100) DEFAULT NULL, config LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8BB1A49B5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template (id INT AUTO_INCREMENT NOT NULL, categoria_id INT DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, codigo VARCHAR(100) DEFAULT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, texto LONGTEXT DEFAULT NULL, interno TINYINT(1) NOT NULL, is_default TINYINT(1) DEFAULT \'0\' NOT NULL, INDEX IDX_97601F833397707A (categoria_id), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_categoria (id INT AUTO_INCREMENT NOT NULL, codigo VARCHAR(100) NOT NULL, nome VARCHAR(255) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, tipo VARCHAR(50) DEFAULT NULL, icon VARCHAR(20) DEFAULT NULL, UNIQUE INDEX UNIQ_8743649B20332D99 (codigo), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unidade_federativa (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variavel (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT NULL, created_at DATETIME DEFAULT NULL, created_by INT DEFAULT NULL, created_by_user VARCHAR(255) DEFAULT NULL, date_modified DATETIME DEFAULT NULL, modified_by INT DEFAULT NULL, modified_by_user VARCHAR(255) DEFAULT NULL, checked_out DATETIME DEFAULT NULL, checked_out_by INT DEFAULT NULL, checked_out_by_user VARCHAR(255) DEFAULT NULL, ordering SMALLINT DEFAULT NULL, deleted TINYINT(1) DEFAULT NULL, name VARCHAR(255) NOT NULL, value LONGTEXT NOT NULL, description VARCHAR(255) DEFAULT NULL, callback LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:object)\', UNIQUE INDEX UNIQ_7F7C8FCC5E237E06 (name), INDEX deleted (deleted), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app_user ADD CONSTRAINT FK_88BDF3E9217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE app_users_group ADD CONSTRAINT FK_D352C630A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE app_users_group ADD CONSTRAINT FK_D352C630FE54D947 FOREIGN KEY (group_id) REFERENCES app_user_group (id)');
        $this->addSql('ALTER TABLE cidade ADD CONSTRAINT FK_6A98335C705D2C5F FOREIGN KEY (uf_id) REFERENCES unidade_federativa (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73EF1A9D84 FOREIGN KEY (notification_id) REFERENCES notification (id)');
        $this->addSql('ALTER TABLE notification_user ADD CONSTRAINT FK_35AF9D73A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176B5FE8DA9 FOREIGN KEY (registration_origin_id) REFERENCES person_registration_origin (id)');
        $this->addSql('ALTER TABLE person_tagperson ADD CONSTRAINT FK_2421C746217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_tagperson ADD CONSTRAINT FK_2421C746BAD26311 FOREIGN KEY (tag_id) REFERENCES person_tag (id)');
        $this->addSql('ALTER TABLE person_address ADD CONSTRAINT FK_2FD0DC087FC21131 FOREIGN KEY (purpose_id) REFERENCES person_type_address_purpose (id)');
        $this->addSql('ALTER TABLE person_address ADD CONSTRAINT FK_2FD0DC08217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_communication_history ADD CONSTRAINT FK_3E898A51217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_communication_history ADD CONSTRAINT FK_3E898A51A76ED395 FOREIGN KEY (user_id) REFERENCES app_user (id)');
        $this->addSql('ALTER TABLE person_contact_email ADD CONSTRAINT FK_C2365FB6217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_contact_email ADD CONSTRAINT FK_C2365FB662C1C714 FOREIGN KEY (type_purpose_id) REFERENCES person_type_email_purpose (id)');
        $this->addSql('ALTER TABLE person_contact_extra ADD CONSTRAINT FK_689B2EA762C1C714 FOREIGN KEY (type_purpose_id) REFERENCES person_type_contact_extra_purpose (id)');
        $this->addSql('ALTER TABLE person_contact_extra ADD CONSTRAINT FK_689B2EA7217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_contact_phone ADD CONSTRAINT FK_61EBB41F217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_contact_phone ADD CONSTRAINT FK_61EBB41F62C1C714 FOREIGN KEY (type_purpose_id) REFERENCES person_type_phone (id)');
        $this->addSql('ALTER TABLE person_document ADD CONSTRAINT FK_4C2F2F41C54C8C93 FOREIGN KEY (type_id) REFERENCES person_type_document (id)');
        $this->addSql('ALTER TABLE person_document ADD CONSTRAINT FK_4C2F2F41217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_note ADD CONSTRAINT FK_455B7E3F217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_note_history ADD CONSTRAINT FK_4512549C26ED0855 FOREIGN KEY (note_id) REFERENCES person_note (id)');
        $this->addSql('ALTER TABLE person_note_history ADD CONSTRAINT FK_4512549C217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_owner ADD CONSTRAINT FK_6356F9B8217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_owner ADD CONSTRAINT FK_6356F9B87E3C61F9 FOREIGN KEY (owner_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_paper ADD CONSTRAINT FK_E22C7FD2217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_paper ADD CONSTRAINT FK_E22C7FD28D189098 FOREIGN KEY (super_person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_paper ADD CONSTRAINT FK_E22C7FD22B8AAF9D FOREIGN KEY (type_paper_id) REFERENCES person_type_paper (id)');
        $this->addSql('ALTER TABLE person_personextrafield ADD CONSTRAINT FK_47AE6217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE person_personextrafield ADD CONSTRAINT FK_47AE6E8A4E12F FOREIGN KEY (extrafield_id) REFERENCES person_extrafield (id)');
        $this->addSql('ALTER TABLE person_tag ADD CONSTRAINT FK_80C4709861220EA6 FOREIGN KEY (creator_id) REFERENCES person (id)');
        $this->addSql('ALTER TABLE sm_message_attachment ADD CONSTRAINT FK_47C3B098537A1329 FOREIGN KEY (message_id) REFERENCES sm_message (id)');
        $this->addSql('ALTER TABLE sm_message_log ADD CONSTRAINT FK_8D08C3C1537A1329 FOREIGN KEY (message_id) REFERENCES sm_message (id)');
        $this->addSql('ALTER TABLE template ADD CONSTRAINT FK_97601F833397707A FOREIGN KEY (categoria_id) REFERENCES template_categoria (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE app_users_group DROP FOREIGN KEY FK_D352C630A76ED395');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73A76ED395');
        $this->addSql('ALTER TABLE person_communication_history DROP FOREIGN KEY FK_3E898A51A76ED395');
        $this->addSql('ALTER TABLE app_users_group DROP FOREIGN KEY FK_D352C630FE54D947');
        $this->addSql('ALTER TABLE notification_user DROP FOREIGN KEY FK_35AF9D73EF1A9D84');
        $this->addSql('ALTER TABLE app_user DROP FOREIGN KEY FK_88BDF3E9217BBB47');
        $this->addSql('ALTER TABLE person_tagperson DROP FOREIGN KEY FK_2421C746217BBB47');
        $this->addSql('ALTER TABLE person_address DROP FOREIGN KEY FK_2FD0DC08217BBB47');
        $this->addSql('ALTER TABLE person_communication_history DROP FOREIGN KEY FK_3E898A51217BBB47');
        $this->addSql('ALTER TABLE person_contact_email DROP FOREIGN KEY FK_C2365FB6217BBB47');
        $this->addSql('ALTER TABLE person_contact_extra DROP FOREIGN KEY FK_689B2EA7217BBB47');
        $this->addSql('ALTER TABLE person_contact_phone DROP FOREIGN KEY FK_61EBB41F217BBB47');
        $this->addSql('ALTER TABLE person_document DROP FOREIGN KEY FK_4C2F2F41217BBB47');
        $this->addSql('ALTER TABLE person_note DROP FOREIGN KEY FK_455B7E3F217BBB47');
        $this->addSql('ALTER TABLE person_note_history DROP FOREIGN KEY FK_4512549C217BBB47');
        $this->addSql('ALTER TABLE person_owner DROP FOREIGN KEY FK_6356F9B8217BBB47');
        $this->addSql('ALTER TABLE person_owner DROP FOREIGN KEY FK_6356F9B87E3C61F9');
        $this->addSql('ALTER TABLE person_paper DROP FOREIGN KEY FK_E22C7FD2217BBB47');
        $this->addSql('ALTER TABLE person_paper DROP FOREIGN KEY FK_E22C7FD28D189098');
        $this->addSql('ALTER TABLE person_personextrafield DROP FOREIGN KEY FK_47AE6217BBB47');
        $this->addSql('ALTER TABLE person_tag DROP FOREIGN KEY FK_80C4709861220EA6');
        $this->addSql('ALTER TABLE person_personextrafield DROP FOREIGN KEY FK_47AE6E8A4E12F');
        $this->addSql('ALTER TABLE person_note_history DROP FOREIGN KEY FK_4512549C26ED0855');
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176B5FE8DA9');
        $this->addSql('ALTER TABLE person_tagperson DROP FOREIGN KEY FK_2421C746BAD26311');
        $this->addSql('ALTER TABLE person_address DROP FOREIGN KEY FK_2FD0DC087FC21131');
        $this->addSql('ALTER TABLE person_contact_extra DROP FOREIGN KEY FK_689B2EA762C1C714');
        $this->addSql('ALTER TABLE person_document DROP FOREIGN KEY FK_4C2F2F41C54C8C93');
        $this->addSql('ALTER TABLE person_contact_email DROP FOREIGN KEY FK_C2365FB662C1C714');
        $this->addSql('ALTER TABLE person_paper DROP FOREIGN KEY FK_E22C7FD22B8AAF9D');
        $this->addSql('ALTER TABLE person_contact_phone DROP FOREIGN KEY FK_61EBB41F62C1C714');
        $this->addSql('ALTER TABLE sm_message_attachment DROP FOREIGN KEY FK_47C3B098537A1329');
        $this->addSql('ALTER TABLE sm_message_log DROP FOREIGN KEY FK_8D08C3C1537A1329');
        $this->addSql('ALTER TABLE template DROP FOREIGN KEY FK_97601F833397707A');
        $this->addSql('ALTER TABLE cidade DROP FOREIGN KEY FK_6A98335C705D2C5F');
        $this->addSql('DROP TABLE api_token');
        $this->addSql('DROP TABLE app_global_config');
        $this->addSql('DROP TABLE app_user');
        $this->addSql('DROP TABLE app_users_group');
        $this->addSql('DROP TABLE app_user_group');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE cidade');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE notification_user');
        $this->addSql('DROP TABLE permission_module');
        $this->addSql('DROP TABLE person');
        $this->addSql('DROP TABLE person_tagperson');
        $this->addSql('DROP TABLE person_address');
        $this->addSql('DROP TABLE person_communication_history');
        $this->addSql('DROP TABLE person_contact_email');
        $this->addSql('DROP TABLE person_contact_extra');
        $this->addSql('DROP TABLE person_contact_phone');
        $this->addSql('DROP TABLE person_document');
        $this->addSql('DROP TABLE person_extrafield');
        $this->addSql('DROP TABLE person_note');
        $this->addSql('DROP TABLE person_note_history');
        $this->addSql('DROP TABLE person_owner');
        $this->addSql('DROP TABLE person_paper');
        $this->addSql('DROP TABLE person_personextrafield');
        $this->addSql('DROP TABLE person_registration_origin');
        $this->addSql('DROP TABLE person_tag');
        $this->addSql('DROP TABLE person_type_address_purpose');
        $this->addSql('DROP TABLE person_type_contact_extra_purpose');
        $this->addSql('DROP TABLE person_type_document');
        $this->addSql('DROP TABLE person_type_email_purpose');
        $this->addSql('DROP TABLE person_type_paper');
        $this->addSql('DROP TABLE person_type_phone');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE sm_message');
        $this->addSql('DROP TABLE sm_message_attachment');
        $this->addSql('DROP TABLE sm_message_log');
        $this->addSql('DROP TABLE sm_message_type');
        $this->addSql('DROP TABLE template');
        $this->addSql('DROP TABLE template_categoria');
        $this->addSql('DROP TABLE unidade_federativa');
        $this->addSql('DROP TABLE variavel');
        $this->addSql('DROP TABLE messenger_messages');
    }
}