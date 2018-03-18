<?php

namespace Spark\Tools\Excel;

use PHPExcel;
use PHPExcel_IOFactory;
use Spark\Utils\Asserts;
use Spark\Utils\Objects;

class ExcelPHP {

    const START_INDEX_FOR_CELL = 0;
    const START_INDEX_FOR_ROW = 1;

    private $sheetTitle = "sheet 1";
    private $fileName = "defaultName.xlsx";

    private $arrayRows = array();

    function __construct($fileName) {
        $this->fileName = $fileName;
    }

    public function addRow($row) {
        Asserts::checkArray($row);
        $this->arrayRows[] = $row;
    }

    public function build() {
        $objPHPExcel = new PHPExcel();
        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);

        $rowIndex = self::START_INDEX_FOR_ROW;
        foreach ($this->arrayRows as $row) {
            $cellIndex = self::START_INDEX_FOR_CELL;
            foreach ($row as $cell) {
                $activeSheet->setCellValueByColumnAndRow($cellIndex, $rowIndex, $cell);
                $cellIndex++;
            }
            $rowIndex++;
        }

        $objPHPExcel->getActiveSheet()->setTitle($this->sheetTitle);
        $objPHPExcel->setActiveSheetIndex(0);
        $this->prepareRequestHeader();

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    private function prepareRequestHeader() {
//      Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->fileName.'.xlsx"');
        header('Cache-Control: max-age=0');
//      If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

//      If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
    }

    public function addRows($rows) {
        Asserts::checkArray($rows);
        foreach($rows as $row){
            $this->addRow($row);
        }

    }

}
