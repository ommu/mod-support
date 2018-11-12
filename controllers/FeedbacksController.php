<?php
/**
 * FeedbacksController
 * @var $this yii\web\View
 * @var $model app\modules\support\models\SupportFeedbacks
 *
 * FeedbacksController implements the CRUD actions for SupportFeedbacks model.
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
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 13:55 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\support\controllers;

use Yii;
use app\modules\support\models\SupportFeedbacks;
use app\modules\support\models\search\SupportFeedbacks as SupportFeedbacksSearch;
use app\components\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\support\models\SupportFeedbackView;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use mdm\admin\components\AccessControl;

class FeedbacksController extends Controller
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
	 * Lists all SupportFeedbacks models.
	 * @return mixed
	 */
	public function actionIndex()
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

		$this->view->title = Yii::t('app', 'Support Feedbacks');
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('admin_index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'columns'	  => $columns,
		]);
	}

	/**
	 * Updates an existing SupportFeedbacks model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param string $id
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);

		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			//return $this->redirect(['view', 'id' => $model->feedback_id]);
			return $this->redirect(['index']);

		} else {
			$this->view->title = Yii::t('app', 'Update {modelClass}: {displayname}', ['modelClass' => 'Support Feedbacks', 'displayname' => $model->displayname]);
			$this->view->description = '';
			$this->view->keywords = '';
			return $this->render('admin_update', [
				'model' => $model,
			]);
		}
	}

	/**
	 * Displays a single SupportFeedbacks model.
	 * @param string $id
	 * @return mixed
	 */
	public function actionView($id)
	{
		$model = $this->findModel($id);

		$this->view->title = Yii::t('app', 'View {modelClass}: {displayname}', ['modelClass' => 'Support Feedbacks', 'displayname' => $model->displayname]);
		$this->view->description = '';
		$this->view->keywords = '';

		$feedback = new SupportFeedbackView();
		$feedbackView = $feedback->insertFeedbackView($model->feedback_id);

		return $this->render('admin_view', [
			'model' => $model,
		]);
	}

	/**
	 * Deletes an existing SupportFeedbacks model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
	 * @return mixed
	 */


	/**
	 * Publish/Unpublish an existing SupportFeedbacks model.
	 * If publish/unpublish is successful, the browser will be redirected to the 'index' page.
	 * @param string $id
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
	 * Finds the SupportFeedbacks model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 * @param string $id
	 * @return SupportFeedbacks the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = SupportFeedbacks::findOne($id)) !== null)
			return $model;
		else
			throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
	}
	public function actionDelete($id)
	{
		$model = $this->findModel($id);
		$model->publish = 2;

		if ($model->save(false, ['publish'])) {
			//return $this->redirect(['view', 'id' => $model->feedback_id]);
			return $this->redirect(['index']);
		}
	}
	/**
	* add export data
	*/
	public function actionExport() {
		$searchModel = new SupportFeedbacksSearch();
		$params = Yii::$app->request->queryParams;
		if(array_key_exists(1, $params))
			$params = Yii::$app->request->queryParams[1];
		$dataProvider = $searchModel->search($params);
		if($dataProvider->getCount() < 1) {
			Yii::$app->session->setFlash('error', 'Data tidak ada!', false);
			return $this->redirect(['index']);
		}

		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator('Agus Susilo')
			->setLastModifiedBy('Eko Hariyanto')
			->setTitle('Office 2007 XLSX Company Industry Document')
			->setSubject('Office 2007 XLSX Company Industry Document')
			->setDescription('Company Industry document for Office 2007 XLSX, generated using PHP classes.')
			->setKeywords('office 2007 openxml php')
			->setCategory('Company Industry result file');

		$spreadsheet->setActiveSheetIndex(0)
	        ->setCellValue('A1', 'No')
	        ->setCellValue('B1', 'Alamat Email')
	        ->setCellValue('C1', 'Pesan');

		$i = 2;
		$no = 1;
		foreach($dataProvider->getModels() as $items) {
			$spreadsheet->setActiveSheetIndex(0)
		        ->setCellValue('A' . $i, $no)
		        ->setCellValue('B' . $i, $items->email)
		        ->setCellValue('C' . $i, $items->message);

			$i++;
			$no++;
		}

		$spreadsheet->getActiveSheet()->setTitle('feedback');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="feedback.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
		Yii::$app->end();
	}
	
}
