<?php
/**
 * ContactController
 * @var $this ContactController
 * @var $model SupportContacts
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Manage
 *	Add
 *	Edit
 *	View
 *	Delete
 *	Runaction
 *	Publish
 *	Address
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 27 September 2018, 11:46 WIB
 * @link https://github.com/ommu/mod-support
 *
 *----------------------------------------------------------------------------------------------------------
 */

class ContactController extends Controller
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
		if(!Yii::app()->user->isGuest) {
			if(in_array(Yii::app()->user->level, array(1,2))) {
				$arrThemes = $this->currentTemplate('admin');
				Yii::app()->theme = $arrThemes['folder'];
				$this->layout = $arrThemes['layout'];
			}
		} else
			$this->redirect(Yii::app()->createUrl('site/login'));
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
				'actions'=>array('index','manage','edit','view','address'),
				'users'=>array('@'),
				'expression'=>'in_array(Yii::app()->user->level, array(1,2))',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('add','delete','runaction','publish'),
				'users'=>array('@'),
				'expression'=>'Yii::app()->user->level == 1',
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
		$this->redirect(array('manage'));
	}

	/**
	 * Manages all models.
	 */
	public function actionManage($category=null) 
	{
		$model=new SupportContacts('search');
		$model->unsetAttributes();	// clear any default values
		$SupportContacts = Yii::app()->getRequest()->getParam('SupportContacts');
		if($SupportContacts)
			$model->attributes=$SupportContacts;

		$columns = $model->getGridColumn($this->gridColumnTemp());

		$pageTitle = Yii::t('phrase', 'Support Contacts');
		if($category != null) {
			$data = SupportContactCategory::model()->findByPk($category);
			$pageTitle = Yii::t('phrase', 'Contacts: {name}', array ('{name}'=>$data->title->message));
		}

		$this->pageTitle = $pageTitle;
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_manage', array(
			'model'=>$model,
			'columns' => $columns,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionAdd() 
	{
		$model=new SupportContacts;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['SupportContacts'])) {
			$model->attributes=$_POST['SupportContacts'];

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-support-contacts',
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Support contact success created.').'</strong></div>',
						));
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Create Contact');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_add', array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionEdit($id) 
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['SupportContacts'])) {
			$model->attributes=$_POST['SupportContacts'];

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				echo $jsonError;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 5,
							'get' => Yii::app()->controller->createUrl('manage'),
							'id' => 'partial-support-contacts',
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Support contact success updated.').'</strong></div>',
						));
					} else
						print_r($model->getErrors());
				}
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Update Contact: {name} {contact_name}', array('{name}'=>$model->category->title->message, '{contact_name}'=>$model->contact_name));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_edit', array(
			'model'=>$model,
		));
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id) 
	{
		$model=$this->loadModel($id);

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 600;

		$this->pageTitle = Yii::t('phrase', 'Detail Contact: {name} {contact_name}', array('{name}'=>$model->category->title->message, '{contact_name}'=>$model->contact_name));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_view', array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id) 
	{
		$model=$this->loadModel($id);
		
		if(Yii::app()->request->isPostRequest) {
			// we only allow deletion via POST request
			$model->publish = 2;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			
			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-support-contacts',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Support contact success deleted.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', 'Delete Contact: {name} {contact_name}', array('{name}'=>$model->category->title->message, '{contact_name}'=>$model->contact_name));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_delete');
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionRunaction() 
	{
		$id       = $_POST['trash_id'];
		$criteria = null;
		$actions  = Yii::app()->getRequest()->getParam('action');

		if(count($id) > 0) {
			$criteria = new CDbCriteria;
			$criteria->addInCondition('id', $id);

			if($actions == 'publish') {
				SupportContacts::model()->updateAll(array(
					'publish' => 1,
				), $criteria);
			} elseif($actions == 'unpublish') {
				SupportContacts::model()->updateAll(array(
					'publish' => 0,
				), $criteria);
			} elseif($actions == 'trash') {
				SupportContacts::model()->updateAll(array(
					'publish' => 2,
				), $criteria);
			} elseif($actions == 'delete') {
				SupportContacts::model()->deleteAll($criteria);
			}
		}

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!Yii::app()->getRequest()->getParam('ajax'))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('manage'));
	}

	/**
	 * Publish a particular model.
	 * If publish is successful, the browser will be redirected to the 'manage' page.
	 * @param integer $id the ID of the model to be publish
	 */
	public function actionPublish($id) 
	{
		$model=$this->loadModel($id);
		$title = $model->publish == 1 ? Yii::t('phrase', 'Unpublish') : Yii::t('phrase', 'Publish');
		$replace = $model->publish == 1 ? 0 : 1;

		if(Yii::app()->request->isPostRequest) {
			// we only allow publish via POST request
			$model->publish = $replace;
			$model->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

			if($model->update()) {
				echo CJSON::encode(array(
					'type' => 5,
					'get' => Yii::app()->controller->createUrl('manage'),
					'id' => 'partial-support-contacts',
					'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Support contact success updated.').'</strong></div>',
				));
			}
			Yii::app()->end();
		}

		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('manage');
		$this->dialogWidth = 350;

		$this->pageTitle = Yii::t('phrase', '{title} Contact: {name} {contact_name}', array('{title}'=>$title, '{name}'=>$model->category->title->message, '{contact_name}'=>$model->contact_name));
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_publish', array(
			'title'=>$title,
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionAddress() 
	{
		$model = OmmuMeta::model()->findByPk(1);
		if($model == null)
			$model=new OmmuMeta;

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['OmmuMeta'])) {
			$model->attributes=$_POST['OmmuMeta'];
			$model->scenario = 'contact';

			$jsonError = CActiveForm::validate($model);
			if(strlen($jsonError) > 2) {
				$errors = $model->getErrors();
				$summary['msg'] = "<div class='errorSummary'><strong>".Yii::t('phrase', 'Please fix the following input errors:')."</strong>";
				$summary['msg'] .= "<ul>";
				foreach($errors as $key => $val) {
					$summary['msg'] .= "<li>{$val[0]}</li>";
				}
				$summary['msg'] .= "</ul></div>";

				$message = json_decode($jsonError, true);
				$merge = array_merge_recursive($summary, $message);
				$encode = json_encode($merge);
				echo $encode;

			} else {
				if(Yii::app()->getRequest()->getParam('enablesave') == 1) {
					if($model->save()) {
						echo CJSON::encode(array(
							'type' => 0,
							'msg' => '<div class="errorSummary success"><strong>'.Yii::t('phrase', 'Address success updated.').'</strong></div>',
						));
					} else {
						print_r($model->getErrors());
					}
				}
			}
			Yii::app()->end();
		}
		
		$this->pageTitle = Yii::t('phrase', 'Update Address');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('admin_address', array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = SupportContacts::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='support-contacts-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

}
