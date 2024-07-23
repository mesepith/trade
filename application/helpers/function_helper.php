<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('mostFrequent')) {

    /* @author: ZAHIR
     * make find frequent element in an array/
     * https://www.geeksforgeeks.org/frequent-element-array/
     */

    function mostFrequent($arr, $n) {

        // Sort the array 
        sort($arr);
        sort($arr, $n);

        // find the max frequency  
        // using linear traversal 
        $max_count = 1;
        $res = $arr[0];
        $curr_count = 1;
        for ($i = 1; $i < $n; $i++) {
            if ($arr[$i] == $arr[$i - 1])
                $curr_count++;
            else {
                if ($curr_count > $max_count) {
                    $max_count = $curr_count;
                    $res = $arr[$i - 1];
                }
                $curr_count = 1;
            }
        }

        // If last element  
        // is most frequent 
        if ($curr_count > $max_count) {
            $max_count = $curr_count;
            $res = $arr[$n - 1];
        }

        return $res;
    }

}
/*
 * @author: ZAHIR
 * DESC: difference of two dates
 */

function diffOfTwoDates($underlying_date, $expiry_date) {

    $start = new DateTime($underlying_date);

    //$end = new DateTime('2012-09-11');
    $end = new DateTime($expiry_date);
    //        print_r($end);
    // otherwise the  end date is excluded (bug?)
    $end->modify('+1 day');
    //        print_r($end);

    $interval = $end->diff($start);

    // total days
    $days = $interval->days;

    // create an iterateable period of date (P1D equates to 1 day)
    $period = new DatePeriod($start, new DateInterval('P1D'), $end);

    // best stored as array, so you can add more than one
    $holidays = array('2019-12-25');

    foreach ($period as $dt) {
        $curr = $dt->format('D');

        // substract if Saturday or Sunday
        if ($curr == 'Sat' || $curr == 'Sun') {
            $days--;
        }

        // (optional) for the updated question
        elseif (in_array($dt->format('Y-m-d'), $holidays)) {
            $days--;
        }
    }


    return $days;
}

/*
 * Encode Url
 * https://stackoverflow.com/questions/1374753/passing-base64-encoded-strings-in-url
 */

function base64_url_encode($input) {
    return strtr(base64_encode($input), '+/=', '._-');
}

/*
 * Decode Url
 * https://stackoverflow.com/questions/1374753/passing-base64-encoded-strings-in-url
 */

function base64_url_decode($input) {
    return base64_decode(strtr($input, '._-', '+/='));
}

/*
 * Display percentage between differance of two number
 */

function percentOfTwoNumber($first_number, $second_number) {

    try {

        if ($first_number == 0) {
            return 0;
        }

        $step1 = $first_number - $second_number;
        $step2 = ( $step1 / $first_number );
        $step3 = $step2 * 100;

        return number_format($step3, 2);
    } catch (Exception $e) {

        return false;
    }
}

/*
 * Display percentage between differance of two number
 * @author: ChatGpt 4o
 */

 function computeGrowthPercentage($current_value, $first_value) {
    try {
        if ($first_value == 0) {
            throw new Exception('Buying value cannot be zero');
        }

        $step1 = $current_value - $first_value;
        $step2 = ($step1 / $first_value) * 100;

        return number_format($step2, 2);
    } catch (Exception $e) {
        return false;
    }
}


/*
 * Data Calculate Quatarly, Monthly And Weekly wise
 */

function avgReturnCalc($input_data, $tb_column, $tb_date_column) {

    $return = array();

    $quarters = array();
    $months = array();
    $weeks = array();

    foreach ($input_data as $input_data_val) {

        $cur_month = date("m", strtotime($input_data_val->$tb_date_column));
        $cur_quarter = ceil($cur_month / 3);
//        $quarters[date('Y', strtotime($input_data_val->$tb_date_column))][$cur_quarter][$input_data_val->$tb_date_column] = $input_data_val->$tb_column;
        $quarters[date('Y', strtotime($input_data_val->$tb_date_column))][$cur_quarter][$input_data_val->$tb_date_column] = empty($input_data_val->$tb_column) ? 0 : $input_data_val->$tb_column;

        $months[date('Y', strtotime($input_data_val->$tb_date_column))][date('M', strtotime($input_data_val->$tb_date_column))][$input_data_val->$tb_date_column] = empty($input_data_val->$tb_column) ? 0 : $input_data_val->$tb_column;
        $weeks[date('Y', strtotime($input_data_val->$tb_date_column))][date('M', strtotime($input_data_val->$tb_date_column))][date("W", strtotime($input_data_val->$tb_date_column))][$input_data_val->$tb_date_column] = empty($input_data_val->$tb_column) ? 0 : $input_data_val->$tb_column;
    }

//        $avg_data = calcAvgofQuarterMonthWeek( $quarters, $months, $weeks );

    /* Get Average Value from quarter Start */

    $quarter_val = array();

    foreach ($quarters AS $quarter_year => $quarter_first_key_val) {

        foreach ($quarter_first_key_val AS $quarter_no => $quarter_date_n_val) {

            $quarter_sum = 0;

            foreach ($quarter_date_n_val AS $date => $val) {

                $quarter_sum = $quarter_sum + $val;
            }

            $quarter_val[$quarter_year][$quarter_no] = ( $quarter_sum / count($quarter_date_n_val) );
        }
    }

    /* Get Average Value from quarter End */

    /* Get Average Value from Month Start */

    $month_val = array();

    foreach ($months AS $months_year => $months_first_key_val) {

        foreach ($months_first_key_val AS $month_name => $month_date_n_val) {

            $month_sum = 0;

            foreach ($month_date_n_val AS $date => $val_month) {

                $month_sum = $month_sum + $val_month;
            }
            $month_val[$months_year][$month_name] = ( $month_sum / count($month_date_n_val) );
        }
    }

    /* Get Average Value from Month End */

    /* Get Average Value from Weeks Start */

    $week_val = array();

    foreach ($weeks AS $weeks_year => $weeks_first_key_val) {

        foreach ($weeks_first_key_val AS $month_name => $weeks_second_key_val) {

            foreach ($weeks_second_key_val AS $week_no => $week_date_n_val) {

                $week_sum = 0;

                foreach ($week_date_n_val AS $date => $val_week) {

                    $week_sum = $week_sum + $val_week;
                }

                $week_val[$weeks_year][$month_name][$week_no] = ( $week_sum / count($week_date_n_val) );
            }
        }
    }

    /* Get Average Value from Weeks End */


    $return['quarter'] = $quarter_val;
    $return['month'] = $month_val;
    $return['week'] = $week_val;

//    echo '<pre>'; print_r($return); exit;

    return $return;
}

/*
 * Calc Average by days
 */

function calcAveragesByDays($input_data, $calc_avg_by_arr, $tb_column_arr) {

    foreach ($calc_avg_by_arr AS $calc_avg_by_arr_val) {

        foreach ($tb_column_arr AS $tb_column_arr_val) {

            foreach ($input_data as $input_data_key => $input_data_val) {

                $sum = 0;

                for ($i = ($calc_avg_by_arr_val - 1); $i >= 0; $i--) {

                    if (empty($input_data[$input_data_key - $i])) {
                        break;
                    } else {

                        $sum = floatval($sum) + $input_data[$input_data_key - $i]->$tb_column_arr_val . "<br/>";

                        if ($i == 0) {

                            $avg_data = floatval($sum) / $calc_avg_by_arr_val;

                            $avg_arr[$calc_avg_by_arr_val][$tb_column_arr_val][] = $avg_data;
                        }
                    }
                }
            }
        }
    }

//    echo '<pre>';
//    print_r($avg_arr);

    return $avg_arr;
}

function date_sort($a, $b) {
    return strtotime($a) - strtotime($b);
}

/*
 * function to check if date is valid date
 */

function validateDate($date, $format = 'd-M-Y') {
    $d = DateTime::createFromFormat($format, $date);
    // The Y ( 4 digits year ) returns TRUE for any integer with any number of digits so changing the comparison from == to === fixes the issue.
    return $d && $d->format($format) === $date;
}

/**
 * @author: ZAHIR
 * Number in Indian Format, i.e display number in comma separeted
 */
function indianNumberFormat($value){

    $fmt = new NumberFormatter('en_IN', NumberFormatter::DECIMAL);
    echo $fmt->format($value);

}
