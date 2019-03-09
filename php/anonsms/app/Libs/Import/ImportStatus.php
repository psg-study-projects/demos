<?php
namespace App\Libs\Import;

use Illuminate\Support\Facades\Storage;

class ImportStatus
{
    protected $_exceptions;
    protected $_stats;
    public $_rowNum = null;
    public $_numberOfRows = 0;
    public $_csvtype;

    public function __construct($csvtype, $importqueue = null)
    {
        $this->_exceptions = [];
        $this->_rowNum = 1;
        $this->_csvtype = $csvtype;
        $this->_importQueue = $importqueue;
    }

    // %NOTE: these are actually just warnings
    // %TODO: distinguish between warnings & errors
    public function hasException() : bool
    {
        return count($this->_exceptions) > 0;
    }


    public function hasExceptionByRow(int $rowNum=null) : bool
    {
        $rowNum = empty($rowNum) ? $this->_rowNum : $rowNum;
        $is = (!empty($this->_exceptions[$rowNum])&&count($this->_exceptions[$rowNum])) ? true : false;
        return $is;
    }

    public function nextRow()
    {
        ++$this->_rowNum;
    }

    public function getStats(bool $isJSON=false)
    {
        return $isJSON ? json_encode($this->_stats) : $this->_stats;
    }

    public function pushStat($key,$stat)
    {
        $this->_stats[] = [
            'key' => $key,
            'stat' => $stat,
        ];
    }

    public function getExceptions(bool $isJSON=false)
    {
        return $isJSON ? json_encode($this->_exceptions) : $this->_exceptions;
    }

    public function pushException($message)
    {
        if ( empty($this->_exceptions[$this->_rowNum]) ) {
            $this->_exceptions[$this->_rowNum] = []; // init
        }
        $this->_exceptions[$this->_rowNum][] = ['row'=>$this->_rowNum,'message'=>$message];
    }

    // %TODO: deprecate
    public function toJson()
    {
        $statusArray = [
            'exceptions'=>$this->_exceptions,
            'stats'=>$this->_stats,
        ];
        $json = json_encode($statusArray);
        return $json;
    }

    public function _info($msg,$linefeed=1) {
        $this->pushStat($this->_rowNum, $msg);
        if (\App::runningInConsole()) {
            if ($linefeed) {
                echo $this->_rowNum.': '.$msg."\n";
            } else {
                echo $this->_rowNum.': '.$msg;
            }
        }
    }

    public function printExceptions($disk=null)
    {
        if ( is_null($disk) ) {
            Storage::put('importer-error.log',  'Total error #: '.count($this->_exceptions) );
            Storage::append('importer-error.log',  print_r($this->_exceptions, true) );
        } else {
            Storage::disk($disk)->put('importer-error.log',  'Total error #: '.count($this->_exceptions) );
            Storage::disk($disk)->append('importer-error.log',  print_r($this->_exceptions, true) );
        }
    }
}
