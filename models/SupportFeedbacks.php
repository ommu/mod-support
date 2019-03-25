<?php
/**
 * SupportFeedbacks
 * 
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 18 April 2018, 14:39 WIB
 * @modified date 25 January 2019, 15:11 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedbacks".
 *
 * The followings are the available columns in table "ommu_support_feedbacks":
 * @property integer $feedback_id
 * @property integer $publish
 * @property integer $subject_id
 * @property integer $user_id
 * @property string $email
 * @property string $displayname
 * @property string $phone
 * @property string $message
 * @property string $reply_message
 * @property string $replied_date
 * @property integer $replied_id
 * @property string $creation_date
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportFeedbackUser[] $users
 * @property SupportFeedbackView[] $views
 * @property Users $user
 * @property SupportFeedbackSubject $subject
 * @property Users $replied
 * @property Users $modified
 *
 */

namespace ommu\support\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use ommu\users\models\Users;
use ommu\support\models\view\SupportFeedbacks as SupportFeedbacksView;

class SupportFeedbacks extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['phone', 'replied_date', 'repliedDisplayname', 'modified_date', 'updated_date', 'modifiedDisplayname', 'publish'];

	public $subjectName;
	public $userDisplayname;
	public $repliedDisplayname;
	public $modifiedDisplayname;

	public $OldSubjectId;
	public $OldSubjectName;

	public $reply;

	const SCENARIO_REPLY = 'replyForm';

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_feedbacks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['email', 'displayname', 'phone', 'message', 'subjectName'], 'required'],
			[['reply_message'], 'required', 'on' => self::SCENARIO_REPLY],
			[['publish', 'subject_id', 'user_id', 'replied_id', 'modified_id'], 'integer'],
			[['subject_id'], 'safe'],
			[['message', 'reply_message'], 'string'],
			[['email'], 'email'],
			[['email', 'subjectName'], 'string', 'max' => 64],
			[['displayname'], 'string', 'max' => 32],
			[['phone'], 'string', 'max' => 15],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			[['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportFeedbackSubject::className(), 'targetAttribute' => ['subject_id' => 'subject_id']],
			[['replied_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['replied_id' => 'user_id']],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		$scenarios = parent::scenarios();
		$scenarios[self::SCENARIO_REPLY] = ['reply_message'];
		return $scenarios;
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'feedback_id' => Yii::t('app', 'Feedback'),
			'publish' => Yii::t('app', 'Publish'),
			'subject_id' => Yii::t('app', 'Subject'),
			'user_id' => Yii::t('app', 'User'),
			'email' => Yii::t('app', 'Email'),
			'displayname' => Yii::t('app', 'Name'),
			'phone' => Yii::t('app', 'Phone'),
			'message' => Yii::t('app', 'Message'),
			'reply_message' => Yii::t('app', 'Reply Message'),
			'replied_date' => Yii::t('app', 'Replied Date'),
			'replied_id' => Yii::t('app', 'Replied'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'users' => Yii::t('app', 'Users'),
			'views' => Yii::t('app', 'Views'),
			'reply' => Yii::t('app', 'Reply'),
			'subjectName' => Yii::t('app', 'Subject'),
			'userDisplayname' => Yii::t('app', 'User'),
			'repliedDisplayname' => Yii::t('app', 'Replied'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(SupportFeedbackUser::className(), ['feedback_id' => 'feedback_id'])
				->andOnCondition([sprintf('%s.publish', SupportFeedbackUser::tableName()) => $publish]);
		}

		$model = SupportFeedbackUser::find()
			->where(['feedback_id' => $this->feedback_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$users = $model->count();

		return $users ? $users : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews($count=false, $publish=1)
	{
		if($count == false) {
			return $this->hasMany(SupportFeedbackView::className(), ['feedback_id' => 'feedback_id'])
				->andOnCondition([sprintf('%s.publish', SupportFeedbackView::tableName()) => $publish]);
		}

		$model = SupportFeedbackView::find()
			->where(['feedback_id' => $this->feedback_id]);
		if($publish == 0)
			$model->unpublish();
		elseif($publish == 1)
			$model->published();
		elseif($publish == 2)
			$model->deleted();
		$views =$model->sum('views');

		return $views ? $views : 0;
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
	public function getSubject()
	{
		return $this->hasOne(SupportFeedbackSubject::className(), ['subject_id' => 'subject_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReplied()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'replied_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getModified()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'modified_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(SupportFeedbacksView::className(), ['feedback_id' => 'feedback_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\query\SupportFeedbacks the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\support\models\query\SupportFeedbacks(get_called_class());
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
		if(!Yii::$app->request->get('subject')) {
			$this->templateColumns['subject_id'] = [
				'attribute' => 'subject_id',
				'value' => function($model, $key, $index, $column) {
					return isset($model->subject) ? $model->subject->title->message : '-';
					// return $model->subjectName;
				},
				'filter' => SupportFeedbackSubject::getSubject(),
			];
		}
		// if(!Yii::$app->request->get('user')) {
		// 	$this->templateColumns['userDisplayname'] = [
		// 		'attribute' => 'userDisplayname',
		// 		'value' => function($model, $key, $index, $column) {
		// 			return isset($model->user) ? $model->user->displayname : '-';
		//			// return $model->userDisplayname;
		// 		},
		// 	];
		// }
		$this->templateColumns['email'] = [
			'attribute' => 'email',
			'value' => function($model, $key, $index, $column) {
				return $model->email;
			},
		];
		$this->templateColumns['displayname'] = [
			'attribute' => 'displayname',
			'value' => function($model, $key, $index, $column) {
				return $model->displayname;
			},
		];
		$this->templateColumns['phone'] = [
			'attribute' => 'phone',
			'value' => function($model, $key, $index, $column) {
				return $model->phone;
			},
		];
		// $this->templateColumns['message'] = [
		// 	'attribute' => 'message',
		// 	'value' => function($model, $key, $index, $column) {
		// 		return $model->message;
		// 	},
		// ];
		// $this->templateColumns['reply_message'] = [
		// 	'attribute' => 'reply_message',
		// 	'value' => function($model, $key, $index, $column) {
		// 		return $model->reply_message;
		// 	},
		// ];
		$this->templateColumns['replied_date'] = [
			'attribute' => 'replied_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->replied_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'replied_date'),
		];
		if(!Yii::$app->request->get('replied')) {
			$this->templateColumns['repliedDisplayname'] = [
				'attribute' => 'repliedDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->replied) ? $model->replied->displayname : '-';
					// return $model->repliedDisplayname;
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
					// return $model->modifiedDisplayname;
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
			'value' => function($model, $key, $index, $column) {
				$views = $model->getViews(true);
				return Html::a($views, ['feedback/view/manage', 'feedback'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'value' => function($model, $key, $index, $column) {
				$users = $model->getUsers(true);
				return Html::a($users, ['feedback/user/manage', 'feedback'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} users', ['count'=>$users])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
		];
		$this->templateColumns['reply'] = [
			'attribute' => 'reply',
			'value' => function($model, $key, $index, $column) {
				$reply = $this->filterYesNo($model->reply);
				return $model->reply == 0 ? Html::a($reply, ['reply', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Click to reply')]) : $reply;
			},
			'filter' => $this->filterYesNo(),
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
				->where(['feedback_id' => $id])
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
	public function getReplyStatus()
	{
		return $this->reply_message != '' || $this->replied_id != null ? 1 : 0;
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		// $this->subjectName = isset($this->subject) ? $this->subject->title->message : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
		// $this->repliedDisplayname = isset($this->replied) ? $this->replied->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';

		$this->OldSubjectId = $this->subject_id;
		$this->OldSubjectName = $this->subjectName;

		$this->reply = $this->getReplyStatus();
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;

				if($this->scenario = self::SCENARIO_REPLY) {
					if($this->replied_id == null)
						$this->replied_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
				}
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		if($insert) {
			if(!$this->subject_id)
				$this->subject_id = SupportFeedbackSubject::insertSubject($this->subjectName);
		} else {
			if($this->subject_id == $this->OldSubjectId && $this->subjectName != $this->OldSubjectName)
				$this->subject_id = SupportFeedbackSubject::insertSubject($this->subjectName);
		}

		return true;
	}
}
