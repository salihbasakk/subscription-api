<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501123314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE app (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, username VARCHAR(100) NOT NULL, password VARCHAR(100) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, osId INT NOT NULL, INDEX IDX_C96E70CF7FD2BF4D (osId), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE device (id INT AUTO_INCREMENT NOT NULL, uid VARCHAR(150) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, languageId INT NOT NULL, osId INT NOT NULL, INDEX IDX_92FB68E940D8C7E (languageId), INDEX IDX_92FB68E7FD2BF4D (osId), UNIQUE INDEX uniqueUidIdx (uid), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, UNIQUE INDEX uniqueCodeIdx (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE operating_system (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, UNIQUE INDEX uniqueNameIdx (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id INT AUTO_INCREMENT NOT NULL, status TINYINT(1) NOT NULL, purchaseStatus VARCHAR(100) NOT NULL, receipt VARCHAR(200) NOT NULL, expireDate DATETIME DEFAULT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, subscriptionId INT NOT NULL, INDEX IDX_6117D13BCA77D3A9 (subscriptionId), INDEX purchaseStatusIdx (purchaseStatus), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, clientToken VARCHAR(150) NOT NULL, status VARCHAR(50) NOT NULL, expireDate DATETIME NOT NULL, createdAt DATETIME NOT NULL, updatedAt DATETIME DEFAULT NULL, deletedAt DATETIME DEFAULT NULL, deviceId INT NOT NULL, appId INT NOT NULL, INDEX IDX_A3C664D3ADBFE9A1 (deviceId), INDEX IDX_A3C664D336DA3021 (appId), INDEX activeDeviceAppsIdx (deviceId, appId, status), UNIQUE INDEX uniqueDeviceAppIdx (deviceId, appId), UNIQUE INDEX uniqueClientTokenIdx (clientToken), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE app ADD CONSTRAINT FK_C96E70CF7FD2BF4D FOREIGN KEY (osId) REFERENCES operating_system (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E940D8C7E FOREIGN KEY (languageId) REFERENCES language (id)');
        $this->addSql('ALTER TABLE device ADD CONSTRAINT FK_92FB68E7FD2BF4D FOREIGN KEY (osId) REFERENCES operating_system (id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BCA77D3A9 FOREIGN KEY (subscriptionId) REFERENCES subscription (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3ADBFE9A1 FOREIGN KEY (deviceId) REFERENCES device (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D336DA3021 FOREIGN KEY (appId) REFERENCES app (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE app DROP FOREIGN KEY FK_C96E70CF7FD2BF4D');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E940D8C7E');
        $this->addSql('ALTER TABLE device DROP FOREIGN KEY FK_92FB68E7FD2BF4D');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BCA77D3A9');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3ADBFE9A1');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D336DA3021');
        $this->addSql('DROP TABLE app');
        $this->addSql('DROP TABLE device');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE operating_system');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE subscription');
    }
}
