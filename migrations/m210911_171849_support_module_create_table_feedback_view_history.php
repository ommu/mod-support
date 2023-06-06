<?php
/**
 * m210911_171849_support_module_create_table_feedback_view_history
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 11 September 2021, 17:19 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\db\Schema;

class m210911_171849_support_module_create_table_feedback_view_history extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedback_view_history';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'trigger\'',
				'view_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED NOT NULL',
				'view_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
				'view_ip' => Schema::TYPE_STRING . '(20) NOT NULL',
				'PRIMARY KEY ([[id]])',
				'CONSTRAINT ommu_support_feedback_view_history_ibfk_1 FOREIGN KEY ([[view_id]]) REFERENCES ommu_support_feedback_view ([[view_id]]) ON DELETE CASCADE ON UPDATE CASCADE',
			], $tableOptions);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedback_view_history';
		$this->dropTable($tableName);
	}
}
