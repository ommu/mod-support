<?php
/**
 * SupportContactCategory
 * version: 0.0.1
 *
 * This is the model class for table "ommu_support_contact_category".
 *
 * The followings are the available columns in table "ommu_support_contact_category":
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $name
 * @property string $cat_icon
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property SupportContacts[] $contacts
 * @property SupportWidget[] $widgets

 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 15 September 2017, 15:07 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace app\modules\support\models;

use Yii;
use yii\helpers\Url;
use yii\behaviors\SluggableBehavior;
use app\modules\user\models\Users;
use app\libraries\grid\GridView;

class SupportContactCategory extends \app\components\ActiveRecord
{
	public $gridForbiddenColumn = [];

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_contact_category';
	}

	/**
	 * @return \yii\db\Connection the database connection used by this AR class.
	 */
	public static function getDb()
	{
		return Yii::$app->get('ecc4');
	}

	/**
	 * behaviors model class.
	 */
	public function behaviors() {
		return [
			[
				'class'		=> SluggableBehavior::className(),
				'attribute'	=> 'name',
				'immutable'	=> true,
				'ensureUnique' => true,
			],
		];
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['publish', 'name', 'creation_id', 'modified_id'], 'integer'],
			[['name', 'cat_icon'], 'required'],
			[['creation_date', 'modified_date', 'updated_date'], 'safe'],
			[['cat_icon', 'slug'], 'string', 'max' => 32],
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getContacts()
	{
		return $this->hasMany(SupportContacts::className(), ['cat_id' => 'cat_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getWidgets()
	{
		return $this->hasMany(SupportWidget::className(), ['cat_id' => 'cat_id']);
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
			'cat_id' => Yii::t('app', 'Cat'),
			'publish' => Yii::t('app', 'Publish'),
			'name' => Yii::t('app', 'Name'),
			'cat_icon' => Yii::t('app', 'Cat Icon'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'slug' => Yii::t('app', 'Slug'),
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
		$this->templateColumns['name'] = 'name';
		$this->templateColumns['cat_icon'] = 'cat_icon';
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'creation_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->creation_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->creation_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['creation_search'] = [
			'attribute' => 'creation_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->creation) ? $model->creation->displayname : '-';
			},
		];
		$this->templateColumns['modified_date'] = [
			'attribute' => 'modified_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'modified_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->modified_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->modified_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['modified_search'] = [
			'attribute' => 'modified_search',
			'value' => function($model, $key, $index, $column) {
				return isset($model->modified->displayname) ? $model->modified->displayname : '-';
			},
		];
		$this->templateColumns['updated_date'] = [
			'attribute' => 'updated_date',
			'filter'	=> \yii\jui\DatePicker::widget([
				'dateFormat' => 'yyyy-MM-dd',
				'attribute' => 'updated_date',
				'model'	 => $this,
			]),
			'value' => function($model, $key, $index, $column) {
				return !in_array($model->updated_date, ['0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00']) ? Yii::$app->formatter->format($model->updated_date, 'date'/*datetime*/) : '-';
			},
			'format'	=> 'html',
		];
		$this->templateColumns['slug'] = 'slug';
		$this->templateColumns['publish'] = [
			'attribute' => 'publish',
			'value' => function($model, $key, $index, $column) {
				$url = Url::to(['publish', 'id'=>$model->primaryKey]);
				return $this->quickAction($url, $model->publish);
			},
			'contentOptions' => ['class'=>'center'],
			'format' => 'raw',
		];
	}

	/**
	 * function getContactCategory
	 */
	public function getContactCategory()	{
		$items = [];
		$model = self::find()->orderBy('name ASC')->all();
		if($model !== null) {
			foreach($model as $val) {
				$items[$val->cat_id] = $val->name;
			}
		}
		
		return $items;
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
