<?php
class App_Globals {

    static public function escape($str)
    {
        return htmlentities(stripslashes($str), ENT_QUOTES, "UTF-8");
    }


	static public function formatDate($date, $showDate = true, $showTime = true)
	{
		if (!$date) {
			return '';
		}

		// +------------------------------------------+
		//  replace milliseconds in input string
		if (preg_match('/(\d+)[^\d](\d+)[^\d](\d+)\s+(\d+)\:(\d+)\:(\d+)/', $date, $m)) {

			if ($showDate) {
				$result = intval($m[3]);

				$result .= ' '.self::formatMonth($m[2], true).' ';
				$result .= self::formatYear($m[1]);
			}

			if ($showTime)
			{
				$result .= ' '.$m[4].':'.$m[5];
			}

			return $result;
		}

		return $date;
	}

	/**
	 * Преобразует номер дня недели в его название
	 *
	 * @param $weekDay int Начало с 0
	 * @param $offset int Смещение индекса дня. Если нумерация дня недели начинаеся с 1 то $offset = 1
	 * @param $type int Тип вывода дня недели $type = 1 - полный, $type = 2 - короткий
	 * @return string
	 */
	static public function formatWeekDay($weekDay, $offset = 0, $type = 1){
		$days = array(1=>array(
				0   => 'Понедельник',
				1	=> 'Вторник',
				2	=> 'Среда',
				3	=> 'Четверг',
				4	=> 'Пятница',
				5	=> 'Суббота',
				6	=> 'Воскресенье',
			),
			2=>array(
				0   => 'Пн',
				1	=> 'Вт',
				2	=> 'Ср',
				3	=> 'Чт',
				4	=> 'Пт',
				5	=> 'Сб',
				6	=> 'Вс',
			)
		);
		return $days[$type][$weekDay+$offset];
	}

	static public function formatMonth($month, $rod = false)
	{
		$m1 = array(
			1	=> 'Январь',
			2	=> 'Февраль',
			3	=> 'Март',
			4	=> 'Апрель',
			5	=> 'Май',
			6	=> 'Июнь',
			7	=> 'Июль',
			8	=> 'Август',
			9	=> 'Сентябрь',
			10	=> 'Октябрь',
			11	=> 'Ноябрь',
			12	=> 'Декабрь'
		);

		$m2 = array(
			1	=> 'Января',
			2	=> 'Февраля',
			3	=> 'Марта',
			4	=> 'Апреля',
			5	=> 'Мая',
			6	=> 'Июня',
			7	=> 'Июля',
			8	=> 'Августа',
			9	=> 'Сентября',
			10	=> 'Октября',
			11	=> 'Ноября',
			12	=> 'Декабря'
		);

		$month = intval($month);
		if (!$rod) {
			$result = $m1[$month];
		} else {
			$result = $m2[$month];
		}

		return $result;
	}

	static public function formatYear($year)
	{
		return (strlen($year) == 2) ? '20'.$year : $year;
	}

}
