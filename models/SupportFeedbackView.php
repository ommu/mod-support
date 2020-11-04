<?php
/**
 * SupportFeedbackView
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
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
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportFeedbacks $feedback
 * @property Users $user
 * @property SupportFeedbackViewHistory[] $histories
 *
 */

namespace ommu\support\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Users;

class SupportFeedbackView extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['view_ip', 'updated_date', 'feedbackEmail', 'feedbackDisplayname', 'feedbackPhone'];

	public $feedbackSubject;
	public $feedbackEmail;
	public $feedbackDisplayname;
	public $feedbackPhone;
	public $feedbackMessage;
	public $userDisplayname;

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
			[['publish', 'feedback_id', 'user_id', 'views'], 'integer'],
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
			'user_id' => Yii::t('app', 'Viewer'),
			'views' => Yii::t('app', 'Views'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'histories' => Yii::t('app', 'Histories'),
			'feedbackSubject' => Yii::t('app', 'Subject'),
			'feedbackEmail' => Yii::t('app', 'Email'),
			'feedbackDisplayname' => Yii::t('app', 'Name'),
			'feedbackPhone' => Yii::t('app', 'Phone'),
			'feedbackMessage' => Yii::t('app', 'Message'),
			'userDisplayname' => Yii::t('app', 'Viewer'),
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
        if ($count == false) {
            return $this->hasMany(SupportFeedbackViewHistory::className(), ['view_id' => 'view_id']);
        }

		$model = SupportFeedbackViewHistory::find()
            ->alias('t')
            ->where(['t.view_id' => $this->view_id]);
		$histories = $model->count();

		return $histories ? $histories : 0;
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
		$this->templateColumns['feedbackDisplayname'] = [
			'attribute' => 'feedbackDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->feedback) ? $model->feedback->displayname : '-';
				// return $model->feedbackDisplayname;
			},
			'visible' => !Yii::$app->request->get('feedback') ? true : false,
		];
		$this->templateColumns['feedbackEmail'] = [
			'attribute' => 'feedbackEmail',
			'value' => function($model, $key, $index, $column) {
				return isset($model->feedback) ? Yii::$app->formatter->asEmail($model->feedback->email) : '-';
				// return $model->feedbackEmail;
			},
			'visible' => !Yii::$app->request->get('feedback') ? true : false,
		];
		$this->templateColumns['feedbackPhone'] = [
			'attribute' => 'feedbackPhone',
			'value' => function($model, $key, $index, $column) {
				return isset($model->feedback) ? $model->feedback->phone : '-';
				// return $model->feedbackPhone;
			},
			'visible' => !Yii::$app->request->get('feedback') ? true : false,
		];
		$this->templateColumns['feedbackSubject'] = [
			'attribute' => 'feedbackSubject',
			'value' => function($model, $key, $index, $column) {
				return isset($model->feedback) ? $model->feedback->subject->title->message : '-';
				// return $model->feedbackSubject;
			},
			'visible' => !Yii::$app->request->get('feedback') ? true : false,
		];
		$this->templateColumns['feedbackMessage'] = [
			'attribute' => 'feedbackMessage',
			'value' => function($model, $key, $index, $column) {
				return isset($model->feedback) ? $model->feedback->message : '-';
				// return $model->feedbackMessage;
			},
			'visible' => !Yii::$app->request->get('feedback') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->feedback) ? $model->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('user') ? true : false,
		];
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
				$views = $model->views;
				return Html::a($views, ['feedback/view-detail/manage', 'view'=>$model->primaryKey], ['title'=>Yii::t('app', '{count} views', ['count'=>$views]), 'data-pjax'=>0]);
			},
			'filter' => false,
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
            $model = $model->where(['view_id' => $id])->one();
            return is_array($column) ? $model : $model->$column;

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
            ->alias('t')
            ->where(['t.feedback_id' => $feedback_id, 'user_id' => $user_id])
            ->one();

        if ($findView != null) {
            $findView->updateAttributes(['views'=>$findView->views+1, 'view_ip'=>$_SERVER['REMOTE_ADDR']]);
        } else {
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

		// $this->feedbackSubject = isset($this->feedback) ? $this->feedback->subject->title->message : '-';
		// $this->feedbackEmail = isset($this->feedback) ? $this->feedback->email : '-';
		// $this->feedbackDisplayname = isset($this->feedback) ? $this->feedback->displayname : '-';
		// $this->feedbackPhone = isset($this->feedback) ? $this->feedback->phone : '-';
		// $this->feedbackMessage = isset($this->feedback) ? $this->feedback->message : '-';
		// $this->userDisplayname = isset($this->user) ? $this->user->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
        if (parent::beforeValidate()) {
            if ($this->isNewRecord) {
                if ($this->user_id == null) {
                    $this->user_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
                }
            }
            $this->view_ip = $_SERVER['REMOTE_ADDR'];
        }
        return true;
	}
}
