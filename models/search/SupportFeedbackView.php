<?php
/**
 * SupportFeedbackView
 *
 * SupportFeedbackView represents the model behind the search form about `ommu\support\models\SupportFeedbackView`.
 *
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2017 OMMU (www.ommu.id)
 * @created date 25 September 2017, 14:11 WIB
 * @modified date 28 January 2019, 12:20 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use ommu\support\models\SupportFeedbackView as SupportFeedbackViewModel;

class SupportFeedbackView extends SupportFeedbackViewModel
{
	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['view_id', 'publish', 'feedback_id', 'user_id', 'views'], 'integer'],
			[['view_date', 'view_ip', 'updated_date', 'feedbackSubject', 'feedbackEmail', 'feedbackDisplayname', 'feedbackPhone', 'feedbackMessage', 'userDisplayname'], 'safe'],
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
            $query = SupportFeedbackViewModel::find()->alias('t');
        } else {
            $query = SupportFeedbackViewModel::find()->alias('t')
                ->select($column);
        }
		$query->joinWith([
			'feedback feedback', 
			'feedback.subject.title subject', 
			'user user', 
		]);

		$query->groupBy(['view_id']);

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
			'defaultOrder' => ['view_id' => SORT_DESC],
		]);

		$this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

		// grid filtering conditions
		$query->andFilterWhere([
			't.view_id' => $this->view_id,
			't.feedback_id' => isset($params['feedback']) ? $params['feedback'] : $this->feedback_id,
			't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
			't.views' => $this->views,
			'cast(t.view_date as date)' => $this->view_date,
			'cast(t.updated_date as date)' => $this->updated_date,
		]);

        if (isset($params['trash'])) {
            $query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
        } else {
            if (!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == '')) {
                $query->andFilterWhere(['IN', 't.publish', [0,1]]);
            } else {
                $query->andFilterWhere(['t.publish' => $this->publish]);
            }
        }

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
