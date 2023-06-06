<?php
/**
 * m210910_233614_support_module_update_role_feedback_view
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2021 OMMU (www.ommu.id)
 * @created date 10 September 2021, 23:37 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\base\InvalidConfigException;
use yii\rbac\DbManager;
use app\models\Menu;
use mdm\admin\components\Configs;

class m210910_233614_support_module_update_role_feedback_view extends \yii\db\Migration
{
    /**
     * @throws yii\base\InvalidConfigException
     * @return DbManager
     */
    protected function getAuthManager()
    {
        $authManager = Yii::$app->getAuthManager();
        if (!$authManager instanceof DbManager) {
            throw new InvalidConfigException('You should configure "authManager" component to use database before executing this migration.');
        }

        return $authManager;
    }

	public function up()
	{
        $authManager = $this->getAuthManager();
        $this->db = $authManager->db;
        $schema = $this->db->getSchema()->defaultSchema;

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemChildTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->delete($tableName, ['parent' => 'supportModLevelModerator', 'child' => '/support/feedback/view-detail/*']);
        }

		$tableName = Yii::$app->db->tablePrefix . $authManager->itemTable;
        if (Yii::$app->db->getTableSchema($tableName, true)) {
            $this->delete($tableName, ['name' => '/support/feedback/view-detail/*']);
        }
	}
}
