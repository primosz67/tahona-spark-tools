<?php
/**
 *
 *
 * Date: 25.10.16
 * Time: 21:02
 */

namespace Spark\Pdf;


use Spark\Http\Request;
use Spark\Core\Routing\RequestData;
use Spark\View\ViewHandler;
use Spark\View\ViewModel;

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