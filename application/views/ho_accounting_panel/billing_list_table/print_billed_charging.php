<?php
//============================================================+
// File name   : example_005.php
// Begin       : 2008-03-04
// Last Update : 2013-05-14
//
// Description : Example 005 for TCPDF class
//               Multicell
//
// Author: Nicola Asuni
//
// (c) Copyright:
//               Nicola Asuni
//               Tecnick.com LTD
//               www.tecnick.com
//               info@tecnick.com
//============================================================+

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: Multicell
 * @author Nicola Asuni
 * @since 2008-03-04
 * @group cell
 * @group pdf
 */

// Include the main TCPDF library (search for installation path).
// require_once('tcpdf_include.php');

// create new PDF document


$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->setPrintHeader(false);

// set document information
$pdf->setCreator(PDF_CREATOR);
$pdf->setAuthor('Nicola Asuni');
$pdf->setTitle('Accounts Report');
$pdf->setSubject('TCPDF Tutorial');
$pdf->setKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 005', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->setDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->setAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// ---------------------------------------------------------

// set font
$pdf->setFont('times', '', 10);

// add a page
$pdf->AddPage();

// set cell padding
$pdf->setCellPaddings(1, 1, 1, 1);

// set cell margins
$pdf->setCellMargins(1, 1, 1, 1);

// set color for background
$pdf->setFillColor(255, 255, 127);

// MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
// // set color for background
// $pdf->setFillColor(220, 255, 220);

$title =  '<h3>ALTURAS HEALTHCARE SYSTEM</h3>
            <h3>Billing Summary Details</h3>
            <h3></h3><br>';


$table = '<table style="border:1px solid #000; padding:6px">';
$table .= ' <thead>
                <tr class="border-secondary border-2 border-0 border-top border-bottom">
                    <th class="fw-bold ls-2"><strong>Billing No</strong></th>
                    <th class="fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                    <th class="fw-bold ls-2"><strong>Patient Name</strong></th>
                    <th class="fw-bold ls-2"><strong>Business Unit</strong></th>
                    <th class="fw-bold ls-2"><strong>Current MBL</strong></th>
                    <th class="fw-bold ls-2"><strong>Percentage</strong></th>
                    <th class="fw-bold ls-2"><strong>Hospital Bill</strong></th>
                    <th class="fw-bold ls-2"><strong>Company Charge</strong></th>
                    <th class="fw-bold ls-2"><strong>Healthcare Advance</strong></th>
                    <th class="fw-bold ls-2"><strong>Total Payable</strong></th>
                    <th class="fw-bold ls-2"><strong>Personal Charge</strong></th>
                    <th class="fw-bold ls-2"><strong>Remaining MBL</strong></th>
                    <th></th>
                </tr>
            </thead>';

        foreach($billed as $bill){
            if($bill['loa_id'] != ''){
				$loa_noa = $bill['loa_no'];
                if($bill['work_related'] == 'Yes'){ 
					if($bill['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $bill['percentage'].'%  W-R';
					   $result = 100 - floatval($bill['percentage']);
					   if($bill['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($bill['work_related'] == 'No'){
				   if($bill['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $bill['percentage'].'% Non W-R';
					   $result = 100 - floatval($bill['percentage']);
					   if($bill['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			}else if($bill['noa_id'] != ''){
				$loa_noa = $bill['noa_no'];
                if($bill['work_related'] == 'Yes'){ 
					if($bill['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $bill['percentage'].'%  W-R';
					   $result = 100 - floatval($bill['percentage']);
					   if($bill['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($bill['work_related'] == 'No'){
				   if($bill['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $bill['percentage'].'% Non W-R';
					   $result = 100 - floatval($bill['percentage']);
					   if($bill['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }
			}

            $fullname =  $bill['first_name'] . ' ' . $bill['middle_name'] . ' ' . $bill['last_name'] . ' ' . $bill['suffix'];
            
            $total_payable = floatval($bill['company_charge'] + $bill['cash_advance']);

            $remaining_mbl = floatval($pay['remaining_balance'] - $pay['company_charge']);
			if(floatval($remaining_mbl) <= 0){
				$mbl = 0;
			}else if(floatval($remaining_mbl) > 0){
				$mbl = $remaining_mbl;
			}
$table .= ' <tbody>
                <tr">
                    <td class="fs-5">'.$bill['billing_no'].'</td>
                    <td class="fs-5">'.$loa_noa.'</td>
                    <td class="fs-5">'.$fullname.'</td>
                    <td class="fs-5">'.$bill['business_unit'].'</td>
                    <td class="fs-5">'.$bill['remaining_balance'].'</td>
                    <td class="fs-5">'.$wpercent. ', '.$nwpercent.'</td>
                    <td class="fs-5">'.$bill['net_bill'].'</td>
                    <td class="fs-5">'.$bill['company_charge'].'</td>
                    <td class="fs-5">'.$bill['cash_advance'].'</td>
                    <td class="fs-5">'.$total_payable.'</td>
                    <td class="fs-5">'.$bill['personal_charge'].'</td>
                    <td class="fs-5">'.$mbl.'</td>
                    <td></td>
                </tr>
            </tbody>';
        }

$table .= '</table>';

$pdf->WriteHtmlCell(0, 0, '', '', $title, 0, 1, 0, true, 'C', true );
$pdf->WriteHtmlCell(0, 0, '', '', $table, 0, 1, 0, true, 'C', true );

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// set color for background
$pdf->setFillColor(215, 235, 255);

// set some text for example


// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// AUTO-FITTING

// set color for background
$pdf->setFillColor(255, 235, 235);

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

// CUSTOM PADDING

// set color for background
$pdf->setFillColor(255, 255, 215);

// set font
$pdf->setFont('helvetica', '', 8);


// move pointer to last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('print_billed_charging.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
