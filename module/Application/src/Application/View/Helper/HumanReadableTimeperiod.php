<?php

/**
 *
 * Barbossa - A Web-Frontend to manage Bareos
 * 
 * @link      http://github.com/fbergkemper/barbossa for the canonical source repository
 * @copyright Copyright (c) 2013-2014 dass-IT GmbH (http://www.dass-it.de/)
 * @license   GNU Affero General Public License (http://www.gnu.org/licenses/)
 * @author    Frank Bergkemper
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * 
 */
class HumanReadableTimeperiod extends AbstractHelper
{

    protected $result;

    /**
     * A function for making timeperiods human readable
     * @method 
     * @return string 
     * @param
     * @param
     */
    public function __invoke($time, $format="short")
    {

	if(empty($time)) {
		return $this->result = "never";
	}
	else {

		$this->result = "-";
		$dateTime = date_create($time); 
		$timestamp = date_format($dateTime, 'U');
		$seconds = time() - $timestamp;

		if($format == "short") {

			$units = array(
				'y' => $seconds / 31556926 % 12, 
				'w' => $seconds / 604800 % 52, 
				'd' => $seconds / 86400 % 7,
				'h' => $seconds / 3600 % 24, 
				'm' => $seconds / 60 % 60, 
				's' => $seconds % 60
			);  

			foreach($units as $key => $value) {
				if($value > 0) {
					$res[] = $value . $key;
				}   
			}   
			
			$this->result = join(' ', $res) . " ago";

		} 		
		elseif($format == "long") {
			
			$units = array(
				'Year(s)' => $seconds / 31556926 % 12,
				'Week(s)' => $seconds / 604800 % 52,
				'Day(s)' => $seconds / 86400 % 7,
				'Hour(s)' => $seconds / 3600 % 24,
				'Minute(s)' => $seconds / 60 % 60,
				'Second(s)' => $seconds % 60
			);

			foreach($units as $key => $value) {
				if($value > 0) {
					$res[] = $value . $key;
				}
			}		
			
			$this->result = join(' ', $res) . " ago";

		}
		elseif($format == "fuzzy") {

			$t1 = explode("-", $time);
			$t2 = explode("-", date("Y-m-d", time("NOW")));

			$d1 = mktime(0, 0, 0, (int)$t1[1],(int)$t1[2],(int)$t1[0]);
			$d2 = mktime(0, 0, 0, (int)$t2[1],(int)$t2[2],(int)$t2[0]);

 			$interval = ($d2 - $d1) / (3600 * 24);

			if($interval < 1) {

/*

STILL BUGGY TEST CODE

				$tmp = explode(" ", $time);
				$t1 = explode(":", $tmp[1]);
				$t2 = explode(":", date("H:i:s", time("NOW")));
				$h1 = $t1[0];
				$h2 = $t2[0];

				if($h1 > $h2) {
					$interval = (24 - $h1) + ($h2);
				}
				else {
					$interval = $h2 - $h1;
				}

				if($interval <= 1) {

					$tmp = explode(" ", $time); 
					$t1 = explode(":", $tmp[1]);
					$t2 = explode(":", date("H:i:s", time("NOW")));
					$m1 = $t1[1];
					$m2 = $t2[1];

					if($m1 > $m2) {
						$interval = (60 - $m1) + ($m2);
					}
					else {
						$interval = $m2 - $m1;
					}
					
					if($interval > 1) {	
						return $this->result = "about " . $interval . " minute(s) ago";
					}
					else {
						return $this->result = " just now";
					}

				}
				elseif($interval < 24) {
					return $this->result = "about " . $interval . " hour(s) ago";
				}
*/				
				return $this->result = "today";

			}
			elseif($interval <= 31 && $interval >= 1) {
				$this->result = "about " . $interval . " day(s) ago";
			}
			elseif($interval >= 31 && $interval <= 365) {
				$interval = round($interval / 31, 0, PHP_ROUND_HALF_UP);
				$this->result = "about " . $interval . " month ago";
			}
			elseif($interval > 365) {
				$interval = round($interval / 12, 1, PHP_ROUND_HALF_UP);
				$this->result = "about " . $interval . " year(s) ago";
			}

		}
		
		return $this->result;

		}

    }

}
