<?php
/**
 * HistoryController
 * @var $this ommu\support\controllers\feedback\view\HistoryController
 * @var $model ommu\support\models\SupportFeedbackViewHistory
 *
 * HistoryController implements the CRUD actions for SupportFeedbackViewHistory model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	View
 *	Delete
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 25 September 2017, 14:32 WIB
 * @modified date 28 January 2019, 14:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\controllers\feedback\view;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\support\models\SupportFeedbackViewHistory;
use ommu\support\models\search\SupportFeedbackViewHistory as SupportFeedbackViewHistorySearch;

class HistoryController extends Controller
{
	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
        return [
            'access' => [
                'class' => AccessControl::className(),
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all SupportFeedbackViewHistory models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new SupportFeedbackViewHistorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $gridColumn = Yii::$app->request->get('GridColumn', null);
        $cols = [];
        if ($gridColumn != null && count($gridColumn) > 0) {
            foreach ($gridColumn as $key => $val) {
                if ($gridColumn[$key] == 1) {
                    $cols[] = $key;
                }
            }
        }
        $columns = $searchModel->getGridColumn($cols);

        if (($view = Yii::$app->request->get('view')) != null) {
            $view = \ommu\support\models\SupportFeedbackView::findOne($view);
        }

		$this->view->title = Yii::t('app', 'Feedback View Histories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'view' => $view,
		]);
	}

	/**
	 * Displays a single SupportFeedbackViewHistory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Feedback View History: {view-id}', ['view-id' => $model->view->feedback->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SupportFeedbackViewHistory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->delete();

		Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback view history success deleted.'));
		return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
	}

	/**
	 * Finds the SupportFeedbackViewHistory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SupportFeedbackViewHistory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = SupportFeedbackViewHistory::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
