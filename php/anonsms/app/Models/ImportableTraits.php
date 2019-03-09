<?php
namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use League\Csv\Reader; // https://github.com/thephpleague/csv
use App\Libs\Import\ImportStatus;

trait ImportableTraits
{
    protected static $_attrsImportableTraits = [
        'CSV_ROWS_PER_FETCH' => 200,
    ];

    // $headerMap param overrides any default header mapping
    public static function doCsvImport(string $filepath, array $headerMap, $isDebug=true)
    {
        $csvReader = Reader::createFromPath($filepath,'r');
        $importStatus = new ImportStatus(self::getTableName(), 1);

        // Normalize and 'flip' CSV headers...
        //   ~ Result is a map of header names => column index (integer)
        $csvHeaders = ( function( $headersIn ) {
            $_normalized = [];
            foreach ($headersIn as $h) {
                $_normalized[] = self::normalizeHeader($h);
            }
            return array_flip($_normalized);
        } )( $csvReader->fetchOne() );

        $saveLog = [];
        $offset = 1;
        do {

            // Import IN (from CSV file)
            $csvLines = $csvReader->setOffset($offset)->setLimit(self::$_attrsImportableTraits['CSV_ROWS_PER_FETCH'])->fetchAll();
            if ( empty($csvLines) ) {
                break;
            }
            $importStatus->_info(" - processing csv lines ".$offset." to ".($offset+self::$_attrsImportableTraits['CSV_ROWS_PER_FETCH'])."...");

            $offset += self::$_attrsImportableTraits['CSV_ROWS_PER_FETCH'];

            // %NOTE %TODO: For now we only do stores, no updates. To do updates, we need to slugify the csv row and determine if
            // it exists yet in the table or not (this could be problematic if slugs are forced to be unique
            // by adding a number at the end).
            foreach ($csvLines as $csvLine) {

                $attrs = [];
                foreach ($headerMap as $colname => $obj) {

                    try {
                        $attrs[$colname] = ( function($_colname,$_obj, $csvHeaders, $csvLine) {
                            if ( empty($_obj) ) {
                                $val = $csvLine[$csvHeaders[$_colname]]; // Default case
                            } else if ( array_key_exists('xform', $_obj) ) {
                                if ( !array_key_exists('csv_col', $_obj) ) {
                                    throw new \Exception('csv_col attribute is required if column config has xform attribute');
                                }
                                // in this case, csv_col may be an array
                                if ( is_array($_obj['csv_col']) ) {
                                    // array
                                    $vals = [];
                                    foreach ( $_obj['csv_col'] as $k ) {
                                        $vals[$k] = $csvLine[$csvHeaders[$k]];
                                    }
                                    $val = ($_obj['xform'])($vals); // invoke closure
                                } else {
                                    // scalar
                                    if ( !empty($_obj['csv_col']) ) {
                                        $_colname = $_obj['csv_col'];
                                        $val = $csvLine[$csvHeaders[$_colname]];
                                        $val = ($_obj['xform'])($val); // invoke closure
                                    } else { 
                                        // csv_col set to null, just take val from xform w/o argument
                                        $val = ($_obj['xform'])(); // invoke closure
                                    }
                                }
                            } else if ( array_key_exists('csv_col', $_obj) ) {
                                $_colname = $_obj['csv_col'];
                                $val = $csvLine[$csvHeaders[$_colname]];
                            }
                            if ( is_string($val) ) {
                                $val = ('null'===strtolower($val)) ? null : $val; // set to real null if value is 'null' as a string
                            }
                            return $val;
                        } )($colname, $obj, $csvHeaders, $csvLine);
                    } catch (\Exception $e) {
                        $importStatus->pushException($e->getMessage()); // these are actually just warnings
                        //$importStatus->_info("skipping: EXCEPTION: ".$e->getMessage());
                        if ($isDebug) {
                            dump($colname, $obj);
                            throw $e; // DEBUG
                        }
                    } 

                } // foreach ($headerMap)

                //self::validateAttrs($attrs,$rules); // %TODO
                $action = 'CREATE';
                //dump($attrs);
                $obj = self::create($attrs);

                $saveLog[] = json_encode([ 'action'=>$action, 'attrs'=>$attrs ]);
                $importStatus->_info('Saving [obj] with attrs : '.json_encode($attrs));
                $importStatus->pushStat($importStatus->_rowNum, [ 'action'=>$action, 'attrs'=>$attrs ]);

                $importStatus->nextRow();

            } // foreach($csvLines)

        } while (1);

        $importStatus->_info("SUCCESS");

        $importStatus->_info("Print save log...");
        Storage::disk('applog')->put('importer-save.log',  print_r($saveLog, true) );

        $importStatus->_info("Print exception log...");
        $importStatus->printExceptions('applog');

        $importStatus->_info("COMPLETE");

        $csvReader = null;
        unset($importStatus);

    } // doCsvImport()


    protected static function normalizeHeader(string $header) : string
    {
//dump('in', $header, strlen($header), strlen('pm'));
        $header = strtolower(trim($header)); // to lowercase
        $header = preg_replace('[\s+]','-', $header); // replace whitespace with dash
        $header = preg_replace('[\/]','', $header); // remove forward slash
        $header = preg_replace('[\?]','', $header); // remove question mark
        $header = preg_replace('[\']','', $header); // remove Single quote
        $header = preg_replace('/\([^)]+\)/','',$header);  // remove any text within parentheses
        $header = preg_replace('[\\\]','', $header); // remove backward slash
        $header = preg_replace('/-{2,}/','-',$header); // collapse multiple string of dashes
        $header = trim($header,'-');
        //$header = preg_replace('/\x{feff}$/u', '', $header);
        $header = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $header);
        return $header;
    }

}
