<?php
/**
 * CategoryController
 * @var $this app\components\View
 * @var $model app\modules\support\models\SupportContactCategory
 *
 * CategoryController implements the CRUD actions for SupportContactCategory model.
 * Reference start
 * TOC :
 *	Index
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *
 *	findModel
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 11:08 WIB
 * @contact (+62)856-299-4114
 *
 */
 
namespace ommu\support\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use app\modules\support\models\SupportContactCategory;
use app\modules\support\models\search\SupportContactCategory as SupportContactCategorySearch;

class CategoryController extends Controller
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
	 * Lists all SupportContactCategory models.
	 * @return mixed
	 */
	public function actionManage()
	{
		$searchModel = new SupportContactCategorySearch();
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

		$this->view->title = Yii::t('app', 'Support Contact Categories');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Creates a new SupportContactCategory model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new SupportContactCategory();

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->cat_id]);
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Create Support Contact Category');
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_create', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Updates an existing SupportContactCategory model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->cat_id]);
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {name}', ['modelClass' => 'Support Contact Category', 'name' => $model->name]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single SupportContactCategory model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {name}', ['modelClass' => 'Support Contact Category', 'name' => $model->name]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SupportContactCategory model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->cat_id]);
			return $this->redirect(['index']);
		}
	}

	/**
	 * Publish/Unpublish an existing SupportContactCategory model.
	 * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

		if ($model->save(false, ['publish']))
			return $this->redirect(['index']);
	}

	/**
	 * Finds the SupportContactCategory model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SupportContactCategory the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = SupportContactCategory::findOne($id)) !== null)
			return $model;

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
