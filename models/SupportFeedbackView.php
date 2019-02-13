<?php
/**
 * SupportFeedbackView
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 25 September 2017, 14:10 WIB
 * @modified date 27 January 2019, 10:56 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedback_view".
 *
 * The followings are the available columns in table "ommu_support_feedback_view":
 * @property integer $view_id
 * @property integer $publish
 * @property integer $feedback_id
 * @property integer $user_id
 * @property integer $views
 * @property string $view_date
 * @property string $view_ip
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportFeedbacks $feedback
 * @property Users $user
 * @property SupportFeedbackViewHistory[] $histories
 * @property Users $modified
 *
 */

namespace ommu\support\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;

class SupportFeedbackView extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];

	// Search Variable
	public $feedbackDisplayname;
	public $userDisplayname;
	public $modifiedDisplayname;
	public $feedbackSubject;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_feedback_view';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['feedback_id', 'user_id'], 'required'],
			[['publish', 'feedback_id', 'user_id', 'views', 'modified_id'], 'integer'],
			[['view_ip'], 'string', 'max' => 20],
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
			'view_id' => Yii::t('app', 'View'),
			'publish' => Yii::t('app', 'Publish'),
			'feedback_id' => Yii::t('app', 'Feedback'),
			'user_id' => Yii::t('app', 'User'),
			'views' => Yii::t('app', 'Views'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View Ip'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'histories' => Yii::t('app', 'Histories'),
			'feedbackDisplayname' => Yii::t('app', 'Name'),
			'userDisplayname' => Yii::t('app', 'User'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getHistories($count=false)
	{
		if($count == false)
			return $this->hasMany(SupportFeedbackViewHistory::className(), ['view_id' => 'view_id']);

		$model = SupportFeedbackViewHistory::find()
			->where(['view_id' => $this->view_id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\query\SupportFeedbackView the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\support\models\query\SupportFeedbackView(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		$this->templateColumns['_no'] = [
			'header' => Yii::t('app', 'No'),
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!Yii::$app->request->get('feedback')) {
			$this->templateColumns['feedbackDisplayname'] = [
				'attribute' => 'feedbackDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->feedback) ? $model->feedback->displayname : '-';
				},
			];
			$this->templateColumns['feedbackSubject'] = [
				'attribute' => 'feedbackSubject',
				'value' => function($model, $key, $index, $column) {
					return isset($model->feedback) ? $model->feedback->subject->title->message : '-';
				},
			];
		}
		if(!Yii::$app->request->get('user')) {
			$this->templateColumns['userDisplayname'] = [
				'attribute' => 'userDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->view_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'view_date'),
		];
		$this->templateColumns['view_ip'] = [
			'attribute' => 'view_ip',
			'value' => function($model, $key, $index, $column) {
				return $model->view_ip;
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		if(!Yii::$app->request->get('modified')) {
			$this->templateColumns['modifiedDisplayname'] = [
				'attribute' => 'modifiedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->updated_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'updated_date'),
		];
		$this->templateColumns['views'] = [
			'attribute' => 'views',
			'filter' => false,
			'value' => function($model, $key, $index, $column) {
				$views = $model->views;
				return Html::a($views, ['feedback/view-detail/manage', 'view'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		if(!Yii::$app->request->get('trash')) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => $this->filterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return $this->quickAction($url, $model->publish);
				},
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
			$model = self::find()
				->select([$column])
				->where(['view_id' => $id])
				->one();
			return $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public static function insertFeedbackView($feedback_id)
	{
		$user_id = Yii::$app->user->id;
		$findView = self::find()
			->where(['feedback_id' => $feedback_id, 'user_id' => $user_id])
			->one();

		if($findView != null)
			$findView->updateAttributes(['views'=>$findView->views+1, 'view_ip'=>$_SERVER['REMOTE_ADDR']]);

		else {
			$feedbackView = new self();
			$feedbackView->feedback_id = $feedback_id;
			$feedbackView->user_id = $user_id;
			$feedbackView->save();
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
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
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
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
			$this->view_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}
