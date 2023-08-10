<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH.'libraries/Barcode_generator.php');
require_once(APPPATH.'libraries/Qrcode_generator.php');

class Api extends CI_Controller {

    function generate_barcode($text)
    {
        $barcode = new Barcode_generator();
        $barcode->barcode($text);
    }

    function generate_qrcode($text)
    {
        $qrcode = new Qrcode_generator();
        $qrcode->qrcode($text);
    }

}
$route['api/generate_barcode/(:any)'] = 'api/generate_barcode/$1';
$route['api/generate_qrcode/(:any)'] = 'api/generate_qrcode/$1';