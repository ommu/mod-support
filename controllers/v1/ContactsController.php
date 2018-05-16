<?php
namespace app\modules\support\controllers\v1;

use Yii;
use app\components\api\ActiveController;
use app\modules\support\models\SupportContactCategory;
use app\models\CoreMeta;

/**
 * ContactsController
 * version: 0.0.1
 *
 * @copyright Copyright(c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link    http://ecc.ft.ugm.ac.id
 * @author  Eko Hariyanto <haryeko29@gmail.com>
 * @created 4 April 2018, 10:47 WIB
 * @contact (+62)857-4381-4273
 *
 */
class ContactsController extends ActiveController
{
	public $modelClass = 'app\modules\Support\Models\SupportContactCategory';
	public $searchModelClass = 'app\modules\support\models\search\SupportContactCategory';
	public static $authType = 0;
	
	//hak akses api
	 protected function verbs() {
	   return [
		   'getcontacts' => ['GET'],
		   'Create' => ['POST']
	   ];
	}
	public function actionGetcontacts() {
		//merubah data ke array
		$contact_category = [];
		$meta = CoreMeta::find()->one();
		
		$connection = Yii::$app->get('ecc4');
		$command = $connection->createCommand("
				SELECT a.id,b.`cat_icon`,a.`contact_name` FROM `ommu_support_contacts` AS a LEFT JOIN`ommu_support_contact_category`AS b
				ON a.`cat_id`= b.`cat_id` WHERE a.`publish`=1 AND b.`publish`=1 AND a.`contact_name` NOT LIKE''");
		$result = $command->queryAll();

		
		$contact_category['contact_data']=$result;
		$contact_category['meta']=$meta;
		if($meta['office_country_id'] == ''){
		$contact_category['country']= '';
		}
		else {
			$contact_category['country']=$meta->country->country_name;
		}
		if($meta['office_province_id'] == ''){
			$contact_category['province']= '' ;
		}
		else{
		$contact_category['province']=$meta->province->province_name;
		}
		if($meta['office_city_id'] == ''){
			$contact_category['city']= '' ;
		}
		else{
		$contact_category['city']=$meta->city->city_name;
		}
		return $contact_category;
	}
	
}