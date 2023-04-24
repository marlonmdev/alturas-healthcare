<?php
/**
 * 
 */
class UNILAB_MPDI_ctrl extends CI_Controller
{
     function __construct()
     {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('simplify/simplify','simplify');
        $this->load->model('simplify/pdf_simplify','pdf_');
        $this->load->model('Unilab_model');
        $this->load->model('Mpdi_mod');

     }
     public function unilab_ui()
     {

        $this->load->view('unilab/unilab_ui');
     }

/*generate excel file=================================================================================================*/
     function get_excel()
     {
         $Document_num = $_POST['document_num'];
         $tin_no       = $_POST['get_pad_number'];
         $tbl         = '';
         $file_name    = "Marcela Pharma Distribution Inc-".$Document_num;
          
         header("content-type: application/vnd.ms-excel");
         header("Content-Disposition: attachment; filename=".$file_name.".xls");

         $si_details   = $this->Unilab_model->get_specific_si($Document_num);
         $border       = 0;
         $style        = "height:20px;color:blue;"; //cell height sa kara row item sa table

         $grand_discount_total      = 0.00;
         $discount_total            = 0.00;
         $grand_amount_total        = 0.00;
         $amount_total              = 0.00;
         $vatable_sales             = 0.00;
         $total_sales_vat_inclusive = 0.00;
         $vat_exempt_sales          = 0.00;
         $less_vat                  = 0.00;
         $vat_amount                = 0.00;
         $Amount_net_vat            = 0.00;
         $total_amt                 = 0.00;

         foreach($si_details as $dat)
         {
            if($dat['line_description'] == 'customer sold to')
            {
                 $Sell_to_Customer_No = $dat['cell_value'];
            }
            else 
            if($dat['line_description'] == 'invoice date')
            {
                 $Shipment_Date = $dat['cell_value'];
            }
            else     
            if($dat['line_description'] == 'account type')
            {
                 $account_type = $dat['cell_value'];
            }
            else 
            if($dat['line_description'] == 'VATable Sales')            
            {
                $cell_value           = preg_replace('/,/i','',$dat['cell_value']); 
                $vatable_sales        = $cell_value;
                $Amount_net_vat       = $cell_value;
            }
            else 
            if($dat['line_description'] == 'Gross Sales')            
            {
                $cell_value                = preg_replace('/,/i','',$dat['cell_value']); 
                $total_sales_vat_inclusive = $cell_value;            
            }
            else 
            if($dat['line_description'] == 'Total Amount Due')            
            {
                $cell_value                = preg_replace('/,/i','',$dat['cell_value']); 
                $total_amt                 = $cell_value;                
            }
            else 
            if($dat['line_description'] == 'VAT Exempt Sales')                        
            {
                $cell_value = preg_replace('/,/i','',$dat['cell_value']); 
                $vat_exempt_sales = $cell_value;
            }   
            else  
            if($dat['line_description'] == 'Discount 1- invoice discount')                        
            {
                $cell_value = preg_replace('/,/i','',$dat['cell_value']); 
                $less_vat   = $cell_value; 
            }
            else 
            if($dat['line_description'] == 'VAT Amount')            
            {
                $cell_value = preg_replace('/,/i','',$dat['cell_value']); 
                $vat_amount = $cell_value;
            }
            else 
            if($dat['line_description'] == 'salesman')            
            {
                $salesman = $dat['cell_value'];
            }

         }


         $address     = '';
         $get_address = $this->Unilab_model->get_address($Document_num);
         foreach($get_address as $add)
         {
            $address .= $add['cell_value'];
         }

         $current_page_number = 1;
         $total_pages         = $this->get_number_of_pages($Document_num,$tin_no);
         $Page_number_label   = "Page ".$current_page_number." of ".$total_pages;


         $tbl .= $this->get_page_header_excel($border,$Sell_to_Customer_No,$Document_num,$address,$Shipment_Date,$tin_no,$account_type,$salesman,$Page_number_label);   
         $tbl .= $this->get_row_header_excel($border); 
         

         $Description  = ''; 
         $si_details   = $this->Unilab_model->get_specific_si($Document_num);
         $second_line  = ''; // mao nig Lot NO. ExpiryDate ug Qty sa second line sa item
         $line_counter = 0;
         $amount       = '';

         foreach($si_details as $si)
         {            

             $cell_data        = $this->get_row_data_excel($amount,$si['line_description'],$Description,$si['cell_value'],$tbl,$second_line,$line_counter,$style,$discount_total,$amount_total);                
             $tbl              = $cell_data[0];                
             $line_description = $cell_data[1]; 
             $cell_value       = $cell_data[2];
             $Description      = $cell_data[3]; 
             $second_line      = $cell_data[4];  
             $line_counter     = $cell_data[5]; 
             $discount_total   = $cell_data[6]; 
             $amount_total     = $cell_data[7];  
             $amount           = $cell_data[8];  

             
             if($line_counter == 20)
             {  
                  $tbl                   = $this->sub_total($tbl,$discount_total,$amount_total,$style);
                  if(is_numeric($discount_total))
                  {
                     $grand_discount_total += $discount_total;
                  }
                  else 
                  {
                     $grand_discount_total = '';  
                  }
                  $grand_amount_total   += $amount_total;
                  $discount_total        = 0.00;
                  $amount_total          = 0.00;   
                  $tbl                   = $this->get_footer_excel($border,$current_page_number,$total_pages,$vatable_sales,$total_sales_vat_inclusive,$vat_exempt_sales,$less_vat,$total_amt,$vat_amount,$Amount_net_vat,$tbl);
               
                  echo $tbl;
                  $line_counter = 0;
                  
                  $tbl                   = '';
                  $current_page_number  += 1;                                 
                  $total_pages           = $this->get_number_of_pages($Document_num,$tin_no);
                  $Page_number_label     = "Page ".$current_page_number." of ".$total_pages;
                  $tbl                  .= $this->get_page_header_excel($border,$Sell_to_Customer_No,$Document_num,$address,$Shipment_Date,$tin_no,$account_type,$salesman,$Page_number_label);   
                  $tbl                  .= $this->get_row_header_excel($border);   
             }
         }


         $remaining_line = 20 -$line_counter;   
          if($remaining_line >0)
          {
            
             for($a=0;$a<$remaining_line;$a++)
             {
                    $tbl.= '<tr>
                                   <td style="'.$style.'"></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                            </tr>';
             }

             $tbl = $this->sub_total($tbl,$discount_total,$amount_total,$style);
             if(is_numeric($discount_total))
             {
                 $grand_discount_total += $discount_total;
             }
             else 
             {
                 $grand_discount_total = '';
             }

             $grand_amount_total   += $amount_total;
             $discount_total        = 0.00;
             $amount_total          = 0.00; 


             $tbl = $this->get_footer_excel($border,$current_page_number,$total_pages,$vatable_sales,$total_sales_vat_inclusive,$vat_exempt_sales,$less_vat,$total_amt,$vat_amount,$Amount_net_vat,$tbl);
             echo $tbl;
             /*$this->ppdf->writeHTML($tbl, true, false, false, false, '');    */           

         }
     } 


    public function get_footer_excel($border,$current_page_number,$total_pages,$vatable_sales,$total_sales_vat_inclusive,$vat_exempt_sales,$less_vat,$total_amt,$vat_amount,$Amount_net_vat,$tbl)
    {
        if($current_page_number == $total_pages)
         {
            $vatable_sales_             = number_format($vatable_sales,2);
            $total_sales_vat_inclusive_ = number_format($total_sales_vat_inclusive,2);
            $vat_exempt_sales_          = number_format($vat_exempt_sales,2);
            $less_vat_                  = number_format($less_vat,2);
            $total_amt_                 = number_format($total_amt,2);
            $vat_amount_                = number_format($vat_amount,2);
            $zero_rated_sales_          = '0.00';
            $Amount_net_vat_            = number_format($Amount_net_vat);
            $less_sc_pwd_               = '0.00';
            $add_vat_                   = '0.00';
         }
         else 
         {
            $vatable_sales_             = '-';
            $total_sales_vat_inclusive_ = '-';
            $vat_exempt_sales_          = '-';
            $less_vat_                  = '-';
            $total_amt_                 = '-';
            $vat_amount_                = '-';
            $zero_rated_sales_          = '-';
            $Amount_net_vat_            = '-'; 
            $less_sc_pwd_               = '-';
            $add_vat_                   = '-';
         }

         $tbl .= $this->footer_total_excel($vatable_sales_                     , $total_sales_vat_inclusive_ , $border);   
         $tbl .= $this->footer_total_excel($vat_exempt_sales_                  , $less_vat_                  , $border);
         $tbl .= $this->footer_total_excel($zero_rated_sales_                  , $Amount_net_vat_            , $border);
         $tbl .= $this->footer_total_excel($vat_amount_                        , $less_sc_pwd_               , $border);
         $tbl .= $this->footer_total_excel(''                                  , $total_amt_                 , $border);
         $tbl .= $this->footer_total_excel(''                                  , $add_vat_                    , $border);
         $tbl .= $this->footer_total_excel(''                                  , $total_amt_                 , $border);

         return $tbl;
    }


     function footer_total_excel($column2,$column5,$border)
    {
         $label = 'text-align:left;width:87px;';
         $value = 'text-align:right;width:123px;height: 20px;';
         
         $tbl  = '<table border="'.$border.'" style="font-size: 7px;">';
         $tbl .= '<tr style="color:blue;">
                      <td style="'.$label.'" ></td>
                      <td style="'.$value.'" >'.$column2.'</td>
                      <td style="width:149px;" ></td>
                      <td style="width:100px;" ></td>
                      <td style="width:149px;" ></td>
                      <td style="width:100px;" ></td>
                      <td style="width:100px;" ></td>                      
                      <td style="'.$value.'" >'.$column5.'</td>                                            
                 </tr>';
         $tbl .= "</table>";        
         return $tbl; 
    } 


    function get_row_header_excel($border)
    {        
            $tbl = '
                     <table border="'.$border.'" style="font-size: 13px;">
                         <tr style="text-align: center;font-weight: bold;">
                                 <th style="width:30px;vertical-align: top;">                                     
                                        
                                 </th>
                                 <th style="width:500px;">
                                      
                                 </th>
                                 <th style="width:35px;">                                        
                                        
                                 </th>
                                 <th style="width:50px;height:30px;">                                        
                                       
                                 </th>
                                 <th style="width:60px;">
                                       
                                 </th>
                                 <th style="width:35px;">
                                        
                                 </th>
                                 <th style="width:60px;">
                                        
                                 </th>
                                 <th style="width:62px;">                                              
                                        
                                 </th>
                                                        <!-- <th style="width:48px;">
                                                               
                                                         </th>
                                                         <th style="width:48px;">
                                                               
                                                         </th>
                                                         <th style="width:37px;">
                                                                 
                                                         </th>
                                                         <th style="width:50px;">
                                                               
                                                         </th>-->    
                         </tr>        
                ';       

           return  $tbl;    
    } 

  

    function get_row_data_excel($amount,$line_description,$Description,$cell_value,$html,$second_line,$line_counter,$style,$discount_total,$amount_total)
    {

          if($line_description == 'item No.')   
          {
                $Item_no_length = 10-strlen($cell_value);
                for($a=0;$a<$Item_no_length;$a++)
                {
                   $cell_value .= "&nbsp;";
                }
                $html       .= '<tr>
                                     <td style="'.$style.'">'.$cell_value.'</td>                                           
                               ';                      
          }     
          else 
          if($line_description == 'Discription')    
          {
             $Description    .= $cell_value;
                                          
          } 
          else 
          if($line_description == 'QTY')    
          {
                $Quantity_length = 8- strlen($cell_value);     
                for($a=0;$a<$Quantity_length;$a++)   
                {
                    $cell_value = "&nbsp;".$cell_value;
                }

                 $html  .= '
                                <td style="'.$style.'">'.$Description.'</td>
                                <td style="'.$style.'">'.$cell_value.'</td>                                      
                           ';
                $Description = '';           
          } 
          else                   
          if($line_description == 'Unit Price')   
          {
              $cell_value        = preg_replace('/,/i','',$cell_value); 
              $cell_value        = number_format($cell_value,2);
              $unit_price_length = 15 -strlen($cell_value);  
              for($a=0;$a<$unit_price_length;$a++)
              {
                 $cell_value = "&nbsp;".$cell_value;
              } 

              $html .= '       
                         <td style="'.$style.'text-align:right;">'.$cell_value.'</td>                                      
                       ';
          }
          else                   
          if($line_description == 'Discount Total' || $line_description == 'Discount %' || $line_description == 'UOM')   
          {
              if($line_description == 'Discount Total')
              {
                  $value = preg_replace('/,/i','',$cell_value);      

                  if(is_numeric($value))
                  {
                     $discount_total += $value;
                  }
                  else 
                  {
                     $discount_total = '';   
                  }


                  $Line_disc_amount_length = 16 -strlen($cell_value);     
                  for($a=0;$a<$Line_disc_amount_length;$a++)
                  {
                        $cell_value =  "&nbsp;".$cell_value; 
                  }


              }  
              else 
              if($line_description == 'Discount %')
              {
                     $Line_discount_length = 14 - strlen($cell_value);
                     for($a=0;$a<$Line_discount_length;$a++)
                     {
                         $cell_value = "&nbsp;".$cell_value;
                     }
              }     
              else 
              if($line_description == 'UOM')              
              {
                     $Unit_of_Measure_length  = 6- strlen($cell_value);
                     for($a=0;$a<$Unit_of_Measure_length;$a++)
                     {
                         $cell_value .= "&nbsp;";
                     }
              }    


              $html  .= '
                               <td style="'.$style.'text-align:right;">'.$cell_value.'</td>                                                                     
                         ';  

          }
          else 
          if($line_description == 'Amount')   
          {

                $amount = $cell_value;

               /*  $Amount        = $cell_value;   
                 $Amount_length = 20 -strlen($cell_value);
                 for($a=0;$a<$Amount_length;$a++)
                 {
                     $Amount = "&nbsp;".$Amount;
                 }

                $html  .= '
                                 <td style="'.$style.'text-align:right;">'.$Amount.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                                   
                             </tr>                                         
                           '; 
                $cell_value = preg_replace('/,/i','',$cell_value); 
                $amount_total += $cell_value;                       
                $line_counter +=1;  */          
           }
           else 
           if($line_description == 'Vat excempt')   
           {

                 $Amount_        = $amount;   
                 $Amount_length  = 20 -strlen($Amount_);
                 for($a=0;$a<$Amount_length;$a++)
                 {
                     $Amount_ = "&nbsp;".$Amount_;
                 }

                $html  .= '
                                 <td style="'.$style.'text-align:right;">'.$Amount_." ".$cell_value.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                                   
                             </tr>                                         
                           '; 
                $amount = preg_replace('/,/i','',$amount); 
                $amount_total += $amount;                       
                $line_counter +=1;  
                $amount = '';
           }
           else 
           if($line_description == 'LOT NO')   
           {
               /*$html .= '<tr>
                                 <td></td>
                                 <td>'.$cell_value.'</td>                                                                     
                        ';*/

                if( $amount != ''  )
                {
                    $Amount_        = $amount;   
                    $Amount_length  = 20 -strlen($Amount_);
                     for($a=0;$a<$Amount_length;$a++)
                     {
                         $Amount_ = "&nbsp;".$Amount_;
                     }

                    $html  .= '
                                     <td style="'.$style.'text-align:right;">'.$Amount_.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                                   
                                 </tr>                                         
                               '; 
                    $amount = preg_replace('/,/i','',$amount); 
                    $amount_total += $amount;                       
                    $line_counter +=1;  
                    $amount = '';
                }

                $second_line .= $cell_value.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';        
           }
           else 
           if($line_description == 'Expiry Date')   
           {
               /*$html .= '
                             <td>'.$cell_value.'</td>                                                                     
                        '; */
               $second_line .= $cell_value.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';        
           }
           else 
           if($line_description == 'Lot Qty')   
           {
               $second_line .= $cell_value;        

                $html .= '<tr>
                               <td></td>
                               <td style="'.$style.'">'.$second_line.'</td> 
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                               <td></td>
                           </tr>                                                                      
                        '; 
               $second_line = ''; 
               $line_counter +=1;             
           }


               return array($html,$line_description,$cell_value,$Description,$second_line,$line_counter,$discount_total,$amount_total,$amount);     
    }


    function get_page_header_excel($border,$Sell_to_Customer_No,$Document_num,$address,$Shipment_Date,$tin_no,$account_type,$salesman,$Page_number_label)
    {  

         $font = 'font-size: 13px;color:blue';
         $align = "text-align:left;" ;
          $html = "
                                    
                                        
                                        <table border='".$border."'>                                                                                       
                                            <tr>
                                                <td colspan='12'>
                                                     
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan='12'>
                                                     
                                                </td>
                                            </tr>
                                        </table>";


         $html .= '<table border="'.$border.'"  style="'.$font.'" >                              
                   <tr>                         
                         <td colspan="4" style="padding-left:80px;">'.$Sell_to_Customer_No.'</td>                          
                   </tr>';



         $html  .= '<tr>';
         $html .= '   
                      <td colspan="4"  style="'.$align.'padding-left:80px;">'.$tin_no.'</td>                                             
                      <td colspan="3" style="'.$align.'padding-left:70px;">'.date('m-d-Y',strtotime(date($Shipment_Date))).'</td>     
                  ';
         $html .= '</tr>';

         $html .= '<tr>';
         $html .= '  
                      <td colspan="4" style="'.$align.'padding-left:80px;">'.$address.'</td>                                               
                      <td colspan="3" style="'.$align.'padding-left:70px;">'.$account_type.'</td> 
                  ';   
         $html .= '</tr>';

         $html .= '<tr>';
         $html .= '   
                      <td colspan="4" style="padding-left:80px;">'.$salesman.'</td>                                         
                      <td colspan="3" style="'.$align.'padding-left:70px;">'.''/*date('m-d-Y',strtotime(date($due_Date)))*/.'</td>   
                  ';
         $html .= '</tr>';

         $html .= '<tr>';
         $html .= '  
                      <td colspan="4" style="padding-left:80px;">'.$Document_num.'</td>   
                      <td colspan="3" style="'.$align.'padding-left:80px;">'.$Page_number_label.'</td>
                  ';   
         $html .= '</tr>';

         return $html;      

           
             
    }
/*end of generate excel file=================================================================================================*/






    function header_line($border,$first_column,$second_column,$third_column,$text1,$text2)
    {
         $tbl = '<table cellspacing="1" cellpadding="1" border="'.$border.'"  style="font-size:7px;color:blue;">
                     <tr>
                          <th style="'.$first_column.'"></th>
                          <th style="'.$second_column.'">'.$text1.'</th>
                          <th style="'.$third_column.'">'.$text2.'</th>                  
                     </tr>
                </table>
               '; 

         return $tbl;      

    }


    public function get_page_header($border,$Sell_to_Customer_No,$Document_num,$address,$Shipment_Date,$get_pad_number,$account_type,$salesman,$Page_number_label,$batch_no)
    {
         $tbl = ''; 
         $posting_date = str_replace('/','-',$Shipment_Date); 


 
         $first_column  = 'width:230px;';
         $second_column = '';
         $third_column  = '';         
         $text1         = '' ;  
         $text2         = '';           
         //$tbl          .= $this->header_line($border,$first_column,$second_column,$third_column,$text1,$text2);  

         $first_column  = 'width:292px;height:14px;'; 
         $second_column = '';  
         $third_column  = '';  
         //$text1         = 'Tagbilaran City' ;
         $text1         = '' ;
         $text2         = '';
         //$tbl          .= $this->header_line($border,$first_column,$second_column,$third_column,$text1,$text2);  

         $first_column  = 'width:170px;height:8px;'; 
         $second_column = 'width:155px;';
         $third_column  = '';
         /*$text1         = 'VAT Reg. TIN#:_______________';
         $text2         = 'Permit #:_______________';*/
         $text1         = '';
         $text2         = '';
         $tbl          .= $this->header_line($border,$first_column,$second_column,$third_column,$text1,$text2); 


         $first_column  = 'width:210px;height:10px;'; 
         $second_column = 'width:115px;';
         $third_column  = '';
         /*$text1         = 'MIN#:_______________'; 
         $text2         = 'SN:_______________';*/
         $text1         = ''; 
         $text2         = '';
         $tbl          .= $this->header_line($border,$first_column,$second_column,$third_column,$text1,$text2);   

         $first_column  = 'width:25px;height:10px;'; 
         $second_column = 'width:330px;';
         $third_column  = '';
         //$text1       = 'SALES INVOICE';
         $text1         = '';
         $text2         = '';
         $tbl          .= $this->header_line($border,$first_column,$second_column,$third_column,$text1,$text2);           


         $first_column  = 'width:30px;'; 
         $second_column = 'width:330px;height:15px;';
         $third_column  = '';
         //$text1       = 'SALES INVOICE';
         $text1         = $Sell_to_Customer_No;
         $text2         = '';
         $tbl          .= $this->header_line($border,$first_column,$second_column,$third_column,$text1,$text2);

         $firstColumn   = 'width:30px;height:10px;';   
         $first_column  = 'width:250px;'; 
         $second_column = 'width:135px;'; 
         $third_column  = 'width:120px;';
         $fourth_column = 'width:70px;';


         $tbl .= '<table cellspacing="1" cellpadding="1" border="'.$border.'"  style="font-size:7.5px;color:blue;">
                   
                     <tr>
                          <th style="'.$firstColumn.'"></th>
                          <th style="'.$first_column.'">'.$get_pad_number.'</th>
                          <th style="'.$second_column.'"></th>
                          <th style="'.$third_column.'" >'.$posting_date/*date('m-d-Y',strtotime(date($posting_date)))*/.'</th>    
                          <th style="'.$fourth_column.'"></th>                      
                     </tr>
                     <tr>
                          <td style="'.$firstColumn.'"></td>
                          <td style="width:320px;'.''/*$first_column*/.'">'.$address.'</td>
                          <td style="width:65px'.''/*$second_column*/.'"></td>
                          <td style="'.$third_column.'">'.$account_type.'</td> 
                          <td style="'.$fourth_column.'"></td>                         
                     </tr>
                     <tr>
                          <td style="'.$firstColumn.'"></td>
                          <td style="'.$first_column.'">'.$salesman.'</td> 
                          <td style="'.$second_column.'"></td> 
                          <td style="'.$third_column.'">'.''/*date('m-d-Y',strtotime(date($due_Date)))*/.'</td> 
                          <td style="'.$fourth_column.'"></td>
                     </tr>
                     <tr>
                          <td style="'.$firstColumn.'"></td>
                          <td style="width:37px;">'.$batch_no.'</td> 
                          <td style="width:350px;">  '.$Document_num/*$ref_no*/.'</td> 
                          <td style="'.$third_column.'">'.$Page_number_label.'</td> 
                          <td style="'.$fourth_column.'"></td>
                     </tr>
                   </table>';

         return $tbl;     
    }  


    function get_row_header($border)
    {        
           
            $tbl = '
                     <table border="'.$border.'" style="font-size:7px;">
                         <tr style="text-align: center;font-weight: bold;">
                                 <th style="width:50px;vertical-align: top;">                                     
                                      <!--  item -->  
                                 </th>
                                 <th style="width:209px;">
                                       <!-- description  orig  width:280px;-->  
                                 </th>
                                 <th style="width:30px;text-align: right;">                                        
                                        <!-- quantity -->
                                 </th>
                                 <th style="width:35px;height:30px;">                                        
                                       <!-- UOM-->
                                 </th>
                                 <th style="width:45px;">
                                        <!-- unit price  -->  
                                 </th>
                                 <th style="width:23px;">
                                        <!-- deal -->   
                                 </th>
                                 <th style="width:35px;">
                                        <!--Line discount -->
                                 </th>
                                 <th style="width:45px;">
                                        <!-- net price -->
                                 </th>
                                 <th style="width:50px;">
                                         <!-- discount total  -->  
                                 </th>
                                 <th style="width:62px;">                                              
                                        
                                 </th>
                                                        <!-- <th style="width:48px;">
                                                               
                                                         </th>
                                                         <th style="width:48px;">
                                                               
                                                         </th>
                                                         <th style="width:37px;">
                                                                 
                                                         </th>
                                                         <th style="width:50px;">
                                                               
                                                         </th>-->    
                         </tr>        
                ';       

           return  $tbl;    
    } 


    function get_cell_value($amount,$line_description,$Description,$cell_value,$html,$second_line,$line_counter,$style,$discount_total,$amount_total)
    {
                  $font_overall = "font-size:8px;";
                  $money_style  = "text-align:right;" ;

                   if($line_description == 'Deal')   
                   {
                        if($cell_value == '0')
                        {
                             $cell_value ='<span style="font-family:zapfdingbats;">3</span>';                          
                        }                        

                         $html  .= ' 
                                         <td style="'.$style.$font_overall.'text-align:center;">'.$cell_value.'</td>                                           
                                   '; 
                   }
                   else 
                   if($line_description == 'Net Price')   
                   {
                         if(is_numeric($cell_value))
                         {
                            $cell_value  = number_format($cell_value,2);
                         }
                         else 
                         {
                            $money_style = 'text-align:center;';
                         }
                         $html  .= ' 
                                         <td style="'.$style.$money_style.$font_overall.'">'.$cell_value.'</td>                                           
                                   '; 
                   }
                   else 
                   if($line_description == 'item No.')   
                   {
                         $html  .= '<tr>
                                         <td style="'.$style.'">'.$cell_value.'</td>                                           
                                 '; 
                              
                   }     
                   else 
                   if($line_description == 'Discription')    
                   {
                          
                            $Description .= $cell_value;
                                                  
                   } 
                   else 
                   if($line_description == 'QTY')    
                   {
                         $html  .= '
                                        <td style="'.$style.'">'.$Description.'</td>
                                        <td style="'.$style.$font_overall.'">'.$cell_value.'</td>                                      
                                   ';
                        $Description = '';           
                   } 
                   else                   
                   if($line_description == 'Unit Price')   
                   {
                        $cell_value = preg_replace('/,/i','',$cell_value); 
                        $cell_value = preg_replace('/-/i','',$cell_value); 
                        if(is_numeric($cell_value))
                        {
                            $cell_value = number_format($cell_value,2);
                        }
                        
                        $html .= '       
                                     <td style="'.$style.$font_overall.'text-align:right;">'.$cell_value.'</td>                                      
                                  ';
                   }
                   else                   
                   if($line_description == 'Discount Total' || $line_description == 'Discount %' || $line_description == 'UOM')   
                   {

                        $style2 = 'text-align:right;';
                        
                        if($cell_value == '-')
                        {
                            $style2 = 'text-align:center;';
                        }
                         

                        $html  .= '
                                         <td style="'.$style.$style2.$font_overall.'">'.$cell_value.'</td>                                                                     
                                   ';  
                        $cell_value = preg_replace('/,/i','',$cell_value);    

                        if($line_description == 'Discount Total')
                        {
                            if(is_numeric($cell_value) )
                            {                                 
                                $discount_total += $cell_value;
                            }   
                            else 
                            {
                                $discount_total += 0;    
                            } 

                        }    
                         



                   }
                   else 
                   if($line_description == 'Amount')   
                   {               

                        $amount =  $cell_value;         

                       /* $html  .= '
                                         <td style="'.$style.'text-align:right;">'.$cell_value.'</td>                                   
                                     </tr>                                         
                                   '; 
                        $cell_value = preg_replace('/,/i','',$cell_value); 
                        $amount_total += $cell_value;                       
                        $line_counter +=1;   */         
                   }
                   else 
                   if($line_description == 'Vat excempt')   
                   {
                        $html         .= '
                                                 <td style="'.$style.$font_overall.'text-align:right;">'.$amount."&nbsp;&nbsp;&nbsp;".$cell_value.'</td>                                   
                                             </tr>                                         
                                           '; 
                        $amount        = preg_replace('/,/i','',$amount); 
                        $amount_total += $amount;                       
                        $line_counter += 1;
                        $amount        = '';
                   }
                   else 
                   if($line_description == 'LOT NO')   
                   {

                       if($amount != '')
                       {
                            $html         .= '
                                                 <td style="'.$style.$font_overall.'text-align:right;">'.$amount.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>                                   
                                             </tr>                                         
                                             '; 
                            $amount        = preg_replace('/,/i','',$amount); 
                            $amount_total += $amount;                       
                            $line_counter += 1;
                            $amount        = '';
                       }
                       /*$html .= '<tr>
                                         <td></td>
                                         <td>'.$cell_value.'</td>                                                                     
                                ';*/
                        $second_line .= $cell_value.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';        
                   }
                   else 
                   if($line_description == 'Expiry Date')   
                   {
                       /*$html .= '
                                     <td>'.$cell_value.'</td>                                                                     
                                '; */
                       $second_line .= $cell_value.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';        
                   }
                   else 
                   if($line_description == 'Lot Qty')   
                   {
                       $second_line .= $cell_value;        

                        $html .= '<tr>
                                       <td></td>
                                       <td style="'.$style.'">'.$second_line.'</td> 
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>
                                       <td></td>

                                   </tr>                                                                      
                                '; 
                       $second_line = ''; 
                       $line_counter +=1;             
                   }


               return array($html,$line_description,$cell_value,$Description,$second_line,$line_counter,$discount_total,$amount_total,$amount);    
    }



    function footer_total($column2,$column5,$column3,$border)
    {
         

         if($column5 == '-')
         {
             $value = 'text-align:center;width:80;height: 22px;';
         }
         else 
         {
             $value = 'text-align:right;width:80px;height: 22px;';
         }

         $label = 'text-align:left;width:87px;';
         
         $tbl  = '<table border="'.$border.'" style="font-size: 8px;">';
         $tbl .= '<tr style="color:blue;">
                      <td style="'.$label.'" ></td>
                      <td style="'.$value.'" >'.$column2.'</td>
                      <td style="width:180px;" ></td>
                      <td style="width:150px;text-align:right;" >'.$column3.'</td>
                      <td style="'.$value.'" >'.$column5.'</td>                                            
                 </tr>';
         $tbl .= "</table>";        
         return $tbl; 
    } 


    function sub_total($tbl,$discount_total,$amount_total,$style)
    {

          if(is_numeric($discount_total))
          {
              $discount_total = number_format($discount_total,2);
          }
          else 
          {
              $discount_total = '-';
          }

          /*$tbl        .='
                            <tr>
                                    <td style="height: 1px;"></td>     
                                    <td></td>     
                                    <td></td>     
                                    <td></td>     
                                    <td></td>     
                                    <td></td>     
                                    <td></td> 
                                    <td></td>     
                                    <td></td>     
                                    <td></td> 
                            </tr>
                        ';*/

          $total_style = "text-align: right;color:blue;height: 25px;font-size:8px;";
          $tbl        .= '  <tr>
                                               <td style="'.$style.'"></td>     
                                               <td></td>     
                                               <td></td>     
                                               <td></td>     
                                               <td></td>     
                                               <td></td>     
                                               <td></td>     
                                               <td></td> 
                                               <td style="'.$total_style.'">'.$discount_total.'</td>     
                                               <td style="'.$total_style.'">'.number_format($amount_total,2).'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>     
                                       </tr>
                                   </table>
                                    '; 
         return $tbl;                   
    }




    function get_number_of_pages($Document_num,$tin_no)
    {

         $page_counter   = 0;
         $line_counter   = 1; 
         $tbl            = ''; 
         $second_line    = ''; // mao nig Lot NO. ExpiryDate ug Qty sa second line sa item
         $si_details     = $this->Unilab_model->get_specific_si($Document_num);
         $style          = "height:20px;color:blue;"; //cell height sa kara row item sa table
         $discount_total = 0.00;
         $amount_total   = 0.00;
         $Description    = ''; 
         $amount         = '';
         foreach($si_details as $si)
         {
              $cell_data = $this->get_cell_value($amount,$si['line_description'],$Description,$si['cell_value'],$tbl,$second_line,$line_counter,$style,$discount_total,$amount_total);                
              $tbl              = $cell_data[0];                
              $line_description = $cell_data[1]; 
              $cell_value       = $cell_data[2];
              $Description      = $cell_data[3]; 
              $second_line      = $cell_data[4];  
              $line_counter     = $cell_data[5]; 
              $discount_total   = $cell_data[6]; 
              $amount_total     = $cell_data[7];
              $amount           = $cell_data[8];
              if($line_counter == 20)
              {
                $line_counter = 0;
                $page_counter+=1;
              }
         }

         $remaining_line = 20 -$line_counter;   
         if($remaining_line >0)
         {
                $page_counter+=1;
         }

         return $page_counter;
    }
    


    public function get_footer($border,$current_page_number,$total_pages,$vatable_sales,$total_sales_vat_inclusive,$vat_exempt_sales,$less_vat,$total_amt,$vat_amount,$Amount_net_vat,$tbl,$discount,$invoice_discount)
    {
        if($current_page_number == $total_pages)
         {

            $Amount_net_vat              =  $vatable_sales + $vat_exempt_sales;


            $vatable_sales_             = number_format($vatable_sales,2);
            $total_sales_vat_inclusive_ = number_format($total_sales_vat_inclusive,2);
            $vat_exempt_sales_          = number_format($vat_exempt_sales,2);
            $less_vat_                  = number_format($less_vat,2);
            $total_amt_                 = number_format($total_amt,2);
            $vat_amount_                = number_format($vat_amount,2);
            $zero_rated_sales_          = '0.00';
            $Amount_net_vat_            = number_format($Amount_net_vat,2);
            $less_sc_pwd_               = $invoice_discount;
            $add_vat_                   = '0.00';
            $discount_                  = number_format($discount,2)."%";
            $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
         }
         else 
         {
            $vatable_sales_             = '-';
            $total_sales_vat_inclusive_ = '-';
            $vat_exempt_sales_          = '-';
            $less_vat_                  = '-';
            $total_amt_                 = '-';
            $vat_amount_                = '-';
            $zero_rated_sales_          = '-';
            $Amount_net_vat_            = '-';
            $less_sc_pwd_               = '-';
            $add_vat_                   = '-';
            $discount_                  = '-';
            $space = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
         }



         $tbl .= $this->footer_total($vatable_sales_.$space                     , $total_sales_vat_inclusive_.$space ,'', $border);   
         $tbl .= $this->footer_total($vat_exempt_sales_.$space                  , $less_vat_.$space                  ,'', $border);
         $tbl .= $this->footer_total($zero_rated_sales_.$space                  , $Amount_net_vat_.$space            ,'', $border);
         $tbl .= $this->footer_total($vat_amount_.$space                        , $less_sc_pwd_.$space               ,$discount_, $border);
         $tbl .= $this->footer_total(''                                         , $total_amt_.$space                 ,'', $border);
         $tbl .= $this->footer_total(''                                         , $add_vat_.$space                   ,'', $border);
         $tbl .= $this->footer_total(''                                         , $total_amt_.$space                 ,'', $border);

         return $tbl;
    }



     public function gen_report()
     {
          $this->ppdf = new TCPDF();
          $this->ppdf->SetTitle("Marcela Pharma Distribution Inc.");
          $this->ppdf->SetMargins(5, 13, 0.20, true); //top 15
          $this->ppdf->setPrintHeader(false);
          $this->ppdf->SetFont('', '', 10, '', true);                    
          $this->ppdf->AddPage("P");
          $this->ppdf->SetAutoPageBreak(false);


         $Document_num = $_POST['document_num'];
         $tin_no       = $_POST['get_pad_number']; 

         $line_counter = 1; 
         $border       = 0; //if 0 walay border if 1 naay border ang table
         $style        = "height:18.5px;color:blue;font-size:7px;"; //cell height sa kara row item sa table
         $tbl          = '';    


         $grand_discount_total      = 0.00;
         $discount_total            = 0.00;
         $grand_amount_total        = 0.00;
         $amount_total              = 0.00;
         $vatable_sales             = 0.00;
         $total_sales_vat_inclusive = 0.00;
         $vat_exempt_sales          = 0.00;
         $less_vat                  = 0.00;
         $vat_amount                = 0.00;
         $Amount_net_vat            = 0.00;
         $total_amt                 = 0.00;
         $discount                  = 0.00;
         $invoice_discount          = 0.00;

         $address = '';
         $get_address = $this->Unilab_model->get_address($Document_num);
         foreach($get_address as $add)
         {
            $address .= $add['cell_value'];
         }

         $si_dat = $this->Unilab_model->get_specific_si($Document_num);
         foreach($si_dat as $dat)
         {
            if($dat['line_description'] == 'customer sold to')
            {
                 $Sell_to_Customer_No = $dat['cell_value'];
            }
            else 
            if($dat['line_description'] == 'invoice date')
            {
                 $Shipment_Date = $dat['cell_value'];
            }
            else     
            if($dat['line_description'] == 'account type')
            {
                 $account_type = $dat['cell_value'];
            }
            else 
            if($dat['line_description'] == 'VATable Sales')            
            {
                $cell_value           = preg_replace('/,/i','',$dat['cell_value']); 
                $vatable_sales        = $cell_value;
                $Amount_net_vat       = $cell_value;
            }
            else 
            if($dat['line_description'] == 'Gross Sales')            
            {
                $cell_value                = preg_replace('/,/i','',$dat['cell_value']); 
                $total_sales_vat_inclusive = $cell_value;
            }
            else 
            if($dat['line_description'] == 'Total Amount Due')            
            {
                $cell_value                = preg_replace('/,/i','',$dat['cell_value']); 
                $total_amt                 = $cell_value;                                
            }
            else 
            if($dat['line_description'] == 'VAT Exempt Sales')                        
            {
                $cell_value = preg_replace('/,/i','',$dat['cell_value']); 
                $vat_exempt_sales = $cell_value;
            }   
            else  
            if($dat['line_description'] == 'Discount 1- invoice discount' || $dat['line_description'] == 'Discount 2- invoice discount' || $dat['line_description'] == 'Discount 3- invoice discount')                        
            {
                $cell_value = preg_replace('/,/i','',$dat['cell_value']); 
                $invoice_discount += $cell_value;
            }
            else 
            if($dat['line_description'] == 'VAT Amount')            
            {
                $cell_value = preg_replace('/,/i','',$dat['cell_value']); 
                $vat_amount = $cell_value;
                $less_vat   = $cell_value; 
            }
            else 
            if($dat['line_description'] == 'salesman')            
            {
                $salesman = $dat['cell_value'];
            }
            else 
            if($dat['line_description'] == 'Discount 1' || $dat['line_description'] == 'Discount 2' || $dat['line_description'] == 'Discount 3')            
            {
                if(preg_match('/%/i',$dat['cell_value']))
                {
                     $cell_value = preg_replace('/,/i','',$dat['cell_value']);   
                     $cell_value = preg_replace('/%/i','',$cell_value);                        
                     $discount  += $cell_value; 
                }
            }
            else
            if($dat['line_description'] == 'Batch No.')  
            {

                $batch_no = preg_replace('/SO-MPDI-/i','',$dat['cell_value']);
            }


         }

         $current_page_number = 1;
         $total_pages         = $this->get_number_of_pages($Document_num,$tin_no);
         $Page_number_label   = "Page ".$current_page_number." of ".$total_pages;


         $tbl .= $this->get_page_header($border,$Sell_to_Customer_No,$Document_num,$address,$Shipment_Date,$tin_no,$account_type,$salesman,$Page_number_label,$batch_no);   
         $tbl .= $this->get_row_header($border); 

   

         $Description  = ''; 
         $si_details   = $this->Unilab_model->get_specific_si($Document_num);
         $second_line  = ''; // mao nig Lot NO. ExpiryDate ug Qty sa second line sa item
         $line_counter = 0;
         $amount       = '';
          

         foreach($si_details as $si)
         { 
             $cell_data        = $this->get_cell_value($amount,$si['line_description'],$Description,$si['cell_value'],$tbl,$second_line,$line_counter,$style,$discount_total,$amount_total);                
             $tbl              = $cell_data[0];                
             $line_description = $cell_data[1]; 
             $cell_value       = $cell_data[2];
             $Description      = $cell_data[3]; 
             $second_line      = $cell_data[4];  
             $line_counter     = $cell_data[5]; 
             $discount_total   = $cell_data[6]; 
             $amount_total     = $cell_data[7];   
             $amount           = $cell_data[8];       


             //var_dump($discount);
             if($line_counter == 20)
             {                  
                  $tbl                   = $this->sub_total($tbl,$discount_total,$amount_total,$style);

                  if(is_numeric($discount_total))
                  {
                     $grand_discount_total += $discount_total;
                  }
                  else 
                  {
                     $grand_discount_total = '-';  
                  }
                  
                  $grand_amount_total   += $amount_total;
                  $discount_total        = 0.00;
                  $amount_total          = 0.00;   
                  $tbl                   = $this->get_footer($border,$current_page_number,$total_pages,$vatable_sales,$total_sales_vat_inclusive,$vat_exempt_sales,$less_vat,$total_amt,$vat_amount,$Amount_net_vat,$tbl,$discount,$invoice_discount);
              
                  $this->ppdf->writeHTML($tbl, true, false, false, false, '');                                    
                  $line_counter = 0;

                  $this->ppdf->AddPage("P");
                  $this->ppdf->SetAutoPageBreak(false);   
                  $tbl                   = '';
                  $current_page_number  += 1;                                 
                  $total_pages           = $this->get_number_of_pages($Document_num,$tin_no);
                  $Page_number_label     = "Page ".$current_page_number." of ".$total_pages;
                  $tbl                  .= $this->get_page_header($border,$Sell_to_Customer_No,$Document_num,$address,$Shipment_Date,$tin_no,$account_type,$salesman,$Page_number_label,$batch_no);   
                  $tbl                  .= $this->get_row_header($border);        
             }    

          }

          $remaining_line = 20 -$line_counter;   
          if($remaining_line >0)
          {
            
             for($a=0;$a<$remaining_line;$a++)
             {
                    $tbl.= '<tr>
                                   <td style="'.$style.'"></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>     
                                   <td></td>  
                            </tr>';
             }

             $tbl = $this->sub_total($tbl,$discount_total,$amount_total,$style);
             $grand_discount_total += $discount_total;
             $grand_amount_total   += $amount_total;
             $discount_total        = 0.00;
             $amount_total          = 0.00; 


             $tbl = $this->get_footer($border,$current_page_number,$total_pages,$vatable_sales,$total_sales_vat_inclusive,$vat_exempt_sales,$less_vat,$total_amt,$vat_amount,$Amount_net_vat,$tbl,$discount,$invoice_discount);
   
             $this->ppdf->writeHTML($tbl, true, false, false, false, '');               
          } 

         



         ob_end_clean();
         $this->ppdf->Output();            
     }




     public function extract_textfile()
     {
        for($i=0; $i<count($_FILES['files']['name']); $i++)
        {        
            $pop_up = "";

            unset($RESS2);/*clear ang sulod sa array para inig loop sa sunod textfile dili mu sumpay ang sulod*/

            if(!empty($_FILES['files']['tmp_name']))
            {
                
                $fileName = $_FILES['files']['tmp_name'][$i];
        
                $file = fopen($fileName,"r") or exit("Unable to open file!");
            
                while(!feof($file)) 
                {
                    @$RESS2 .= fgets($file). "";
                }
            }

            $ress_sanitize = explode(PHP_EOL, $RESS2);   
             
            for($a=0;$a<count($ress_sanitize);$a++)
            {
                $parts = preg_split('/\s{2,}/', $ress_sanitize[$a]);  //atleast 2 spaces maoy iyahang i split.. 

               /* if(is_numeric($parts[0]))
                {*/

                    //preg_match($pattern, $str);

                    for($b=0;$b<count($parts);$b++)   
                    {
                        echo $parts[$b]."-->";
                    }
                    echo "<br>";
               /* }*/
            }

        }    
     }



     public function line_description_checker($invoice_num,$line_description_checker,$line_description,$string,$a,$unit_price,$line_discount)
     {

        $unit_price    = preg_replace('/,/i','',$unit_price);
        $line_discount = preg_replace('/,/i','',$line_discount);


        if(preg_match('/SO-MPDI-/i',$string))
        {
             $line_description         = 'Batch No.';
             $line_description_checker = 'Batch No.';
        }
        else 
        if( $line_description_checker == 'Deal')
        {
              $this->Unilab_model->insert_unilab_data($invoice_num,'-','Discount %','');
              $this->Unilab_model->insert_unilab_data($invoice_num,$unit_price,'Net Price','');
              $this->Unilab_model->insert_unilab_data($invoice_num,'-','Discount Total',''); 
              $line_description         = 'Amount';
              $line_description_checker = 'Amount';
              
        }
        else 
        if($line_description_checker == 'Unit Price') 
        {
            if(preg_match('/%/i',$string))
            {
                 $line_description         = 'Discount %';
                 $line_description_checker = 'Discount %';
                 $line_discount            = preg_replace('/%/i','',$string);  
                 $this->Unilab_model->insert_unilab_data($invoice_num,'-','Deal','');
                 
            }             
            else  
            { 
                 $this->Unilab_model->insert_unilab_data($invoice_num,'-','Deal','');
                 $this->Unilab_model->insert_unilab_data($invoice_num,'-','Discount %','');
                 $this->Unilab_model->insert_unilab_data($invoice_num,$unit_price,'Net Price','');
                 $this->Unilab_model->insert_unilab_data($invoice_num,'-','Discount Total','');
                 $line_description         = 'Amount';
                 $line_description_checker = 'Amount';                

            }

         
        }
        else 
        if($line_description_checker == 'Lot Qty') 
        {
              if(is_numeric( $string ) && strlen($string) == 6 ) //pag identify if ang gi loop nga entry kay item no. siya
              {
                  $line_description         = 'item No.';
                  $line_description_checker = 'item No.'; 
              }
              else
              {                                                        
                  $line_description         = 'LOT NO';
                  $line_description_checker = 'LOT NO'; 
              }
        }
        else 
        if($line_description_checker == 'Expiry Date') 
        {
             $line_description         = 'Lot Qty';
             $line_description_checker = 'Lot Qty'; 
        }
        else 
        if($line_description_checker == 'LOT NO') 
        {
             $line_description         = 'Expiry Date';
             $line_description_checker = 'Expiry Date'; 
        }
        else 
        if($line_description_checker == 'Amount') 
        {                                                     
             if(is_numeric( $string ) && strlen($string) == 6 ) //pag identify if ang gi loop nga entry kay item no. siya
             {
                 $line_description         = 'item No.';
                 $line_description_checker = 'item No.'; 
             }
             else 
             if($string == 'VE')
             {
                 $line_description         = 'Vat excempt';
                 $line_description_checker = 'Amount';
             }   
             else
             {
                 $line_description         = 'LOT NO';
                 $line_description_checker = 'LOT NO'; 
             }
        }
        else 
        if($line_description_checker == 'UOM') 
        {
            if($string == '0')
            {
                 $line_description         = 'Deal';
                 $line_description_checker = 'Deal';
                 $this->Unilab_model->insert_unilab_data($invoice_num,'-','Unit Price','');
            } 
            else 
            {
                 $line_description         = 'Unit Price';
                 $line_description_checker = 'Unit Price';                                   
                 $unit_price               =  $string;
            }
        }
        else 
        if($line_description_checker == 'QTY') 
        {
             $line_description         = 'UOM';
             $line_description_checker = 'UOM'; 
        }   
        else 
        if($line_description_checker == 'requested uom') 
        {
             $line_description         = 'QTY';
             $line_description_checker = 'QTY';    
        }   
        else  
        if($line_description_checker == 'requested quantity') 
        {
             $line_description         = 'requested uom';
             $line_description_checker = 'requested uom';
        }
        else 
        if($line_description_checker == 'Discription') //if Discription ang value ani. pasabot ani kay ang column na sunod ani kay Description 
        {
            if(is_numeric($string)) //if numeric ni siya pasabot ani  naa nata sa entry nga requested quantity
            {
                 $line_description         = 'requested quantity';
                 $line_description_checker = 'requested quantity';
            }
            else 
            {
                 $line_description         = 'Discription';
                 $line_description_checker = 'Discription';
            }
        }
        else 
        if($line_description_checker == 'item No.') //if item No. ang value ani. pasabot ani kay ang column na sunod ani kay Description 
        {
             $line_description         = 'Discription';
             $line_description_checker = 'Discription';
             $line_discount            = '-';
             $unit_price               = '-';
        }
        else 
        if($line_description_checker == 'Discount Total')  //if Discount Total ang value ani.pasabot ani kay  ang column na sunod ani kay  Amount nga column
        {
             $line_description         = 'Amount';
             $line_description_checker = 'Amount';                                                    
        }
        else 
        if($line_description_checker == 'Discount %')  //if Discount % ang value ani.pasabot ani kay  ang column na sunod ani kay Discount Total ug Amount nga column
        {
             $line_description         = 'Discount Total';
             $line_description_checker = 'Discount Total';  
             //$net_price                = $unit_price - ($unit_price * $line_discount);  
             if($unit_price == '-' || $line_discount == '-')
             {
                $net_price     ='-';
             }  
             else 
             {
               //$line_discount = '0.'.str_replace('.','',$line_discount);
               
               $net_price     = $unit_price - ($unit_price * ($line_discount/100) );  
               //$net_price     = $unit_price."-"."(".$unit_price ."*(".$line_discount."/100) )";  
               $net_price     =number_format($net_price,2);
             }          
             $this->Unilab_model->insert_unilab_data($invoice_num,$net_price,'Net Price','');   
                
             //$this->Unilab_model->insert_unilab_data($invoice_num,$net_price,'Net Price','');       
             //var_dump($unit_price,$line_discount);                                              
        } 
        else 
        if($a == 0)
        {
            $line_description = 'sales invoice';
        } 
        else
        if($a == 1) 
        {
            $line_description = 'customer sold to';                                                
        }
        else
        if($a == 3)
        {
            $line_description = 'salesman';                                                                                                
        }  
        else 
        if($a == 5)
        {
            $line_description = 'invoice date';                                                                                                                                                
        } 
        else  
        if($a == 6 || $a == 7 || $a == 8) 
        {
            $line_description = 'address';                                                
        }
        else 
        if($a == 10)
        {
            $line_description = 'posting date';    
        }    
        else                                                 
        if($a == 11 || $a == 12 || $a == 13 || $a == 14)    
        {
            $line_description = 'page label'; 
        }   
        else
        if($a == 15) 
        {
            $line_description = 'account type'; 
        }
        else                                    
        if($a > 30)    
        {
             $line_description = 'item line';  

             //$if_decimal = preg_replace('/,/i', '', $string);   
             //if(preg_match("/^[0-9,]+$/", $string) && is_float($if_decimal)) //ehck if discount total siya or amount ba
             if(is_numeric( $string ) && strlen($string) == 6 ) //pag identify if ang gi loop nga entry kay item no. siya
             {
                  $if_decimal = explode('.',$string);
                  if(count($if_decimal)>1) //check if discount total siya or amount ba
                  {
                      $line_description = 'Discount total OR amount';                                                            
                  }
                  else 
                  {
                      $line_description         = 'item No.';
                      $line_description_checker = 'item No.';
                  }
             }
             else 
             if($a == 31) //if dili item no.  ang value sa   first item line sa  ani nga page   it means LOT NO ni siya
             {
                  $line_description         = 'LOT NO';
                  $line_description_checker = 'LOT NO';
             }

             if(preg_match('/%/i',$string))
             {
                 $line_description         = 'Discount %';
                 $line_description_checker = 'Discount %';
             } 
         }

        return  array($line_description,$line_description_checker,$unit_price,$line_discount);

     }  







     public function extract_pdf()
     {

        for($i=0; $i<count($_FILES['files']['name']); $i++)
        {
            if(!empty($_FILES["files"]["name"]))
            {                             
               /* $PDFfileName = $_FILES['files']['tmp_name'][$i];*/
                $PDFfileName = basename($_FILES["files"]["name"][$i]); 
                $PDFfileType = pathinfo($PDFfileName, PATHINFO_EXTENSION); 
                     
                include realpath('assets/pdf_extract/vendor/autoload.php'); 
               
                $allowTypes = array('pdf'); 
                //var_dump($PDFfileType);
                if(in_array($PDFfileType, $allowTypes))
                { 
                     $parser   = new \Smalot\PdfParser\Parser(); 
                     // Source file
                     $PDFfile  = $_FILES["files"]["tmp_name"][$i]; 
                     $PDF      = $parser->parseFile($PDFfile); 
                     $fileText = $PDF->getText();                          
                     // line break 
                     $PDFContent = nl2br($fileText); 
                     
                     // var_dump($data[1][1]);
                      //var_dump(count($data));
                     //var_dump($data);
                     $number_of_pages = count($PDF->getPages()); 

                     $data = $PDF->getPages()[0]->getDataTm(); // checkon ang first page , pangitaon ang SI number para  mag checking sa database if existing naba ning data or wala pa
                     if(substr($data[0][1],0,5) == 'MPDI-')    
                     {
                         $checking_si = $this->Unilab_model->check_existing_si($data[0][1]); //isearch ning SI number if naa naba sa database.if naa na.dili i proceed
                         if(empty($checking_si)) 
                         {                             
                             

                             for($b=0;$b<$number_of_pages;$b++) //looping ug pila ka pages
                             {
                                 $page_number = $b+1;
                                 $data        = $PDF->getPages()[$b]->getDataTm();                                
                              
                                 $unit_price    = 0;
                                 $line_discount = 0;

                                 if(($number_of_pages-1) == $b) //if last page na siya
                                 {
                                     
                                     $last_line                = ''; 
                                     $line_description_checker = '';//current value sa array nga gi loop
                                     $line_description         = ''; 
                                     for($a=0;$a<count($data);$a++)
                                     {

                                         $label          = array('VATable Sales','Gross Sales','Zero Rated Sales','Discount 1','VAT Exempt Sales','Discount 2','VAT Amount','Discount 3','Total Amount Due','TOTAL INVOICE ');
                                         $plus_index     = array(1,2,1,1,1,1,1,1,1,3);
                                         $discount_label = array('Discount 1','Discount 2','Discount 3');


                                         if($data[$a][1] == 'VATable Sales') //if makit an nani nga line pasabot ana nahuman na ang mga item lines ug loop
                                         {
                                            $last_line = 'wa nay item line';                                             
                                         }                                         
                                         else
                                         if($a > 30  && $last_line == '')     
                                         {
                                              
                                              $string                   = $data[$a][1];
                                              $checker_arr              =  $this->line_description_checker($data[0][1],$line_description_checker,$line_description,$string,$a,$unit_price,$line_discount);
                                              
                                              $line_description         = $checker_arr[0];
                                              $line_description_checker = $checker_arr[1];
                                              $unit_price               = $checker_arr[2]; 
                                              $line_discount            = $checker_arr[3];


                                              $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);                                                                                                                                                    
                                         }  
                                        /* if($a > 30  && $last_line == '')    
                                         {
                                              $line_description = 'item line'; 
                                              $string           = $data[$a][1];
                                              $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);                                                                                                                                                    
                                         


                                         }*/
                                         
                                         for($c=0;$c<count($label);$c++)
                                         {
                                             if($data[$a][1] == $label[$c])  
                                             {
                                                //echo $data[$a][1]."<br>";  
                                                $index            = $a+$plus_index[$c];
                                                $line_description = $label[$c]; 
                                                $string           = $data[$index][1];

                                                $if_disc =  preg_match('/%/i', $string );

                                                if( $if_disc == 1 && in_array($label[$c],$discount_label) )
                                                {                                                    
                                                     $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);
                                                     $string           = $data[$index+1][1];
                                                     $line_description = $label[$c].'- invoice discount'; 
                                                     $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);                                                   
                                                }
                                                else 
                                                if( $if_disc == 0 &&  in_array($label[$c],$discount_label) ) //if walay gibutang nga discount
                                                {
                                                     $this->Unilab_model->insert_unilab_data($data[0][1],'-',$line_description,$page_number);
                                                     $string           = $data[$index][1];
                                                     $line_description = $label[$c].'- invoice discount'; 
                                                     $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);                                                   
                                                }
                                                else 
                                                {                                                                                                         

                                                     $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);                                                    
                                                }
                                             } 




                                         }
                                     }                                   
                                 }
                                 else 
                                 {
                                         $line_description_checker = '';//current value sa array nga gi loop
                                         for($a=0;$a<count($data);$a++)
                                         {
                                                $string           = $data[$a][1];                                               
                                                $line_description = '';

                                                $checker_arr              =  $this->line_description_checker($data[0][1],$line_description_checker,$line_description,$string,$a,$unit_price,$line_discount);
                                                $line_description         = $checker_arr[0];
                                                $line_description_checker = $checker_arr[1];
                                                $unit_price               = $checker_arr[2]; 
                                                $line_discount            = $checker_arr[3];                                                
                                               
                                                $this->Unilab_model->insert_unilab_data($data[0][1],$string,$line_description,$page_number);
                                         }                                              
                                  }   
                                            
                             }
                             $response = 'success';
                         }
                         else 
                         {
                            //echo $data[0][1]."<---- this Sales Invoice already existed in database ";
                            $response = "Sales Invoice:".$data[0][1]."  already existed in database ";
                         }   
                     }        


                }
                else
                { 
                     $response = 'only PDF file is allowed to upload.'; 
                } 
            }else{ 
                $response = 'Please select a file.'; 
            }            
             

            //echo $PDFContent;
        }     
        $data['response'] = $response;
        echo json_encode($data);
     }  


     public function extract_pdf_()
     {       
        $data['file_name']  = $_FILES["files"]["name"];  
        $data['tmp_name']   =  $_FILES["files"]["tmp_name"];  


         
        require(APPPATH."third_party/PHPExcel/Classes/PHPExcel.php");
        require_once APPPATH."third_party/PHPExcel/Classes/PHPExcel/IOFactory.php";
        

        for($i=0; $i<count($_FILES['files']['name']); $i++)
        {
            unset($RESS2);/*clear ang sulod sa array para inig loop sa sunod textfile dili mu sumpay ang sulod*/

            if(!empty($_FILES['files']['tmp_name'])):
                
                $inputFile = $_FILES['files']['tmp_name'][$i];


                $fileObj=PHPExcel_IOFactory::load($inputFile);
                $sheetObj=$fileObj->getActiveSheet();
                $startFrom=1;
                //$limit=null;
                $limit=9;

                foreach ($sheetObj->getRowIterator($startFrom,$limit) as $row) 
                {
                    $RESS2="";
                    foreach ($row->getCellIterator() as $cell) 
                    {
                        $value=$cell->getCalculatedValue();
                        $RESS2.=$value.",";

                    }
                    echo $RESS2."<br>";
                }

        
                /*$file = fopen($fileName,"r") or exit("Unable to open file!");
            
                while(!feof($file)) {
                    @$RESS2 .= fgets($file). "";
                }*/
            endif;

            /*$ress_sanitize = explode(PHP_EOL, $RESS2);

            var_dump($ress_sanitize); */

            //https://www.youtube.com/watch?v=p2304BjvrB8

        }    




        
        //$this->load->view('unilab/pdf_extractor_ui',$data);
     }




}
