<?php
/**
 * m190319_120101_support_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use Yii;
use app\models\Menu;

class m190319_120101_support_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
		$tableName = Yii::$app->db->tablePrefix . 'ommu_core_menus';
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert('ommu_core_menus', ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Feedbacks', 'support', null, Menu::getParentId('Dashboard#rbac'), '/support/feedback/admin/index', null, null],
			]);
		}
	}
}
