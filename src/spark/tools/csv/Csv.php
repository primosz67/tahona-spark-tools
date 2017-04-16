<?php

namespace spark\tools\csv;

use spark\utils\Asserts;
use spark\utils\Collections;
use spark\utils\StringUtils;

class Csv {

    private $fileName = "file.csv";

    private $rows = array();

    /**
     * Csv constructor.
     * @param $fileName
     */
    public function __construct($fileName) {
        $this->fileName = $fileName;
    }

    public function addRow(CsvRow $row) {
        Asserts::notNull($row);
        $this->rows[Collections::size($this->rows)] = $row;
    }


    function join($fields, $delimiter = ',', $enclosure = '"', $escape_char = '\\' ) {
//        foreach ($fields as &$field) {
//            $field = str_replace($enclosure, $escape_char.$enclosure, $field);
//            $field = $enclosure . $field . $enclosure;
//        }
        return StringUtils::join($delimiter, $fields) . "\n";
    }

    public function build() {
        $this->prepareRequestHeader();

        $fp = fopen("php://output", 'w');
        foreach ($this->rows as $fields) {
            fwrite($fp, $this->join($fields->getData()));
        }

        fclose($fp);
        exit;
    }

    private function prepareRequestHeader() {
//      Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment;filename="' . $this->fileName . '"');
        header('Cache-Control: max-age=0');
//      If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

//      If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
    }
}