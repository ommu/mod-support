<?php
/**
 * SupportFeedbacks
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.id)
 * @created date 18 April 2018, 14:39 WIB
 * @modified date 25 January 2019, 15:11 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedbacks".
 *
 * The followings are the available columns in table "ommu_support_feedbacks":
 * @property integer $feedback_id
 * @property string $app
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
use app\models\Users;
use ommu\support\models\view\SupportFeedbacks as SupportFeedbacksView;

class SupportFeedbacks extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['phone', 'replied_date', 'repliedDisplayname', 'modified_date', 'updated_date', 'modifiedDisplayname', 'publish'];

	public $subjectName;
	public $userDisplayname;
	public $repliedDisplayname;
	public $modifiedDisplayname;
	public $verifyCode;
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
			[['subject_id', 'email', 'displayname', 'phone', 'message'], 'required'],
			[['reply_message'], 'required', 'on' => self::SCENARIO_REPLY],
			[['publish', 'user_id', 'replied_id', 'modified_id'], 'integer'],
			[['app', 'message', 'reply_message'], 'string'],
			[['app', 'subject_id'], 'safe'],
			[['email'], 'email'],
			// ['verifyCode', 'captcha'],
			[['subject_id', 'email'], 'string', 'max' => 64],
			[['app', 'displayname'], 'string', 'max' => 32],
			[['phone'], 'string', 'max' => 15],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			// [['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportFeedbackSubject::className(), 'targetAttribute' => ['subject_id' => 'subject_id']],
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
			'app' => Yii::t('app', 'Application'),
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
			'verifyCode' => Yii::t('app', 'Verification Code'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(SupportFeedbackUser::className(), ['feedback_id' => 'feedback_id'])
                ->alias('users')
                ->andOnCondition([sprintf('%s.publish', 'users') => $publish]);
        }

		$model = SupportFeedbackUser::find()
            ->alias('t')
            ->where(['t.feedback_id' => $this->feedback_id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
		$users = $model->count();

		return $users ? $users : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews($count=false, $publish=1)
	{
        if ($count == false) {
            return $this->hasMany(SupportFeedbackView::className(), ['feedback_id' => 'feedback_id'])
                ->alias('views')
                ->andOnCondition([sprintf('%s.publish', 'views') => $publish]);
        }

		$model = SupportFeedbackView::find()
            ->alias('t')
            ->where(['t.feedback_id' => $this->feedback_id]);
        if ($publish == 0) {
            $model->unpublish();
        } else if ($publish == 1) {
            $model->published();
        } else if ($publish == 2) {
            $model->deleted();
        }
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

        if (!(Yii::$app instanceof \app\components\Application)) {
            return;
        }

        if (!$this->hasMethod('search')) {
            return;
        }

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'app\components\grid\SerialColumn',
			'contentOptions' => ['class'=>'text-center'],
		];
		$this->templateColumns['app'] = [
			'attribute' => 'app',
			'value' => function($model, $key, $index, $column) {
				return $model->app;
			},
		];
		$this->templateColumns['subject_id'] = [
			'attribute' => 'subject_id',
			'value' => function($model, $key, $index, $column) {
				return isset($model->subject) ? $model->subject->title->message : '-';
				// return $model->subjectName;
			},
			'filter' => SupportFeedbackSubject::getSubject(),
			'visible' => !Yii::$app->request->get('subject') ? true : false,
		];
		// $this->templateColumns['userDisplayname'] = [
		// 	'attribute' => 'userDisplayname',
		// 	'value' => function($model, $key, $index, $column) {
		// 		return isset($model->user) ? $model->user->displayname : '-';
		// 		// return $model->userDisplayname;
		// 	},
		// 	'visible' => !Yii::$app->request->get('user') ? true : false,
		// ];
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
		$this->templateColumns['repliedDisplayname'] = [
			'attribute' => 'repliedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->replied) ? $model->replied->displayname : '-';
				// return $model->repliedDisplayname;
			},
			'visible' => !Yii::$app->request->get('replied') ? true : false,
		];
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
		$this->templateColumns['modifiedDisplayname'] = [
			'attribute' => 'modifiedDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
				// return $model->modifiedDisplayname;
			},
			'visible' => !Yii::$app->request->get('modified') ? true : false,
		];
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
				return Html::a($views, ['feedback/view/manage', 'feedback'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} views', ['count'=>$views]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['users'] = [
			'attribute' => 'users',
			'value' => function($model, $key, $index, $column) {
				$users = $model->getUsers(true);
				return Html::a($users, ['feedback/user/manage', 'feedback'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} users', ['count'=>$users]), 'data-pjax'=>0]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['reply'] = [
			'attribute' => 'reply',
			'value' => function($model, $key, $index, $column) {
				$reply = $this->filterYesNo($model->reply);
				return $model->reply == 0 ? Html::a($reply, ['reply', 'id'=>$model->primaryKey], ['title'=>Yii::t('app', 'Click to reply'), 'class'=>'modal-btn', 'data-pjax'=>0]) : $reply;
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
		];
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'filter' => $this->filterYesNo(),
			'contentOptions' => ['class'=>'text-center'],
			'format' => 'raw',
			'visible' => !Yii::$app->request->get('trash') ? true : false,
		];
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
        if ($column != null) {
            $model = self::find();
            if (is_array($column)) {
                $model->select($column);
            } else {
                $model->select([$column]);
            }
            $model = $model->where(['feedback_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

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

		$this->reply = $this->getReplyStatus();
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if (!$this->isNewRecord) {
                if ($this->modified_id == null) {
                    $this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }

                if ($this->scenario == self::SCENARIO_REPLY) {
                    if ($this->replied_id == null) {
                        $this->replied_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                    }
				}
			}
            if ($this->subject_id == '') {
                $this->addError('subject_id', Yii::t('app', '{attribute} cannot be blank.', ['attribute'=>$this->getAttributeLabel('subject_id')]));
            }
        }
        return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$this->app = Yii::$app->id;
        if (!isset($this->subject)) {
            $this->subject_id = SupportFeedbackSubject::insertSubject($this->subject_id);
        }

		return true;
	}
}
