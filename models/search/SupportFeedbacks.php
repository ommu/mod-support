<?php
/**
 * SupportFeedbacks
 *
 * SupportFeedbacks represents the model behind the search form about `app\modules\support\models\SupportFeedbacks`.
 *
 * @copyright Copyright (c) 2017 OMMU (www.ommu.co)
 * @link https://github.com/ommu/mod-support
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @created date 20 September 2017, 13:55 WIB
 * @contact (+62)856-299-4114
 *
 */

namespace ommu\support\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\support\models\SupportFeedbacks as SupportFeedbacksModel;

class SupportFeedbacks extends SupportFeedbacksModel
{
	// Variable Search	
	public $user_search;
	public $modified_search;

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['feedback_id', 'publish', 'user_id', 'modified_id'], 'integer'],
            [['email', 'displayname', 'phone', 'subject_id_id', 'message', 'creation_date', 'modified_date', 'updated_date',
				'user_search', 'modified_search'], 'safe'],
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
		$query = SupportFeedbacksModel::find()->alias('t');
		$query->joinWith(['user user', 'modified modified']);

		// add conditions that should always apply here
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$attributes = array_keys($this->getTableSchema()->columns);
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
			'defaultOrder' => ['feedback_id' => SORT_DESC],
		]);

		$this->load($params);

		if (!$this->validate()) {
			// uncomment the following line if you do not want to return any records when validation fails
			// $query->where('0=1');
			return $dataProvider;
		}

		// grid filtering conditions
		$query->andFilterWhere([
			't.feedback_id' => $this->feedback_id,
			't.publish' => isset($params['publish']) ?$params['publish']: $this->publish,
            't.user_id' => isset($params['user']) ? $params['user'] : $this->user_id,
            'cast(t.creation_date as date)' => $this->creation_date,
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

        $query->andFilterWhere(['like', 't.email', $this->email])
            ->andFilterWhere(['like', 't.displayname', $this->displayname])
            ->andFilterWhere(['like', 't.phone', $this->phone])
            ->andFilterWhere(['like', 't.subject', $this->subject])
            ->andFilterWhere(['like', 't.message', $this->message])
            ->andFilterWhere(['like', 'user.displayname', $this->user_search])
            ->andFilterWhere(['like', 'modified.displayname', $this->modified_search]);

		return $dataProvider;
	}
}
