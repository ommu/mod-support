<?php
/**
 * SupportFeedbackViewHistory
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @created date 23 August 2017, 17:19 WIB
 * @modified date 27 September 2018, 12:35 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedback_view_history".
 *
 * The followings are the available columns in table 'ommu_support_feedback_view_history':
 * @property integer $id
 * @property integer $view_id
 * @property string $view_date
 * @property string $view_ip
 *
 * The followings are the available model relations:
 * @property SupportFeedbackView $view
 */

class SupportFeedbackViewHistory extends OActiveRecord
{
	use GridViewTrait;

	public $gridForbiddenColumn = array();

	// Variable Search
	public $subject_search;
	public $feedback_search;
	public $user_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupportFeedbackViewHistory the static model class
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
		return $matches[1].'.ommu_support_feedback_view_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('view_id, view_date, view_ip', 'required'),
			array('view_id', 'numerical', 'integerOnly'=>true),
			array('view_id', 'length', 'max'=>11),
			array('view_ip', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, view_id, view_date, view_ip,
				subject_search, feedback_search, user_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'SupportFeedbackView', 'view_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'view_id' => Yii::t('attribute', 'View'),
			'view_date' => Yii::t('attribute', 'View Date'),
			'view_ip' => Yii::t('attribute', 'View Ip'),
			'subject_search' => Yii::t('attribute', 'Subject'),
			'feedback_search' => Yii::t('attribute', 'Feedback'),
			'user_search' => Yii::t('attribute', 'View By'),
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
				'select' => 'feedback_id, user_id',
			),
			'view.feedback' => array(
				'alias' => 'feedback',
				'select' => 'subject_id, user_id, email, displayname, message',
			),
			'view.feedback.subject.title' => array(
				'alias' => 'subjectTitle',
				'select' => 'message',
			),
			'view.user' => array(
				'alias' => 'user',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.view_id', Yii::app()->getRequest()->getParam('view') ? Yii::app()->getRequest()->getParam('view') : $this->view_id);
		if($this->view_date != null && !in_array($this->view_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.view_date)', date('Y-m-d', strtotime($this->view_date)));
		$criteria->compare('t.view_ip', strtolower($this->view_ip), true);

		$criteria->compare('subjectTitle.message', strtolower($this->subject_search), true);			//view.feedback.subject.title.message
		$criteria->compare('feedback.message', strtolower($this->feedback_search), true);			//view.feedback.message
		$criteria->compare('user.displayname', strtolower($this->user_search), true);			//view.user.displayname

		if(!Yii::app()->getRequest()->getParam('SupportFeedbackViewHistory_sort'))
			$criteria->order = 't.id DESC';

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
			if(!Yii::app()->getRequest()->getParam('view')) {
				$this->templateColumns['subject_search'] = array(
					'name' => 'subject_search',
					'value' => '$data->view->feedback->subject->title->message',
				);
				$this->templateColumns['feedback_search'] = array(
					'name' => 'feedback_search',
					'value' => '$data->view->feedback->message',
				);
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->view->user->displayname',
				);
			}
			$this->templateColumns['view_date'] = array(
				'name' => 'view_date',
				'value' => '!in_array($data->view_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->view_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'view_date'),
			);
			$this->templateColumns['view_ip'] = array(
				'name' => 'view_ip',
				'value' => '$data->view_ip',
			);
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
			$this->view_ip = $_SERVER['REMOTE_ADDR'];
		}
		return true;
	}
}