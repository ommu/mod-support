<?php
/**
 * SupportFeedbackViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 25 September 2017, 11:22 WIB
 * @modified date 25 January 2019, 15:13 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedback_view_history".
 *
 * The followings are the available columns in table "ommu_support_feedback_view_history":
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property SupportFeedbackView $view
 *
 */

namespace ommu\support\models;

use Yii;

class SupportFeedbackViewHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = ['feedbackEmail', 'feedbackDisplayname', 'feedbackPhone'];

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
		return 'ommu_support_feedback_view_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['view_id', 'view_ip'], 'required'],
			[['view_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportFeedbackView::className(), 'targetAttribute' => ['view_id' => 'view_id']],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'id' => Yii::t('app', 'ID'),
			'view_id' => Yii::t('app', 'View'),
			'view_date' => Yii::t('app', 'View Date'),
			'view_ip' => Yii::t('app', 'View IP'),
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
	public function getView()
	{
		return $this->hasOne(SupportFeedbackView::className(), ['view_id' => 'view_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\query\SupportFeedbackViewHistory the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\support\models\query\SupportFeedbackViewHistory(get_called_class());
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
			'contentOptions' => ['class' => 'text-center'],
		];
		$this->templateColumns['feedbackDisplayname'] = [
			'attribute' => 'feedbackDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->feedback->displayname : '-';
				// return $model->feedbackDisplayname;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['feedbackEmail'] = [
			'attribute' => 'feedbackEmail',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? Yii::$app->formatter->asEmail($model->view->feedback->email) : '-';
				// return $model->feedbackEmail;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['feedbackPhone'] = [
			'attribute' => 'feedbackPhone',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->feedback->phone : '-';
				// return $model->feedbackPhone;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['feedbackSubject'] = [
			'attribute' => 'feedbackSubject',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->feedback->subject->title->message : '-';
				// return $model->feedbackSubject;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['feedbackMessage'] = [
			'attribute' => 'feedbackMessage',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->feedback->message : '-';
				// return $model->feedbackMessage;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
		];
		$this->templateColumns['userDisplayname'] = [
			'attribute' => 'userDisplayname',
			'value' => function($model, $key, $index, $column) {
				return isset($model->view) ? $model->view->user->displayname : '-';
				// return $model->userDisplayname;
			},
			'visible' => !Yii::$app->request->get('view') ? true : false,
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

		// $this->feedbackSubject = isset($this->view) ? $this->view->feedback->subject->title->message : '-';
		// $this->feedbackEmail = isset($this->view) ? $this->view->feedback->email : '-';
		// $this->feedbackDisplayname = isset($this->view) ? $this->view->feedback->displayname : '-';
		// $this->feedbackPhone = isset($this->view) ? $this->view->feedback->phone : '-';
		// $this->feedbackMessage = isset($this->view) ? $this->view->feedback->message : '-';
		// $this->userDisplayname = isset($this->view) ? $this->view->user->displayname : '-';
	}
}
