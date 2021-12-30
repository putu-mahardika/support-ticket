<?php

namespace App\Helpers;

use App\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class FunctionHelper {

    public const IMAGES_EXT = ['jpg', 'JPG', 'jpeg', 'JPEG', 'gif', 'GIF', 'png', 'PNG'];
    public const WORDS_EXT = ['doc', 'DOC', 'docx', 'DOCX', 'odt', 'ODT'];
    public const EXCELS_EXT = ['xls', 'XLS', 'xlsx', 'XLSX', 'ods', 'ODS'];
    public const PDF_EXT = ['pdf', 'PDF'];
    public const COMPRESSES_EXT = ['rar', 'RAR', 'zip', 'ZIP', '7z', '7Z'];

    /**
     * Truncate string in the middle
     *
     * @param string $text
     * @param integer $length
     * @param string $separator
     * @return string
     **/
    public static function substrMiddle($text, $length = 15, $separator = '...')
    {
        $maxlength = $length - strlen($separator);
        $start = $maxlength / 2 ;
        $trunc =  strlen($text) - $maxlength;
        return substr_replace($text, $separator, $start, $trunc);
    }

    public static function addMinuteColumn($seconds)
    {
        $minute = $seconds == null ? 0 : $seconds/60;
        return $minute;
    }

    public static function getDayName($names)
    {
        $data = [];
        for($i=0;$i<=6;$i++){
            switch ($names[$i]){
                case "Sunday" :
                    array_push($data, 'Minggu');
                    break;
                case "Monday" :
                    array_push($data, 'Senin');
                    break;
                case "Tuesday" :
                    array_push($data, 'Selasa');
                    break;
                case "Wednesday" :
                    array_push($data, 'Rabu');
                    break;
                case "Thursday" :
                    array_push($data, 'Kamis');
                    break;
                case "Friday" :
                    array_push($data, 'Jumat');
                    break;
                case "Saturday" :
                    array_push($data, 'Sabtu');
                    break;
            }
        }
        return $data;
    }

    /**
     * Get type of variable. returnable as class name
     *
     * @param mixed $var Variable to be checked
     * @param bool $getClass Get class name if exist
     * @return string
     **/
    public static function varIs($var, $getClass = false)
    {
        $result = gettype($var);
        try {
            if ($getClass && $result === "object") {
                $result = get_class($var);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $result;
    }

    public static function generateTicketCode($project_id)
    {
        $lastCode = Ticket::with('project')
                          ->where('project_id', $project_id)
                          ->whereYear('created_at', now()->year)
                          ->latest()
                          ->first();
        $newNum = empty($lastCode) ?
                    1 : intval(explode('.', $lastCode->code)[2]) + 1;
        return $lastCode->project->code . '.' . now()->format('my') . '.' . Str::padLeft($newNum, 4, '0');
    }

    public static function floor_work_duration($time){
        $data = floor($time/3600) . ' jam ' . floor(($time/60)%60) . ' menit';
        return $data;
    }

    public static function progressBar($done, $total, $size = 30) {

        static $start_time;

        // if we go over our bound, just ignore it
        if($done > $total) return;

        if(empty($start_time)) $start_time=time();
        $now = time();

        $perc=(double)($done/$total);

        $bar=floor($perc*$size);

        $status_bar="\r[";
        $status_bar.=str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="] $disp%  $done/$total";

        $rate = ($now-$start_time)/$done;
        $left = $total - $done;
        $eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        $status_bar.= " remaining: ".number_format($eta)." sec.  elapsed: ".number_format($elapsed)." sec.";

        echo "$status_bar  ";

        flush();

        // when done, send a newline
        if($done == $total) {
            echo "\n";
        }
    }

    public static function dxFilterGenerator(Builder $query, $dxFilter)
    {
        dd($dxFilter);
        $filter = eval('return' . $dxFilter . ';');

        $filterArr = explode('.', $filter[0]);
    }
}
