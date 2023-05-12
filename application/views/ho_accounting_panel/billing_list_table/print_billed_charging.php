<?php
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

$formatted_start_date = date('F d, Y', strtotime($start_date));
$formatted_end_date = date('F d, Y', strtotime($end_date));

$title =  '<h4>ALTURAS HEALTHCARE SYSTEM</h4>
            <h4>Billing Summary Details</h4>
            <h6>From '.$formatted_start_date.' to '.$formatted_end_date.'</h6>';
$table = '<table style="border:1px solid #000; padding:6px">';
$table .= '<tr class="border-secondary border-2 border-0 border-top border-bottom">
                <th class="text-center fw-bold ls-2"><strong>Billing No</strong></th>
                <th class="text-center fw-bold ls-2"><strong>LOA/NOA #</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Employee Name</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Business Unit</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Remaining MBL</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Percentage</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Hospital Bill</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Company Charge</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Personal Charge</strong></th>
                <th class="text-center fw-bold ls-2"><strong>Total Payable</strong></th>
           </tr>';
       foreach($billed as $bill){
        $wpercent = '';
		$nwpercent = '';
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

$table .= '<tr>
            <td style="border:1px solid #000; padding:6px">'.$bill['billing_no'].'</td>
            <td style="border:1px solid #000; padding:6px">'.$loa_noa.'</td>
            <td style="border:1px solid #000; padding:6px">'.$fullname.'</td>
            <td style="border:1px solid #000; padding:6px">'.$bill['bisuness_unit'].'</td>
            <td style="border:1px solid #000; padding:6px">'.$bill['remaining_balance'].'</td>
            <td style="border:1px solid #000; padding:6px">'.$wpercent. ', '.$nwpercent.'</td>
            <td style="border:1px solid #000; padding:6px">'.$bill['net_bill'].'</td>
            <td style="border:1px solid #000; padding:6px">'.$bill['company_charge'].'</td>
            <td style="border:1px solid #000; padding:6px">'.$bill['personal_charge'].'</td>
            <td style="border:1px solid #000; padding:6px">'.$bill['net_bill'].'</td>
        </tr>';
        }
$table .= '<tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>TOTAL</td>
            <td>'.$total_bill.'</td>
        </tr>';

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
$pdf->Output('example_005.pdf', 'I');

