<?php
/**
 * SupportFeedbacks
 * version: 0.0.1
 *
 * This is the model class for table "ommu_support_feedbacks".
 *
 * The followings are the available columns in table "ommu_support_feedbacks":
 * @property string $feedback_id
 * @property integer $publish
 * @property integer $subject_id
 * @property integer $user_id
 * @property string $email
 * @property string $displayname
 * @property string $phone
 * @property string $subject
 * @property string $message
 * @property string $reply_message
 * @property string $replied_date
 * @property string $replied_id
 * @property string $creation_date
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 * @property string $_inbox_id
 * @property string $_website
 * @property integer $_reply
 * @property integer $_read_flag
 * @property string $_reply_id
 * @property integer $_status
 *
 * The followings are the available model relations:
 * @property SupportFeedbackUser[] $users
 * @property SupportFeedbackView[] $views
 * @property Users $user
 * @property SupportFeedbackSubject $subject
 * @property Users $user

 * @copyright Copyright (c) 2018 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Eko Hariyanto <haryeko29@gmail.com>
 * @created date 18 April 2018, 14:39 WIB
 * @contact (+62)857-4381-4273
 *
 */

namespace app\modules\support\models;

use Yii;
use yii\helpers\Url;
use app\modules\user\models\Users;
//use app\modules\user\models\Users;
use app\models\SourceMessage;
use app\modules\support\models\SupportFeedbackSubject;
use app\libraries\grid\GridView;
use app\components\Utility;

class SupportFeedbacks extends \app\components\ActiveRecord
{
	
	public $gridForbiddenColumn = ['creation_date', 'modified_date', 'updated_date', 'modified_search'];

  
	public $subject_i;
	public $verifyCode;
	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_feedbacks';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
		 [['publish', 'subject_id', 'user_id', 'replied_id', 'modified_id', '_inbox_id', '_reply', '_read_flag', '_reply_id', '_status'], 'integer'],
			[['email', 'displayname', 'message', 'subject_i'], 'required'],
			[['message', 'reply_message', 'subject_i', '_website'], 'string'],
			[['subject_id', 'replied_date', 'creation_date', 'modified_date', 'updated_date', 'phone', 'subject', 'reply_message', 'modified_id'], 'safe'],
			[['email', 'displayname'], 'string', 'max' => 32],
			[['phone'], 'string', 'max' => 15],
			[['subject'], 'string', 'max' => 64],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'user_id']],
			[['subject_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportFeedbackSubject::className(), 'targetAttribute' => ['subject_id' => 'subject_id']],
			[['replied_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['replied_id' => 'user_id']],
			['email', 'email'],
			// verifyCode needs to be entered correctly
			['verifyCode', 'captcha'],
	  ];
	}
	public function scenarios()
	{
		return [
			'api_submit' => ['email', 'displayname', 'message', 'subject_i', 'publish', 'subject_id', 'user_id', 'replied_id', 'modified_id', '_inbox_id', '_reply', '_read_flag', '_reply_id', '_status'],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUsers()
	{
		return $this->hasMany(SupportFeedbackUser::className(), ['feedback_id' => 'feedback_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViews()
	{
		return $this->hasMany(SupportFeedbackView::className(), ['feedback_id' => 'feedback_id']);
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
	public function getUserreplied()
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'verifyCode' => 'Verification Code',
			'feedback_id' => Yii::t('app', 'Feedback'),
			'publish' => Yii::t('app', 'Publish'),
			'subject_id' => Yii::t('app', 'Subject'),
			'user_id' => Yii::t('app', 'User'),
			'email' => Yii::t('app', 'Email'),
			'displayname' => Yii::t('app', 'Name'),
			'phone' => Yii::t('app', 'Phone'),
			'subject' => Yii::t('app', 'Subject'),
			'subject_i' => Yii::t('app', 'Subject'),
			'message' => Yii::t('app', 'Message'),
			'reply_message' => Yii::t('app', 'Reply Message'),
			'replied_date' => Yii::t('app', 'Replied Date'),
			'replied_id' => Yii::t('app', 'Replied'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'_inbox_id' => Yii::t('app', 'Inbox'),
			'_website' => Yii::t('app', 'Website'),
			'_reply' => Yii::t('app', 'Reply'),
			'_read_flag' => Yii::t('app', 'Read Flag'),
			'_reply_id' => Yii::t('app', 'Reply'),
			'_status' => Yii::t('app', 'Status'),
			'subject_search' => Yii::t('app', 'Subject'),
			'user_search' => Yii::t('app', 'User'),
			'user_search' => Yii::t('app', 'User'),
			'modified_search' => Yii::t('app', 'Modified'),
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
			'class'  => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		if(!isset($_GET['subject'])) {
			$this->templateColumns['subject_search'] = [
				'attribute' => 'subject_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->subject) ? $model->subject->title->message : '-';
				},
			];
		}
		if(!isset($_GET['user'])) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['email'] = 'email';
		$this->templateColumns['displayname'] = 'displayname';
		$this->templateColumns['phone'] = 'phone';
		$this->templateColumns['message'] = 'message';
		$this->templateColumns['reply_message'] = 'reply_message';
		$this->templateColumns['replied_date'] = [
			'attribute' => 'replied_date',
			'filter'    => \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'replied_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->replied_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->replied_date, 'date');/*datetime*/
				}else {
					return '-';
				}
			},
			'format'    => 'html',
		];
		if(!isset($_GET['user'])) {
			$this->templateColumns['user_search'] = [
				'attribute' => 'user_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->user) ? $model->user->displayname : '-';
				},
			];
		}
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter'    => \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'creation_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->creation_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'    => 'html',
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'    => \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->modified_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'    => 'html',
		];
		if(!isset($_GET['modified'])) {
			$this->templateColumns['modified_search'] = [
				'attribute' => 'modified_search',
				'value' => function($model, $key, $index, $column) {
					return isset($model->modified) ? $model->modified->displayname : '-';
				},
			];
		}
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'    => \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'  => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				if(!in_array($model->updated_date, 
					['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00','0002-12-02 00:00:00','-0001-11-30 00:00:00'])) {
					return Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/);
				}else {
					return '-';
				}
			},
			'format'    => 'html',
		];
		$this->templateColumns['_inbox_id'] = '_inbox_id';
		$this->templateColumns['_website'] = '_website';
		$this->templateColumns['_reply_id'] = '_reply_id';
		$this->templateColumns['_reply'] = [
			'attribute' => '_reply',
			'value' => function($model, $key, $index, $column) {
				return $model->_reply;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['_read_flag'] = [
			'attribute' => '_read_flag',
			'value' => function($model, $key, $index, $column) {
				return $model->_read_flag;
			},
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['_status'] = [
			'attribute' => '_status',
			'value' => function($model, $key, $index, $column) {
				return $model->_status;
			},
			'contentOptions' => ['class'=>'center'],	
		];
		if(!isset($_GET['trash'])) {
			$this->templateColumns['publish'] = [
				'attribute' => 'publish',
				'filter' => GridView::getFilterYesNo(),
				'value' => function($model, $key, $index, $column) {
					$url = Url::to(['publish', 'id'=>$model->primaryKey]);
					return GridView::getPublish($url, $model->publish);
				},
				'contentOptions' => ['class'=>'center'],
				'format'    => 'raw',
			];
		}
	}

	public function beforeSave($insert) 
	{
	   // print_r($this->attributeLabels);exit;
		if(parent::beforeSave($insert)) {
			$subject_i = Utility::getUrlTitle($this->subject_i);
			$subject = SupportFeedbackSubject::find()
				->where(['slug' =>  $subject_i])
				->one();

			if($subject != null) {
				$this->subject_id = $subject->subject_id;

			} else {
				$data = new SupportFeedbackSubject();
				$data->subject_name_i = $this->subject_i;
				if($data->save())
					$this->subject_id = $data->subject_id;
			}
		}
		return true;    
	}


}
