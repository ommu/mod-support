<?php
/**
 * m230603_091533_support_module_addView_all
 *
 * @author Putra Sudaryanto <dwptr@ommu.id>
 * @contact (+62)811-2540-432
 * @copyright Copyright (c) 2023 OMMU (www.ommu.id)
 * @created date 3 June 2023, 09:18 WIB
 * @link https://github.com/ommu/mod-support
 *
 */

use yii\db\Schema;

class m230603_091533_support_module_addView_all extends \yii\db\Migration
{
	public function up()
	{
		// alter view _support_feedback_subject
		$alterViewSupportFeedbackSubject = <<< SQL
CREATE VIEW `_support_feedback_subject` AS
select
  `a`.`subject_id` AS `subject_id`,
  sum(case when `b`.`publish` <> 2 then 1 else 0 end) AS `feedbacks`,
  count(`b`.`feedback_id`) AS `feedback_all`
from (`ommu_support_feedback_subject` `a`
   left join `ommu_support_feedbacks` `b`
     on (`a`.`subject_id` = `b`.`subject_id`))
group by `a`.`subject_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_support_feedback_subject`');
		$this->execute($alterViewSupportFeedbackSubject);

		// alter view _support_statistic_feedback_view
		$alterViewSupportStatisticFeedbackView = <<< SQL
CREATE VIEW `_support_statistic_feedback_view` AS
select 
`a`.`feedback_id` AS `feedback_id`,
case when `a`.`view_id` is not null then 1 else 0 end AS `view`,
sum(case when `a`.`publish` = '1' then `a`.`views` else 0 end) AS `views`,
sum(`a`.`views`) AS `view_all`
from `ommu_support_feedback_view` `a`
group by `a`.`feedback_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_support_statistic_feedback_view`');
		$this->execute($alterViewSupportStatisticFeedbackView);

		// alter view _support_feedbacks
		$alterViewSupportFeedbacks = <<< SQL
CREATE VIEW `_support_feedbacks` AS
select
`a`.`feedback_id` AS `feedback_id`,
case when `a`.`reply_message` <> '' or `a`.`replied_id` is not null then 1 else 0 end AS `reply`,
`c`.`view` AS `view`,`c`.`views` AS `views`,`c`.`view_all` AS `view_all`,
sum(case when `b`.`publish` = '1' then 1 else 0 end) AS `users`,
count(`b`.`user_id`) AS `user_all`
from ((`ommu_support_feedbacks` `a`
left join `ommu_support_feedback_user` `b` on(`a`.`feedback_id` = `b`.`feedback_id`))
left join `_support_statistic_feedback_view` `c` on(`a`.`feedback_id` = `c`.`feedback_id`))
group by `a`.`feedback_id`;
SQL;
		$this->execute('DROP VIEW IF EXISTS `_support_feedbacks`');
		$this->execute($alterViewSupportFeedbacks);
	}

	public function down()
	{
		$this->execute('DROP VIEW IF EXISTS `_support_feedback_subject`');
		$this->execute('DROP VIEW IF EXISTS `_support_statistic_feedback_view`');
		$this->execute('DROP VIEW IF EXISTS `_support_feedbacks`');
	}
}
