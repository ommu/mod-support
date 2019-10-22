<?php
/**
 * SupportFeedbackUser
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 September 2017, 15:37 WIB
 * @modified date 27 January 2019, 10:56 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedback_user".
 *
 * The followings are the available columns in table "ommu_support_feedback_user":
 * @property integer $id
 * @property integer $publish
 * @property integer $feedback_id
 * @property integer $user_id
 * @property string $creation_date
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportFeedbacks $feedback
 * @property Users $user
 *
 */

namespace ommu\support\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;

class SupportFeedbackUser extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['updated_date'];

	public $feedbackDisplayname;
	public $userDisplayname;
	public $feedbackSubject;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_feedback_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['feedback_id', 'user_id'], 'required'],
			[['publish', 'feedback_id', 'user_id'], 'integer'],
			[['feedback_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportFeedbacks::className(), 'targetAttribute' => ['feedback_id' => 'feedback_id']],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'publish' => Yii::t('app', 'Publish'),
			'feedback_id' => Yii::t('app', 'Feedback'),
			'user_id' => Yii::t('app', 'User'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'feedbackDisplayname' => Yii::t('app', 'Name'),
			'userDisplayname' => Yii::t('app', 'User'),
			'feedbackSubject' => Yii::t('app', 'Subject'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFeedback()
	{
		return $this->hasOne(SupportFeedbacks::className(), ['feedback_id' => 'feedback_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'user_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\query\SupportFeedbackUser the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\support\models\query\SupportFeedbackUser(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		if(!$this->hasMethod('search'))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('feedback')) {
			$this->templateColumns['feedbackDisplayname'] = [
				'attribute' => 'feedbackDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->feedback) ? $model->feedback->displayname : '-';
					// return $model->feedbackDisplayname;
				},
			];
			$this->templateColumns['feedbackSubject'] = [
				'attribute' => 'feedbackSubject',
				'value' => function($model, $key, $index, $column) {
					return isset($model->feedback) ? $model->feedback->subject->title->message : '-';
					// return $model->feedbackSubject;
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
					// return $model->userDisplayname;
				},
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
				'filter' => $this->filterYesNo(),
				'contentOptions' => ['class'=>'center'],
				'format' => 'raw',
			];
		}
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::find();
			if(is_array($column))
				$model->select($column);
			else
				$model->select([$column]);
			$model = $model->where(['id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->feedbackDisplayname = isset($this->feedback) ? $this->feedback->displayname : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->feedbackSubject = isset($this->feedback) ? $this->feedback->subject->title->message : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->user_id == null)
					$this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}
}
