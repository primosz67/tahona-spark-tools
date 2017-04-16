<?php

namespace spark\tools\excel;

use PHPExcel;
use PHPExcel_Cell;
use PHPExcel_IOFactory;

class ExcelPHPReader {

    private $fileName = "defaultName.xlsx";

    function __construct($fileName) {
        $this->fileName = $fileName;
    }

    public function getAsArray($removeEmptyRows = false) {
        $inputFileType = PHPExcel_IOFactory::identify($this->fileName);
        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load($this->fileName);

        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

        $highestRow = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();

        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $arrayData = array();
        for ($rowIndex = 0; $rowIndex <= $highestRow; ++$rowIndex) {
            $arrayData [$rowIndex] = array();

            for ($colIndex = 0; $colIndex < $highestColumnIndex; ++$colIndex) {
                $value = $objWorksheet->getCellByColumnAndRow($colIndex, $rowIndex + 1)->getValue();
                $arrayData[$rowIndex][$colIndex] = $value;
            }
        }

        if ($removeEmptyRows) {
            return $this->clearData($arrayData);
        } else {
            return $arrayData;
        }
    }

    /**
     * @param $inputData
     * @param $resultData
     * @return array
     */
    private function clearData($inputData) {
        $resultData = array();
        foreach ($inputData as $row) {
            if (false == $this->isRowEmpty($row)) {
                $resultData [] = $row;
            }
        }
        return $resultData;
    }

    private function isRowEmpty($row) {
        foreach ($row as $rowValue) {
            if (false == is_null($rowValue)) {
                return false;
            }
        }
        return true;
    }
}
