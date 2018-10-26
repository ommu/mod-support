<?php
/**
 * SupportFeedbackView
 *
 * SupportFeedbackView represents the model behind the search form about `app\modules\support\models\SupportFeedbackView`.
 *
 * @copyright Copyright (c) 2017 ECC UGM (ecc.ft.ugm.ac.id)
 * @link http://ecc.ft.ugm.ac.id
 * @author Arifin Avicena <avicenaarifin@gmail.com>
 * @created date 25 September 2017, 14:11 WIB
 * @contact (+62)857-2971-9487
 *
 */

namespace app\modules\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\support\models\SupportFeedbackView as SupportFeedbackViewModel;
//use app\modules\support\models\SupportFeedbacks;
//use app\modules\user\models\Users;

class SupportFeedbackView extends SupportFeedbackViewModel
{
	// Variable Search	
	public $feedbacks_search;
	public $user_search;
	public $modified_search;

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['view_id', 'publish', 'feedback_id', 'user_id', 'views', 'modified_id'], 'integer'],
            [['view_date', 'view_ip', 'modified_date', 'updated_date',
				'feedbacks_search', 'user_search', 'modified_search'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
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
		$query = SupportFeedbackViewModel::find()->alias('t');
		$query->joinWith(['feedbacks feedbacks', 'user user', 'modified modified']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
		$attributes['feedbacks_search'] = [
			'asc' => ['feedbacks.displayname' => SORT_ASC],
			'desc' => ['feedbacks.displayname' => SORT_DESC],
		];
		$attributes['user_search'] = [
			'asc' => ['user.displayname' => SORT_ASC],
			'desc' => ['user.displayname' => SORT_DESC],
		];
		$attributes['modified_search'] = [
			'asc' => ['modified.displayname' => SORT_ASC],
			'desc' => ['modified.displayname' => SORT_DESC],
		];
		$dataProvider->setSort([
			'attributes' => $attributes,
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
			't.feedback_id' => isset($params['feedbacks']) ? $params['feedbacks'] : $this->feedback_id,
            't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
            't.views' => $this->views,
            'cast(t.view_date as date)' => $this->view_date,
            'cast(t.modified_date as date)' => $this->modified_date,
            't.modified_id' => isset($params['modified']) ? $params['modified'] : $this->modified_id,
            'cast(t.updated_date as date)' => $this->updated_date,
        ]);

		if(isset($params['trash']))
			$query->andFilterWhere(['NOT IN', 't.publish', [0,1]]);
		else {
			if(!isset($params['publish']) || (isset($params['publish']) && $params['publish'] == ''))
				$query->andFilterWhere(['IN', 't.publish', [0,1]]);
			else
				$query->andFilterWhere(['t.publish' => $this->publish]);
		}

        $query->andFilterWhere(['like', 't.view_ip', $this->view_ip])
            ->andFilterWhere(['like', 'feedbacks.displayname', $this->feedbacks_search])
            ->andFilterWhere(['like', 'user.displayname', $this->user_search])
            ->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
