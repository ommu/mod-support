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

	public function init() {
        // Paksa semua request data yg datang menjadi format json, untuk semua action
        // pada controller ini.
        $this->detachBehavior('contentNegotiator');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parent::init();
    }
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
		$model = SupportContactCategory::find()->where(['publish' => 1])->all();
		$meta = CoreMeta::find()->one();
		$i=0;

		foreach ($model as $key ) {
			 $contact_category[$i] = $key; 
			//$contact_category[$i]['icon'] = $key->cat_icon; 
			 $i++;
		}

		//tampilkan data core meta
		$contact_category['meta']=$meta;
		return $contact_category;
	}
	
}