<?php
namespace app\modules\support\controllers\v1;

use Yii;
use app\components\api\ActiveController;
use app\modules\support\models\SupportFeedbackSubject;


/**
 * FeedbacksSubjectController
 *
 * @copyright Copyright(c) 2018 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author  Eko Hariyanto <haryeko29@gmail.com>
 * @created 9 April 2018, 14:43 WIB
 * @contact (+62)857-4381-4273
 *
 */
class FeedbackSubjectController extends ActiveController
{
	public $modelClass = 'app\modules\support\models\SupportFeedbackSubject';
	public $searchModelClass = 'app\modules\support\models\search\SupportFeedbackSubject';
	public static $authType = 0;

/*	public function init() {
				// Paksa semua request data yg datang menjadi format json, untuk semua action
				// pada controller ini.
				$this->detachBehavior('contentNegotiator');
				Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
				parent::init();
		}*/
		 protected function verbs() {
			 return [
					 'getsubject' => ['GET'],
					 'Create' => ['POST']
			 ];
		}
		public function actionGetsubject() {
			//merubah data ke array
		$subject_category = [];
		$model = SupportFeedbackSubject::find()->where(['publish' => 1])->all();
		//$meta = CoreMeta::find()->one();
		$i=0;

		foreach ($model as $key ) {
			 //$contact_category[$i] = $key; 
			$subject_category[$i]['id'] = $key->subject_id; 
			$subject_category[$i]['subject'] = $key->title->message; 
			 $i++;
		}
		return $subject_category;
	}
	
}
