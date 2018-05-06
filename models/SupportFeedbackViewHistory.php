<?php
/**
 * SupportFeedbackViewHistory
 * version: 0.0.1
 *
 * This is the model class for table "ommu_support_feedback_view_history".
 *
 * The followings are the available columns in table "ommu_support_feedback_view_history":
 * @property string $id
 * @property string $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property SupportFeedbackView $feedbackView

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Arifin Avicena <avicenaarifin@gmail.com>
 * @created date 25 September 2017, 11:22 WIB
 * @contact (+62)857-2971-9487
 *
 */

namespace app\modules\support\models;

use Yii;
use yii\helpers\Url;

class SupportFeedbackViewHistory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_feedback_view_history';
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
			[['view_id', 'view_ip'], 'required'],
			[['view_id'], 'integer'],
			[['view_date'], 'safe'],
			[['view_ip'], 'string', 'max' => 20],
			[['view_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportFeedbackView::className(), 'targetAttribute' => ['view_id' => 'view_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFeedbackView()
	{
		return $this->hasOne(SupportFeedbackView::className(), ['view_id' => 'view_id']);
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
			'view_ip' => Yii::t('app', 'View Ip'),
			'feedbackView_search' => Yii::t('app', 'FeedbackView'),
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
		];
		$this->templateColumns['feedbackView_search'] = [
			'attribute' => 'feedbackView_search',
			'value' => function($model, $key, $index, $column) {
				return $model->feedbackView->view_id;
			},
		];
		$this->templateColumns['view_date'] = [
			'attribute' => 'view_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'view_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->view_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00']) ? Yii::$app->formatter->format($model->view_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['view_ip'] = 'view_ip';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			// Create action
		}
		return true;
	}
}
