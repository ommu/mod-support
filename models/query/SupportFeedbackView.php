<?php
/**
 * SupportFeedbackView
 *
 * This is the ActiveQuery class for [[\ommu\support\models\SupportFeedbackView]].
 * @see \ommu\support\models\SupportFeedbackView
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)856-299-4114
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 25 January 2019, 15:13 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\models\query;

class SupportFeedbackView extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 */
	public function published()
	{
		return $this->andWhere(['t.publish' => 1]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function unpublish()
	{
		return $this->andWhere(['t.publish' => 0]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function deleted()
	{
		return $this->andWhere(['t.publish' => 2]);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\SupportFeedbackView[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\SupportFeedbackView|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
