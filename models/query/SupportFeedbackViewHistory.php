<?php
/**
 * SupportFeedbackViewHistory
 *
 * This is the ActiveQuery class for [[\ommu\support\models\SupportFeedbackViewHistory]].
 * @see \ommu\support\models\SupportFeedbackViewHistory
 * 
 * @author Putra Sudaryanto <putra@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2019 OMMU (www.ommu.id)
 * @created date 25 January 2019, 15:13 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

namespace ommu\support\models\query;

class SupportFeedbackViewHistory extends \yii\db\ActiveQuery
{
	/*
	public function active()
	{
		return $this->andWhere('[[status]]=1');
	}
	*/

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\SupportFeedbackViewHistory[]|array
	 */
	public function all($db = null)
	{
		return parent::all($db);
	}

	/**
	 * {@inheritdoc}
	 * @return \ommu\support\models\SupportFeedbackViewHistory|array|null
	 */
	public function one($db = null)
	{
		return parent::one($db);
	}
}
