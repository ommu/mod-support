<?php
/**
 * m210911_171825_support_module_create_table_feedback_view
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 11 September 2021, 17:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use Yii;
use yii\db\Schema;

class m210911_171825_support_module_create_table_feedback_view extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedback_view';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'view_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'1\'',
				'feedback_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'user_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'views' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'1\'',
				'view_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'view_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[view_id]])',
				'CONSTRAINT ommu_support_feedback_view_ibfk_1 FOREIGN KEY ([[feedback_id]]) REFERENCES ommu_support_feedbacks ([[feedback_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);

			$this->createIndex(
				'user_id',
				$tableName,
				['user_id']
			);

			$this->createIndex(
				'publish_feedbackId_userId',
				$tableName,
				['publish', 'feedback_id', 'user_id']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedback_view';
		$this->dropTable($tableName);
	}
}
