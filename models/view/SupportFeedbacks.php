<?php
/**
 * SupportFeedbacks
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.co)
 * @created date 27 January 2019, 12:58 WIB
 * @modified date 28 January 2019, 12:14 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "_support_feedbacks".
 *
 * The followings are the available columns in table "_support_feedbacks":
 * @property integer $feedback_id
 * @property integer $reply
 * @property integer $view
 * @property string $views
 * @property string $view_all
 * @property string $users
 * @property integer $user_all
 *
 */

namespace ommu\support\models\view;

use Yii;

class SupportFeedbacks extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return '_support_feedbacks';
	}

	/**
	 * @return string the primarykey column
	 */
	public static function primaryKey()
	{
		return ['feedback_id'];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['feedback_id', 'reply', 'view', 'user_all'], 'integer'],
			[['views', 'view_all', 'users'], 'number'],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'feedback_id' => Yii::t('app', 'Feedback'),
			'reply' => Yii::t('app', 'Reply'),
			'view' => Yii::t('app', 'View'),
			'views' => Yii::t('app', 'Views'),
			'view_all' => Yii::t('app', 'View All'),
			'users' => Yii::t('app', 'Users'),
			'user_all' => Yii::t('app', 'User All'),
		];
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['feedback_id'] = [
			'attribute' => 'feedback_id',
			'value' => function($model, $key, $index, $column) {
				return $model->feedback_id;
			},
		];
		$this->templateColumns['reply'] = [
			'attribute' => 'reply',
			'value' => function($model, $key, $index, $column) {
				return $model->reply;
			},
		];
		$this->templateColumns['view'] = [
			'attribute' => 'view',
			'value' => function($model, $key, $index, $column) {
				return $model->view;
			},
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'value' => function($model, $key, $index, $column) {
				return $model->views;
			},
		];
		$this->templateColumns['view_all'] = [
			'attribute' => 'view_all',
			'value' => function($model, $key, $index, $column) {
				return $model->view_all;
			},
		];
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'value' => function($model, $key, $index, $column) {
				return $model->users;
			},
		];
		$this->templateColumns['user_all'] = [
			'attribute' => 'user_all',
			'value' => function($model, $key, $index, $column) {
				return $model->user_all;
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
				->where(['feedback_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}
}
