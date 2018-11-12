<?php
/**
 * SupportFeedbackViewHistory
 *
 * SupportFeedbackViewHistory represents the model behind the search form about `app\modules\support\models\SupportFeedbackViewHistory`.
 *
 * @copyright Copyright (c) 2017 Ommu Platform (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Arifin Avicena <avicenaarifin@gmail.com>
 * @created date 25 September 2017, 14:32 WIB
 * @contact (+62)857-2971-9487
 *
 */

namespace app\modules\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\support\models\SupportFeedbackViewHistory as SupportFeedbackViewHistoryModel;
//use app\modules\support\models\SupportFeedbackView;

class SupportFeedbackViewHistory extends SupportFeedbackViewHistoryModel
{
	// Variable Search	
	public $feedbackView_search;

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['id', 'view_id'], 'integer'],
            [['view_date', 'view_ip',
				'feedbackView_search'], 'safe'],
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
	 * @return ActiveDataProvider
	 */
	public function search($params)
	{
		$query = SupportFeedbackViewHistoryModel::find()->alias('t');
		$query->joinWith(['feedbackView feedbackView']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['feedbackView_search'] = [
			'asc' => ['feedbackView.id' => SORT_ASC],
			'desc' => ['feedbackView.id' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
			'defaultOrder' => ['id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.id' => $this->id,
			't.view_id' => isset($params['feedbackView']) ? $params['feedbackView'] : $this->view_id,
            'cast(t.view_date as date)' => $this->view_date,
        ]);

        $query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
            ->andFilterWhere(['like', 'feedbackView.view_id', $this->feedbackView_search]);

		return $dataProvider;
	}
}
