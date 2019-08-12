<?php
/**
 * SupportFeedbackSubject
 *
 * @author Putra Sudaryanto <putra@ommu.co>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 15 March 2018, 13:59 WIB
 * @modified date 21 September 2018, 11:39 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedback_subject".
 *
 * The followings are the available columns in table 'ommu_support_feedback_subject':
 * @property integer $subject_id
 * @property integer $publish
 * @property integer $parent_id
 * @property integer $subject_name
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 * @property string $slug
 *
 * The followings are the available model relations:
 * @property ViewSupportFeedbackSubject $view
 * @property SupportFeedbacks[] $feedbacks
 * @property SupportFeedbacks[] $feedbackAll
 * @property SourceMessage $title
 * @property Users $creation
 * @property Users $modified
 */

class SupportFeedbackSubject extends OActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;

	public $gridForbiddenColumn = array('modified_date', 'modified_search', 'updated_date', 'slug');
	public $subject_name_i;
	public $feedback_i;

	// Variable Search
	public $parent_search;
	public $creation_search;
	public $modified_search;

	/**
	 * Behaviors for this model
	 */
	public function behaviors() 
	{
		return array(
			'sluggable' => array(
				'class'=>'ext.yii-sluggable.SluggableBehavior',
				'columns' => array('title.message'),
				'unique' => true,
				'update' => true,
			),
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupportFeedbackSubject the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		preg_match("/dbname=([^;]+)/i", $this->dbConnection->connectionString, $matches);
		return $matches[1].'.ommu_support_feedback_subject';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject_name_i', 'required'),
			array('publish, parent_id, subject_name, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('publish, parent_id', 'safe'),
			array('creation_id, modified_id', 'length', 'max'=>11),
			array('subject_name_i', 'length', 'max'=>64),
			// array('creation_date, modified_date, updated_date, slug', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('subject_id, publish, parent_id, subject_name, creation_date, creation_id, modified_date, modified_id, updated_date, slug,
				subject_name_i, feedback_i, parent_search, creation_search, modified_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'view' => array(self::BELONGS_TO, 'ViewSupportFeedbackSubject', 'subject_id'),
			'feedbacks' => array(self::HAS_MANY, 'SupportFeedbacks', 'subject_id', 'on'=>'feedbacks.publish=1'),
			'feedbackAll' => array(self::HAS_MANY, 'SupportFeedbacks', 'subject_id'),
			'title' => array(self::BELONGS_TO, 'SourceMessage', 'subject_name'),
			'parent' => array(self::BELONGS_TO, 'SupportFeedbackSubject', 'parent_id'),
			'creation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'subject_id' => Yii::t('attribute', 'Subject'),
			'publish' => Yii::t('attribute', 'Publish'),
			'parent_id' => Yii::t('attribute', 'Parent'),
			'subject_name' => Yii::t('attribute', 'Subject'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'slug' => Yii::t('attribute', 'Slug'),
			'subject_name_i' => Yii::t('attribute', 'Subject'),
			'feedback_i' => Yii::t('attribute', 'Feedabcks'),
			'parent_search' => Yii::t('attribute', 'Parent'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
		$criteria->with = array(
			'view' => array(
				'alias' => 'view',
			),
			'title' => array(
				'alias' => 'title',
				'select' => 'message',
			),
			'parent' => array(
				'alias' => 'parent',
				'select' => 'subject_name',
			),
			'parent.title' => array(
				'alias' => 'parent_title',
				'select' => 'message',
			),
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.subject_id', $this->subject_id);
		if(Yii::app()->getRequest()->getParam('type') == 'publish')
			$criteria->compare('t.publish', 1);
		elseif(Yii::app()->getRequest()->getParam('type') == 'unpublish')
			$criteria->compare('t.publish', 0);
		elseif(Yii::app()->getRequest()->getParam('type') == 'trash')
			$criteria->compare('t.publish', 2);
		else {
			$criteria->addInCondition('t.publish', array(0,1));
			$criteria->compare('t.publish', $this->publish);
		}
		$criteria->compare('t.parent_id', $this->parent_id);
		$criteria->compare('t.subject_name', $this->subject_name);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation') ? Yii::app()->getRequest()->getParam('creation') : $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));
		$criteria->compare('t.slug', strtolower($this->slug), true);

		$criteria->compare('title.message', strtolower($this->subject_name_i), true);
		$criteria->compare('parent_title.message', strtolower($this->parent_search), true);
		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.feedbacks', $this->feedback_i);

		if(!Yii::app()->getRequest()->getParam('SupportFeedbackSubject_sort'))
			$criteria->order = 't.subject_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 50,
			),
		));
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->templateColumns) == 0) {
			$this->templateColumns['_option'] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			$this->templateColumns['_no'] = array(
				'header' => Yii::t('app', 'No'),
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1',
				'htmlOptions' => array(
					'class' => 'center',
				),
			);
			$this->templateColumns['subject_name_i'] = array(
				'name' => 'subject_name_i',
				'value' => '$data->title->message',
			);
			$this->templateColumns['parent_search'] = array(
				'name' => 'parent_search',
				'value' => '$data->parent_id != 0 ? $data->parent->title->message : \'-\'',
			);
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
			if(!Yii::app()->getRequest()->getParam('creation')) {
				$this->templateColumns['creation_search'] = array(
					'name' => 'creation_search',
					'value' => '$data->creation->displayname ? $data->creation->displayname : \'-\'',
				);
			}
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->modified_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'modified_date'),
			);
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
			$this->templateColumns['updated_date'] = array(
				'name' => 'updated_date',
				'value' => '!in_array($data->updated_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->updated_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'updated_date'),
			);
			$this->templateColumns['slug'] = array(
				'name' => 'slug',
				'value' => '$data->slug',
			);
			$this->templateColumns['feedback_i'] = array(
				'name' => 'feedback_i',
				'value' => 'CHtml::link($data->feedback_i ? $data->feedback_i : 0, Yii::app()->controller->createUrl(\'o/feedback/manage\', array(\'subject\'=>$data->subject_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => false,
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->subject_id)), $data->publish)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter' => $this->filterYesNo(),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * Model get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id, array(
				'select' => $column,
			));
			if(count(explode(',', $column)) == 1)
				return $model->$column;
			else
				return $model;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * function getSubject
	 */
	public static function getSubject($publish=null, $array=true) 
	{
		$criteria=new CDbCriteria;
		if($publish != null)
			$criteria->compare('t.publish', $publish);

		$model = self::model()->findAll($criteria);

		if($array == true) {
			$items = array();
			if($model != null) {
				foreach($model as $key => $val) {
					$items[$val->subject_id] = $val->title->message;
				}
				return $items;
			} else
				return false;
		} else
			return $model;
	}

	/**
	 * This is invoked when a record is populated with data from a find() call.
	 */
	protected function afterFind()
	{
		parent::afterFind();
		$this->subject_name_i = $this->title->message;
		$this->feedback_i = $this->view->feedbacks;

		return true;
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	protected function beforeSave()
	{
		$module = strtolower(Yii::app()->controller->module->id);
		$controller = strtolower(Yii::app()->controller->id);
		$action = strtolower(Yii::app()->controller->action->id);

		$location = $this->urlTitle($module.' '.$controller);
		
		if(parent::beforeSave()) {
			if($this->isNewRecord || (!$this->isNewRecord && !$this->subject_name)) {
				$subject_name=new SourceMessage;
				$subject_name->message = $this->subject_name_i;
				$subject_name->location = $location.'_title';
				if($subject_name->save())
					$this->subject_name = $subject_name->id;

				$this->slug = $this->urlTitle($this->subject_name_i);

			} else {
				$subject_name = SourceMessage::model()->findByPk($this->subject_name);
				$subject_name->message = $this->subject_name_i;
				$subject_name->save();
			}

		}
		return true;
	}
}