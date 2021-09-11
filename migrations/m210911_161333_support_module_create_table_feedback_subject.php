<?php
/**
 * m210911_161333_support_module_create_table_feedback_subject
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 11 September 2021, 17:16 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use Yii;
use yii\db\Schema;

class m210911_161333_support_module_create_table_feedback_subject extends \yii\db\Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
		}
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedback_subject';
		if (!Yii::$app->db->getTableSchema($tableName, true)) {
			$this->createTable($tableName, [
				'subject_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL AUTO_INCREMENT',
				'publish' => Schema::TYPE_TINYINT . '(1) NOT NULL DEFAULT \'0\'',
				'parent_id' => Schema::TYPE_SMALLINT . '(5) UNSIGNED NOT NULL DEFAULT \'0\'',
				'subject_name' => Schema::TYPE_INTEGER . '(11) NOT NULL COMMENT \'trigger[delete]\'',
				'creation_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT \'trigger\'',
				'creation_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'modified_date' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'trigger,on_update\'',
				'modified_id' => Schema::TYPE_INTEGER . '(11) UNSIGNED',
				'updated_date' => Schema::TYPE_DATETIME . ' NOT NULL DEFAULT \'0000-00-00 00:00:00\' COMMENT \'trigger\'',
				'PRIMARY KEY ([[subject_id]])',
			], $tableOptions);

			$this->createIndex(
				'publish_parentId_subjectName',
				$tableName,
				['publish', 'parent_id', 'subject_name']
			);

			$this->createIndex(
				'parent_id',
				$tableName,
				['parent_id']
			);

			$this->createIndex(
				'subject_name',
				$tableName,
				['subject_name']
			);
		}
	}

	public function down()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_support_feedback_subject';
		$this->dropTable($tableName);
	}
}
