<?php
class HelpFunction {

    public static function changeTitle($str,$strSymbol='-',$case=MB_CASE_LOWER){// MB_CASE_UPPER / MB_CASE_TITLE / MB_CASE_LOWER

        $str=trim($str);
    
        if ($str=="") return "";
    
        $str = str_replace('"','',$str);
    
        $str = str_replace("'",'',$str);
    
        $str = self::stripUnicode($str);
    
        $str = mb_convert_case($str,$case,'utf-8');
    
        $str = preg_replace('/[\W|_]+/',$strSymbol,$str);
    
        return $str;
    
    }
    
    
    
    public static function stripUnicode($str){
    
        if(!$str) return '';
    
        //$str = str_replace($a, $b, $str);
    
        $unicode = array(
    
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|å|ä|æ|ā|ą|ǻ|ǎ',
    
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Å|Ä|Æ|Ā|Ą|Ǻ|Ǎ',
    
            'ae'=>'ǽ',
    
            'AE'=>'Ǽ',
    
            'c'=>'ć|ç|ĉ|ċ|č',
    
            'C'=>'Ć|Ĉ|Ĉ|Ċ|Č',
    
            'd'=>'đ|ď',
    
            'D'=>'Đ|Ď',
    
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|ë|ē|ĕ|ę|ė',
    
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Ë|Ē|Ĕ|Ę|Ė',
    
            'f'=>'ƒ',
    
            'F'=>'',
    
            'g'=>'ĝ|ğ|ġ|ģ',
    
            'G'=>'Ĝ|Ğ|Ġ|Ģ',
    
            'h'=>'ĥ|ħ',
    
            'H'=>'Ĥ|Ħ',
    
            'i'=>'í|ì|ỉ|ĩ|ị|î|ï|ī|ĭ|ǐ|į|ı',	  
    
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị|Î|Ï|Ī|Ĭ|Ǐ|Į|İ',
    
            'ij'=>'ĳ',	  
    
            'IJ'=>'Ĳ',
    
            'j'=>'ĵ',	  
    
            'J'=>'Ĵ',
    
            'k'=>'ķ',	  
    
            'K'=>'Ķ',
    
            'l'=>'ĺ|ļ|ľ|ŀ|ł',	  
    
            'L'=>'Ĺ|Ļ|Ľ|Ŀ|Ł',
    
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ö|ø|ǿ|ǒ|ō|ŏ|ő',
    
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ö|Ø|Ǿ|Ǒ|Ō|Ŏ|Ő',
    
            'Oe'=>'œ',
    
            'OE'=>'Œ',
    
            'n'=>'ñ|ń|ņ|ň|ŉ',
    
            'N'=>'Ñ|Ń|Ņ|Ň',
    
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|û|ū|ŭ|ü|ů|ű|ų|ǔ|ǖ|ǘ|ǚ|ǜ',
    
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Û|Ū|Ŭ|Ü|Ů|Ű|Ų|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ',
    
            's'=>'ŕ|ŗ|ř',
    
            'R'=>'Ŕ|Ŗ|Ř',
    
            's'=>'ß|ſ|ś|ŝ|ş|š',
    
            'S'=>'Ś|Ŝ|Ş|Š',
    
            't'=>'ţ|ť|ŧ',
    
            'T'=>'Ţ|Ť|Ŧ',
    
            'w'=>'ŵ',
    
            'W'=>'Ŵ',
    
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|ÿ|ŷ',
    
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ|Ÿ|Ŷ',
    
            'z'=>'ź|ż|ž',
    
            'Z'=>'Ź|Ż|Ž'
    
        );
    
        foreach($unicode as $khongdau=>$codau) {
    
            $arr=explode("|",$codau);
    
            $str = str_replace($arr,$khongdau,$str);
    
        }
    
        return $str;
    
    }

    public static function convert($number) {
        $hyphen      = ' ';
        $conjunction = '  ';
        $separator   = ' ';
        $negative    = 'âm ';
        $decimal     = ' phẩy ';
        $dictionary  = array(
            0                   => 'không',
            1                   => 'một',
            2                   => 'hai',
            3                   => 'ba',
            4                   => 'bốn',
            5                   => 'năm',
            6                   => 'sáu',
            7                   => 'bảy',
            8                   => 'tám',
            9                   => 'chín',
            10                  => 'mười',
            11                  => 'mười một',
            12                  => 'mười hai',
            13                  => 'mười ba',
            14                  => 'mười bốn',
            15                  => 'mười năm',
            16                  => 'mười sáu',
            17                  => 'mười bảy',
            18                  => 'mười tám',
            19                  => 'mười chín',
            20                  => 'hai mươi',
            30                  => 'ba mươi',
            40                  => 'bốn mươi',
            50                  => 'năm mươi',
            60                  => 'sáu mươi',
            70                  => 'bảy mươi',
            80                  => 'tám mươi',
            90                  => 'chín mươi',
            100                 => 'trăm',
            1000                => 'nghìn',
            1000000             => 'triệu',
            1000000000          => 'tỷ',
            1000000000000       => 'nghìn tỷ',
            1000000000000000    => 'nghìn triệu triệu',
            1000000000000000000 => 'tỷ tỷ'
        );
        if (!is_numeric($number)) {
            return false;
        }
        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }
        if ($number < 0) {
            return $negative . convert(abs($number));
        }
        $string = $fraction = null;
        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }
        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::convert($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = self::convert($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= self::convert($remainder);
                }
                break;
        }
        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }
        return $string;
    }

    public static function setDate($string) {
        if ($string != null) {
            $arr =  explode("-", $string);
            $_day = $arr[2];
            $_month = $arr[1];
            $_year = $arr[0];
            return $_day . "-" . $_month . "-" . $_year;
        }
    }

    public static function revertMonth($date = '2020-01') {
        if ($date == null) return "";
        $arr = explode('-', $date);
        return $arr[1] . '-' . $arr[0];
    }

    public static function revertDate($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode('-', $date);
        return $arr[2] . '-' . $arr[1] . '-' . $arr[0];
    }

    public static function getMonthInDay($date = '01-01-2021') {
        if ($date == null) return "";
        $arr = explode('-', $date);
        return $arr[1] . '-' . $arr[2];
    }

    public static function revertTimeInput($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode('T', $date);
        $newDate = $arr[0];
        $_time = $arr[1];
        return $_time . ' ' . self::revertDate($newDate);
    }

    public static function revertCreatedAt($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode(' ', $date);
        $newDate = $arr[0];
        $_time = $arr[1];
        return $_time . ' ' . self::revertDate($newDate);
    }

    public static function revertCreatedAtGetTime($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode(' ', $date);
        $newDate = $arr[0];
        $_time = $arr[1];
        return $_time;
    }

    public static function getDateRevertCreatedAt($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode(' ', $date);
        $newDate = $arr[0];
        $_time = $arr[1];
        return self::revertDate($newDate);
    }

    public static function getDateCreatedAt($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode(' ', $date);
        $newDate = $arr[0];
        $arr2 = explode('-', $newDate);
        return $arr2[1] . "/" . $arr2[0];
    }

    public static function getDateCreatedAtRevert($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode(' ', $date);
        $newDate = $arr[0];
        $arr2 = explode('-', $newDate);
        return $arr2[0] . "" . $arr2[1];
    }

    public static function getArrCreatedAt($date = '2020-01-01') {
        if ($date == null) return "";
        $arr = explode(' ', $date);
        $newDate = $arr[0];
        $arr2 = explode('-', $newDate);
        return $arr2;
    }

    public static function countDayInMonth($month = 1, $year = 2020) {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
        // return date('t', mktime(0, 0, 0, $month, 1, $year)); 
    }

    public static function isSunday($day, $month, $year) {
        $date = date($year."-".$month."-".$day);
        $date = strtotime($date);
        $date = getdate($date);
        if ($date['weekday'] == "Sunday") return true;
        return false;
    }
}
?>
