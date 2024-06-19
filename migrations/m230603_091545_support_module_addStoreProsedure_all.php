<?php
/**
 * m230603_091545_support_module_addStoreProsedure_all
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 09:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\db\Schema;

class m230603_091545_support_module_addStoreProsedure_all extends \yii\db\Migration
{
	public function up()
	{
		// alter sp supportSetFeedbackUser
		$alterProsedureSupportSetFeedbackUser = <<< SQL
CREATE PROCEDURE `supportSetFeedbackUser`(IN `feedback_id_sp` int, IN `user_id_sp` int, IN `creation_date_sp` DATETIME)
BEGIN
	DECLARE id_sp INT;
	
	IF (user_id_sp IS NOT NULL) THEN
		SELECT `id` INTO `id_sp` 
		FROM `ommu_support_feedback_user` 
		WHERE `publish`='1' AND `feedback_id`=`feedback_id_sp` AND `user_id`=`user_id_sp`;
		
		IF (id_sp IS NULL) THEN
			INSERT `ommu_support_feedback_user` (`feedback_id`, `user_id`, `creation_date`)
			VALUE (feedback_id_sp, user_id_sp, creation_date_sp);
		END IF;
	END IF;
END;
SQL;
        $this->execute('DROP PROCEDURE IF EXISTS `supportSetFeedbackUser`');
		$this->execute($alterProsedureSupportSetFeedbackUser);

		// alter sp supportSetFeedbackView
		$alterProsedureSupportSetFeedbackView = <<< SQL
CREATE PROCEDURE `supportSetFeedbackView`(IN `feedback_id_sp` INT, IN `user_id_sp` INT, IN `view_date_sp` DATETIME)
BEGIN
	DECLARE view_id_sp INT;
	
	SELECT `view_id` INTO view_id_sp
	FROM `ommu_support_feedback_view`
	WHERE `publish`='1' AND `feedback_id`=`feedback_id_sp` AND `user_id`=`user_id_sp` LIMIT 1;
	
	IF (view_id_sp IS NULL) THEN
		INSERT `ommu_support_feedback_view` (`feedback_id`, `user_id`, `view_date`)
		VALUE (feedback_id_sp, user_id_sp, view_date_sp);
	END IF;
END;
SQL;
        $this->execute('DROP PROCEDURE IF EXISTS `supportSetFeedbackView`');
		$this->execute($alterProsedureSupportSetFeedbackView);
	}

	public function down()
	{
        $this->execute('DROP PROCEDURE IF EXISTS `supportSetFeedbackUser`');
        $this->execute('DROP PROCEDURE IF EXISTS `supportSetFeedbackView`');
	}
}
