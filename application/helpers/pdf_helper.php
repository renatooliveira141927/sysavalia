<?php
/**
 * Created by PhpStorm.
 * User: luiz.alberto
 * Date: 07/04/2015
 * Time: 12:26
 */

if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @param string $html
 * @param string $filename
 * @param bool $stream
 * @return string
 */
function pdf_create($html, $filename='', $stream=TRUE)
{
    require_once("../libraries/dompdf-master/dompdf_config.inc.php");

    $dompdf = new DOMPDF();
    $dompdf->load_html($html);
    $dompdf->render();
    if ($stream) {
        $dompdf->stream($filename.".pdf");
    } else {
        return $dompdf->output();
    }
}
