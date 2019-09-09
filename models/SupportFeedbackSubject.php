<?php
/**
 * SupportFeedbackSubject
 * 
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 OMMU (www.ommu.co)
 * @created date 18 April 2018, 17:39 WIB
 * @modified date 27 January 2019, 18:51 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedback_subject".
 *
 * The followings are the available columns in table "ommu_support_feedback_subject":
 * @property integer $subject_id
 * @property integer $publish
 * @property integer $parent_id
 * @property integer $subject_name
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportFeedbacks[] $feedbacks
 * @property SourceMessage $title
 * @property Users $creation
 * @property Users $modified
 *
 */

namespace ommu\support\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Inflector;
use app\models\SourceMessage;
use ommu\users\models\Users;
use ommu\support\models\view\SupportFeedbackSubject as SupportFeedbackSubjectView;

class SupportFeedbackSubject extends \app\components\ActiveRecord
{
	use \ommu\traits\UtilityTrait;

	public $gridForbiddenColumn = ['modified_date', 'modifiedDisplayname', 'updated_date'];

	public $subjectName;
	public $parentName;
	public $creationDisplayname;
	public $modifiedDisplayname;

	/**
	 * @return string the associated database table name
	 */
	public static function tableName()
	{
		return 'ommu_support_feedback_subject';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return [
			[['subjectName'], 'required'],
			[['publish', 'parent_id', 'subject_name', 'creation_id', 'modified_id'], 'integer'],
			[['parent_id'], 'safe'],
			[['subjectName'], 'string'],
			[['subjectName'], 'string', 'max' => 64],
		];
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return [
			'subject_id' => Yii::t('app', 'Subject'),
			'publish' => Yii::t('app', 'Publish'),
			'parent_id' => Yii::t('app', 'Parent'),
			'subject_name' => Yii::t('app', 'Subject'),
			'creation_date' => Yii::t('app', 'Creation Date'),
			'creation_id' => Yii::t('app', 'Creation'),
			'modified_date' => Yii::t('app', 'Modified Date'),
			'modified_id' => Yii::t('app', 'Modified'),
			'updated_date' => Yii::t('app', 'Updated Date'),
			'subjectName' => Yii::t('app', 'Subject'),
			'parentName' => Yii::t('app', 'Paerent'),
			'feedbacks' => Yii::t('app', 'Feedbacks'),
			'creationDisplayname' => Yii::t('app', 'Creation'),
			'modifiedDisplayname' => Yii::t('app', 'Modified'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getFeedbacks($count=false, $publish=null)
	{
		if($count == false) {
			return $this->hasMany(SupportFeedbacks::className(), ['subject_id' => 'subject_id'])
				->alias('feedbacks')
				->andOnCondition([sprintf('%s.publish', 'feedbacks') => $publish]);
		}

		$model = SupportFeedbacks::find()
			->alias('t')
			->where(['t.subject_id' => $this->subject_id]);
		if($publish === null)
			$model->send();
		else {
			if($publish == 0)
				$model->unpublish();
			elseif($publish == 1)
				$model->published();
			elseif($publish == 2)
				$model->deleted();
		}
		$feedbacks = $model->count();

		return $feedbacks ? $feedbacks : 0;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTitle()
	{
		return $this->hasOne(SourceMessage::className(), ['id' => 'subject_name']);
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
	 * @return \yii\db\ActiveQuery
	 */
	public function getParent()
	{
		return $this->hasOne(SupportFeedbackSubject::className(), ['subject_id' => 'parent_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getView()
	{
		return $this->hasOne(SupportFeedbackSubjectView::className(), ['subject_id' => 'subject_id']);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\query\SupportFeedbackSubject the active query used by this AR class.
	 */
	public static function find()
	{
		return new \ommu\support\models\query\SupportFeedbackSubject(get_called_class());
	}

	/**
	 * Set default columns to display
	 */
	public function init()
	{
		parent::init();

		if(!(Yii::$app instanceof \app\components\Application))
			return;

		$this->templateColumns['_no'] = [
			'header' => '#',
			'class' => 'yii\grid\SerialColumn',
			'contentOptions' => ['class'=>'center'],
		];
		$this->templateColumns['subjectName'] = [
			'attribute' => 'subjectName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->title) ? $model->title->message : '-';
				// return $model->subjectName;
			},
		];
		$this->templateColumns['parentName'] = [
			'attribute' => 'parentName',
			'value' => function($model, $key, $index, $column) {
				return isset($model->parent) ? $model->parent->title->message : '-';
				// return $model->parentName;
			},
		];
		$this->templateColumns['creation_date'] = [
			'attribute' => 'creation_date',
			'value' => function($model, $key, $index, $column) {
				return Yii::$app->formatter->asDatetime($model->creation_date, 'medium');
			},
			'filter' => $this->filterDatepicker($this, 'creation_date'),
		];
		if(!Yii::$app->request->get('creation')) {
			$this->templateColumns['creationDisplayname'] = [
				'attribute' => 'creationDisplayname',
				'value' => function($model, $key, $index, $column) {
					return isset($model->creation) ? $model->creation->displayname : '-';
					// return $model->creationDisplayname;
				},
			];
		}
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
		$this->templateColumns['feedbacks'] = [
			'attribute' => 'feedbacks',
			'value' => function($model, $key, $index, $column) {
				$feedbacks = $model->getFeedbacks(true);
				return Html::a($feedbacks, ['feedback/admin/manage', 'subject'=>$model->primaryKey, 'publish'=>1], ['title'=>Yii::t('app', '{count} feedbacks', ['count'=>$feedbacks])]);
			},
			'filter' => false,
			'contentOptions' => ['class'=>'center'],
			'format' => 'html',
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
			$model = $model->where(['subject_id' => $id])->one();
			return is_array($column) ? $model : $model->$column;
			
		} else {
			$model = self::findOne($id);
			return $model;
		}
	}

	/**
	 * function getSubject
	 */
	public static function getSubject($publish=null, $array=true) 
	{
		$model = self::find()->alias('t');
		$model->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.subject_name=title.id');
		if($publish != null)
			$model->andWhere(['t.publish' => $publish]);

		$model = $model->orderBy('title.message ASC')->all();

		if($array == true)
			return \yii\helpers\ArrayHelper::map($model, 'subject_id', 'subjectName');

		return $model;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function insertSubject($subjectName)
	{
		$subject = self::find()
			->alias('t')
			->select(['subject_id'])
			->leftJoin(sprintf('%s title', SourceMessage::tableName()), 't.subject_name=title.id')
			->andWhere(['title.message' => $subjectName])
			->one();

		if($subject != null)
				return $subject->subject_id;
		else {
			$model = new self();
			$model->subjectName = $subjectName;
			if($model->save())
				return $model->subject_id;
		}
	}

	/**
	 * after find attributes
	 */
	public function afterFind()
	{
		parent::afterFind();

		$this->subjectName = isset($this->title) ? $this->title->message : '';
		$this->parentName = isset($this->parent) ? $this->parent->title->message : '';
		// $this->creationDisplayname = isset($this->creation) ? $this->creation->displayname : '-';
		// $this->modifiedDisplayname = isset($this->modified) ? $this->modified->displayname : '-';
	}

	/**
	 * before validate attributes
	 */
	public function beforeValidate()
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				if($this->creation_id == null)
					$this->creation_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			} else {
				if($this->modified_id == null)
					$this->modified_id = !Yii::$app->user->isGuest ? Yii::$app->user->id : null;
			}
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	public function beforeSave($insert)
	{
		$module = strtolower(Yii::$app->controller->module->id);
		$controller = strtolower(Yii::$app->controller->id);
		$action = strtolower(Yii::$app->controller->action->id);

		$location = Inflector::slug($module.' '.$controller);

		if(parent::beforeSave($insert)) {
			if($insert || (!$insert && !$this->subject_name)) {
				$subject_name = new SourceMessage();
				$subject_name->location = $location.'_title';
				$subject_name->message = $this->subjectName;
				if($subject_name->save())
					$this->subject_name = $subject_name->id;

			} else {
				$subject_name = SourceMessage::findOne($this->subject_name);
				$subject_name->message = $this->subjectName;
				$subject_name->save();
			}

		}
		return true;
	}
}
