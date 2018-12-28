<?php
namespace app\modules\support\controllers\v1;

use Yii;
use app\components\api\ActiveController;
use app\modules\support\models\SupportFeedbacks;


/**
 * FeedbacksController
 *
 * @copyright Copyright(c) 2018 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author  Eko Hariyanto <haryeko29@gmail.com>
 * @created 5 April 2018, 13:36 WIB
 * @contact (+62)857-4381-4273
 *
 */
class FeedbacksController extends ActiveController
{
	public $modelClass = 'app\modules\support\models\SupportFeedbacks';
    public $searchModelClass = 'app\modules\support\models\search\SupportFeedbacks';
    public static $authType = 0;
	public $createScenario = 'api_submit';
	
	/*public function init() {
        // Paksa semua request data yg datang menjadi format json, untuk semua action
        // pada controller ini.
        $this->detachBehavior('contentNegotiator');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        parent::init();
    }*/
    // hak akses api
     protected function verbs() {
       return [
           'Create' => ['POST'],
           'get' => ['GET'],
       ];
    }

  public function actions()
    {
        $actions = parent::actions();
        $feedbacks = [
            'update' => [
                'class'       => 'app\modules\support\actions\feedbacks\CreateAction',
                'modelClass'  => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'scenario' => $this->createScenario,
            ],
        ];

        return array_merge($actions, $feedbacks);
    }
}
