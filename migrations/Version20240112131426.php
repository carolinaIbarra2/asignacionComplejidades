<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240112131426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE asignacion (id INT AUTO_INCREMENT NOT NULL, soportes_id INT DEFAULT NULL, trabajadores_id INT DEFAULT NULL, fecha_asignacion DATE NOT NULL, UNIQUE INDEX UNIQ_256292718FDFCE99 (soportes_id), INDEX IDX_256292718594A7C0 (trabajadores_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cliente (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, apellido VARCHAR(50) NOT NULL, correo VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soporte (id INT AUTO_INCREMENT NOT NULL, clientes_id INT DEFAULT NULL, descripcion VARCHAR(255) NOT NULL, complejidad SMALLINT NOT NULL, fecha DATE NOT NULL, INDEX IDX_2273AC6FBC3AF09 (clientes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trabajador (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE asignacion ADD CONSTRAINT FK_256292718FDFCE99 FOREIGN KEY (soportes_id) REFERENCES soporte (id)');
        $this->addSql('ALTER TABLE asignacion ADD CONSTRAINT FK_256292718594A7C0 FOREIGN KEY (trabajadores_id) REFERENCES trabajador (id)');
        $this->addSql('ALTER TABLE soporte ADD CONSTRAINT FK_2273AC6FBC3AF09 FOREIGN KEY (clientes_id) REFERENCES cliente (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asignacion DROP FOREIGN KEY FK_256292718FDFCE99');
        $this->addSql('ALTER TABLE asignacion DROP FOREIGN KEY FK_256292718594A7C0');
        $this->addSql('ALTER TABLE soporte DROP FOREIGN KEY FK_2273AC6FBC3AF09');
        $this->addSql('DROP TABLE asignacion');
        $this->addSql('DROP TABLE cliente');
        $this->addSql('DROP TABLE soporte');
        $this->addSql('DROP TABLE trabajador');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
