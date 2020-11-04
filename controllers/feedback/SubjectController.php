<?php
/**
 * SubjectController
 * @var $this ommu\support\controllers\feedback\SubjectController
 * @var $model ommu\support\models\SupportFeedbackSubject
 *
 * SubjectController implements the CRUD actions for SupportFeedbackSubject model.
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Create
 *	Update
 *	View
 *	Delete
 *	RunAction
 *	Publish
 *	Suggest
 *
 *	findModel
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 27 January 2019, 18:54 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\controllers\feedback;

use Yii;
use app\components\Controller;
use mdm\admin\components\AccessControl;
use yii\filters\VerbFilter;
use ommu\support\models\SupportFeedbackSubject;
use ommu\support\models\search\SupportFeedbackSubject as SupportFeedbackSubjectSearch;
use app\models\SourceMessage;

class SubjectController extends Controller
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
	public function allowAction(): array {
		return ['suggest'];
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionIndex()
	{
        return $this->redirect(['manage']);
	}

	/**
	 * Lists all SupportFeedbackSubject models.
	 * @return mixed
	 */
	public function actionManage()
	{
        $searchModel = new SupportFeedbackSubjectSearch();
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

		$this->view->title = Yii::t('app', 'Feedback Subjects');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_manage', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns' => $columns,
		]);
	}

	/**
	 * Creates a new SupportFeedbackSubject model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
        $model = new SupportFeedbackSubject();

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            $model->parent_id = $postData['parent_id'] ? $postData['parent_id'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback subject success created.'));
                if (!Yii::$app->request->isAjax) {
                    return $this->redirect(['manage']);
                }
                return $this->redirect(Yii::$app->request->referrer ?: ['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Create Feedback Subject');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_create', [
			'model' => $model,
		]);
	}

	/**
	 * Updates an existing SupportFeedbackSubject model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            $postData = Yii::$app->request->post();
            $model->load($postData);
            $model->parent_id = $postData['parent_id'] ? $postData['parent_id'] : 0;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback subject success updated.'));
                if (!Yii::$app->request->isAjax) {
                    return $this->redirect(['manage']);
                }
                return $this->redirect(Yii::$app->request->referrer ?: ['manage']);

            } else {
                if (Yii::$app->request->isAjax) {
                    return \yii\helpers\Json::encode(\app\components\widgets\ActiveForm::validate($model));
                }
            }
        }

		$this->view->title = Yii::t('app', 'Update Feedback Subject: {subject-name}', ['subject-name' => $model->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_update', [
			'model' => $model,
		]);
	}

	/**
	 * Displays a single SupportFeedbackSubject model.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionView($id)
	{
        $model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'Detail Feedback Subject: {subject-name}', ['subject-name' => $model->title->message]);
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->oRender('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SupportFeedbackSubject model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback subject success deleted.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * actionPublish an existing SupportFeedbackSubject model.
	 * If publish is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id
	 * @return mixed
	 */
	public function actionPublish($id)
	{
		$model = $this->findModel($id);
		$replace = $model->publish == 1 ? 0 : 1;
		$model->publish = $replace;

        if ($model->save(false, ['publish', 'modified_id'])) {
			Yii::$app->session->setFlash('success', Yii::t('app', 'Support feedback subject success updated.'));
			return $this->redirect(Yii::$app->request->referrer ?: ['manage']);
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function actionSuggest()
	{
		Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

		$term = Yii::$app->request->get('query');
		$parent = Yii::$app->request->get('parent', null);

        if ($term == null) return [];

		$model = SupportFeedbackSubject::find()
            ->alias('t')
			->select(['subject_id', 'subject_name'])
			->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.subject_name=title.id')
			->andWhere(['like', 'title.message', $term]);
        if ($parent != null) {
            $model->andWhere(['t.parent_id' => $parent]);
        }
		$model = $model->published()->limit(15)->all();
		
		$result = [];
		$i = 0;
        foreach ($model as $val) {
			$result[] = [
				'id' => $val->subject_id, 
				'label' => $val->subjectName,
			];
		}
		return $result;
	}

	/**
	 * Finds the SupportFeedbackSubject model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param integer $id
	 * @return SupportFeedbackSubject the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
        if (($model = SupportFeedbackSubject::findOne($id)) !== null) {
            return $model;
        }

		throw new \yii\web\NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
}
