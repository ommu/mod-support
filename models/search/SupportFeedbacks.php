<?php
/**
 * SupportFeedbacks
 *
 * SupportFeedbacks represents the model behind the search form about `ommu\support\models\SupportFeedbacks`.
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @created date 20 September 2017, 13:55 WIB
 * @modified date 27 January 2019, 09:55 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\support\models\SupportFeedbacks as SupportFeedbacksModel;

class SupportFeedbacks extends SupportFeedbacksModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['feedback_id', 'publish', 'subject_id', 'user_id', 'replied_id', 'modified_id'], 'integer'],
			[['email', 'displayname', 'phone', 'message', 'reply_message', 'replied_date', 'creation_date', 'modified_date', 'updated_date',
				'subjectName', 'userDisplayname', 'repliedDisplayname', 'modifiedDisplayname', 'reply'], 'safe'],
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function scenarios()
	{
		// bypass scenarios() implementation in the parent class
		return Model::scenarios();
	}

	/**
	 * Tambahkan fungsi beforeValidate ini pada model search untuk menumpuk validasi pd model induk. 
	 * dan "jangan" tambahkan parent::beforeValidate, cukup "return true" saja.
	 * maka validasi yg akan dipakai hanya pd model ini, semua script yg ditaruh di beforeValidate pada model induk
	 * tidak akan dijalankan.
	 */
	public function beforeValidate() {
		return true;
	}

	/**
	 * Creates data provider instance with search query applied
	 *
	 * @param array $params
	 *
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = SupportFeedbacksModel::find()->alias('t');
		$query->joinWith([
			'view view', 
			'subject.title subject', 
			'user user', 
			'replied replied', 
			'modified modified'
		]);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
		if(isset($params['pagination']) && $params['pagination'] == 0)
			$dataParams['pagination'] = false;
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['subject_id'] = [
			'asc' => ['subject.message' => SORT_ASC],
			'desc' => ['subject.message' => SORT_DESC],
		];
		$attributes['subjectName'] = [
			'asc' => ['subject.message' => SORT_ASC],
			'desc' => ['subject.message' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['repliedDisplayname'] = [
			'asc' => ['replied.displayname' => SORT_ASC],
			'desc' => ['replied.displayname' => SORT_DESC],
		];
		$attributes['modifiedDisplayname'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$attributes['views'] = [
			'asc' => ['view.views' => SORT_ASC],
			'desc' => ['view.views' => SORT_DESC],
		];
		$attributes['reply'] = [
			'asc' => ['view.reply' => SORT_ASC],
			'desc' => ['view.reply' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['feedback_id' => SORT_DESC],
		]);

		$this->load($params);

		if(!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.feedback_id' => $this->feedback_id,
			't.subject_id' => isset($params['subject']) ? $params['subject'] : $this->subject_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			'cast(t.replied_date as date)' => $this->replied_date,
			't.replied_id' => isset($params['replied']) ? $params['replied'] : $this->replied_id,
			'cast(t.creation_date as date)' => $this->creation_date,
			'cast(t.modified_date as date)' => $this->modified_date,
			't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
			'cast(t.updated_date as date)' => $this->updated_date,
			'view.reply' => isset($params['reply']) ? $params['reply'] : $this->reply,
		]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

		$query->andFilterWhere(['like', 't.email', $this->email])
			->andFilterWhere(['like', 't.displayname', $this->displayname])
			->andFilterWhere(['like', 't.phone', $this->phone])
			->andFilterWhere(['like', 't.message', $this->message])
			->andFilterWhere(['like', 't.reply_message', $this->reply_message])
			->andFilterWhere(['like', 'subject.message', $this->subjectName])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname])
			->andFilterWhere(['like', 'replied.displayname', $this->repliedDisplayname])
			->andFilterWhere(['like', 'modified.displayname', $this->modifiedDisplayname]);

		return $dataProvider;
	}
}
