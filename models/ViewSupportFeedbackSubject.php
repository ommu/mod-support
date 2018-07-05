<?php
/**
 * ViewSupportFeedbackSubject
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2018 Ommu Platform (www.ommu.co)
 * @created date 19 March 2018, 15:23 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "_support_feedback_subject".
 *
 * The followings are the available columns in table '_support_feedback_subject':
 * @property integer $subject_id
 * @property string $feedbacks
 * @property string $feedback_all
 */

class ViewSupportFeedbackSubject extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	// Variable Search

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ViewSupportFeedbackSubject the static model class
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
		return $matches[1].'._support_feedback_subject';
	}

	/**
	 * @return string the primarykey column
	 */
	public function primaryKey()
	{
		return 'subject_id';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('subject_id', 'numerical', 'integerOnly'=>true),
			array('feedbacks', 'length', 'max'=>23),
			array('feedback_all', 'length', 'max'=>21),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('subject_id, feedbacks, feedback_all', 'safe', 'on'=>'search'),
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
			'subject_id' => Yii::t('attribute', 'Subject'),
			'feedbacks' => Yii::t('attribute', 'Feedbacks'),
			'feedback_all' => Yii::t('attribute', 'Feedback All'),
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

		$criteria->compare('t.subject_id', $this->subject_id);
		$criteria->compare('t.feedbacks', $this->feedbacks);
		$criteria->compare('t.feedback_all', $this->feedback_all);

		if(!Yii::app()->getRequest()->getParam('ViewSupportFeedbackSubject_sort'))
			$criteria->order = 't.subject_id DESC';

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
			$this->templateColumns['subject_id'] = array(
				'name' => 'subject_id',
				'value' => '$data->subject_id',
			);
			$this->templateColumns['feedbacks'] = array(
				'name' => 'feedbacks',
				'value' => '$data->feedbacks',
			);
			$this->templateColumns['feedback_all'] = array(
				'name' => 'feedback_all',
				'value' => '$data->feedback_all',
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

}