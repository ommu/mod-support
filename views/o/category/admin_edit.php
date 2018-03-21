<?php
/**
 * Support Contact Categories (support-contact-category)
 * @var $this CategoryController
 * @var $model SupportContactCategory
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 20 March 2018, 14:29 WIB
 * @link https://github.com/ommu/ommu-support
 *
 */

	$this->breadcrumbs=array(
		'Support Contact Categories'=>array('manage'),
		$model->title->message=>array('view','id'=>$model->cat_id),
		'Update',
	);
?>

<?php echo $this->renderPartial('_form', array(
	'model'=>$model,
)); ?>
