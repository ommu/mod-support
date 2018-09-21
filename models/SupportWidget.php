<?php
/**
 * SupportWidget
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2016 Ommu Platform (www.ommu.co)
 * @created date 3 February 2016, 12:24 WIB
 * @modified date 21 September 2018, 06:29 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_widget".
 *
 * The followings are the available columns in table 'ommu_support_widget':
 * @property integer $widget_id
 * @property integer $publish
 * @property integer $cat_id
 * @property string $widget_source
 * @property string $creation_date
 * @property integer $creation_id
 * @property string $modified_date
 * @property integer $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportContactCategory $category
 * @property Users $creation
 * @property Users $modified
 */

class SupportWidget extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array('modified_date','modified_search','updated_date');

	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupportWidget the static model class
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
		return $matches[1].'.ommu_support_widget';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('cat_id, widget_source', 'required'),
			array('publish, cat_id, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('publish', 'safe'),
			array('creation_id, modified_id', 'length', 'max'=>11),
			// array('creation_date, modified_date, updated_date', 'trigger'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('widget_id, publish, cat_id, widget_source, creation_date, creation_id, modified_date, modified_id, updated_date,
				creation_search, modified_search', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO, 'SupportContactCategory', 'cat_id'),
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
			'widget_id' => Yii::t('attribute', 'Widget'),
			'publish' => Yii::t('attribute', 'Publish'),
			'cat_id' => Yii::t('attribute', 'Category'),
			'widget_source' => Yii::t('attribute', 'Widget Source'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
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
			'creation' => array(
				'alias' => 'creation',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.widget_id', $this->widget_id);
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
		$criteria->compare('t.cat_id', Yii::app()->getRequest()->getParam('category') ? Yii::app()->getRequest()->getParam('category') : $this->cat_id);
		$criteria->compare('t.widget_source', strtolower($this->widget_source), true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id', Yii::app()->getRequest()->getParam('creation') ? Yii::app()->getRequest()->getParam('creation') : $this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('creation.displayname', strtolower($this->creation_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('SupportWidget_sort'))
			$criteria->order = 't.widget_id DESC';

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
			if(!Yii::app()->getRequest()->getParam('category')) {
				$this->templateColumns['cat_id'] = array(
					'name' => 'cat_id',
					'value' => '$data->category->title->message ? $data->category->title->message : \'-\'',
					'filter' => SupportContactCategory::getCategory(),
				);
			}
			$this->templateColumns['widget_source'] = array(
				'name' => 'widget_source',
				'value' => '$data->widget_source',
				'type' => 'raw',
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
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->widget_id)), $data->publish)',
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
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->isNewRecord)
				$this->creation_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			else {
				$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;

				if($this->widget_source == '')
					$this->addError('widget_source', Yii::t('phrase', '{widget_source} source cannot be blank.', array('{widget_source}'=>$this->category->title->message)));
			}
		}
		return true;
	}
}