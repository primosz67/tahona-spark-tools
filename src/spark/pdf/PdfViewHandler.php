<?php
/**
 * Created by PhpStorm.
 * User: primosz67
 * Date: 25.10.16
 * Time: 21:02
 */

namespace spark\pdf;


use spark\http\Request;
use spark\core\routing\RequestData;
use spark\view\ViewHandler;
use spark\view\ViewModel;

class PdfViewHandler extends ViewHandler{

    public function isView($viewModel) {
        return $viewModel instanceof PdfViewModel;
    }

    public function handleView($viewModel, RequestData $request) {
        /** @var \mPDF $mpdf */
        $mpdf = $viewModel->getMpdf();
        $mpdf->Output();
        exit;
    }
}