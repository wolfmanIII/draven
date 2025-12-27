<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251227182339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE approval_decision (id UUID NOT NULL, approval_request_id UUID NOT NULL, user_id UUID NOT NULL, decision VARCHAR(32) NOT NULL, note TEXT DEFAULT NULL, decided_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B871A5223E8BAA4 ON approval_decision (approval_request_id)');
        $this->addSql('CREATE INDEX IDX_1B871A52A76ED395 ON approval_decision (user_id)');
        $this->addSql('COMMENT ON COLUMN approval_decision.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN approval_decision.approval_request_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN approval_decision.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN approval_decision.decided_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE approval_request (id UUID NOT NULL, job_id UUID NOT NULL, required_count INT NOT NULL, status VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AFD730D9BE04EA9 ON approval_request (job_id)');
        $this->addSql('COMMENT ON COLUMN approval_request.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN approval_request.job_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN approval_request.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN approval_request.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE deploy_job (id UUID NOT NULL, project_id UUID NOT NULL, environment_id UUID NOT NULL, integration_id UUID NOT NULL, requested_by_id UUID NOT NULL, type VARCHAR(32) NOT NULL, ref_type VARCHAR(32) NOT NULL, ref_name VARCHAR(255) NOT NULL, commit_sha VARCHAR(64) DEFAULT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status VARCHAR(32) NOT NULL, locked_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, finished_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, external_run_id VARCHAR(255) DEFAULT NULL, external_run_url VARCHAR(1024) DEFAULT NULL, summary VARCHAR(255) DEFAULT NULL, error_message TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_57A3A6B9166D1F9C ON deploy_job (project_id)');
        $this->addSql('CREATE INDEX IDX_57A3A6B9903E3A94 ON deploy_job (environment_id)');
        $this->addSql('CREATE INDEX IDX_57A3A6B99E82DDEA ON deploy_job (integration_id)');
        $this->addSql('CREATE INDEX IDX_57A3A6B94DA1E751 ON deploy_job (requested_by_id)');
        $this->addSql('COMMENT ON COLUMN deploy_job.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.environment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.integration_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.requested_by_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.locked_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN deploy_job.finished_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE deploy_log_line (id BIGSERIAL NOT NULL, job_id UUID NOT NULL, seq INT NOT NULL, stream VARCHAR(32) NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_418058CFBE04EA9 ON deploy_log_line (job_id)');
        $this->addSql('COMMENT ON COLUMN deploy_log_line.job_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN deploy_log_line.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE environment (id UUID NOT NULL, project_id UUID NOT NULL, policy_id UUID NOT NULL, name VARCHAR(32) NOT NULL, is_enabled BOOLEAN NOT NULL, lock_strategy VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_4626DE22166D1F9C ON environment (project_id)');
        $this->addSql('CREATE INDEX IDX_4626DE222D29E3C6 ON environment (policy_id)');
        $this->addSql('COMMENT ON COLUMN environment.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN environment.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN environment.policy_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN environment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN environment.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE environment_lock (environment_id UUID NOT NULL, job_id UUID NOT NULL, locked_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(environment_id))');
        $this->addSql('CREATE INDEX IDX_3DC38B0ABE04EA9 ON environment_lock (job_id)');
        $this->addSql('COMMENT ON COLUMN environment_lock.environment_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN environment_lock.job_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN environment_lock.locked_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN environment_lock.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE policy (id UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, rules_json JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN policy.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN policy.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN policy.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE project (id UUID NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN project.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN project.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN project.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE repo_integration (id UUID NOT NULL, project_id UUID NOT NULL, provider VARCHAR(32) NOT NULL, repo_full_name VARCHAR(255) NOT NULL, default_branch VARCHAR(255) NOT NULL, pipeline_selector JSON DEFAULT NULL, webhook_secret VARCHAR(255) DEFAULT NULL, credential_ref VARCHAR(255) DEFAULT NULL, is_active BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DEA98363166D1F9C ON repo_integration (project_id)');
        $this->addSql('COMMENT ON COLUMN repo_integration.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN repo_integration.project_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN repo_integration.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN repo_integration.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'messenger_messages\', NEW.queue_name::text);
                RETURN NEW;
            END;
        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE approval_decision ADD CONSTRAINT FK_1B871A5223E8BAA4 FOREIGN KEY (approval_request_id) REFERENCES approval_request (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE approval_decision ADD CONSTRAINT FK_1B871A52A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE approval_request ADD CONSTRAINT FK_AFD730D9BE04EA9 FOREIGN KEY (job_id) REFERENCES deploy_job (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deploy_job ADD CONSTRAINT FK_57A3A6B9166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deploy_job ADD CONSTRAINT FK_57A3A6B9903E3A94 FOREIGN KEY (environment_id) REFERENCES environment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deploy_job ADD CONSTRAINT FK_57A3A6B99E82DDEA FOREIGN KEY (integration_id) REFERENCES repo_integration (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deploy_job ADD CONSTRAINT FK_57A3A6B94DA1E751 FOREIGN KEY (requested_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE deploy_log_line ADD CONSTRAINT FK_418058CFBE04EA9 FOREIGN KEY (job_id) REFERENCES deploy_job (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE environment ADD CONSTRAINT FK_4626DE22166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE environment ADD CONSTRAINT FK_4626DE222D29E3C6 FOREIGN KEY (policy_id) REFERENCES policy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE environment_lock ADD CONSTRAINT FK_3DC38B0A903E3A94 FOREIGN KEY (environment_id) REFERENCES environment (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE environment_lock ADD CONSTRAINT FK_3DC38B0ABE04EA9 FOREIGN KEY (job_id) REFERENCES deploy_job (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE repo_integration ADD CONSTRAINT FK_DEA98363166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE approval_decision DROP CONSTRAINT FK_1B871A5223E8BAA4');
        $this->addSql('ALTER TABLE approval_decision DROP CONSTRAINT FK_1B871A52A76ED395');
        $this->addSql('ALTER TABLE approval_request DROP CONSTRAINT FK_AFD730D9BE04EA9');
        $this->addSql('ALTER TABLE deploy_job DROP CONSTRAINT FK_57A3A6B9166D1F9C');
        $this->addSql('ALTER TABLE deploy_job DROP CONSTRAINT FK_57A3A6B9903E3A94');
        $this->addSql('ALTER TABLE deploy_job DROP CONSTRAINT FK_57A3A6B99E82DDEA');
        $this->addSql('ALTER TABLE deploy_job DROP CONSTRAINT FK_57A3A6B94DA1E751');
        $this->addSql('ALTER TABLE deploy_log_line DROP CONSTRAINT FK_418058CFBE04EA9');
        $this->addSql('ALTER TABLE environment DROP CONSTRAINT FK_4626DE22166D1F9C');
        $this->addSql('ALTER TABLE environment DROP CONSTRAINT FK_4626DE222D29E3C6');
        $this->addSql('ALTER TABLE environment_lock DROP CONSTRAINT FK_3DC38B0A903E3A94');
        $this->addSql('ALTER TABLE environment_lock DROP CONSTRAINT FK_3DC38B0ABE04EA9');
        $this->addSql('ALTER TABLE repo_integration DROP CONSTRAINT FK_DEA98363166D1F9C');
        $this->addSql('DROP TABLE approval_decision');
        $this->addSql('DROP TABLE approval_request');
        $this->addSql('DROP TABLE deploy_job');
        $this->addSql('DROP TABLE deploy_log_line');
        $this->addSql('DROP TABLE environment');
        $this->addSql('DROP TABLE environment_lock');
        $this->addSql('DROP TABLE policy');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE repo_integration');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
