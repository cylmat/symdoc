<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211216142553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'create sport entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sport (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL)');
        $this->addSql('DROP INDEX UNIQ_5F37A13BA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__token AS SELECT id, user_id, created_at, value FROM token');
        $this->addSql('DROP TABLE token');
        $this->addSql('CREATE TABLE token (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , value VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_5F37A13BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO token (id, user_id, created_at, value) SELECT id, user_id, created_at, value FROM __temp__token');
        $this->addSql('DROP TABLE __temp__token');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13BA76ED395 ON token (user_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, phone FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, token_id INTEGER NOT NULL, username VARCHAR(255) NOT NULL COLLATE BINARY, email VARCHAR(255) DEFAULT NULL COLLATE BINARY, phone VARCHAR(255) DEFAULT NULL COLLATE BINARY, age INTEGER DEFAULT NULL, CONSTRAINT FK_8D93D64941DEE7B9 FOREIGN KEY (token_id) REFERENCES token (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user (id, username, email, phone) SELECT id, username, email, phone FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64941DEE7B9 ON user (token_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sport');
        $this->addSql('DROP INDEX UNIQ_5F37A13BA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__token AS SELECT id, user_id, created_at, value FROM token');
        $this->addSql('DROP TABLE token');
        $this->addSql('CREATE TABLE token (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , value VARCHAR(255) NOT NULL, user_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO token (id, user_id, created_at, value) SELECT id, user_id, created_at, value FROM __temp__token');
        $this->addSql('DROP TABLE __temp__token');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5F37A13BA76ED395 ON token (user_id)');
        $this->addSql('DROP INDEX UNIQ_8D93D64941DEE7B9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, username, email, phone FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, username, email, phone) SELECT id, username, email, phone FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
    }
}
