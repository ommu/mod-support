<?php
/**
 * SupportFeedbackViewHistory
 *
 * SupportFeedbackViewHistory represents the model behind the search form about `ommu\support\models\SupportFeedbackViewHistory`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 25 September 2017, 14:32 WIB
 * @modified date 28 January 2019, 14:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\support\models\SupportFeedbackViewHistory as SupportFeedbackViewHistoryModel;

class SupportFeedbackViewHistory extends SupportFeedbackViewHistoryModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'view_id'], 'integer'],
			[['view_date', 'view_ip', 'feedbackSubject', 'feedbackEmail', 'feedbackDisplayname', 'feedbackPhone', 'feedbackMessage', 'userDisplayname'], 'safe'],
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
	public function search($params, $column=null)
	{
        if (!($column && is_array($column))) {
            $query = SupportFeedbackViewHistoryModel::find()->alias('t');
        } else {
            $query = SupportFeedbackViewHistoryModel::find()->alias('t')->select($column);
        }
		$query->joinWith([
			'view.feedback feedback',
			'view.feedback.subject.title subject',
			'view.user user',
		])
		->groupBy(['id']);

		// add conditions that should always apply here
		$dataParams = [
			'query' => $query,
		];
		// disable pagination agar data pada api tampil semua
        if (isset($params['pagination']) && $params['pagination'] == 0) {
            $dataParams['pagination'] = false;
        }
		$dataProvider = new ActiveDataProvider($dataParams);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['feedbackSubject'] = [
			'asc' => ['subject.message' => SORT_ASC],
			'desc' => ['subject.message' => SORT_DESC],
		];
		$attributes['feedbackEmail'] = [
			'asc' => ['feedback.email' => SORT_ASC],
			'desc' => ['feedback.email' => SORT_DESC],
		];
		$attributes['feedbackDisplayname'] = [
			'asc' => ['feedback.displayname' => SORT_ASC],
			'desc' => ['feedback.displayname' => SORT_DESC],
		];
		$attributes['feedbackPhone'] = [
			'asc' => ['feedback.phone' => SORT_ASC],
			'desc' => ['feedback.phone' => SORT_DESC],
		];
		$attributes['feedbackMessage'] = [
			'asc' => ['feedback.message' => SORT_ASC],
			'desc' => ['feedback.message' => SORT_DESC],
		];
		$attributes['userDisplayname'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

        if (Yii::$app->request->get('id')) {
            unset($params['id']);
        }
		$this->load($params);

        if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.view_id' => isset($params['view']) ? $params['view'] : $this->view_id,
			'cast(t.view_date as date)' => $this->view_date,
		]);

		$query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
			->andFilterWhere(['like', 'subject.message', $this->feedbackSubject])
			->andFilterWhere(['like', 'feedback.email', $this->feedbackEmail])
			->andFilterWhere(['like', 'feedback.displayname', $this->feedbackDisplayname])
			->andFilterWhere(['like', 'feedback.phone', $this->feedbackPhone])
			->andFilterWhere(['like', 'feedback.message', $this->feedbackMessage])
			->andFilterWhere(['like', 'user.displayname', $this->userDisplayname]);

		return $dataProvider;
	}
}
