<?php
/**
 * m230603_091556_support_module_addTrigger_all
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 09:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\db\Schema;

class m230603_091556_support_module_addTrigger_all extends \yii\db\Migration
{
	public function up()
	{
		// alter trigger supportBeforeUpdateFeedbackSubject
		$alterTriggerSupportBeforeUpdateFeedbackSubject = <<< SQL
CREATE
    TRIGGER `supportBeforeUpdateFeedbackSubject` BEFORE UPDATE ON `ommu_support_feedback_subject` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbackSubject`');
		$this->execute($alterTriggerSupportBeforeUpdateFeedbackSubject);

		// alter trigger supportAfterDeleteFeedbackSubject
		$alterTriggerSupportAfterDeleteFeedbackSubject = <<< SQL
CREATE
    TRIGGER `supportAfterDeleteFeedbackSubject` AFTER DELETE ON `ommu_support_feedback_subject` 
    FOR EACH ROW BEGIN
	/*
	DELETE FROM `source_message` WHERE `id`=OLD.subject_name;
	*/
	UPDATE `source_message` SET `message`=CONCAT(message,'_DELETED') WHERE `id`=OLD.subject_name;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterDeleteFeedbackSubject`');
		$this->execute($alterTriggerSupportAfterDeleteFeedbackSubject);

		// alter trigger supportBeforeInsertFeedbacks
		$alterTriggerSupportBeforeInsertFeedbacks = <<< SQL
CREATE
    TRIGGER `supportBeforeInsertFeedbacks` BEFORE INSERT ON `ommu_support_feedbacks` 
    FOR EACH ROW BEGIN
	DECLARE user_id_tr INT;
	DECLARE displayname_tr VARCHAR(32);
	
	IF (NEW.user_id IS NULL) THEN
		SELECT `user_id`, `displayname` 
		INTO user_id_tr, displayname_tr 
		FROM `ommu_users` 
		WHERE `email`=NEW.email;
		
		IF (user_id_tr IS NOT NULL) THEN
			IF (NEW.user_id IS NULL) THEN
				SET NEW.user_id = user_id_tr;
			END IF;	
			
			IF (NEW.displayname IS NULL) THEN
				SET NEW.displayname = displayname_tr;
			END IF;	
		END IF;	
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeInsertFeedbacks`');
		$this->execute($alterTriggerSupportBeforeInsertFeedbacks);

		// alter trigger supportAfterInsertFeedbacks
		$alterTriggerSupportAfterInsertFeedbacks = <<< SQL
CREATE
    TRIGGER `supportAfterInsertFeedbacks` AFTER INSERT ON `ommu_support_feedbacks` 
    FOR EACH ROW BEGIN
	CALL supportSetFeedbackUser(NEW.feedback_id, NEW.user_id, NEW.creation_date);
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterInsertFeedbacks`');
		$this->execute($alterTriggerSupportAfterInsertFeedbacks);

		// alter trigger supportBeforeUpdateFeedbacks
		$alterTriggerSupportBeforeUpdateFeedbacks = <<< SQL
CREATE
    TRIGGER `supportBeforeUpdateFeedbacks` BEFORE UPDATE ON `ommu_support_feedbacks` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;	
	
	IF (NEW.reply_message <> OLD.reply_message) THEN
		IF (OLD.reply_message = '' AND NEW.replied_date = '0000-00-00 00:00:00') THEN
			SET NEW.replied_date = NOW();
		END IF;
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbacks`');
		$this->execute($alterTriggerSupportBeforeUpdateFeedbacks);

		// alter trigger supportAfterUpdateFeedbacks
		$alterTriggerSupportAfterUpdateFeedbacks = <<< SQL
CREATE
    TRIGGER `supportAfterUpdateFeedbacks` AFTER UPDATE ON `ommu_support_feedbacks` 
    FOR EACH ROW BEGIN
	DECLARE view_id_tr INT;
	DECLARE replied_date_tr DATETIME;
	
	IF (NEW.user_id <> OLD.user_id) THEN
		CALL supportSetFeedbackUser(NEW.feedback_id, NEW.user_id, NEW.modified_date);
	END IF;
	
	IF (NEW.replied_date <> OLD.replied_date) THEN
		CALL supportSetFeedbackUser(NEW.feedback_id, NEW.replied_id, NEW.replied_date);
	else
		IF (NEW.replied_id <> OLD.replied_id) THEN
			IF (OLD.replied_id IS NULL) THEN
				set replied_date_tr = NEW.replied_date;
			ELSE
				IF (NEW.replied_id IS NOT NULL) THEN
					SET replied_date_tr = NEW.modified_date;
				END IF;
			END IF;
			CALL supportSetFeedbackUser(NEW.feedback_id, NEW.replied_id, replied_date_tr);
		END IF;
	END IF;
	
	/*
	IF (NEW.replied_id IS NOT NULL) THEN
	*/
	IF (NEW.replied_id <> OLD.replied_id) THEN
		CALL supportSetFeedbackView(NEW.feedback_id, NEW.replied_id, NEW.replied_date);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterUpdateFeedbacks`');
		$this->execute($alterTriggerSupportAfterUpdateFeedbacks);

		// alter trigger supportAfterInsertFeedbackView
		$alterTriggerSupportAfterInsertFeedbackView = <<< SQL
CREATE
    TRIGGER `supportAfterInsertFeedbackView` AFTER INSERT ON `ommu_support_feedback_view` 
    FOR EACH ROW BEGIN
	CALL supportSetFeedbackUser(NEW.feedback_id, NEW.user_id, NEW.view_date);
	
	IF (NEW.publish = 1 AND NEW.views <> 0) THEN
		INSERT `ommu_support_feedback_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterInsertFeedbackView`');
		$this->execute($alterTriggerSupportAfterInsertFeedbackView);

		// alter trigger supportBeforeUpdateFeedbackView
		$alterTriggerSupportBeforeUpdateFeedbackView = <<< SQL
CREATE
    TRIGGER `supportBeforeUpdateFeedbackView` BEFORE UPDATE ON `ommu_support_feedback_view` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	ELSE
		IF (NEW.publish = 1 AND NEW.views <> OLD.views AND NEW.views > OLD.views) THEN
			SET NEW.view_date = NOW();
		END IF;
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbackView`');
		$this->execute($alterTriggerSupportBeforeUpdateFeedbackView);

		// alter trigger supportAfterUpdateFeedbackView
		$alterTriggerSupportAfterUpdateFeedbackView = <<< SQL
CREATE
    TRIGGER `supportAfterUpdateFeedbackView` AFTER UPDATE ON `ommu_support_feedback_view` 
    FOR EACH ROW BEGIN
	IF (NEW.user_id <> OLD.user_id) THEN
		CALL supportSetFeedbackUser(NEW.feedback_id, NEW.user_id, NEW.view_date);
	END IF;
	
	IF (NEW.view_date <> OLD.view_date) THEN
		INSERT `ommu_support_feedback_view_history` (`view_id`, `view_date`, `view_ip`)
		VALUE (NEW.view_id, NEW.view_date, NEW.view_ip);
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterUpdateFeedbackView`');
		$this->execute($alterTriggerSupportAfterUpdateFeedbackView);

		// alter trigger supportBeforeUpdateFeedbackUser
		$alterTriggerSupportBeforeUpdateFeedbackUser = <<< SQL
CREATE
    TRIGGER `supportBeforeUpdateFeedbackUser` BEFORE UPDATE ON `ommu_support_feedback_user` 
    FOR EACH ROW BEGIN
	IF (NEW.publish <> OLD.publish) THEN
		SET NEW.updated_date = NOW();
	END IF;
    END;
SQL;
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbackUser`');
		$this->execute($alterTriggerSupportBeforeUpdateFeedbackUser);
	}

	public function down()
	{
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbackSubject`');
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterDeleteFeedbackSubject`');
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeInsertFeedbacks`');
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterInsertFeedbacks`');
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbacks`');
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterUpdateFeedbacks`');
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterInsertFeedbackView`');
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbackView`');
        $this->execute('DROP TRIGGER IF EXISTS `supportAfterUpdateFeedbackView`');
        $this->execute('DROP TRIGGER IF EXISTS `supportBeforeUpdateFeedbackUser`');
	}
}
