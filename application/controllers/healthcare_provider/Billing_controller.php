<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Billing_controller extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        $this->load->model('healthcare_provider/billing_model');
        $this->load->model('healthcare_provider/loa_model');
        $this->load->model('healthcare_provider/noa_model');
        $this->load->model('healthcare_provider/initial_billing_model');
        $this->load->model('ho_accounting/list_model');
        $user_role = $this->session->userdata('user_role');
        $logged_in = $this->session->userdata('logged_in');
        if ($logged_in !== true && $user_role !== 'healthcare-provider') {
            redirect(base_url());
        }
    } 

    function redirectBack() {
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('location:' . $_SERVER['HTTP_REFERER']);
        } else {
            header('location:http://' . $_SERVER['SERVER_NAME']);
        }
        exit();
    }

    public function get_personal_and_company_charge($label,$loa_noa,$net_b,$status,$prevmbl,$old_billing) {
        
        $loa_info = $this->loa_model->db_get_loa_info($loa_noa);
        $noa_info = $this->noa_model->db_get_noa_info($loa_noa);
       
			$company_charge = 0;
			$personal_charge = 0;
			$remaining_mbl = 0;
			
			$wpercent = '';
			$nwpercent = '';
			$net_bill = floatval($net_b);
            $last_bill = ($old_billing != null)? floatval($old_billing): 0;
            // var_dump("status",$status);
            // var_dump("prev mbl",$prevmbl);

			if($label === "loa"){
                
                if( date('Y', strtotime($loa_info['request_date'])) < date('Y') && $last_bill != floatval($loa_info['remaining_balance']) && $old_billing != null){     
                    $previous_mbl = ($status) ? floatval($prevmbl) : $last_bill;

                }else if( date('Y', strtotime($loa_info['request_date'])) == date('Y') && $last_bill != floatval($loa_info['remaining_balance']) && $old_billing != null){
                    $previous_mbl = ($status) ? floatval($prevmbl) : floatval($loa_info['remaining_balance']);
                   
                }
                else if( date('Y', strtotime($loa_info['request_date'])) == date('Y') && $last_bill != floatval($loa_info['remaining_balance']) && $old_billing == null){
                    $previous_mbl = ($status) ? floatval($prevmbl) : floatval($loa_info['remaining_balance']);
                   
                }
                else{
                    $previous_mbl = ($status) ? floatval($prevmbl) : $last_bill;
                   
                }
                
                $used_mbl = floatval($loa_info['used_mbl']);
                $max_mbl = floatval($loa_info['max_benefit_limit']);
                // var_dump($previous_mbl);
				if($loa_info['work_related'] == 'Yes'){ 
					if($loa_info['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $loa_info['percentage'].'%  W-R';
					   $result = 100 - floatval($loa_info['percentage']);
					   if($loa_info['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($loa_info['work_related'] == 'No'){
				   if($loa_info['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $loa_info['percentage'].'% Non W-R';
					   $result = 100 - floatval($loa_info['percentage']);
					   if($loa_info['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			   $percentage = floatval($loa_info['percentage']);

			if($loa_info['work_related'] == 'Yes'){
				if($loa_info['percentage'] == ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        $company_charge = $net_bill;
                        $personal_charge = 0;
                        if($net_bill >= $previous_mbl){
                            $remaining_mbl = 0;
                        }else if($net_bill < $previous_mbl){
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }
                    }
					
                    
				}else if($loa_info['percentage'] != ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }else if($net_bill > $previous_mbl){
                            $converted_percent = $percentage/100;
                            $initial_company_charge = floatval($converted_percent) * $net_bill;
                            $initial_personal_charge = $net_bill - floatval($initial_company_charge);                     
                            if(floatval($initial_company_charge) <= $previous_mbl){
                                $result = $previous_mbl - floatval($initial_company_charge);
                                $int_personal = floatval($initial_personal_charge) - floatval($result);
                                $personal_charge = $int_personal;
                                $company_charge = $previous_mbl;
                                $remaining_mbl = 0;
                        
                            }else if(floatval($initial_company_charge) > $previous_mbl){
                                $personal_charge = $initial_personal_charge;
                                $company_charge = $initial_company_charge;
                                $remaining_mbl = 0;
                            }
                            
                        }
                    }
				}
			}else if($loa_info['work_related'] == 'No'){
				if($loa_info['percentage'] == ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $company_charge;
                        }else if($net_bill > $previous_mbl){
                            $company_charge = $previous_mbl;
                            $personal_charge = $net_bill - $previous_mbl;
                            $remaining_mbl = 0;
                        }
                    }
					
                    
				}else if($loa_info['percentage'] != ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }else if($net_bill > $previous_mbl){
                            $converted_percent = $percentage/100;
                            $initial_personal_charge = $converted_percent * $net_bill;
                            $initial_company_charge = $net_bill - floatval($initial_personal_charge);
                        
                            if($initial_company_charge <= $previous_mbl){
                                $result = $previous_mbl - $initial_company_charge;
                                $initial_personal = $initial_personal_charge - $result;
                                if($initial_personal < 0 ){
                                    $personal_charge = 0;
                                    $company_charge = $initial_company_charge + $initial_personal_charge;
                                    $remaining_mbl = $previous_mbl - floatval($company_charge);
                                }else if($initial_personal >= 0){
                                    $personal_charge = $initial_personal;
                                    $company_charge = $previous_mbl;
                                    $remaining_mbl = 0;
                                }
                            }else if($initial_company_charge > $previous_mbl){
                                $personal_charge = $initial_personal_charge;
                                $company_charge = $initial_company_charge;
                                $remaining_mbl = 0;
                            }
                        }
                    }
				}
			}

			}else if($label === "noa"){

                if( date('Y', strtotime($noa_info['request_date'])) < date('Y') && $last_bill != floatval($noa_info['remaining_balance']) && $old_billing != null){     
                    $previous_mbl = ($status) ? floatval($prevmbl) : floatval($noa_info['remaining_balance']);
                }else if( date('Y', strtotime($noa_info['request_date'])) == date('Y') && $last_bill != floatval($noa_info['remaining_balance']) && $old_billing != null){
                    $previous_mbl = ($status) ? floatval($prevmbl) : floatval($noa_info['remaining_balance']);
                }
                else if( date('Y', strtotime($noa_info['request_date'])) == date('Y') && $last_bill != floatval($noa_info['remaining_balance']) && $old_billing == null){
                    $previous_mbl = ($status) ? floatval($prevmbl) : floatval($noa_info['remaining_balance']);
                }
                else{
                    $previous_mbl = ($status) ? floatval($prevmbl) : $last_bill;
                }
                
                $used_mbl = floatval($noa_info['used_mbl']);
                $max_mbl = floatval($noa_info['max_benefit_limit']);
                // var_dump($previous_mbl);
				if($noa_info['work_related'] == 'Yes'){ 
					if($noa_info['percentage'] == ''){
					   $wpercent = '100% W-R';
					   $nwpercent = '';
					}else{
					   $wpercent = $noa_info['percentage'].'%  W-R';
					   $result = 100 - floatval($noa_info['percentage']);
					   if($noa_info['percentage'] == '100'){
						   $nwpercent = '';
					   }else{
						   $nwpercent = $result.'% Non W-R';
					   }
					  
					}	
			   }else if($noa_info['work_related'] == 'No'){
				   if($noa_info['percentage'] == ''){
					   $wpercent = '';
					   $nwpercent = '100% Non W-R';
					}else{
					   $nwpercent = $noa_info['percentage'].'% Non W-R';
					   $result = 100 - floatval($noa_info['percentage']);
					   if($noa_info['percentage'] == '100'){
						   $wpercent = '';
					   }else{
						   $wpercent = $result.'%  W-R';
					   }
					 
					}
			   }

			   $percentage = floatval($noa_info['percentage']);

			if($noa_info['work_related'] == 'Yes'){
               
				if($noa_info['percentage'] == ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        $company_charge = $net_bill;
                        $personal_charge = 0;
                        if($net_bill >= $previous_mbl){
                            $remaining_mbl = 0;
                        }else if($net_bill < $previous_mbl){
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }
                    }
					
                    
				}else if($noa_info['percentage'] != ''){
					if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $net_bill;
                        }else if($net_bill > $previous_mbl){
                            $converted_percent = $percentage/100;
                            $initial_company_charge = floatval($converted_percent) * $net_bill;
                            $initial_personal_charge = $net_bill - floatval($initial_company_charge);
                            
                            if(floatval($initial_company_charge) <= $previous_mbl){
                                $result = $previous_mbl - floatval($initial_company_charge);
                                $int_personal = floatval($initial_personal_charge) - floatval($result);
                                $personal_charge = $int_personal;
                                $company_charge = $previous_mbl;
                                $remaining_mbl = 0;
                        
                            }else if(floatval($initial_company_charge) > $previous_mbl){
                                $personal_charge = $initial_personal_charge;
                                $company_charge = $initial_company_charge;
                                $remaining_mbl = 0;
                            }
                        }
                    }
					
				}
			}else if($noa_info['work_related'] == 'No'){
				if($noa_info['percentage'] == ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - $company_charge;
                        }else if($net_bill > $previous_mbl){
                            $company_charge = $previous_mbl;
                            $personal_charge = $net_bill - $previous_mbl;
                            $remaining_mbl = 0;
                        }
                    }
					
                   
				}else if($noa_info['percentage'] != ''){
                    if($previous_mbl <= 0){
                        $company_charge = 0;
                        $personal_charge =  $net_bill;
                        $remaining_mbl =  0;
                    }else{
                        if($net_bill <= $previous_mbl){
                            $company_charge = $net_bill;
                            $personal_charge = 0;
                            $remaining_mbl = $previous_mbl - floatval($net_bill);
                        }else if($net_bill > $previous_mbl){
                            $converted_percent = $percentage/100;
                            $initial_personal_charge = $converted_percent * $net_bill;
                            $initial_company_charge = $net_bill - floatval($initial_personal_charge);
                            
                            if($initial_company_charge <= $previous_mbl){
                                $result = $previous_mbl - $initial_company_charge;
                                $initial_personal = $initial_personal_charge - $result;
                                if($initial_personal < 0 ){
                                    $personal_charge = 0;
                                    $company_charge = $initial_company_charge + $initial_personal_charge;
                                    $remaining_mbl = $previous_mbl - floatval($company_charge);
                                }else if($initial_personal >= 0){
                                    $personal_charge = $initial_personal;
                                    $company_charge = $previous_mbl;
                                    $remaining_mbl = 0;
                                }
                            }else if($initial_company_charge > $previous_mbl){
                                $personal_charge = $initial_personal_charge;
                                $company_charge = $initial_company_charge;
                                $remaining_mbl = 0;
                            }
                            
                        }
                    }
				}
			}
			}
            
            $data = array(
                'company_charge' => $company_charge,
                'personal_charge' => $personal_charge,
                'remaining_balance' =>$rmbl = $remaining_mbl,
                'used_mbl' =>  (($max_mbl-$rmbl)>0)? $max_mbl-$rmbl : $max_mbl,
                'previous_mbl' => $previous_mbl,
            );
			return  $data;
	}
    function billing_search_member() {
        $data['user_role'] = $this->session->userdata('user_role');
        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/search_member');
        $this->load->view('templates/footer');
    }

    function search_member_by_name() {
        $this->security->get_csrf_hash();
        $first_name = $this->security->xss_clean($this->input->post('first_name'));
        $last_name = $this->security->xss_clean($this->input->post('last_name'));
        $date_of_birth = $this->security->xss_clean($this->input->post('date_of_birth'));
        $hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
        $member = $this->billing_model->get_member_by_name($first_name, $last_name, $date_of_birth);

        if (!$member) {
            $arr = ['error' => 'No Members Found!'];
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        } else {
            $data['member'] = $member;
            $data['user_role'] = $this->session->userdata('user_role');
            $data['member_mbl'] = $member_mbl = $this->billing_model->get_member_mbl($member['emp_id']);
            $data['hp_name'] = $hp_name = $this->billing_model->get_healthcare_provider($hcare_provider_id);
            $data['loa_requests'] = $this->billing_model->get_member_loa($member['emp_id'], $hcare_provider_id);
            $data['noa_requests'] = $this->billing_model->get_member_noa($member['emp_id'], $hcare_provider_id);
            $data['re_upload_requests'] = $this->billing_model->get_re_upload_requests($hcare_provider_id,$member['emp_id']);
            /* This is checking if the image file exists in the directory. */
            $file_path = './uploads/profile_pics/' . $member['photo'];
            $data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

            $this->session->set_userdata([
                'b_member_info'    => $member,
                'b_member_mbl'     => $member_mbl['max_benefit_limit'],
                'b_member_bal'     => $member_mbl['remaining_balance'],
                'b_hcare_provider' => $hp_name,
                'b_healthcard_no'  => $member['health_card_no'],
            ]);

            $this->load->view('templates/header', $data);
            $this->load->view('healthcare_provider_panel/billing/search_member_result');
            $this->load->view('templates/footer');
        }
    }

    function search_member_by_healthcard() {
        $this->security->get_csrf_hash();
        $healthcard_no = $this->security->xss_clean($this->input->post('healthcard_no'));
        $hcare_provider_id =  $this->session->userdata('dsg_hcare_prov');
        $member = $this->billing_model->get_member_by_healthcard($healthcard_no);

        if (!$member) {
            $arr = ['error' => 'No Members Found!'];
            $this->session->set_flashdata($arr);
            $this->redirectBack();
        } else {
            $data['member'] = $member;
            $data['user_role'] = $this->session->userdata('user_role');
            $data['member_mbl'] = $member_mbl = $this->billing_model->get_member_mbl($member['emp_id']);
            $data['hp_name'] = $hp_name = $this->billing_model->get_healthcare_provider($hcare_provider_id);
            $data['loa_requests'] = $this->billing_model->get_member_loa($member['emp_id'], $hcare_provider_id);
            $data['noa_requests'] = $this->billing_model->get_member_noa($member['emp_id'], $hcare_provider_id);
            $data['re_upload_requests'] = $this->billing_model->get_re_upload_requests($hcare_provider_id,$member['emp_id']);
            //var_dump($data['re_upload_requests']);
            /* This is checking if the image file exists in the directory. */
            $file_path = './uploads/profile_pics/' . $member['photo'];
            $data['member_photo_status'] = file_exists($file_path) ? 'Exist' : 'Not Found';

            $this->session->set_userdata([
                'b_member_info'    => $member,
                'b_member_mbl'     => $member_mbl['max_benefit_limit'],
                'b_member_bal'     => $member_mbl['remaining_balance'], 
                'b_hcare_provider' => $hp_name,
                'b_healthcard_no'  => $member['health_card_no'],
            ]);

            $this->load->view('templates/header', $data);
            $this->load->view('healthcare_provider_panel/billing/search_member_result');
            $this->load->view('templates/footer');
        }
    }

    function bill_patient_loa() {
        $this->security->get_csrf_hash();
        $url_id = $this->uri->segment(5); // encrypted id
        $loa_id = $this->myhash->hasher($url_id, 'decrypt');
        // $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $loa = $this->billing_model->get_loa_to_bill($loa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_cost_types_by_hp($loa['hcare_provider']);
        $data['loa'] = $loa;
        $data['request_type'] = $loa["loa_request_type"];
        $data['work_related'] = $loa["work_related"];
        $data['member'] = $this->session->userdata('b_member_info');
        $data['member_mbl'] = $this->session->userdata('b_member_mbl');
        $data['remaining_balance'] = $this->session->userdata('b_member_bal');
        $data['healthcard_no'] = $this->session->userdata('b_healthcard_no');
        $data['billing_no'] = "BLN-" . strtotime(date('Y-m-d h:i:s'));
        $data['loa_id'] = $url_id;
        $data['loa_no'] = $loa['loa_no'];
        $data['billed_by'] = $this->session->userdata('fullname');
        $data['hcare_provider'] = $hcare_provider['hp_name'];

        $view_page = '';
        if($loa["loa_request_type"] == 'Diagnostic Test'){
            $view_page = 'bill_patient_diagnostic_test_loa';
        }elseif($loa["loa_request_type"] == 'Consultation'){
            $view_page = 'bill_patient_consultation_loa';
        }

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/'.$view_page);
        $this->load->view('templates/footer');
    }

    function fetch_loa_to_bill() {
        $this->security->get_csrf_hash();
        $loa_id = $this->myhash->hasher($this->input->post('loa_id'), 'decrypt');
        $loa = $this->billing_model->get_loa_to_bill($loa_id);

       /* Exploding the string and then pushing it to an array. */
        $med_services = [];
        $exploded_med_services = explode(";", $loa['med_services']);

        foreach ($exploded_med_services as $ctype_id) :
            $cost_type = $this->billing_model->get_cost_type_by_id($ctype_id);
            array_push($med_services, $cost_type['cost_type']);
        endforeach;

        $data = [
            'token'             => $this->security->get_csrf_hash(),
            'user_role'         => $this->session->userdata('user_role'),
            'member_mbl'        => $this->session->userdata('b_member_mbl'),
            'remaining_balance' => $this->session->userdata('b_member_bal'),
            'loa_services'      => $med_services,
            'request_type'      => $loa["loa_request_type"],
        ];

        echo json_encode($data);
    }

    
    function diagnostic_loa_final_billing(){
        $token = $this->security->get_csrf_hash();
        // decrypt encrypted id from url
        $loa_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        // get all input values from form with added XSS filter
        $posted_data =  $this->input->post(NULL, TRUE);
        // request type for reference 
        $type = 'LOA';
        // call insert_patient_billing() function
        $inserted = $this->insert_patient_billing($type, $posted_data, $loa_id);

        if($inserted){
            // if patients billing info is saved to DB call insert_billing_services() function
            $this->insert_billing_services($posted_data['ct-name'], $posted_data['ct-qty'], $posted_data['ct-fee'], $posted_data['billing-no']);

            // if there are added medications
            if($posted_data['medication-count'] > 0){
                $this->insert_medications($posted_data['medication-name'], $posted_data['medication-qty'], $posted_data['medication-fee'], $posted_data['billing-no']);
            }

            // if there are added professional fees
            if($posted_data['profee-count'] > 0){
                $this->insert_professional_fees($posted_data['prodoc-name'], $posted_data['profee-amount'], $posted_data['billing-no']);
            }

            // if Philhealth deduction has value
            if($posted_data['philhealth-deduction'] > 0){
                $this->insert_philhealth_deduction($posted_data['philhealth-deduction'], $posted_data['billing-no']);
            }

            // if SSS deduction has value
            // if($posted_data['sss-deduction'] > 0){
            //     $this->insert_sss_deduction($posted_data['sss-deduction'], $posted_data['billing-no']);
            // }
            // if the dynamic deductions exists 
            if($posted_data['deduction-count'] > 0){
                $this->insert_other_deductions($posted_data['deduction-name'], $posted_data['deduction-amount'], $posted_data['billing-no']);
            }
            // if personal charges has amount
            // if($posted_data['personal-charge'] > 0){
            //     $this->insert_personal_charge($type, $posted_data['emp-id'], $loa_id, $posted_data['personal-charge'], $posted_data['billing-no']);
            // }
            // call to function that updates member's credit limit
            // $this->update_member_mbl($posted_data);       
            // call to function that updates loa request status
            $this->update_request_status($type, $loa_id);
            // call to a private function that deletes the temporary session userdata
            $this->_unset_session_data();
            // get billing info based on billing number
            $bill = $this->billing_model->get_billing($posted_data['billing-no']);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            $response = [
                'token'      => $token,
                'status'     => 'success',
                'message'    => 'Billed Successfully',
                'billing_id' => $encrypted_id,
            ];
        }else{
            $response = [
                'token'   => $token,
                'status'  => 'error',
                'message' => 'Bill Transaction Failed',
            ];
        }

        echo json_encode($response);
    }

    private function _unset_session_data(){
        $temp_data = [
            'b_member_info',
            'b_member_mbl',
            'b_member_bal',
            'b_hcare_provider',
            'b_healthcard_no'
        ];
		$this->session->unset_userdata($temp_data);
    }

    function consultation_loa_final_billing(){
        $token = $this->security->get_csrf_hash();
        // decrypt encrypted id from url
        $loa_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        // get all input values from form with added XSS filter
        $posted_data =  $this->input->post(NULL, TRUE);
        $type = 'LOA';
        // call to insert_patient_billing() function
        $inserted = $this->insert_patient_billing($type, $posted_data, $loa_id);

        if($inserted){
            // insert consultation service
            $services = [
                'service_name'     => $posted_data['consultation'],
                'service_quantity' => $posted_data['consult-quantity'],
                'service_fee'      => $posted_data['consult-fee'],
                'billing_no'       => $posted_data['billing-no'],
                'added_on'         => date('Y-m-d')
            ];

            $this->billing_model->insert_consultation_billing_services($services);
            // if Philhealth deduction has value
            if($posted_data['philhealth-deduction'] > 0){
                $this->insert_philhealth_deduction($posted_data['philhealth-deduction'], $posted_data['billing-no']);
            }

            // if the dynamic deductions exists
            if($posted_data['deduction-count'] > 0){
                $this->insert_other_deductions($posted_data['deduction-name'], $posted_data['deduction-amount'], $posted_data['billing-no']);
            }
            // if personal charges has amount
            // if($posted_data['personal-charge'] > 0){
            //     $this->insert_personal_charge($type, $posted_data['emp-id'], $loa_id, $posted_data['personal-charge'], $posted_data['billing-no']);
            // }
            // call to function that updates member's credit limit
            // $this->update_member_mbl($posted_data);     

            // call to function that updates loa request status
            $this->update_request_status($type, $loa_id);  
            // call to a private function that deletes the temporary session userdata
            $this->_unset_session_data();

            // get billing info based on billing number
            $bill = $this->billing_model->get_billing($posted_data['billing-no']);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            
            $response = [
                'token'      => $token,
                'status'     => 'success',
                'message'    => 'Billed Successfully',
                'billing_id' => $encrypted_id
            ];
        }else{
            $response = [
                'token'   => $token,
                'status'  => 'error',
                'message' => 'Bill Transaction Failed'
            ];
        }

        echo json_encode($response);
    }

    function loa_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(6), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

    function bill_patient_noa() {
        $this->security->get_csrf_hash();
        $url_id = $this->uri->segment(5); // encrypted id
        $noa_id = $this->myhash->hasher($url_id, 'decrypt');
        // $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $noa = $this->billing_model->get_noa_to_bill($noa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_hospital_cost_types($noa['hospital_id']);
        $data['noa'] = $noa;
        $data['member'] = $this->session->userdata('b_member_info');
        $data['member_mbl'] = $this->session->userdata('b_member_mbl');
        $data['remaining_balance'] = $this->session->userdata('b_member_bal');
        $data['work_related'] = $noa["work_related"];
        $data['healthcard_no'] = $this->session->userdata('b_healthcard_no');
        $data['billing_no'] = "BLN-" . strtotime(date('Y-m-d h:i:s'));
        $data['noa_id'] = $url_id;
        $data['noa_no'] = $noa['noa_no'];
        $data['billed_by'] = $this->session->userdata('fullname');
        $data['hcare_provider'] = $hcare_provider['hp_name'];
        $data['rooms'] = $this->billing_model->get_hospital_room_types($noa['hospital_id']);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/bill_patient_noa');
        $this->load->view('templates/footer');
    }
    function re_upload_bill_patient() {
        $this->security->get_csrf_hash();
        $url_id = $this->uri->segment(5); // encrypted id
        $noa_id = $this->myhash->hasher($url_id, 'decrypt');
        // $emp_id = $this->input->post('emp_id', TRUE);

        $hcare_provider = $this->billing_model->get_healthcare_provider_by_id($this->session->userdata('dsg_hcare_prov'));
        $noa = $this->billing_model->get_noa_to_bill($noa_id);

        $data['user_role'] = $this->session->userdata('user_role');
        $data['cost_types'] = $this->billing_model->get_hospital_cost_types($noa['hospital_id']);
        $data['noa'] = $noa;
        $data['member'] = $this->session->userdata('b_member_info');
        $data['member_mbl'] = $this->session->userdata('b_member_mbl');
        $data['remaining_balance'] = $this->session->userdata('b_member_bal');
        $data['work_related'] = $noa["work_related"];
        $data['healthcard_no'] = $this->session->userdata('b_healthcard_no');
        $data['billing_no'] = "BLN-" . strtotime(date('Y-m-d h:i:s'));
        $data['noa_id'] = $url_id;
        $data['noa_no'] = $noa['noa_no'];
        $data['billed_by'] = $this->session->userdata('fullname');
        $data['hcare_provider'] = $hcare_provider['hp_name'];
        $data['rooms'] = $this->billing_model->get_hospital_room_types($noa['hospital_id']);

        $this->load->view('templates/header', $data);
        $this->load->view('healthcare_provider_panel/billing/bill_patient_noa');
        $this->load->view('templates/footer');
    }

    function noa_final_billing(){
        $token = $this->security->get_csrf_hash();
        // decrypt encrypted id from url
        $noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        // get all input values from form with added XSS filter
        $posted_data =  $this->input->post(NULL, TRUE);
        // request type for reference 
        $type = 'NOA';
        // call insert_patient_billing() function
        $inserted = $this->insert_patient_billing($type, $posted_data, $noa_id);

        if($inserted){
            // if patients billing info is saved to DB call insert_billing_services() function
            $this->insert_billing_services($posted_data['ct-names'], $posted_data['ct-qtys'], $posted_data['ct-fees'], $posted_data['billing-no']);
            // if there are added medications
            if($posted_data['medication-count'] > 0){
                $this->insert_medications($posted_data['medication-name'], $posted_data['medication-qty'], $posted_data['medication-fee'], $posted_data['billing-no']);
            }
            // if there are added professional fees
            if($posted_data['profee-count'] > 0){
                $this->insert_professional_fees($posted_data['prodoc-name'], $posted_data['profee-amount'], $posted_data['billing-no']);
            }
            // if Philhealth deduction has value
            if($posted_data['philhealth-deduction'] > 0){
                $this->insert_philhealth_deduction($posted_data['philhealth-deduction'], $posted_data['billing-no']);
            }
           
            // if the dynamic deductions exists 
            if($posted_data['deduction-count'] > 0){
                $this->insert_other_deductions($posted_data['deduction-name'], $posted_data['deduction-amount'], $posted_data['billing-no']);
            }
            // if personal charges has amount
            // if($posted_data['personal-charge'] > 0){
            //     $this->insert_personal_charge($type, $posted_data['emp-id'], $noa_id, $posted_data['personal-charge'], $posted_data['billing-no']);
            // }

            $this->insert_room_board($posted_data);

            // call to function that updates member's credit limit
            // $this->update_member_mbl($posted_data);       
            // call to function that updates noa request status
            $this->update_request_status($type, $noa_id);
            // call to a private function that deletes the temporary session userdata
            $this->_unset_session_data();
            // get billing info based on billing number
            $bill = $this->billing_model->get_billing($posted_data['billing-no']);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            $response = [
                'token'      => $token,
                'status'     => 'success',
                'message'    => 'Billed Successfully',
                'billing_id' => $encrypted_id
            ];
        }else{
            $response = [
                'token'   => $token,
                'status'  => 'error',
                'message' => 'Bill Transaction Failed'
            ];
        }
        echo json_encode($response);
    }

    function noa_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/billing_success');
		$this->load->view('templates/footer');
    }

    function insert_patient_billing($type, $posted_data, $id){
        $work_related = $posted_data['work-related'];
        $net_bill = $posted_data['net-bill'];
        $remaining_bal = $posted_data['remaining-balance'];

        if($work_related == 'Yes' && $net_bill > $remaining_bal){
            $after_billing_balance = 0;
        }else{
            $after_billing_balance = $remaining_bal - $net_bill;
        }

        if($type === 'LOA'){
            $data = [
                'billing_no'            => $posted_data['billing-no'],
                'billing_type'          => 'Manual Billing',
                'emp_id'                => $posted_data['emp-id'],
                'loa_id'                => $id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $posted_data['work-related'],
                'total_services'        => $posted_data['total-services'],
                'total_medications'     => $posted_data['total-medications'],
                'total_pro_fees'        => $posted_data['total-profees'],
                'total_room_board'      => $posted_data['total-roomboard'],
                'total_bill'            => $posted_data['total-bill'],
                'total_deduction'       => $posted_data['total-deduction'],
                'net_bill'              => $posted_data['net-bill'],
                // 'company_charge'        => $posted_data['company-charge'],
                // 'personal_charge'       => $posted_data['personal-charge'],
                'before_remaining_bal'  => $posted_data['remaining-balance'],
                // 'after_remaining_bal'   => $after_billing_balance,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed'
            ];      
        }else if($type === 'NOA'){
            $data = [
                'billing_no'            => $posted_data['billing-no'],
                'billing_type'          => 'Manual Billing',
                'emp_id'                => $posted_data['emp-id'],
                'noa_id'                => $id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $posted_data['work-related'],
                'total_services'        => $posted_data['total-services'],
                'total_medications'     => $posted_data['total-medications'],
                'total_pro_fees'        => $posted_data['total-profees'],
                'total_room_board'      => $posted_data['total-roomboard'],
                'total_bill'            => $posted_data['total-bill'],
                'total_deduction'       => $posted_data['total-deduction'],
                'net_bill'              => $posted_data['net-bill'],
                // 'company_charge'        => $posted_data['company-charge'],
                // 'personal_charge'       => $posted_data['personal-charge'],
                'before_remaining_bal'  => $posted_data['remaining-balance'],
                'after_remaining_bal'   => $after_billing_balance,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed'
            ];      
        }
        // return value is either TRUE or FAlSE
        return $this->billing_model->insert_billing($data);
    }

    function insert_billing_services($ct_names, $ct_quantities, $ct_fees, $billing_no){
        $services = [];
        for ($x = 0; $x < count($ct_names); $x++) {
            $services[] = [
                'service_name'     => $ct_names[$x],
                'service_quantity' => $ct_quantities[$x],
                'service_fee'      => $ct_fees[$x],
                'billing_no'       => $billing_no,
                'added_on'         => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_diagnostic_test_billing_services($services);
    }

    function insert_medications($medication_names, $medication_qtys, $medication_fees, $billing_no){
        $medications = []; 
        for ($i = 0; $i < count($medication_names); $i++) {
            $medications[] = [
                'med_name'     => $medication_names[$i],
                'med_qty'      => $medication_qtys[$i],
                'med_fee'   => $medication_fees[$i],
                'billing_no'   => $billing_no,
                'added_on'     => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_billing_medications($medications);
    }

    function insert_professional_fees($doctor_names, $professional_fees, $billing_no){
        $prof_fees = []; 
        for ($i = 0; $i < count($doctor_names); $i++) {
            $prof_fees[] = [
                'doctor_name'   => $doctor_names[$i],
                'pro_fee'      => $professional_fees[$i],
                'billing_no'    => $billing_no,
                'added_on'      => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_billing_professional_fees($prof_fees);
    }

    function insert_philhealth_deduction($philhealth_deduction, $billing_no){
        $philhealth = [];
        $philhealth[] = [
            'deduction_name'   => 'Philhealth',
            'deduction_amount' => $philhealth_deduction,
            'billing_no'       => $billing_no,
            'added_on'         => date('Y-m-d')
        ];

        $this->billing_model->insert_billing_deductions($philhealth);
    }

    function insert_other_deductions($deduction_names, $deduction_amounts, $billing_no){
        $deductions = []; 
        for ($y = 0; $y < count($deduction_names); $y++) {
            $deductions[] = [
                'deduction_name'   => $deduction_names[$y],
                'deduction_amount' => $deduction_amounts[$y],
                'billing_no'       => $billing_no,
                'added_on'         => date('Y-m-d')
            ];
        }

        $this->billing_model->insert_billing_deductions($deductions);
    }

    function insert_personal_charge($type, $emp_id, $id, $personal_charge, $billing_no){
        if($type === 'LOA'){
                $charge = [
                'emp_id'            => $emp_id,
                'loa_id'            => $id,
                'amount'            => $personal_charge,
                'billing_no'        => $billing_no,
                'status'            => 'Unpaid',
                'added_on'          => date('Y-m-d')
            ];
        }else if($type === 'NOA'){
            $charge = [
                'emp_id'            => $emp_id,
                'noa_id'            => $id,
                'amount'            => $personal_charge,
                'billing_no'        => $billing_no,
                'status'            => 'Unpaid',
                'added_on'          => date('Y-m-d')
            ];
        }
        $this->billing_model->insert_personal_charge($charge);
    }

    function insert_room_board($posted_data){
        $data_array = explode(',', $posted_data['room-board']); // Explode the string to create an array
        $room_id = array_shift($data_array); // Get the first index value

        $room = [
            'room_id'        => $room_id,
            'room_rate'      => $posted_data['room-rate'],
            'billing_no'     => $posted_data['billing-no'],
            'added_on'       => date('Y-m-d')
        ];

        $this->billing_model->insert_room_board($room);
    }

    function update_member_mbl($posted_data){
        $emp_id = $posted_data['emp-id'];
        $remaining_bal = $posted_data['remaining-balance'];
        $net_bill = $posted_data['net-bill'];
        $member_mbl = $this->billing_model->get_member_mbl($posted_data['emp-id']);
        $remaining_bal = $member_mbl['remaining_balance'];
        $current_used_mbl = $member_mbl['used_mbl'] != '' ? $member_mbl['used_mbl'] : 0; 
        
        // calculate members used mbl
        $total_used_mbl = $current_used_mbl + $net_bill;

        // Update Member's Remaining Credit Limit Balance
        if($net_bill > 0 && $net_bill < $remaining_bal){
            // set used mbl value for update
            $used_mbl = $total_used_mbl >= $member_mbl['max_benefit_limit'] ?  $member_mbl['max_benefit_limit'] : $total_used_mbl;

            // calculate deduction of member's remaining MBL balance
            $new_balance = $remaining_bal - $net_bill;
            $data = [
                'used_mbl'          => $used_mbl,
                'remaining_balance' => $new_balance
            ];
            $this->billing_model->update_member_remaining_balance($emp_id, $data);
        }else if($net_bill >= $remaining_bal){
            $data = [
                'used_mbl'          => $member_mbl['remaining_balance'],
                'remaining_balance' => 0
            ];
            $this->billing_model->update_member_remaining_balance($emp_id, $data);
        }
    }

    function update_request_status($type, $id){
        $data = ['status' => 'Billed'];

        if($type == 'LOA'){
            $this->billing_model->update_loa_request($id, $data);
        }else if($type == 'NOA'){
            $this->billing_model->update_noa_request($id, $data);
        }
    }

    function view_request_billing(){
        $id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $type = $this->uri->segment(3);

        if($type == 'loa'){
            $data['bill'] = $bill = $this->billing_model->get_loa_billing_info($id);
        }else if($type == 'noa'){
            $data['bill'] = $bill = $this->billing_model->get_noa_billing_info($id);
        }
        
        $data['user_role'] = $this->session->userdata('user_role');
        $data['mbl'] = $this->billing_model->get_member_mbl($bill['emp_id']);
        $data['services'] = $this->billing_model->get_billing_services($bill['billing_no']);
        $data['medications'] = $this->billing_model->get_billing_medications($bill['billing_no']);
        $data['profees'] = $this->billing_model->get_billing_professional_fees($bill['billing_no']);
        $data['roomboards'] = $this->billing_model->get_billing_room_boards($bill['billing_no']);
        $data['deductions'] = $this->billing_model->get_billing_deductions($bill['billing_no']);

        $view_page = $bill['billing_type'] == 'Manual Billing' ? 'billing_receipt' : 'pdf_billing_receipt'; 

		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/'.$view_page);
		$this->load->view('templates/footer');
    }

    function upload_loa_pdf_bill_form() {
        $loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $loa = $this->billing_model->get_loa_to_bill($loa_id);
        $mbl = $this->billing_model->get_member_mbl($loa['emp_id']);
        $data['loa_id'] = $this->uri->segment(5);
        $data['loa_no'] = $loa['loa_no'];
        $data['healthcard_no'] = $loa['health_card_no'];
        $data['remaining_balance'] = $mbl['remaining_balance'];
        $data['patient_name'] = $loa['first_name'].' '. $loa['middle_name'].' '. $loa['last_name'].' '.$loa['suffix'];
        // $data['billing_no'] = 'BLN-' . strtotime(date('Y-m-d h:i:s'));
		$data['user_role'] = $this->session->userdata('user_role');
        $result = $this->billing_model->db_get_max_billing_id();
		$max_billing_id = !$result ? 0 : $result['billing_id'];
		$add_billing = $max_billing_id + 1;
		$current_year = date('Y').date('m');

        if($loa['loa_request_type'] === 'Diagnostic Test'){
            $med_services = [];
            $exploded_med_services = explode(";", $loa['med_services']);

            foreach ($exploded_med_services as $ctype_id) :
                $cost_type = $this->billing_model->get_cost_type_by_id($ctype_id);
                array_push($med_services, $cost_type['item_description']);
            endforeach;
            $data['services'] = $med_services;
        }

        if($loa['loa_request_type'] === 'Consultation'){
            $data['services'] = 'Consultation';
        }

        if($loa['loa_request_type'] === 'Emergency'){
            $data['services'] = 'Emergency';
        }
		// call function loa_number
		$data['billing_no'] = $this->billing_number($add_billing, 5, 'BLN-'.$current_year);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/upload_loa_bill_pdf');
		$this->load->view('templates/footer');
	}

    function _submit_loa_pdf_bill() {
        $this->security->get_csrf_hash();
        if(!empty($_FILES["pdf-file"]["name"])){

            $PDFfileName = basename($_FILES["pdf-file"]["name"]); 
            $PDFfileType = pathinfo($PDFfileName, PATHINFO_EXTENSION); 
                    
            include realpath('assets/pdf_extract/vendor/autoload.php'); 
            
            $allowTypes = array('pdf'); 
            if(in_array($PDFfileType,$allowTypes)){
                
                $parser   = new \Smalot\PdfParser\Parser(); 
                // Source file
                $PDFfile  = $_FILES["pdf-file"]["tmp_name"]; 
                $PDF      = $parser->parseFile($PDFfile); 
                $fileText = $PDF->getText();                          
                // line break 
                $PDFContent = nl2br($fileText); 
                
                var_dump($PDFContent);
                $number_of_pages = count($PDF->getPages()); 

                $data = $PDF->getPages()[0]->getDataTm(); // checkon ang first page , pangitaon ang SI number para  mag checking sa database if existing naba ning data or wala pa
            }
        }
    }

    function submit_loa_pdf_bill() {
        $this->security->get_csrf_hash();
        $loa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $billing_no = $this->input->post('billing-no', TRUE);
        $net_b = $this->input->post('net-bill', TRUE);
        $net_bill = floatval(str_replace(',','',$net_b));
        $hospitalBillData = $_POST['hospital_bill_data'];
        $attending_doctor= $_POST['attending_doctors'];
        $jsonData_item = $_POST['json_final_charges'];
        $jsonData_benefits = $_POST['benefits_deductions'];
        $itemize_bill = json_decode($jsonData_item);
        $benefits_deductions = json_decode($jsonData_benefits);
        // var_dump("benefits",$benefits_deductions);
        // PDF File Upload
        //$config['upload_path'] = './uploads/pdf_bills/';
        $config['allowed_types'] = 'pdf';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);

        // if (!$this->upload->do_upload('pdf-file') && !$this->upload->do_upload('itemize-pdf-file')) {
        //     $response = [
        //         'status'  => 'save-error',
        //         'message' => 'PDF Bill Upload Failed'
        //     ];

        // } 

        $uploaded_files = array();
        $error_occurred = FALSE;
        // Define the upload paths for each file
            $file_paths = array(
                'pdf-file' => './uploads/pdf_bills/',
                'itemize-pdf-file' => './uploads/itemize_bills/',
            );
    
        // Iterate over each file input and perform the upload
            $file_inputs = array('pdf-file','itemize-pdf-file');

            foreach ($file_inputs as $input_name) {

                if ($input_name === 'itemize-pdf-file' && empty($_FILES[$input_name]['name'])) {
                    // Skip the 'Medical-Abstract' field if it is empty
                    continue;
                }

                $config['upload_path'] = $file_paths[$input_name];
                $this->upload->initialize($config);

                if (!$this->upload->do_upload($input_name)) {
                    $error_occurred = TRUE;
                } else {
                    $uploaded_files[$input_name] = $this->upload->data();
                }
            }

            
        if ($error_occurred) {
            $response = [
                'status'  => 'save-error',
                'message' => 'PDF Bill Upload Failed'
            ];
        }
        else {

            $upload_data = $this->upload->data();
            $pdf_file = $upload_data['file_name'];
            $loa = $this->billing_model->get_loa_to_bill($loa_id);
            $old_billing = $this->billing_model->get_billing_by_emp_id($loa['emp_id']);
            $check_bill = $this->billing_model->check_re_upload_billing($billing_no);
            $get_prev_mbl_by_bill_no = $this->billing_model->get_billing($billing_no);
            $get_prev_mbl = $this->billing_model->get_prev_mbl($billing_no,$loa['emp_id']);
            $get_prev_balance = ($get_prev_mbl_by_bill_no != null) ? $get_prev_mbl_by_bill_no['before_remaining_bal'] : null;
            $result_charge = $this->get_personal_and_company_charge("loa",$loa_id,$net_bill,($check_bill !=0)? true : false,
             ($get_prev_mbl !=null)?$get_prev_mbl['after_remaining_bal'] :  $get_prev_balance,
             ($old_billing !=null)? $old_billing['after_remaining_bal'] : null);
            //  var_dump("re upload",$check_bill);
            //  var_dump("done reupload",$check_bill)
            $existed = $this->billing_model->check_billing_loa($loa_id);
            $bill_no = $this->billing_model->get_billing_no($loa_id);
            $data = [
                'billing_no'            => ($existed)? $bill_no['billing_no'] : $billing_no,
                'billing_type'          => 'PDF Billing',
                'emp_id'                => $loa['emp_id'],
                'loa_id'                => $loa_id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $loa['work_related'],
                'net_bill'              => $net_bill,
                'company_charge'        => $result_charge['company_charge'],
                'personal_charge'       => $result_charge['personal_charge'],
                'before_remaining_bal'  => $result_charge['previous_mbl'],
                'after_remaining_bal'   => $result_charge['remaining_balance'],
                'pdf_bill'              => (isset($uploaded_files['pdf-file']))? $uploaded_files['pdf-file']['file_name'] : null,
                'itemized_bill'         => (isset($uploaded_files['itemize-pdf-file']))? $uploaded_files['itemize-pdf-file']['file_name'] : null,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed',
                'extracted_txt'         => $hospitalBillData,
                'attending_doctors'     => $attending_doctor,
                'request_date'          => $loa['request_date']
            ];   

            $mbl = [
                        'used_mbl'            => $result_charge['used_mbl'],
                        'remaining_balance'      => $result_charge['remaining_balance']
                    ];

            $personal_charge = $result_charge['personal_charge'];
            
                    // var_dump("personal",$check_bill);
                    // var_dump("billing no",$billing_no);
            if($check_bill){
                $this->billing_model->insert_old_billing($billing_no);
                $data += ['done_re_upload' => 'Done',
                        're_upload' => 0,
                        ];
                       
                $inserted = $this->billing_model->update_billing($data,$billing_no);

                if($inserted){
                    $row =  $this->billing_model->get_affected_billing($billing_no, $loa['emp_id']);
                    foreach($row as $n){
                        $get_prev_mbl1 = $this->billing_model->get_prev_mbl($n['billing_no'],$n['emp_id']);

                        $get_prev_mbl_by_bill_no1 = $this->billing_model->get_billing($n['billing_no']);

                        $result_charge1 = $this->get_personal_and_company_charge(($n['loa_id'])?"loa":"noa", ($n['loa_id'])?$n['loa_id']:$n['noa_id'], $n['net_bill'],($check_bill !=0)? true : false, ($get_prev_mbl1 !=null)?$get_prev_mbl1['after_remaining_bal']:$get_prev_mbl_by_bill_no1['before_remaining_bal'],($old_billing !=null)? $old_billing['after_remaining_bal'] : null);
                        $data1 =[
                            'company_charge'        => $result_charge1['company_charge'],
                            'personal_charge'       => $result_charge1['personal_charge'],
                            'before_remaining_bal'  => $result_charge1['previous_mbl'],
                            'after_remaining_bal'   => $result_charge1['remaining_balance'],
                        ];

                        $mbl1 = [
                            'used_mbl'            => $result_charge1['used_mbl'],
                            'remaining_balance'      => $result_charge1['remaining_balance']
                        ];

                        if($result_charge1['personal_charge']>0){
                            $advances = ['emp_id'                => $loa['emp_id'],
                                        'billing_id'            => $n['billing_id'],
                                        'hp_id'                =>$this->session->userdata('dsg_hcare_prov'),
                                        'excess_amount'       => $result_charge1['personal_charge'],
                                        'date_added'             => date('Y-m-d'),
                                        'status'                => 'Pending'];
                            $this->billing_model->insert_cash_advance($advances);
                        }
                        //  var_dump("affected",$data1);
                        $this->billing_model->update_affected_billing($data1,$n['billing_no']);
                        $this->billing_model->update_member_remaining_balance($n['emp_id'], $mbl1);
                    //    var_dump("affected",$data);
                    }
                }
            }else{
                
                    // var_dump("existed",$existed);
                    if($existed){
                        $this->billing_model->update_billing($data,$bill_no['billing_no']);
                    }else{
                        $inserted = $this->billing_model->insert_billing($data);

                        $this->billing_model->_set_loa_status_completed($loa_id);
                        if(count($itemize_bill)){
                        $bill_id = $this->billing_model->get_billing_id($data['billing_no'],$data['emp_id'],$data['hp_id']);
                        foreach($itemize_bill as $items){
                                $item = [
                                    'emp_id'        => $data['emp_id'], 
                                    'billing_id'    => $bill_id['billing_id'], 
                                    'hp_id'         => $data['hp_id'], 
                                    'labels'        => $items[0], 
                                    'discription'   => $items[2], 
                                    'qty'           => $items[3], 
                                    'unit_price'    => $items[4], 
                                    'amount'        => $items[5], 
                                    'date'          => $items[1],
                                ];
                
                                $this->billing_model->itemized_bill($item);
                            }
                        }

                        foreach($benefits_deductions as $items){
                            $item = [
                                'emp_id'        => $data['emp_id'], 
                                'billing_id'    => $bill_id['billing_id'], 
                                'loa_id'         => $data['hp_id'], 
                                'hp_id'         => $data['hp_id'], 
                                'benefits_name'        => $items[0], 
                                'benefits_amount'   => floatval(str_replace(array(',', '(', ')'), '', $items[1])),
                            ];
                            
                            $this->billing_model->benefits_deduction($item);
            
                        }

                        if(!$inserted){
                            $response = [
                               'status'  => 'save-error',
                               'message' => 'PDF Bill Upload Failed'
                            ];
                        }else{
                            $billing_id = $this->billing_model->get_billing($billing_no);
        
                            if($personal_charge>0){
                                $advances = ['emp_id'                => $loa['emp_id'],
                                            'billing_id'            => $billing_id['billing_id'],
                                            'hp_id'                =>$this->session->userdata('dsg_hcare_prov'),
                                            'excess_amount'       => $result_charge['personal_charge'],
                                            'date_added'             => date('Y-m-d'),
                                            'status'                => 'Pending'];
                                $this->billing_model->insert_cash_advance($advances);
                            }
                            $this->billing_model->update_member_remaining_balance($loa['emp_id'], $mbl);
                            $existing = $this->billing_model->check_if_loa_already_added($loa_id);
                            $resched = $this->billing_model->check_if_done_created_new_loa($loa_id);
                            $rescheduled = $this->billing_model->check_if_status_cancelled($loa_id);
                        }
                            
        
                        if($rescheduled){   
                            if($existing && $resched['reffered'] == 1){
                                $this->billing_model->set_completed_value($loa_id);
                            }
                        }else{
                            if($existing){
                                $this->billing_model->set_completed_value($loa_id);
                            }
                        }
                       
                    }
                    $type = 'LOA';
                    $this->update_request_status($type, $loa_id);
            }

                
            $bill = $this->billing_model->get_billing($billing_no);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            $response = [
                'status'     => 'success',
                'message'    => 'PDF Bill Uploaded Successfully',
                'billing_id' => $encrypted_id,
            ];   
        }

        echo json_encode($response);
	}

    function pdf_billing_success(){
        $billing_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $data['user_role'] = $this->session->userdata('user_role');
        $data['bill'] = $bill = $this->billing_model->get_billing_info($billing_id);
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/pdf_billing_success');
		$this->load->view('templates/footer');
    }

    function billing_number($input, $pad_len = 7, $prefix = null) {
		if ($pad_len <= strlen($input))
			trigger_error('<strong>$pad_len</strong> cannot be less than or equal to the length of <strong>$input</strong> to generate invoice number', E_USER_ERROR);
		if (is_string($prefix))
			return sprintf("%s%s", $prefix, str_pad($input, $pad_len, "0", STR_PAD_LEFT));

		return str_pad($input, $pad_len, "0", STR_PAD_LEFT);
	}

	function upload_noa_pdf_bill_form() {
        $noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $bill_type = $this->uri->segment(3);
        $noa = $this->billing_model->get_noa_to_bill($noa_id);
        $mbl = $this->billing_model->get_member_mbl($noa['emp_id']);
        $hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
        $result = $this->billing_model->db_get_max_billing_id();
		$max_billing_id = !$result ? 0 : $result['billing_id'];
		$add_billing = $max_billing_id + 1;
		$current_year = date('Y').date('m');
		// call function loa_number
		$billing_no = $this->billing_number($add_billing, 5, 'BLN-'.$current_year);

        $initial = $this->initial_billing_model->get_initial_billing_no($noa_id, $hcare_provider_id, "Initial");
        $data['noa_id'] = $this->uri->segment(5);
        $data['noa_no'] = $noa['noa_no'];
        $data['healthcard_no'] = $noa['health_card_no'];
        $data['remaining_balance'] = $mbl['remaining_balance'];
        $data['patient_name'] = $noa['first_name'].' '. $noa['middle_name'].' '. $noa['last_name'].' '.$noa['suffix'];
        if($initial){
            $data['billing_no'] = $initial->billing_no;
            $data['admission_date'] = intval(str_replace('-', '', $initial->date_uploaded));
        }else{
            // $data['billing_no'] = 'BLN-' . strtotime(date('Y-m-d h:i:s'));
            $data['billing_no'] = $billing_no;
            $data['admission_date'] = intval(str_replace('-', '', $noa['admission_date']));
        }
		$data['user_role'] = $this->session->userdata('user_role');
		$this->load->view('templates/header', $data);
		$this->load->view('healthcare_provider_panel/billing/' . (($bill_type === 'bill-noa') ? "upload_noa_bill_pdf" : "upload_noa_initial_bill_pdf"));
		// $this->load->view('healthcare_provider_panel/billing/upload_noa_bill_pdf');
		$this->load->view('templates/footer');
	}  

	function re_upload_pdf_bill_form() {    
        $loa_noa = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $type = $this->uri->segment(6);
        $hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
        $bill_number = $this->billing_model->get_billing_no($loa_noa);
        if($type == 'loa'){
            $loa = $this->billing_model->get_loa_to_bill($loa_noa);
            $prv_mbl = $this->billing_model->get_prev_mbl( $bill_number['billing_no'],$loa['emp_id']);
            $mbl_by_bill_no = $this->billing_model->get_billing( $bill_number['billing_no']);
            $data['loa_id'] = $this->uri->segment(5);
            $data['loa_no'] = $loa['loa_no'];
            $data['healthcard_no'] = $loa['health_card_no'];
            $data['remaining_balance'] =($prv_mbl != null)?$prv_mbl['after_remaining_bal']:$mbl_by_bill_no['before_remaining_bal'];
            $data['patient_name'] = $loa['first_name'].' '. $loa['middle_name'].' '. $loa['last_name'].' '.$loa['suffix'];
            $data['billing_no'] = $bill_number['billing_no'];
            $data['user_role'] = $this->session->userdata('user_role');
            $data['re_upload'] = true;
            $data['prev_billing'] = $mbl_by_bill_no['pdf_bill'];
            $data['net_bill'] = $mbl_by_bill_no['net_bill'];

            if($loa['loa_request_type'] === 'Diagnostic Test'){
                $med_services = [];
                $exploded_med_services = explode(";", $loa['med_services']);
    
                foreach ($exploded_med_services as $ctype_id) :
                    $cost_type = $this->billing_model->get_cost_type_by_id($ctype_id);
                    array_push($med_services, $cost_type['item_description']);
                endforeach;
                $data['services'] = $med_services;
            }
    
            if($loa['loa_request_type'] === 'Consultation'){
                $data['services'] = 'Consultation';
            }
    
            if($loa['loa_request_type'] === 'Emergency'){
                $data['services'] = 'Emergency';
            }

            $this->load->view('templates/header', $data);
            $this->load->view('healthcare_provider_panel/billing/upload_loa_bill_pdf');
            $this->load->view('templates/footer');
        }

        if($type == 'noa'){
            $noa = $this->billing_model->get_noa_to_bill($loa_noa);
            $prv_mbl = $this->billing_model->get_prev_mbl( $bill_number['billing_no'],$noa['emp_id']);
            $mbl_by_bill_no = $this->billing_model->get_billing($bill_number['billing_no']);
            //$mbl = $this->billing_model->get_member_mbl($noa['emp_id']);
            $initial = $this->initial_billing_model->get_initial_billing_no($loa_noa, $hcare_provider_id, "Initial");
            $data['noa_id'] = $this->uri->segment(5);
            $data['noa_no'] = $noa['noa_no'];
            $data['healthcard_no'] = $noa['health_card_no'];
            $data['remaining_balance'] =($prv_mbl != null)?$prv_mbl['after_remaining_bal']:$mbl_by_bill_no['before_remaining_bal'];
            $data['patient_name'] = $noa['first_name'].' '. $noa['middle_name'].' '. $noa['last_name'].' '.$noa['suffix'];
            $data['re_upload'] = true;
            $data['admission_date'] = intval(str_replace('-', '', $noa['admission_date']));
            $data['prev_billing'] = $mbl_by_bill_no['pdf_bill'];
            $data['net_bill'] = $mbl_by_bill_no['net_bill'];
            if($initial){
                $data['billing_no'] = $initial->billing_no;
            }else{
                $data['billing_no'] =  $bill_number['billing_no'];
            }
            $data['user_role'] = $this->session->userdata('user_role');
            $this->load->view('templates/header', $data);
            $this->load->view('healthcare_provider_panel/billing/upload_noa_bill_pdf');
            $this->load->view('templates/footer');
        }
     
	}

    function submit_noa_pdf_bill() { 
        $this->security->get_csrf_hash();
        $noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $billing_no = $this->input->post('billing-no', TRUE);
        $net_b = $_POST['net_bill'];
        // var_dump('net bill', $net_b);
        $net_bill = floatval(str_replace(',','',$net_b));
        $take_home_meds = $this->input->post('med-services',true);
        $hospitalBillData = $_POST['hospital_bill_data'];
        $attending_doctor= $_POST['attending_doctors'];
        $jsonData_item = $_POST['json_final_charges'];
        $jsonData_benefits = $_POST['benefits_deductions'];
        $itemize_bill = json_decode($jsonData_item);
        $benefits_deductions = json_decode($jsonData_benefits);

        //  var_dump("items",$itemize_bill);
        // $hospitalBillArray = json_decode($hospitalBillData, true);
        //var_dump($hospitalBillArray);
        // PDF File Upload
        // $config['upload_path'] = './uploads/pdf_bills/';
        $config['allowed_types'] = 'pdf|jpeg|jpg|png|gif|svg';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        
        $uploaded_files = array();
        $error_occurred = FALSE;

        $check_bill = $this->billing_model->check_re_upload_billing($billing_no);
        // Define the upload paths for each file
            $file_paths = array(
                'pdf-file' => './uploads/pdf_bills/',
                'itemize-pdf-file' => './uploads/itemize_bills/',
                'Final-Diagnosis' => './uploads/final_diagnosis/',
                'Medical-Abstract' => './uploads/medical_abstract/',
                'Prescription' => './uploads/prescription/'
            );
    
        // Iterate over each file input and perform the upload
            $file_inputs = array('pdf-file','itemize-pdf-file', 'Final-Diagnosis', 'Medical-Abstract', 'Prescription');
            foreach ($file_inputs as $input_name) {
                if ($input_name === 'Medical-Abstract' && empty($_FILES[$input_name]['name'])) {
                    // Skip the 'Medical-Abstract' field if it is empty
                    continue;
                }

                if ($input_name === 'Prescription' && empty($_FILES[$input_name]['name'])) {
                    // Skip the 'Medical-Abstract' field if it is empty
                    continue;
                }

                if($check_bill !=0){
                    if ($input_name === 'Final-Diagnosis' && empty($_FILES[$input_name]['name'])) {
                        // Skip the 'Medical-Abstract' field if it is empty
                        continue;
                    }
                }

                $config['upload_path'] = $file_paths[$input_name];
                $this->upload->initialize($config);

                if (!$this->upload->do_upload($input_name)) {
                    $error = $this->upload->display_errors();
                    if ($input_name !== 'Prescription' || !empty($error)) {
                        // If error occurred for required files or any other file, set error flag
                        $error_occurred = TRUE;
                    }
                } else {
                    $uploaded_files[$input_name] = $this->upload->data();
                }
            }

            
        if ($error_occurred) {
            $response = [
                'status'  => 'save-error',
                'message' => 'PDF Bill Upload Failed'
            ];
        } else {
           
            $noa = $this->billing_model->get_noa_to_bill($noa_id);
            $old_billing = $this->billing_model->get_billing_by_emp_id($noa['emp_id']);
            $get_prev_mbl_by_bill_no = $this->billing_model->get_billing($billing_no);
            $get_prev_mbl = $this->billing_model->get_prev_mbl($billing_no,$noa['emp_id']);
            $get_prev_balance = ($get_prev_mbl_by_bill_no != null) ? $get_prev_mbl_by_bill_no['before_remaining_bal'] : null;
            $get_prev_meds = ($get_prev_mbl_by_bill_no != null) ? $get_prev_mbl_by_bill_no['take_home_meds'] : null;
            $get_prev_maf = ($get_prev_mbl_by_bill_no != null) ? $get_prev_mbl_by_bill_no['medical_abstract_file'] : null;
            $get_prev_pf = ($get_prev_mbl_by_bill_no != null) ? $get_prev_mbl_by_bill_no['prescription_file'] : null;
            $result_charge = $this->get_personal_and_company_charge("noa",$noa_id,$net_bill,($check_bill !=0)? true : false, ($get_prev_mbl !=null)?$get_prev_mbl['after_remaining_bal']:$get_prev_balance,($old_billing !=null)? $old_billing['after_remaining_bal'] : null);
            $existed = $this->billing_model->check_billing_noa($noa_id);
            $bill_no = $this->billing_model->get_billing_no($noa_id);
            $data = [
                'billing_no'            => ($existed) ?  $bill_no['billing_no'] : $billing_no,
                'billing_type'          => 'PDF Billing',
                'emp_id'                => $noa['emp_id'],
                'noa_id'                => $noa_id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'work_related'          => $noa['work_related'],
                'take_home_meds'        => isset($take_home_meds)?implode(',',$take_home_meds):$get_prev_meds,
                'net_bill'              => $net_bill,
                'company_charge'        =>  $result_charge['company_charge'],
                'personal_charge'       =>  $result_charge['personal_charge'],
                'before_remaining_bal'  =>  $result_charge['previous_mbl'],
                'after_remaining_bal'   =>  $result_charge['remaining_balance'],
                'pdf_bill'              => isset($uploaded_files['pdf-file']) ? $uploaded_files['pdf-file']['file_name'] : $get_prev_mbl_by_bill_no['pdf_bill'],
                'itemized_bill'         => isset($uploaded_files['itemize-pdf-file']) ? $uploaded_files['itemize-pdf-file']['file_name'] : $get_prev_mbl_by_bill_no['itemize-pdf-file'],
                'final_diagnosis_file'  => isset($uploaded_files['Final-Diagnosis']) ? $uploaded_files['Final-Diagnosis']['file_name'] : $get_prev_mbl_by_bill_no['final_diagnosis_file'],
                'medical_abstract_file' => isset($uploaded_files['Medical-Abstract']) ? $uploaded_files['Medical-Abstract']['file_name'] : $get_prev_maf,
                'prescription_file'     => isset($uploaded_files['Prescription']) ? $uploaded_files['Prescription']['file_name'] : $get_prev_pf,
                'billed_by'             => $this->session->userdata('fullname'),
                'billed_on'             => date('Y-m-d'),
                'status'                => 'Billed',
                'extracted_txt'         => $hospitalBillData,
                'attending_doctors'      => $attending_doctor,
                'request_date'          => $noa['request_date']
            ];    
            $mbl = [
                'used_mbl'            => $result_charge['used_mbl'],
                'remaining_balance'      => $result_charge['remaining_balance']
            ];
           
            // var_dump("check bill",$check_bill);
            // var_dump("billing no",$billing_no);
            if($check_bill){
                $this->billing_model->insert_old_billing($billing_no);
                $data += ['done_re_upload' => 'Done',
                're_upload' => 0,
                ];
                $inserted = $this->billing_model->update_billing($data,$billing_no);

                if($inserted){
                    $row =  $this->billing_model->get_affected_billing($billing_no, $noa['emp_id']);
                    foreach($row as $n){
                        $get_prev_mbl1 = $this->billing_model->get_prev_mbl($n['billing_no'],$n['emp_id']);

                        $get_prev_mbl_by_bill_no1 = $this->billing_model->get_billing($n['billing_no']);

                        $result_charge1 = $this->get_personal_and_company_charge(($n['noa_id'])?"noa":"loa", ($n['noa_id'])?$n['noa_id']:$n['loa_id'], $n['net_bill'],($check_bill !=0)? true : false, ($get_prev_mbl1 !=null)?$get_prev_mbl1['after_remaining_bal']:$get_prev_mbl_by_bill_no1['before_remaining_bal'],($old_billing !=null)? $old_billing['after_remaining_bal'] : null);
                        $data1 =[
                            'company_charge'        =>  $result_charge1['company_charge'],
                            'personal_charge'       =>  $result_charge1['personal_charge'],
                            'before_remaining_bal'  =>  $result_charge1['previous_mbl'],
                            'after_remaining_bal'   =>  $result_charge1['remaining_balance'],
                        ];

                        $mbl1 = [
                            'used_mbl'            => $result_charge1['used_mbl'],
                            'remaining_balance'      => $result_charge1['remaining_balance']
                        ];

                        if($result_charge1['personal_charge']>0){
                            $advances = ['emp_id'                => $noa['emp_id'],
                                        'billing_id'            => $n['billing_id'],
                                        'hp_id'                =>$this->session->userdata('dsg_hcare_prov'),
                                        'excess_amount'       => $result_charge1['personal_charge'],
                                        'date_added'             => date('Y-m-d'),
                                        'status'                => 'Pending'];
                            $this->billing_model->insert_cash_advance($advances);
                        }
                        //  var_dump("affected",$data1);
                        $this->billing_model->update_affected_billing($data1,$n['billing_no']);
                        $this->billing_model->update_member_remaining_balance($n['emp_id'], $mbl1);
                    //    var_dump("affected",$data);
                    }
                }

            }else{
                    // var_dump("existed",$existed);   
                    if($existed){
                        $this->billing_model->update_billing($data,$bill_no['billing_no']);
                    }else{
                        $inserted = $this->billing_model->insert_billing($data);
                        $personal_charge = $result_charge['personal_charge'];

                        $bill_id = $this->billing_model->get_billing_id($data['billing_no'],$data['emp_id'],$data['hp_id']);
                        foreach($itemize_bill as $items){
                            $item = [
                                'emp_id'        => $data['emp_id'], 
                                'billing_id'    => $bill_id['billing_id'], 
                                'hp_id'         => $data['hp_id'], 
                                'labels'        => $items[0], 
                                'discription'   => $items[2], 
                                'qty'           => $items[3], 
                                'unit_price'    => $items[4], 
                                'amount'        => $items[5], 
                                'date'          => $items[1],
                            ];
            
                            $this->billing_model->itemized_bill($item);
            
                        }

                        foreach($benefits_deductions as $items){
                            $item = [
                                'emp_id'        => $data['emp_id'], 
                                'billing_id'    => $bill_id['billing_id'], 
                                'loa_id'         => $data['hp_id'], 
                                'hp_id'         => $data['hp_id'], 
                                'benefits_name'        => $items[0], 
                                'benefits_amount'   => floatval(str_replace(array(',', '(', ')'), '', $items[1])),
                            ];
            
                            $this->billing_model->benefits_deduction($item);
            
                        }

                    if(!$inserted){
                        $response = [
                        'status'  => 'save-error',
                        'message' => 'PDF Bill Upload Failed'
                        ];
                    } else{
                        $billing_id = $this->billing_model->get_billing($billing_no);

                        if($personal_charge>0){
                            $advances = ['emp_id'                => $noa['emp_id'],
                                        'billing_id'            => $billing_id['billing_id'],
                                        'hp_id'                =>$this->session->userdata('dsg_hcare_prov'),
                                        'excess_amount'       => $result_charge['personal_charge'],
                                        'date_added'             => date('Y-m-d'),
                                        'status'                => 'Pending'];
                            $this->billing_model->insert_cash_advance($advances);
                        }
                        $this->billing_model->update_member_remaining_balance($noa['emp_id'], $mbl);
                    }  
                    
                   
                    }

                    $type = 'NOA';
                    $this->update_request_status($type, $noa_id);
            }

            
            $bill = $this->billing_model->get_billing($billing_no);
            $encrypted_id = $this->myhash->hasher($bill['billing_id'], 'encrypt');
            $response = [
                'status'     => 'success',
                'message'    => 'PDF Bill Uploaded Successfully',
                'billing_id' => $encrypted_id,
            ];   
        }

     echo json_encode($response);
	}

    function submit_initial_noa_pdf_bill() { 
        $this->security->get_csrf_hash();
        $noa_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
        $billing_no = $this->input->post('billing-no', TRUE);
        $net_b = $this->input->post('initial-net-bill', TRUE);
        // $initial_date = $this->input->post('initial-date',TRUE);
        $net_bill = floatval(str_replace(',', '', $net_b));
        // $hospitalBillData = $_POST['hospital_bill_data'];
        
        // var_dump("initial date",$initial_date);
        // PDF File Upload
        $config['upload_path'] = './uploads/pdf_bills/';
        $config['allowed_types'] = 'pdf';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
        // var_dump("initial date", $initial_date);
        // if(empty($initial_date)){
        //     $response = [
        //         'status'  => 'save-error',
        //         'message' => 'Invalid Date'
        //     ];

        // }
        // else 
        if (!$this->upload->do_upload('pdf-file-initial')) {
            $response = [
                'status'  => 'save-error',
                'message' => 'PDF Bill Upload Failed'
            ];

        } else {
            $upload_data = $this->upload->data();
            $pdf_file = $upload_data['file_name'];
            $noa_info = $this->noa_model->db_get_noa_info($noa_id);
    
            $get_prev_mbl_by_bill_no = $this->billing_model->get_billing($billing_no);
            $get_prev_mbl = $this->billing_model->get_prev_mbl($billing_no,$noa_info['emp_id']);
            $old_billing = $this->billing_model->get_billing_by_emp_id($noa_info['emp_id']);
            $check_bill = $this->billing_model->check_re_upload_billing($billing_no);
            $result_charge = $this->get_personal_and_company_charge("noa",$noa_id,$net_bill,($check_bill !=0)? true : false, ($get_prev_mbl !=null)?$get_prev_mbl['after_remaining_bal']:$get_prev_mbl_by_bill_no['before_remaining_bal'],($old_billing !=null)? $old_billing['after_remaining_bal'] : null);
            // var_dump($result_charge);
            $data = [
                'billing_no'            => $billing_no,
                'emp_id'                => $noa_info['emp_id'],
                'noa_id'                => $noa_id,
                'hp_id'                 => $this->session->userdata('dsg_hcare_prov'),
                'initial_bill'          => $net_bill,
                'company_charge'        => floatval(str_replace(',', '', $result_charge['company_charge'])),
                'personal_charge'       => floatval(str_replace(',', '', $result_charge['personal_charge'])),
                'pdf_bill'              => $pdf_file,
                'uploaded_by'           => $this->session->userdata('fullname'),
                'date_uploaded'         => date('Y-m-d'),
                'status'                => 'Initial',
            ];    
            
            $inserted = $this->initial_billing_model->insert_initial_bill($data);
            
            if(!$inserted){
                $response = [
                   'status'  => 'save-error',
                   'message' => 'PDF Bill Upload Failed'
                ];
            }else{
                $response = [
                        'status'     => 'success',
                        'initial'    => true,
                        'message'    => 'Initial Bill Uploaded Successfully'
                    ];   
            }
            
        }

        echo json_encode($response);
	}

    
    function db_upload_textfile(){
        $token = $this->security->get_csrf_hash();
        $arr_f      = [];     
        if(!empty($_FILES['textfile']['tmp_name']))            
        {   
            $filename   = $_FILES['textfile']['tmp_name']; 
            $myfile     = fopen($filename, "r") or die("Unable to open file!");
            while(! feof($myfile)) {
                $arr_f[]= fgets($myfile);                  
            }

            $fname      = $_FILES['textfile']['name'];
            $ext        = explode(".",$fname); 
            $ext        = $ext[1];
            

            if($ext == "csv"){ 
                $delimeter = ",";
            }else if($ext == "txt"){
                $delimeter = "|";
            }else{
                $response = [
                    'token' => $token,
                    'status' => 'error-delimiter',
                    'message' => 'Error in file format delimiter.',
                ];
                echo json_encode($response);
                exit;
            }

            fclose($myfile);     
            $flag = '';
            $check = explode("|", $arr_f[0]);
            $countcolumn = count($check);
            
            if(trim($countcolumn) != 8 && trim($countcolumn) != 6){
                $response = [
                    'token' => $token,
                    'status' => 'error-format',
                    'message' => 'Textfile Uploading Failed. Text file format does not match with the uploaded file!',
                ];
                echo json_encode($response);
                die();
            }
            else
            {                
                for ($i=0; $i < count($arr_f); $i++) 
                {
                    $arr = [];                
                    $arr = explode($delimeter, trim(str_replace('"', "", $arr_f[$i] )) );              
                    
                    if(trim($arr[0])!="" && $countcolumn == 8 )
                    {                          
                        $vcode              = $arr[0]; 
                        $vname              = $arr[1];                            
                        $address            = $arr[2];                            
                        $address2           = $arr[3];                            
                        $city               = $arr[4];                            
                        $contact            = $arr[5];                            
                        $vposting           = $arr[6];                            
                        $currency           = @$arr[7];                            
                    }else if(trim($arr[0])!="" && $countcolumn == 6){
                        $vcode              = $arr[0]; 
                        $vname              = $arr[1];                            
                        $address            = $arr[2];                            
                        $address2           = $arr[3];                            
                        $city               = $arr[4];                            
                        $contact            = $arr[5];                            
                        $vposting           = "";
                        $currency           = "";     
                    }
                    $user_fullname = $this->session->userdata('fullname');
                    $data = [
                        'tf_2' => $vcode,
                        'tf_3' => $vname,
                        'tf_4' => $address,
                        'tf_5' => $address2,
                        'tf_6' => $city,
                        'tf_7' => $contact,
                        'tf_8' => $vposting,
                        'tf_9' => $currency,
                        'date_added' => date('Y-m-d'),
                        'added_by' => $user_fullname,
                        'status' => 'Posted'
                    ];

                    //dli pwede mag double ug upload ang same vendor code ug vendor name
                    // if($vposting == "CONSIGNOR"){

                    //     $where   = "vendor_code = '$vcode' ";  
                    //     $buid    = $this->getStore($vcode);
                    //     $exist   = $this->vendor_model->check_if_already_exist("vendor_code","tbl_vendor",$where);
                    //     if($exist == 0){
                            $flag = $this->billing_model->insert_textfile($data);

                            //LOGS
                           /* $log  = date('Y-m-d H:i:s')."| $vcode | $vname | ".$this->vendor_model->session_user()." \r\n";                          
                            $this->vendor_model->writeLogs($log,"../logs/","UPLOAD VENDOR-");*/
                    //     }else{
                    //         $flag = 1;
                    //     }
                    // }
                } 

                if($flag){
                    $response = [
                        'token' => $token,
                        'status' => 'success',
                        'message' => 'Textfile Uploading Done.',
                    ];
                    echo json_encode($response);
                    
                }else{
                    $response = [
                        'token' => $token,
                        'status' => 'error',
                        'message' => 'Textfile Uploading Failed. Error in inserting data in the table!',
                    ];
                    echo json_encode($response);
                    
                } 
            }
               
        }else{
            $response = [
                'token' => $token,
                'status' => 'empty',
                'message' => 'There must be an error in uploading text file.',
            ];
            echo json_encode($response);
           
        }       
    }  

    // function check_image($str){
	// 	if(isset($_FILES['supporting-docu']['name']) && !empty($_FILES['supporting-docu']['name'])){
	// 		return true;
	// 	}else{
	// 		$this->form_validation->set_message('check_image', 'Supporting Document is Required!');
	// 		return false;
	// 	}
    // }
    function view_payment_details() {
		$details_id = $this->myhash->hasher($this->uri->segment(5), 'decrypt');
		$payment = $this->list_model->get_payment_details($details_id);
		$loa_no = $this->list_model->get_loa($payment['details_no']);
		$noa_no = $this->list_model->get_noa($payment['details_no']);
      
		$noa_loa_array = [];
		foreach($loa_no as $covered_loa){
			if($covered_loa['loa_id'] != '' ){
				array_push($noa_loa_array, $covered_loa['loa_no']);
			}
		}

		foreach($noa_no as $covered_noa){
			if($covered_noa['noa_id'] != ''){
				array_push($noa_loa_array, $covered_noa['noa_no']);
			}
		}

		$loa_noa_no = implode(',    ', $noa_loa_array);
		
			$response = [
				'status' => 'success',
				'token' => $this->security->get_csrf_hash(),
				'payment_no' => $payment['payment_no'],
				'hp_name' => $payment['hp_name'],
				'added_on' => date("F d, Y", strtotime($payment['date_add'])),
				'acc_number' => $payment['acc_number'],
				'acc_name' => $payment['acc_name'],
				'check_num' => $payment['check_num'],
				'check_date' => $payment['check_date'],
				'bank' => $payment['bank'],
				'amount_paid' => number_format($payment['amount_paid'],2,'.',','),
				'billed_date' => 'From '. date("F d, Y", strtotime($payment['startDate'])).' to '. date("F d, Y", strtotime($payment['endDate'])),
				'covered_loa_no' => $loa_noa_no
			]; 

		echo json_encode($response);
	}

    function fetch_initial_billing()
    {
        $csrf_token=$this->security->get_csrf_hash();

        $status = 'Initial';
        $hcare_provider_id = $this->session->userdata('dsg_hcare_prov');
        $noa_id = $this->myhash->hasher($this->uri->segment(4), 'decrypt');
        
        $list = $this->initial_billing_model->get_datatables($noa_id, $hcare_provider_id, $status);

        // var_dump("initial",$list);
        // var_dump("noa_id",$noa_id);
        $data = [];
        foreach ($list as $noa) {
            $date_uploaded = date("Y-m-d", strtotime($noa['date_uploaded']));
            $custom_billing_no = '<mark class="bg-primary text-white">' . $noa['billing_no'] . '</mark>';
            $file_name = $noa['pdf_bill'];
            $initial_bill = number_format($noa['initial_bill'],2,'.',',');
            $custom_actions = '<a href="JavaScript:void(0)" onclick="viewPDFBill(\'' . $noa['pdf_bill'] . '\' , \''. $noa['billing_no'] .'\')" data-bs-toggle="tooltip" title="View LOA"><i class="mdi mdi-file-pdf fs-2 text-danger"></i></a>';
    
            // This data will be rendered to the datatable
            $row = [
                $custom_billing_no,
                $file_name,
                $date_uploaded,
                $initial_bill,
                $custom_actions
            ];
            $data[] = $row;
        }
    
        $draw = $draw = isset($_POST['draw']) ? $_POST['draw'] : 0;
        $totalRecords = $this->initial_billing_model->count_all($noa_id, $hcare_provider_id, $status);
        $filteredRecords = $this->initial_billing_model->count_filtered($noa_id, $hcare_provider_id, $status);
    
        $output = [
            "draw" => intval($draw),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($filteredRecords),
            "data" => $data,
        ];
    
        echo json_encode($output);
    }
    
    function payment_history_fetch() {
		$this->security->get_csrf_hash();
		$list = $this->list_model->get_payment_datatables();
		$data = [];
		$previous_payment_no = '';
		foreach($list as $payment){
			// Check if payment_no is the same as the previous iteration
			if ($payment['payment_no'] !== $previous_payment_no) {
				$row = [];
				$details_id = $this->myhash->hasher($payment['details_id'], 'encrypt');
	
				$custom_details_no = '<span class="text-dark fw-bold">'.$payment['payment_no'].'</span>';
	
				$custom_actions = '<a class="text-info fw-bold ls-1 fs-4" href="JavaScript:void(0)" onclick="viewPaymentInfo(\'' . $details_id . '\',\'' . base_url() . 'uploads/paymentDetails/' . $payment['supporting_file'] . '\')"  data-bs-toggle="tooltip"><u><i class="mdi mdi-view-list fs-3" title="View Payment Details"></i></u></a>';
	
				// $custom_actions .= '<a class="text-success fw-bold ls-1 ps-2 fs-4" href="javascript:void(0)" onclick="viewImage(\'' . base_url() . 'uploads/paymentDetails/' . $payment['supporting_file'] . '\')" data-bs-toggle="tooltip"><u><i class="mdi mdi-file-image fs-3" title="View Proof"></i></u></a>';
	
				$row[] = $custom_details_no;
				$row[] = $payment['acc_number'];
				$row[] = $payment['acc_name'];
				$row[] = $payment['check_num'];
				$row[] = $payment['check_date'];
				$row[] = $payment['bank'];
				$row[] = $custom_actions;
				$data[] = $row;
				
				// Update the previous_payment_no variable
				$previous_payment_no = $payment['payment_no'];
			}
		}
		$output = [
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->list_model->count_payment_all(),
			"recordsFiltered" => $this->list_model->count_payment_filtered(),
			"data" => $data
		];
		echo json_encode($output);
	}

    function upload_final_soa(){

        $this->security->get_csrf_hash();
        $billing_no = $this->input->post('billing-no', TRUE);

        $config['upload_path'] = './uploads/final_soa/';
        $config['allowed_types'] = 'pdf';
        $config['encrypt_name'] = TRUE;
        $this->load->library('upload', $config);
    
        if (!$this->upload->do_upload('finalsoa')) {
            $response = [
                'status'  => 'save-error',
                'message' => 'PDF Bill Upload Failed'
            ];
        } else {
            $upload_data = $this->upload->data();
            $pdf_file = $upload_data['file_name'];
            $inserted = $this->billing_model->update_billing(['final_soa' => $pdf_file],$billing_no);
    
            if(!$inserted){
                $response = [
                   'status'  => 'save-error',
                   'message' => 'Final SOA Upload Failed'
                ];
            }else{
                $response = [
                        'status'     => 'success',
                        'message'    => 'Final SOA Uploaded Successfully'
                    ];   
            }
        }
        echo json_encode($response);
    }
    
}
 