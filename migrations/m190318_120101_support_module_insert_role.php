<?php
/**
 * m190318_120101_support_module_insert_role
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use Yii;

class m190318_120101_support_module_insert_role extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item', ['name', 'type', 'data', 'created_at'], [
				['supportModLevelAdmin', '2', '', time()],
				['supportModLevelModerator', '2', '', time()],
				['/support/feedback/admin/*', '2', '', time()],
				['/support/feedback/admin/index', '2', '', time()],
				['/support/feedback/subject/*', '2', '', time()],
				['/support/feedback/subject/index', '2', '', time()],
				['/support/feedback/user/*', '2', '', time()],
				['/support/feedback/view/*', '2', '', time()],
				['/support/feedback/view-detail/*', '2', '', time()],
			]);
		}

		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_auth_item_child';
		if(Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_auth_item_child', ['parent', 'child'], [
				['userAdmin', 'supportModLevelAdmin'],
				['userModerator', 'supportModLevelModerator'],
				['supportModLevelAdmin', 'supportModLevelModerator'],
				['supportModLevelModerator', '/support/feedback/admin/*'],
				['supportModLevelModerator', '/support/feedback/subject/*'],
				['supportModLevelModerator', '/support/feedback/user/*'],
				['supportModLevelModerator', '/support/feedback/view/*'],
				['supportModLevelModerator', '/support/feedback/view-detail/*'],
			]);
		}
	}
}
