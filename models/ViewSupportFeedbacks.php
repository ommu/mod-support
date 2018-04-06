<?php
/**
 * ViewSupportFeedbacks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (opensource.ommu.co)
 * @created date 16 February 2017, 18:03 WIB
 * @modified date 19 March 2018, 19:53 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "_support_feedbacks".
 *
 * The followings are the available columns in table '_support_feedbacks':
 * @property string $feedback_id
 * @property integer $reply_condition
 * @property integer $view_condition
 * @property string $views
 * @property string $view_all
 * @property string $view_users
 */

class ViewSupportFeedbacks extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewSupportFeedbacks the static model class
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
		return $matches[1].'._support_feedbacks';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'feedback_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('reply_condition, view_condition', 'numerical', 'integerOnly'=>true),
			array('feedback_id', 'length', 'max'=>11),
			array('views, view_all', 'length', 'max'=>32),
			array('view_users', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('feedback_id, reply_condition, view_condition, views, view_all, view_users', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'feedback_id' => Yii::t('attribute', 'Feedback'),
			'reply_condition' => Yii::t('attribute', 'Reply'),
			'view_condition' => Yii::t('attribute', 'View'),
			'views' => Yii::t('attribute', 'Views'),
			'view_all' => Yii::t('attribute', 'View All'),
			'view_users' => Yii::t('attribute', 'View Users'),
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

		$criteria->compare('t.feedback_id', $this->feedback_id);
		$criteria->compare('t.reply_condition', $this->reply_condition);
		$criteria->compare('t.view_condition', $this->view_condition);
		$criteria->compare('t.views', $this->views);
		$criteria->compare('t.view_all', $this->view_all);
		$criteria->compare('t.view_users', $this->view_users);

		if(!Yii::app()->getRequest()->getParam('ViewSupportFeedbacks_sort'))
			$criteria->order = 't.feedback_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>Yii::app()->params['grid-view'] ? Yii::app()->params['grid-view']['pageSize'] : 20,
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
			$this->templateColumns['feedback_id'] = array(
				'name' => 'feedback_id',
				'value' => '$data->feedback_id',
			);
			$this->templateColumns['reply_condition'] = array(
				'name' => 'reply_condition',
				'value' => '$data->reply_condition',
			);
			$this->templateColumns['view_condition'] = array(
				'name' => 'view_condition',
				'value' => '$data->view_condition',
			);
			$this->templateColumns['views'] = array(
				'name' => 'views',
				'value' => '$data->views',
			);
			$this->templateColumns['view_all'] = array(
				'name' => 'view_all',
				'value' => '$data->view_all',
			);
			$this->templateColumns['view_users'] = array(
				'name' => 'view_users',
				'value' => '$data->view_users',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

}