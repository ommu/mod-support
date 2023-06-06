<?php
/**
 * m210911_171719_support_module_create_table_feedbacks
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 11 September 2021, 17:17 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\db\Schema;

class m210911_171719_support_module_create_table_feedbacks extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedbacks';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'feedback_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'app' => Schema::TYPE_STRING . '(32) NOT NULL',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'subject_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'trigger[insert]\'',
				'email' => Schema::TYPE_STRING . '(64) NOT NULL',
				'displayname' => Schema::TYPE_STRING . '(32) NOT NULL',
				'phone' => Schema::TYPE_STRING . '(15) NOT NULL',
				'message' => Schema::TYPE_TEXT . ' NOT NULL',
				'reply_message' => Schema::TYPE_TEXT . ' NOT NULL',
				'replied_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'replied_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED COMMENT \'user\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[feedback_id]])',
				'CONSTRAINT ommu_support_feedbacks_ibfk_1 FOREIGN KEY ([[user_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_support_feedbacks_ibfk_2 FOREIGN KEY ([[subject_id]]) REFERENCES ommu_support_feedback_subject ([[subject_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
				'CONSTRAINT ommu_support_feedbacks_ibfk_3 FOREIGN KEY ([[replied_id]]) REFERENCES ommu_users ([[user_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'app',
				$tableName,
				['app']
			);

			$this->createIndex(
				'publish_subjectId',
				$tableName,
				['publish', 'subject_id']
			);

			$this->createIndex(
				'email',
				$tableName,
				['email']
			);

			$this->createIndex(
				'displayname',
				$tableName,
				['displayname']
			);

			$this->createIndex(
				'phone',
				$tableName,
				['phone']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedbacks';
		$this->dropTable($tableName);
	}
}
