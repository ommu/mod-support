<?php
/**
 * SupportMailSetting
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (opensource.ommu.co)
 * @modified date 19 March 2018, 19:52 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_mail_setting".
 *
 * The followings are the available columns in table 'ommu_support_mail_setting':
 * @property integer $id
 * @property string $mail_contact
 * @property string $mail_name
 * @property string $mail_from
 * @property integer $mail_count
 * @property integer $mail_queueing
 * @property integer $mail_smtp
 * @property string $smtp_address
 * @property string $smtp_port
 * @property integer $smtp_authentication
 * @property string $smtp_username
 * @property string $smtp_password
 * @property integer $smtp_ssl
 * @property string $modified_date
 * @property string $modified_id
 */

class SupportMailSetting extends OActiveRecord
{
	public $gridForbiddenColumn = array();

	// Variable Search
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupportMailSetting the static model class
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
		return $matches[1].'.ommu_support_mail_setting';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('mail_contact, mail_name, mail_from, mail_count', 'required'),
			array('mail_count, mail_queueing, mail_smtp, smtp_authentication, smtp_ssl, modified_id', 'numerical', 'integerOnly'=>true),
			array('mail_contact, mail_name, mail_from, smtp_address, smtp_username, smtp_password', 'length', 'max'=>32),
			array('smtp_port', 'length', 'max'=>16),
			array('modified_id', 'length', 'max'=>11),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('mail_contact, mail_name, mail_from, mail_count, mail_queueing, mail_smtp, smtp_address, smtp_port, smtp_authentication, smtp_username, smtp_password, smtp_ssl, modified_date, modified_id, 
				modified_search', 'safe', 'on'=>'search'),
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
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('attribute', 'ID'),
			'mail_contact' => Yii::t('attribute', 'Contact Form Email'),
			'mail_name' => Yii::t('attribute', 'From Name'),
			'mail_from' => Yii::t('attribute', 'From Address'),
			'mail_count' => Yii::t('attribute', 'Mail Count'),
			'mail_queueing' => Yii::t('attribute', 'Email Queue'),
			'mail_smtp' => Yii::t('attribute', 'Send through SMTP'),
			'smtp_address' => Yii::t('attribute', 'SMTP Server Address'),
			'smtp_port' => Yii::t('attribute', 'SMTP Server Port'),
			'smtp_authentication' => Yii::t('attribute', 'SMTP Authentication?'),
			'smtp_username' => Yii::t('attribute', 'SMTP Username'),
			'smtp_password' => Yii::t('attribute', 'SMTP Password'),
			'smtp_ssl' => Yii::t('attribute', 'Use SSL or TLS?'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
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

		// Custom Search
		$criteria->with = array(
			'modified' => array(
				'alias'=>'modified',
				'select'=>'displayname',
			),
		);

		$criteria->compare('t.id', $this->id);
		$criteria->compare('t.mail_contact', strtolower($this->mail_contact), true);
		$criteria->compare('t.mail_name', strtolower($this->mail_name), true);
		$criteria->compare('t.mail_from', strtolower($this->mail_from), true);
		$criteria->compare('t.mail_count', $this->mail_count);
		$criteria->compare('t.mail_queueing', $this->mail_queueing);
		$criteria->compare('t.mail_smtp', $this->mail_smtp);
		$criteria->compare('t.smtp_address', strtolower($this->smtp_address), true);
		$criteria->compare('t.smtp_port', strtolower($this->smtp_port), true);
		$criteria->compare('t.smtp_authentication', $this->smtp_authentication);
		$criteria->compare('t.smtp_username', strtolower($this->smtp_username), true);
		$criteria->compare('t.smtp_password', strtolower($this->smtp_password), true);
		$criteria->compare('t.smtp_ssl', $this->smtp_ssl);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '1970-01-01 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);

		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);

		if(!Yii::app()->getRequest()->getParam('SupportMailSetting_sort'))
			$criteria->order = 't.id DESC';

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
			$this->templateColumns['mail_contact'] = array(
				'name' => 'mail_contact',
				'value' => '$data->mail_contact',
			);
			$this->templateColumns['mail_name'] = array(
				'name' => 'mail_name',
				'value' => '$data->mail_name',
			);
			$this->templateColumns['mail_from'] = array(
				'name' => 'mail_from',
				'value' => '$data->mail_from',
			);
			$this->templateColumns['mail_count'] = array(
				'name' => 'mail_count',
				'value' => '$data->mail_count',
			);
			$this->templateColumns['smtp_address'] = array(
				'name' => 'smtp_address',
				'value' => '$data->smtp_address',
			);
			$this->templateColumns['smtp_port'] = array(
				'name' => 'smtp_port',
				'value' => '$data->smtp_port',
			);
			$this->templateColumns['smtp_username'] = array(
				'name' => 'smtp_username',
				'value' => '$data->smtp_username',
			);
			$this->templateColumns['smtp_password'] = array(
				'name' => 'smtp_password',
				'value' => '$data->smtp_password',
			);
			$this->templateColumns['modified_date'] = array(
				'name' => 'modified_date',
				'value' => '!in_array($data->modified_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\')) ? Utility::dateFormat($data->modified_date) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => 'native-datepicker',
				/*
				'filter' => Yii::app()->controller->widget('application.libraries.core.components.system.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'modified_date',
					'language' => 'en',
					'i18nScriptFile' => 'jquery-ui-i18n.min.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'modified_date_filter',
						'on_datepicker' => 'on',
						'placeholder' => Yii::t('phrase', 'filter'),
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
				*/
			);
			if(!Yii::app()->getRequest()->getParam('modified')) {
				$this->templateColumns['modified_search'] = array(
					'name' => 'modified_search',
					'value' => '$data->modified->displayname ? $data->modified->displayname : \'-\'',
				);
			}
			$this->templateColumns['mail_queueing'] = array(
				'name' => 'mail_queueing',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'mail_queueing\',array(\'id\'=>$data->id)), $data->mail_queueing)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['mail_smtp'] = array(
				'name' => 'mail_smtp',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'mail_smtp\',array(\'id\'=>$data->id)), $data->mail_smtp)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['smtp_authentication'] = array(
				'name' => 'smtp_authentication',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'smtp_authentication\',array(\'id\'=>$data->id)), $data->smtp_authentication)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
			$this->templateColumns['smtp_ssl'] = array(
				'name' => 'smtp_ssl',
				'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'smtp_ssl\',array(\'id\'=>$data->id)), $data->smtp_ssl)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>Yii::t('phrase', 'Yes'),
					0=>Yii::t('phrase', 'No'),
				),
				'type' => 'raw',
			);
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk(1,array(
				'select' => $column
			));
			return $model->$column;
			
		} else {
			$model = self::model()->findByPk(1);
			return $model;
		}
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		if(parent::beforeValidate()) {
			if($this->mail_smtp == '1') {
				if($this->smtp_address == '')
					$this->addError('smtp_address', Yii::t('phrase', 'SMTP Server Address cannot be blank.'));
				
				if($this->smtp_port == '')
					$this->addError('smtp_port', Yii::t('phrase', 'SMTP Server Port cannot be blank.'));
				
				if($this->smtp_authentication == '1') {
					if($this->smtp_username == '')
						$this->addError('smtp_username', Yii::t('phrase', 'SMTP Username cannot be blank.'));
					if($this->smtp_password == '')
						$this->addError('smtp_password', Yii::t('phrase', 'SMTP Password cannot be blank.'));
				}
			}

			$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		parent::afterSave();
		Utility::generateEmailTemplate();
	}


}