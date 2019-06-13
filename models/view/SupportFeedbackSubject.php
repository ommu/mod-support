<?php
/**
 * SupportFeedbackSubject
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 27 January 2019, 19:58 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "_support_feedback_subject".
 *
 * The followings are the available columns in table "_support_feedback_subject":
 * @property integer $subject_id
 * @property string $feedbacks
 * @property integer $feedback_all
 *
 */

namespace ommu\support\models\view;

use Yii;

class SupportFeedbackSubject extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_support_feedback_subject';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['subject_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['subject_id', 'feedback_all'], 'integer'],
			[['feedbacks'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'subject_id' => Yii::t('app', 'Subject'),
			'feedbacks' => Yii::t('app', 'Feedbacks'),
			'feedback_all' => Yii::t('app', 'Feedback All'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['subject_id'] = [
			'attribute' => 'subject_id',
			'value' => function($model, $key, $index, $column) {
				return $model->subject_id;
			},
		];
		$this->templateColumns['feedbacks'] = [
			'attribute' => 'feedbacks',
			'value' => function($model, $key, $index, $column) {
				return $model->feedbacks;
			},
		];
		$this->templateColumns['feedback_all'] = [
			'attribute' => 'feedback_all',
			'value' => function($model, $key, $index, $column) {
				return $model->feedback_all;
			},
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find()
				->select([$column])
				->where(['subject_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
