<?php
/**
 * SupportWidget
 *
 * This is the model class for table "ommu_support_widget".
 *
 * The followings are the available columns in table "ommu_support_widget":
 * @property string $widget_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $widget_source
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportContactCategory $contactCategory

 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 13:10 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace ommu\support\models;

use Yii;
use yii\helpers\Url;
use ommu\users\models\Users;
use app\components\grid\GridView;

class SupportWidget extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_widget';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'cat_id', 'creation_id', 'modified_id'], 'integer'],
			[['cat_id', 'widget_source'], 'required'],
			[['widget_source'], 'string'],
			[['creation_date', 'modified_date', 'updated_date', 'creation_id', 'modified_id'], 'safe'],
			[['cat_id'], 'exist', 'skipOnError' => true, 'targetClass' => SupportContactCategory::className(), 'targetAttribute' => ['cat_id' => 'cat_id']],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContactCategory()
	{
		return $this->hasOne(SupportContactCategory::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCreation()
	{
		return $this->hasOne(Users::className(), ['user_id' => 'creation_id']);
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
			'widget_id' => Yii::t('app', 'Widget'),
			'publish' => Yii::t('app', 'Publish'),
			'cat_id' => Yii::t('app', 'Cat'),
			'widget_source' => Yii::t('app', 'Widget Source'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'contactCategory_search' => Yii::t('app', 'ContactCategory'),
			'creation_search' => Yii::t('app', 'Creation'),
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
		];
		$this->templateColumns['contactCategory_search'] = [
			'attribute' => 'contactCategory_search',
			'value' => function($model, $key, $index, $column) {
				return $model->contactCategory->name;
			},
		];
		$this->templateColumns['widget_source'] = 'widget_source';
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		$this->templateColumns['creation_search'] = [
			'attribute' => 'creation_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->modified_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'modified_date'),
		];
		$this->templateColumns['modified_search'] = [
			'attribute' => 'modified_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified) ? $model->modified->displayname : '-';
			},
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
	 * before validate attributes
	 */
	public function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
			else
				$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : '0';
			// Create action
		}
		return true;
	}

}
