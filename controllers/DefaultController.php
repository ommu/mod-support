<?php
namespace app\modules\support\controllers;

use app\components\Controller;

/**
 * 
 * @var $this yii\web\View
 * version: 0.0.1
 *
 * Default controller for the `support` module
 * Reference start
 * TOC :
 *	Index
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 15 September 2017, 15:05 WIB
 * @contact (+62)856-299-4114
 *
 */
class DefaultController extends Controller
{
	/**
	 * Renders the index view for the module
	 * @return string
	 */
	public function actionIndex()
	{
		$this->view->title = 'supports';
		$this->view->description = '';
		$this->view->keywords = '';
		return $this->render('index');
	}
}
