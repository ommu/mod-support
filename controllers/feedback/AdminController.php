<?php
/**
 * AdminController
 * @var $this ommu\support\controllers\feedback\AdminController
 * @var $model ommu\support\models\SupportFeedbacks
 *
 * AdminController implements the CRUD actions for SupportFeedbacks model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 September 2017, 13:55 WIB
 * @modified date 27 January 2019, 09:55 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\controllers\feedback;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\support\models\SupportFeedbacks;
use ommu\support\models\search\SupportFeedbacks as SupportFeedbacksSearch;
use ommu\support\models\SupportFeedbackView;

class AdminController extends Controller
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
					'publish' => ['POST'],
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
	 * Lists all SupportFeedbacks models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new SupportFeedbacksSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

		$gridColumn = Yii::$app->request->get('GridColumn', null);
		$cols = [];
		if($gridColumn != null && count($gridColumn) > 0) {
			foreach($gridColumn as $key => $val) {
				if($gridColumn[$key] == 1)
					$cols[] = $key;
			}
		}
		$columns = $searchModel->getGridColumn($cols);

		if(($subject = Yii::$app->request->get('subject')) != null)
			$subject = \ommu\support\models\SupportFeedbackSubject::findOne($subject);
		if(($user = Yii::$app->request->get('user')) != null)
			$user = \ommu\users\models\Users::findOne($user);

		$this->view->title = Yii::t('app', 'Feedbacks');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
			'subject' => $subject,
			'user' => $user,
		]);
	}

	/**
	 * Creates a new SupportFeedbacks model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new SupportFeedbacks();

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback success created.'));
				if(!Yii::$app->request->isAjax)
					return $this->redirect(['manage']);
				return $this->redirect(Yii::$app->request->referrer ?: ['manage']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Create Feedback');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing SupportFeedbacks model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback success updated.'));
				if(!Yii::$app->request->isAjax)
					return $this->redirect(['manage']);
				return $this->redirect(Yii::$app->request->referrer ?: ['manage']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Feedback: {displayname}', ['displayname' => $model->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single SupportFeedbacks model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);
		SupportFeedbackView::insertFeedbackView($model->feedback_id);

		$this->view->title = Yii::t('app', 'Detail Feedback: {displayname}', ['displayname' => $model->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SupportFeedbacks model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * actionPublish an existing SupportFeedbacks model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if($model->save(false, ['publish','modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionReply($id)
	{
		$model = $this->findModel($id);
		$model->scenario = SupportFeedbacks::SCENARIO_REPLY;

		if(Yii::$app->request->isPost) {
			$model->load(Yii::$app->request->post());
			// $postData = Yii::$app->request->post();
			// $model->load($postData);
			// $model->order = $postData['order'] ? $postData['order'] : 0;

			if($model->save()) {
				Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback success replied.'));
				if(!Yii::$app->request->isAjax)
					return $this->redirect(['manage']);
				return $this->redirect(Yii::$app->request->referrer ?: ['manage']);

			} else {
				if(Yii::$app->request->isAjax)
					return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
			}
		}

		$this->view->title = Yii::t('app', 'Update Feedback: {displayname}', ['displayname' => $model->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_reply', [
			'model' => $model,
		]);
	}

	/**
	 * Finds the SupportFeedbacks model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SupportFeedbacks the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if(($model = SupportFeedbacks::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
