<?php
/**
 *
 * 
 * Date: 25.10.16
 * Time: 22:11
 */

namespace Spark\Tools\Csv;


class CsvRow {

    private $data = array();
    private $enclosure = '"';
    private $escape_char = "\\";

    public function addText($text) {
        $text = str_replace($this->enclosure, $this->escape_char . $this->enclosure, $text);
        $this->data[] = $this->enclosure . $text . $this->enclosure;
    }

    public function addNumber($number) {
        $this->data[] = (int) $number;
    }

    public function getData() {
        return $this->data;
    }

    public function map(\Closure $fun) {
        foreach ($this->data as $key=>$el) {
            $this->data[$key] = $fun($el);
        }
    }

}