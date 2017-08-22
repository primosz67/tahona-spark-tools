<?php

namespace spark\pdf;

use spark\http\Response;
use spark\view\ViewModel;

class PdfViewModel implements Response{
    /**
     * @var null
     */
    private $mpdf;


    /**
     * PdfViewModel constructor.
     */
    public function __construct($mpdf) {
        $this->mpdf = $mpdf;
    }

    /**
     * @return \mPDF
     */
    public function getMpdf() {
        return $this->mpdf;
    }


}