<?php
/**
 * SupportFeedbacks
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2012 Ommu Platform (www.ommu.co)
 * @modified date 19 March 2018, 19:52 WIB
 * @link https://github.com/ommu/mod-support
 *
 * This is the model class for table "ommu_support_feedbacks".
 *
 * The followings are the available columns in table 'ommu_support_feedbacks':
 * @property string $feedback_id
 * @property integer $publish
 * @property integer $subject_id
 * @property string $user_id
 * @property string $email
 * @property string $displayname
 * @property string $phone
 * @property string $message
 * @property string $reply_message
 * @property string $replied_date
 * @property string $replied_id
 * @property string $creation_date
 * @property string $modified_date
 * @property string $modified_id
 * @property string $updated_date
 *
 * The followings are the available model relations:
 * @property SupportFeedbackUser[] $users
 * @property SupportFeedbackView[] $views
 * @property SupportFeedbackSubject $subject
 * @property Users $user
 * @property Users $replied
 * @property Users $modified
 */

class SupportFeedbacks extends OActiveRecord
{
	use UtilityTrait;
	use GridViewTrait;

	public $gridForbiddenColumn = array('message','reply_message','replied_date','replied_search','modified_date','modified_search','updated_date');
	public $subject_i;

	// Variable Search
	public $user_search;
	public $replied_search;
	public $modified_search;
	public $views_search;
	public $reply_search;
	public $users_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SupportFeedbacks the static model class
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
		return $matches[1].'.ommu_support_feedbacks';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, displayname, message', 'required'),
			array('reply_message', 'required', 'on'=>'replyMessage'),
			array('publish, subject_id', 'numerical', 'integerOnly'=>true),
			array('subject_id', 'length', 'max'=>5),
			array('user_id, replied_id, modified_id', 'length', 'max'=>11),
			array('phone', 'length', 'max'=>15),
			array('email, displayname', 'length', 'max'=>32),
			array('subject_i', 'length', 'max'=>64),
			array('email', 'email'),
			array('subject_id, user_id, displayname, phone,
				subject_i', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('feedback_id, publish, subject_id, user_id, email, displayname, phone, message, reply_message, replied_date, replied_id, creation_date, modified_date, modified_id, updated_date, 
				subject_i, user_search, replied_search, modified_search, views_search, reply_search', 'safe', 'on'=>'search'),
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
			'view' => array(self::BELONGS_TO, 'ViewSupportFeedbacks', 'feedback_id'),
			'users' => array(self::HAS_MANY, 'SupportFeedbackUser', 'feedback_id'),
			'views' => array(self::HAS_MANY, 'SupportFeedbackView', 'feedback_id'),
			'subject' => array(self::BELONGS_TO, 'SupportFeedbackSubject', 'subject_id'),
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),
			'replied' => array(self::BELONGS_TO, 'Users', 'replied_id'),
			'modified' => array(self::BELONGS_TO, 'Users', 'modified_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'feedback_id' => Yii::t('attribute', 'Feedback'),
			'publish' => Yii::t('attribute', 'Publish'),
			'subject_id' => Yii::t('attribute', 'Subject'),
			'user_id' => Yii::t('attribute', 'User'),
			'email' => Yii::t('attribute', 'Email'),
			'displayname' => Yii::t('attribute', 'Name'),
			'phone' => Yii::t('attribute', 'Phone'),
			'message' => Yii::t('attribute', 'Message'),
			'reply_message' => Yii::t('attribute', 'Reply Message'),
			'replied_date' => Yii::t('attribute', 'Replied Date'),
			'replied_id' => Yii::t('attribute', 'Replied'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'updated_date' => Yii::t('attribute', 'Updated Date'),
			'subject_i' => Yii::t('attribute', 'Subject'),
			'user_search' => Yii::t('attribute', 'User'),
			'replied_search' => Yii::t('attribute', 'Replied'),
			'modified_search' => Yii::t('attribute', 'Modified'),
			'views_search' => Yii::t('attribute', 'Views'),
			'reply_search' => Yii::t('attribute', 'Replies'),
			'users_search' => Yii::t('attribute', 'Users'),
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
			'view' => array(
				'alias' => 'view',
			),
			'subject' => array(
				'alias' => 'subject',
				'select' => 'subject_name',
			),
			'subject.title' => array(
				'alias' => 'subject_title',
				'select' => 'message',
			),
			'user' => array(
				'alias' => 'user',
				'select' => 'displayname',
			),
			'replied' => array(
				'alias' => 'replied',
				'select' => 'displayname',
			),
			'modified' => array(
				'alias' => 'modified',
				'select' => 'displayname',
			),
		);

		$criteria->compare('t.feedback_id', $this->feedback_id);
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
		$criteria->compare('t.subject_id', Yii::app()->getRequest()->getParam('subject') ? Yii::app()->getRequest()->getParam('subject') : $this->subject_id);
		$criteria->compare('t.user_id', Yii::app()->getRequest()->getParam('user') ? Yii::app()->getRequest()->getParam('user') : $this->user_id);
		$criteria->compare('t.email', strtolower($this->email), true);
		$criteria->compare('t.displayname', strtolower($this->displayname), true);
		$criteria->compare('t.phone', strtolower($this->phone), true);
		$criteria->compare('t.message', strtolower($this->message), true);
		$criteria->compare('t.reply_message', strtolower($this->reply_message), true);
		if($this->replied_date != null && !in_array($this->replied_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.replied_date)', date('Y-m-d', strtotime($this->replied_date)));
		$criteria->compare('t.replied_id', Yii::app()->getRequest()->getParam('replied') ? Yii::app()->getRequest()->getParam('replied') : $this->replied_id);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.creation_date)', date('Y-m-d', strtotime($this->creation_date)));
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.modified_date)', date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id', Yii::app()->getRequest()->getParam('modified') ? Yii::app()->getRequest()->getParam('modified') : $this->modified_id);
		if($this->updated_date != null && !in_array($this->updated_date, array('0000-00-00 00:00:00','1970-01-01 00:00:00','0002-12-02 07:07:12','-0001-11-30 00:00:00')))
			$criteria->compare('date(t.updated_date)', date('Y-m-d', strtotime($this->updated_date)));

		$criteria->compare('subject_title.message', strtolower($this->subject_i), true);
		if($this->user_id)
			$criteria->compare('user.displayname', strtolower($this->user_search), true);
		else
			$criteria->compare('t.displayname', strtolower($this->user_search), true);
		$criteria->compare('replied.displayname', strtolower($this->replied_search), true);
		$criteria->compare('modified.displayname', strtolower($this->modified_search), true);
		$criteria->compare('view.view_condition', $this->views_search);
		$criteria->compare('view.reply_condition', $this->reply_search);

		if(!Yii::app()->getRequest()->getParam('SupportFeedbacks_sort'))
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
			if(!Yii::app()->getRequest()->getParam('subject')) {
				$this->templateColumns['subject_i'] = array(
					'name' => 'subject_i',
					'value' => '$data->subject_id ? $data->subject->title->message : \'-\'',
				);
			}
			if(!Yii::app()->getRequest()->getParam('user')) {
				$this->templateColumns['user_search'] = array(
					'name' => 'user_search',
					'value' => '$data->user_id ? $data->user->displayname : ($data->displayname != \'\' ? $data->displayname : \'-\')',
				);
			}
			$this->templateColumns['email'] = array(
				'name' => 'email',
				'value' => '$data->email',
			);
			$this->templateColumns['phone'] = array(
				'name' => 'phone',
				'value' => '$data->phone != \'\' ? $data->phone : \'-\'',
			);
			$this->templateColumns['message'] = array(
				'name' => 'message',
				'value' => '$data->message',
			);
			$this->templateColumns['reply_message'] = array(
				'name' => 'reply_message',
				'value' => '$data->reply_message',
			);
			$this->templateColumns['replied_date'] = array(
				'name' => 'replied_date',
				'value' => '!in_array($data->replied_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->replied_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'replied_date'),
			);
			if(!Yii::app()->getRequest()->getParam('replied')) {
				$this->templateColumns['replied_search'] = array(
					'name' => 'replied_search',
					'value' => '$data->replied->displayname ? $data->replied->displayname : \'-\'',
				);
			}
			$this->templateColumns['creation_date'] = array(
				'name' => 'creation_date',
				'value' => '!in_array($data->creation_date, array(\'0000-00-00 00:00:00\', \'1970-01-01 00:00:00\', \'0002-12-02 07:07:12\', \'-0001-11-30 00:00:00\')) ? Yii::app()->dateFormatter->formatDateTime($data->creation_date, \'medium\', false) : \'-\'',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterDatepicker($this, 'creation_date'),
			);
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
			$this->templateColumns['views_search'] = array(
				'name' => 'views_search',
				'value' => '$data->view->view_condition != 0 ? CHtml::link($data->view->views, Yii::app()->controller->createUrl(\'o/views/manage\', array(\'feedback\'=>$data->feedback_id))) : CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\') ',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			$this->templateColumns['users_search'] = array(
				'name' => 'users_search',
				'value' => 'CHtml::link($data->view->view_users ? $data->view->view_users : \'0\', Yii::app()->controller->createUrl(\'o/user/manage\', array(\'feedback\'=>$data->feedback_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' =>false,
				'type' => 'raw',
			);
			$this->templateColumns['reply_search'] = array(
				'name' => 'reply_search',
				'value' => '$data->view->reply_condition != 0 ? CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/publish.png\') : CHtml::link(CHtml::image(Yii::app()->theme->baseUrl.\'/images/icons/unpublish.png\'), Yii::app()->controller->createUrl(\'reply\', array(\'id\'=>$data->feedback_id)))',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => $this->filterYesNo(),
				'type' => 'raw',
			);
			if(!Yii::app()->getRequest()->getParam('type')) {
				$this->templateColumns['publish'] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl(\'publish\', array(\'id\'=>$data->feedback_id)), $data->publish)',
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

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() 
	{
		$action = strtolower(Yii::app()->controller->action->id);
		
		if(parent::beforeValidate()) {
			if(!$this->isNewRecord) {
				if($action == 'edit')
					$this->modified_id = !Yii::app()->user->isGuest ? Yii::app()->user->id : null;
			}
			
			if($this->subject_id == '' && $this->subject_i == '')
				$this->addError('subject_i', Yii::t('phrase', '{attribute} cannot be blank.', array('{attribute}'=>$this->getAttributeLabel('subject_i'))));
		}
		return true;
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() 
	{
		if(parent::beforeSave()) {
			$subjectSlug = $this->urlTitle($this->subject_i);

			if($this->isNewRecord && $this->subject_id == '') {
				$subject = SupportFeedbackSubject::model()->find(array(
					'select' => 'subject_id, subject_name',
					'condition' => 'slug = :slug',
					'params' => array(
						':slug' => $subjectSlug,
					),
				));
				if($subject != null)
					$this->subject_id = $subject->subject_id;
				else {
					$data = new SupportFeedbackSubject;
					$data->subject_name_i = $this->subject_i;
					if($data->save())
						$this->subject_id = $data->subject_id;
				}
			}
		}
		return true;
	}
	
	/**
	 * After save attributes
	 */
	protected function afterSave() 
	{
		Yii::import('ext.phpmailer.Mailer');
		
		$setting = OmmuSettings::model()->findByPk(1, array(
			'select' => 'site_title',
		));
		
		parent::afterSave();
		
		if($this->isNewRecord) {
			// Send Email to Member
			$feedback_search = array(
				'{displayname}','{subject}','{message}','{creation_date}',
			);
			$feedback_replace = array(
				$this->displayname, $this->subject->title->message, $this->message, Yii::app()->dateFormatter->formatDateTime(date('Y-m-d H:i:s'), true),
			);
			$feedback_template = 'support_feedback';
			$feedback_title = Yii::t('phrase', 'Feedback: {subject}', array('{subject}'=>$this->subject->title->message));
			$feedback_file = YiiBase::getPathOfAlias('support.components.templates').'/'.$feedback_template.'.php';
			if(!file_exists($feedback_file))
				$feedback_file = YiiBase::getPathOfAlias('ommu.support.components.templates').'/'.$feedback_template.'.php';
			$feedback_message = Utility::getEmailTemplate(str_ireplace($feedback_search, $feedback_replace, file_get_contents($feedback_file)));
			Mailer::send($this->email, $this->displayname, $feedback_title, $feedback_message);
		}
	}

}