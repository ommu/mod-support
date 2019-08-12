<?php
/**
 * SubjectController
 * @var $this SubjectController
 * @var $model SupportFeedbackSubject
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Suggest
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 28 September 2018, 06:08 WIB
 * @link https://github.com/ommu/mod-support
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SubjectController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		$arrThemes = $this->currentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','suggest'),
				'users'=>array('@'),
				'expression'=>'Yii::app()->user->level',
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
	}

	/**
	 * Suggest a particular model.
	 * @param integer $limit
	 */
	public function actionSuggest($limit=10)
	{
		if(Yii::app()->request->isAjaxRequest) {
			$term = Yii::app()->getRequest()->getParam('term');
			if($term) {
				$criteria = new CDbCriteria;
				$criteria->select = 't.subject_id, t.subject_name';
				//$criteria->compare('t.publish', $publish);
				$criteria->addInCondition('t.publish', array(0,1));
				$criteria->compare('title.message', $term, true);
				$criteria->limit = $limit;
				$criteria->order = "t.subject_id ASC";
				$model = SupportFeedbackSubject::model()->findAll($criteria);

				if($model) {
					foreach($model as $val) {
						$result[] = array('id'=>$val->subject_id, 'value'=>$val->title->message);
					}
				} else
					$result[] = array('id'=>0, 'value'=>$term);
			}
			echo CJSON::encode($result);
			Yii::app()->end();
			
		} else
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = SupportFeedbackSubject::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='support-feedback-subject-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
