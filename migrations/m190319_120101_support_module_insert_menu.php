<?php
/**
 * m190319_120101_support_module_insert_menu
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 18 March 2019, 19:04 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use app\models\Menu;
use mdm\admin\components\Configs;

class m190319_120101_support_module_insert_menu extends \yii\db\Migration
{
	public function up()
	{
        $menuTable = Configs::instance()->menuTable;
		$tableName = Yii::$app->db->tablePrefix . $menuTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
			$this->batchInsert($tableName, ['name', 'module', 'icon', 'parent', 'route', 'order', 'data'], [
				['Feedbacks', 'support', null, Menu::getParentId('Dashboard#rbac'), '/support/feedback/admin/index', null, null],
			]);
		}
	}
}
