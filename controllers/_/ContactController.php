<?php
/**
 * ContactController
 * @var $this ContactController
 * @var $model SupportMails
 * @var $form CActiveForm
 *
 * Reference start
 * TOC :
 *	Index
 *	Feedback
 *	Success
 *	Office
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
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
		$arrThemes = Utility::getCurrentTemplate('public');
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','feedback','success','office'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
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
		$model = OmmuMeta::model()->findByPk(1, array(
			'select' => 'id, office_name, office_place, office_village, office_district, office_city_id, office_province_id, office_country_id, office_zipcode, office_phone, office_fax, office_hotline, office_email'
		));
		$contact = SupportContacts::model()->findAll(array(
			'condition' => 'publish = :publish',
			'params' => array(
				':publish' => 1,
			),
		));
		
		$this->pageTitle = Yii::t('phrase', 'Contact Us');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('front_index',array(
			'model'=>$model,
			'contact'=>$contact,
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionFeedback() 
	{
		$model=new SupportMails;
		if(!Yii::app()->user->isGuest) {
			$user = Users::model()->findByPk(Yii::app()->user->id, array(
				'select' => 'user_id, email, displayname, photo_id',
			));
		}

		// Uncomment the following line if AJAX validation is needed
		$this->performAjaxValidation($model);

		if(isset($_POST['SupportMails'])) {
			$model->attributes=$_POST['SupportMails'];
			$model->scenario = 'contactus';
			
			if($model->save()) {
				if($model->user_id != 0)
					$url = Yii::app()->controller->createUrl('feedback', array('email'=>$model->email, 'name'=>$model->displayname));
				else
					$url = Yii::app()->controller->createUrl('feedback', array('email'=>$model->email, 'name'=>$model->displayname));
				$this->redirect($url);
				/*
				echo CJSON::encode(array(
					'type' => 5,
					'get' => $url,
				));
				*/
			}
		}
		
		$this->pageTitleShow = true;		
		$this->pageTitle = isset($_GET['email']) ? Yii::t('phrase', 'Kontak Kami Berhasil Dikirim') : Yii::t('phrase', 'Kontak Kami');
		$this->pageDescription = isset($_GET['email']) ? (isset($_GET['name']) ? Yii::t('phrase', 'Hi <strong>$1 ($2)</strong>, terimakasih telah menghubungi support kami.'), array($_GET['name'], $_GET['email'])) : Yii::t('phrase', 'Hi <strong>$1</strong>, terimakasih telah menghubungi support kami.'), array($_GET['email']))) : '';
		$this->pageMeta = '';
		$this->render('front_feedback',array(
			'model'=>$model,
			'user'=>$user,
		));
	}
	
	/**
	 * Lists all models.
	 */
	public function actionOffice() 
	{
		$this->layout = false;
		$model = OmmuMeta::model()->findAll(array(
			//'select' => 'office_on, google_on, twitter_on, facebook_on'
		));
		$setting = OmmuSettings::model()->findByPk(1,array(
			'select' => 'site_title'
		));
		
		$return = array();
		$return['maps'] = array(
			'icon'=>Utility::getProtocol().'://'.Yii::app()->request->serverName.Yii::app()->request->baseUrl.'/public/marker_default.png',
			'width'=>42,
			'height'=>48,
		);
		$i = 0;
		foreach($model as $val){
			$i++;
			$point = explode(',', $val->office_location);
			$return['data'][] = array(
				'id'=>$i,
				'lat'=>$point[0],
				'lng'=>$point[1],
				'name'=>$val->office_name != '' ? $val->office_name : $setting->site_title,
				'address'=>$val->office_place.'. '.$val->office_village.', '.$val->office_district.', '.$val->view_meta->city.', '.$val->view_meta->province.', '.$val->view_meta->country.', '.$val->office_zipcode,
			);
		}
		
		echo CJSON::encode($return);
	}

	/**
	 * Lists all models.
	 */
	public function actionSuccess()
	{
		$this->dialogDetail = true;
		$this->dialogGroundUrl = Yii::app()->controller->createUrl('index');
		$this->dialogWidth = 400;
		
		$this->pageTitle = Yii::t('phrase', 'Delete Contact');
		$this->pageDescription = '';
		$this->pageMeta = '';
		$this->render('front_success');
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
