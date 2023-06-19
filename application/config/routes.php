<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Default Route
$route['default_controller'] = 'page_controller';
// Authentication Routes
$route['check-login'] = 'auth_controller/check_login';
$route['redirect-to-dashboard'] = 'auth_controller/redirect_to_dashboard';
$route['logout'] = 'auth_controller/logout';
//checking if for the terms and conditions
$route['update-member-tnc'] = 'auth_controller/set_member_tnc';
$route['read-member-tnc'] = 'auth_controller/read_member_tnc'; 
// $route['import/members'] = 'masterfile_controller';
//reset mbl
$route['ewdfsasdfsadfdssdaf4gegwwrerhtyuj5dgvbx7cvzdfaef_sdfupdatemembermbl'] = 'Reset_MBL_controller';
// Import Members Data Routes
// $route['import/members'] = 'import_controller';
// $route['members/import'] = 'import_controller/import_csv_to_database';
// $route['members/format-download'] = 'import_controller/csv_format_download';

// Import Text Files
$route['import/txt'] = 'import_controller/import_txt_file_page';
$route['import/txt/upload'] = 'import_controller/db_upload_txt_file';

$route['import/spreadsheet_import'] = 'masterfile_controller/spreadsheet_import';
$route['import/spreadhseet_format_download'] = 'masterfile_controller/spreadhseet_format_download';
$route['import/spreadsheet_export'] = 'masterfile_controller/spreadsheet_export';
//================================================================================================ 

// route for automatically run functions on page load
// $route['check-all/approved-loa/expired/update'] = 'autorun_controller/update_all_expired_loa';
// $route['check-member/approved-loa/expired/update/(:any)'] = 'autorun_controller/update_member_expired_loa';
$route['check-all/approved-requests/expired/update'] = 'autorun_controller/update_all_expired_requests';
$route['check-member/approved-requests/expired/update/(:any)'] = 'autorun_controller/update_member_expired_requests';

//================================================================================================
// Member Navigation Links Routes
$route['member/dashboard'] = 'member/pages_controller';
$route['member/hmo-policy'] = 'member/pages_controller/hmo_policy';
$route['member/healthcare-providers'] = 'member/pages_controller/healthcare_providers';
$route['member/request-loa'] = 'member/pages_controller/request_loa_form';
$route['member/request-noa'] = 'member/pages_controller/request_noa_form';
$route['member/request-emergency-loa'] = 'member/pages_controller/request_emergency_loa_form';
$route['member/requested-loa/pending'] = 'member/pages_controller/pending_requested_loa';
$route['member/requested-loa/approved'] = 'member/pages_controller/approved_requested_loa';
$route['member/requested-loa/disapproved'] = 'member/pages_controller/disapproved_requested_loa';
$route['member/requested-loa/completed'] = 'member/pages_controller/completed_requested_loa';
$route['member/requested-loa/expired'] = 'member/pages_controller/expired_requested_loa';
$route['member/requested-loa/cancelled'] = 'member/pages_controller/cancelled_requested_loa';
$route['member/requested-noa/pending'] = 'member/pages_controller/pending_requested_noa';
$route['member/requested-noa/approved'] = 'member/pages_controller/approved_requested_noa';
$route['member/requested-noa/disapproved'] = 'member/pages_controller/disapproved_requested_noa';
$route['member/requested-noa/completed'] = 'member/pages_controller/completed_requested_noa';
$route['member/requested-emergency-loa/pending'] = 'member/pages_controller/pending_requested_emergency_loa';
$route['member/requested-emergency-loa/approved'] = 'member/pages_controller/approved_requested_emergency_loa';
$route['member/requested-emergency-loa/disapproved'] = 'member/pages_controller/disapproved_requested_emergency_loa';
$route['member/requested-emergency-loa/completed'] = 'member/pages_controller/completed_requested_emergency_loa';
$route['member/personal-charges'] = 'member/pages_controller/unpaid_personal_charges';
$route['member/personal-charges/paid'] = 'member/pages_controller/paid_personal_charges';
$route['member/profile'] = 'member/pages_controller/user_profile';
$route['member/personal-charges/approved'] = 'member/pages_controller/view_approved_advances';
$route['member/personal-charges/disapproved'] = 'member/pages_controller/view_disapproved_advances';
$route['member/requested-loa/billed'] = 'member/pages_controller/view_billed_loa';
$route['member/requested-noa/billed'] = 'member/pages_controller/view_billed_noa';
$route['member/requested-noa/paid'] = 'member/pages_controller/view_paid_noa';
$route['member/requested-emergency-loa/billed'] = 'member/pages_controller/view_billed_emergency_loa';
$route['member/requested-emergency-loa/paid'] = 'member/pages_controller/view_paid_emergency_loa';
$route['member/requested-loa/paid'] = 'member/pages_controller/view_paid_loa';

// Member User Account Routes
$route['member/account-settings'] = 'member/account_controller/account_settings';
$route['member/account-settings/password/update'] = 'member/account_controller/update_account_password';
$route['member/account-settings/username/update'] = 'member/account_controller/update_account_username';

// Member LOA Routes
$route['member/get-services/(:any)'] = 'member/loa_controller/get_hp_services';
$route['member/edit-loa/get-services/(:any)/(:any)'] = 'member/loa_controller/get_hp_services_on_edit';
$route['member/request-loa/submit'] = 'member/loa_controller/submit_loa_request';
$route['member/requested-loa/pending/fetch'] = 'member/loa_controller/fetch_pending_loa';
$route['member/requested-loa/approved/fetch'] = 'member/loa_controller/fetch_approved_loa';
$route['member/requested-loa/disapproved/fetch'] = 'member/loa_controller/fetch_disapproved_loa';
$route['member/requested-loa/completed/fetch'] = 'member/loa_controller/fetch_completed_loa';
$route['member/requested-loa/view/(:any)'] = 'member/loa_controller/get_loa_info';
$route['member/requested-loa/pending/cancel/(:any)'] = 'member/loa_controller/cancel_loa_request';
$route['member/requested-loa/edit/(:any)'] = 'member/loa_controller/edit_loa_request';
$route['member/requested-loa/update/(:any)'] = 'member/loa_controller/update_loa_request';
$route['member/requested-loa/generate-printable-loa/(:any)'] = 'member/loa_controller/generate_printable_loa';
$route['member/requested-loa/approve/cancel-request/(:any)'] = 'member/loa_controller/request_loa_cancellation';
$route['member/requested-loa/expired/fetch'] = 'member/loa_controller/fetch_expired_loa';
$route['member/requested-loa/cancelled/fetch'] = 'member/loa_controller/fetch_cancelled_loa';
$route['member/requested-loa/billed/fetch'] = 'member/loa_controller/fetch_billed_loa';
$route['member/requested-loa/paid/fetch'] = 'member/loa_controller/fetch_paid_loa';


// Member NOA Routes
$route['member/request-noa/submit'] = 'member/noa_controller/submit_noa_request';
$route['member/requested-noa/pending/fetch'] = 'member/noa_controller/fetch_pending_noa';
$route['member/requested-noa/approved/fetch'] = 'member/noa_controller/fetch_approved_noa';
$route['member/requested-noa/disapproved/fetch'] = 'member/noa_controller/fetch_disapproved_noa';
$route['member/requested-noa/completed/fetch'] = 'member/noa_controller/fetch_completed_noa';
$route['member/requested-noa/view/pending/(:any)'] = 'member/noa_controller/get_pending_noa_info';
$route['member/requested-noa/view/approved/(:any)'] = 'member/noa_controller/get_approved_noa_info';
$route['member/requested-noa/view/disapproved/(:any)'] = 'member/noa_controller/get_disapproved_noa_info';
$route['member/requested-noa/view/completed/(:any)'] = 'member/noa_controller/get_completed_noa_info';
$route['member/requested-noa/pending/cancel/(:any)'] = 'member/noa_controller/cancel_noa_request';
$route['member/requested-noa/edit/(:any)'] = 'member/noa_controller/edit_noa_request';
$route['member/requested-noa/update/(:any)'] = 'member/noa_controller/update_noa_request';
$route['member/requested-noa/generate-printable-noa/(:any)'] = 'member/noa_controller/generate_printable_noa';
$route['member/requested-noa/billed/fetch'] = 'member/noa_controller/fetch_billed_noa';
$route['member/requested-noa/paid/fetch'] = 'member/noa_controller/fetch_paid_noa';

// Member emergency LOA Routes
$route['member/request-emergency-loa/submit'] = 'member/emergency_loa/submit_noa_request';
$route['member/requested-emergency-loa/pending/fetch'] = 'member/emergency_loa/fetch_pending_noa';
$route['member/requested-emergency-loa/approved/fetch'] = 'member/emergency_loa/fetch_approved_noa';
$route['member/requested-emergency-loa/disapproved/fetch'] = 'member/emergency_loa/fetch_disapproved_noa';
$route['member/requested-emergency-loa/completed/fetch'] = 'member/emergency_loa/fetch_completed_noa';
$route['member/requested-emergency-loa/view/pending/(:any)'] = 'member/emergency_loa/get_pending_noa_info';
$route['member/requested-emergency-loa/view/approved/(:any)'] = 'member/emergency_loa/get_approved_noa_info';
$route['member/requested-emergency-loa/view/disapproved/(:any)'] = 'member/emergency_loa/get_disapproved_noa_info';
$route['member/requested-emergency-loa/view/completed/(:any)'] = 'member/emergency_loa/get_completed_noa_info';
$route['member/requested-emergency-loa/pending/cancel/(:any)'] = 'member/emergency_loa/cancel_noa_request';
$route['member/requested-emergency-loa/edit/(:any)'] = 'member/emergency_loa/edit_noa_request';
$route['member/requested-emergency-loa/update/(:any)'] = 'member/emergency_loa/update_noa_request';
$route['member/requested-emergency-loa/generate-printable-emergency-loa/(:any)'] = 'member/emergency_loa/generate_printable_noa';
$route['member/requested-emergency-loa/billed/fetch'] = 'member/emergency_loa/fetch_billed_noa';
$route['member/requested-emergency-loa/paid/fetch'] = 'member/emergency_loa/fetch_paid_noa';

// Member Personal Charges Routes
$route['member/personal-charges/unpaid/fetch'] = 'member/pcharges_controller/fetch_unpaid_personal_charges';
$route['member/personal-charges/requested/fetch'] = 'member/pcharges_controller/fetch_requested_personal_charges';
$route['member/personal/submit-healthcare-advance'] = 'member/pcharges_controller/submit_healthcare_advance';
$route['member/personal-charges/view/details/(:any)'] = 'member/pcharges_controller/fetch_charges_details';
$route['member/personal-charges/approved/fetch'] = 'member/pcharges_controller/fetch_approved_advances';
$route['member/personal-charges/disapproved/fetch'] = 'member/pcharges_controller/fetch_disapproved_advances';

// End of Member Routes
//======================================================================================================
// HC Front Desk
 $route['hc-provider-front-desk/dashboard'] = 'hc_provider_front_desk/pages_controller';
// HC Accounting
 $route['hc-provider-accounting/dashboard'] = 'hc_provider_accounting/pages_controller';
 
//======================================================================================================
// Start of HealthCare Provider (Hospital, Clinics, and Laboratories Users) Routes
$route['healthcare-provider/dashboard'] = 'healthcare_provider/pages_controller';

// Search Member
$route['healthcare-provider/search-member/healthcard'] = 'healthcare_provider/search_controller/search_member_by_healthcard';
$route['healthcare-provider/search-member/name'] = 'healthcare_provider/search_controller/search_member_by_name';
// provider front desk
$route['hc-provider-front-desk/search-member/healthcard'] = 'hc_provider_front_desk/search_controller/search_member_by_healthcard';
$route['hc-provider-front-desk/search-member/name'] = 'hc_provider_front_desk/search_controller/search_member_by_name';
// provider accounting
$route['hc-provider-accounting/search-member/healthcard'] = 'hc_provider_accounting/search_controller/search_member_by_healthcard';
$route['hc-provider-accounting/search-member/name'] = 'hc_provider_accounting/search_controller/search_member_by_name';
// $route['healthcare-provider/search-member/healthcard'] = 'healthcare_provider/search_controller/search_by_healthcard';
// $route['healthcare-provider/search-member/name'] = 'healthcare_provider/search_controller/search_by_name';
$route['healthcare-provider/history/get_loa_noa'] = 'healthcare_provider/search_controller/get_loa_noa';

//Upload Textfile
$route['healthcare-provider/billing/payment_list'] = 'healthcare_provider/pages_controller/payment_list';
$route['healthcare-provider/billing/upload-soa-textfile'] = 'healthcare_provider/billing_controller/db_upload_textfile';
// Patient's Billing
$route['healthcare-provider/billing'] = 'healthcare_provider/billing_controller/billing_search_member';
$route['healthcare-provider/billing/search-by-healthcard']  = 'healthcare_provider/billing_controller/search_member_by_healthcard';
$route['healthcare-provider/billing/search-by-name'] = 'healthcare_provider/billing_controller/search_member_by_name';
$route['healthcare-provider/billing/search']  = 'healthcare_provider/billing_controller/search_member_by_healthcard';
$route['healthcare-provider/billing-list/payment-history/fetch']  = 'healthcare_provider/billing_controller/payment_history_fetch';
$route['healthcare-provider/bill/payment-list/view-payment-details/(:any)']  = 'healthcare_provider/billing_controller/view_payment_details';
// $route['healthcare-provider/billing/bill-loa/(:any)'] = 'healthcare_provider/billing_controller/bill_patient_loa';
// LOA Billing
$route['healthcare-provider/billing/bill-loa/upload-pdf/(:any)'] = 'healthcare_provider/billing_controller/upload_loa_pdf_bill_form';
$route['healthcare-provider/billing/bill-loa/upload-pdf/(:any)/submit'] = 'healthcare_provider/billing_controller/submit_loa_pdf_bill';
$route['healthcare-provider/billing/bill-loa/upload-pdf/(:any)/success'] = 'healthcare_provider/billing_controller/pdf_billing_success';
$route['healthcare-provider/billing/bill-loa/manual/(:any)'] = 'healthcare_provider/billing_controller/bill_patient_loa';

$route['healthcare-provider/billing/bill-loa/fetch/loa'] = 'healthcare_provider/billing_controller/fetch_loa_to_bill';
$route['healthcare-provider/billing/bill-loa/diagnostic-test/submit/(:any)'] = 'healthcare_provider/billing_controller/diagnostic_loa_final_billing';
$route['healthcare-provider/billing/bill-loa/consultation/submit/(:any)'] = 'healthcare_provider/billing_controller/consultation_loa_final_billing';
$route['healthcare-provider/billing/bill-loa/(:any)/success/(:any)'] = 'healthcare_provider/billing_controller/loa_billing_success';
// viewing of loa and noa billing receipt
$route['healthcare-provider/billing/loa/view-receipt/(:any)'] = 'healthcare_provider/billing_controller/view_request_billing';
$route['healthcare-provider/billing/noa/view-receipt/(:any)'] = 'healthcare_provider/billing_controller/view_request_billing';

//patient history
$route['healthcare-provider/patient_history/loa/(:any)'] = 'healthcare_provider/patient_controller/get_loa_history_info';
$route['healthcare-provider/patient_history/noa/(:any)'] = 'healthcare_provider/patient_controller/get_noa_history_info';

//SOA LIST
$route['healthcare-provider/patient_soa/soa-list'] = 'healthcare_provider/patient_controller/list_of_soa';
$route['healthcare-provider/patient/fetch-lis-of-soa'] = 'healthcare_provider/patient_controller/fetch_lis_of_soa';
$route['hc-provider-accounting/patient_soa/soa-list'] = 'hc_provider_accounting/patient_controller/list_of_soa';
$route['hc-provider-accounting/patient/fetch-lis-of-soa'] = 'hc_provider_accounting/patient_controller/fetch_lis_of_soa';
// NOA Billing
$route['healthcare-provider/billing/bill-noa/upload-pdf/(:any)'] = 'healthcare_provider/billing_controller/upload_noa_pdf_bill_form';
$route['healthcare-provider/billing/bill-noa/upload-pdf/(:any)/submit'] = 'healthcare_provider/billing_controller/submit_noa_pdf_bill';
$route['healthcare-provider/initial_billing/bill-noa/upload-pdf/(:any)/submit'] = 'healthcare_provider/billing_controller/submit_initial_noa_pdf_bill';
$route['healthcare-provider/fetch_initial_billing/fetch/(:any)'] = 'healthcare_provider/billing_controller/fetch_initial_billing';
$route['healthcare-provider/billing/bill-noa/upload-pdf/(:any)/success'] = 'healthcare_provider/billing_controller/pdf_billing_success';
$route['healthcare-provider/billing/bill-noa/manual/(:any)']  = 'healthcare_provider/billing_controller/bill_patient_noa';
$route['healthcare-provider/billing/bill-noa/submit/(:any)'] = 'healthcare_provider/billing_controller/noa_final_billing';
$route['healthcare-provider/billing/bill-noa/success/(:any)'] = 'healthcare_provider/billing_controller/noa_billing_success';
$route['healthcare-provider/billing/final-soa/submit'] = 'healthcare_provider/billing_controller/upload_final_soa';

$route['healthcare-provider/billing/billing-person/finalBilling']  = 'healthcare_provider/billing_controller/billing3NoaReview';
$route['healthcare-provider/billing/billing-person/finish']['post']  = 'healthcare_provider/billing_controller/billing5Final';

//Re-upload loa noa 
$route['healthcare-provider/billing/bill-r_request/upload-pdf/(:any)/(:any)'] = 'healthcare_provider/billing_controller/re_upload_pdf_bill_form';
$route['healthcare-provider/billing/bill-r_request/manual/(:any)/(:any)']  = 'healthcare_provider/billing_controller/re_upload_bill_patient';
// LOA Pages
$route['healthcare-provider/loa-requests/pending'] = 'healthcare_provider/pages_controller/pending_loa_requests';
$route['healthcare-provider/loa-requests/approved'] = 'healthcare_provider/pages_controller/approved_loa_requests';
$route['healthcare-provider/loa-requests/disapproved'] = 'healthcare_provider/pages_controller/disapproved_loa_requests';
$route['healthcare-provider/loa-requests/completed'] = 'healthcare_provider/pages_controller/completed_loa_requests';
$route['healthcare-provider/loa-requests/billed'] = 'healthcare_provider/pages_controller/billed_loa_requests';

// LOA Datatables fetch data routes
$route['healthcare-provider/loa-requests/pending/fetch'] = 'healthcare_provider/loa_controller/fetch_pending_loa_requests';
$route['healthcare-provider/loa-requests/approved/fetch'] = 'healthcare_provider/loa_controller/fetch_approved_loa_requests';
$route['healthcare-provider/loa/requested-loa/generate-printable-loa/(:any)'] = 'healthcare_provider/loa_controller/generate_printable_loa';
$route['healthcare-provider/loa-requests/disapproved/fetch'] = 'healthcare_provider/loa_controller/fetch_disapproved_loa_requests';
$route['healthcare-provider/loa-requests/completed/fetch'] = 'healthcare_provider/loa_controller/fetch_completed_loa_requests';
$route['healthcare-provider/loa-requests/billed/fetch'] = 'healthcare_provider/loa_controller/fetch_billed_loa_requests';

// LOA modal view
$route['healthcare-provider/loa-requests/pending/view/(:any)'] = 'healthcare_provider/loa_controller/get_pending_loa_info';
$route['healthcare-provider/loa-requests/approved/view/(:any)'] = 'healthcare_provider/loa_controller/get_approved_loa_info';
$route['healthcare-provider/loa-requests/disapproved/view/(:any)'] = 'healthcare_provider/loa_controller/get_disapproved_loa_info';
$route['healthcare-provider/loa-requests/completed/view/(:any)'] = 'healthcare_provider/loa_controller/get_completed_loa_info';
$route['healthcare-provider/loa-requests/billed/view/(:any)'] = 'healthcare_provider/loa_controller/get_billed_loa_info';


// NOA Pages
$route['healthcare-provider/noa-requests/pending'] = 'healthcare_provider/pages_controller/pending_noa_requests';
$route['healthcare-provider/noa-requests/approved'] = 'healthcare_provider/pages_controller/approved_noa_requests';
$route['healthcare-provider/noa-requests/disapproved'] = 'healthcare_provider/pages_controller/disapproved_noa_requests';
$route['healthcare-provider/noa-requests/completed'] = 'healthcare_provider/pages_controller/completed_noa_requests';
$route['healthcare-provider/noa-requests/billed'] = 'healthcare_provider/pages_controller/billed_noa_requests';

// Noa Datatables fetch data routes
$route['healthcare-provider/noa-requests/pending/fetch'] = 'healthcare_provider/noa_controller/fetch_pending_noa_requests';
$route['healthcare-provider/noa-requests/approved/fetch'] = 'healthcare_provider/noa_controller/fetch_approved_noa_requests';
$route['healthcare-provider/noa/requested-noa/generate-printable-noa/(:any)'] = 'healthcare_provider/noa_controller/generate_printable_noa';
$route['healthcare-provider/noa-requests/disapproved/fetch'] = 'healthcare_provider/noa_controller/fetch_disapproved_noa_requests';
$route['healthcare-provider/noa-requests/completed/fetch'] = 'healthcare_provider/noa_controller/fetch_completed_noa_requests';
$route['healthcare-provider/noa-requests/billed/fetch'] = 'healthcare_provider/noa_controller/fetch_billed_noa_requests';

// NOA modal view
$route['healthcare-provider/noa-requests/view/(:any)'] = 'healthcare_provider/noa_controller/get_noa_info';

// LOA Pages hc-provider-front-desk hc_provider_front_desk
$route['hc-provider-front-desk/loa-requests/pending'] = 'hc_provider_front_desk/pages_controller/pending_loa_requests';
$route['hc-provider-front-desk/loa-requests/approved'] = 'hc_provider_front_desk/pages_controller/approved_loa_requests';
$route['hc-provider-front-desk/loa-requests/disapproved'] = 'hc_provider_front_desk/pages_controller/disapproved_loa_requests';
$route['hc-provider-front-desk/loa-requests/completed'] = 'hc_provider_front_desk/pages_controller/completed_loa_requests';
$route['hc-provider-front-desk/loa-requests/billed'] = 'hc_provider_front_desk/pages_controller/billed_loa_requests';

// LOA Datatables fetch data routes hc_provider_front_desk
$route['hc-provider-front-desk/loa-requests/pending/fetch'] = 'hc_provider_front_desk/loa_controller/fetch_pending_loa_requests';
$route['hc-provider-front-desk/loa-requests/approved/fetch'] = 'hc_provider_front_desk/loa_controller/fetch_approved_loa_requests';
$route['hc-provider-front-desk/loa/requested-loa/generate-printable-loa/(:any)'] = 'hc_provider_front_desk/loa_controller/generate_printable_loa';
$route['hc-provider-front-desk/loa-requests/disapproved/fetch'] = 'hc_provider_front_desk/loa_controller/fetch_disapproved_loa_requests';
$route['hc-provider-front-desk/loa-requests/completed/fetch'] = 'hc_provider_front_desk/loa_controller/fetch_completed_loa_requests';
$route['hc-provider-front-desk/loa-requests/billed/fetch'] = 'hc_provider_front_desk/loa_controller/fetch_billed_loa_requests';

// LOA modal view hc_provider_front_desk
$route['hc-provider-front-desk/loa-requests/pending/view/(:any)'] = 'hc_provider_front_desk/loa_controller/get_pending_loa_info';
$route['hc-provider-front-desk/loa-requests/approved/view/(:any)'] = 'hc_provider_front_desk/loa_controller/get_approved_loa_info';
$route['hc-provider-front-desk/loa-requests/disapproved/view/(:any)'] = 'hc_provider_front_desk/loa_controller/get_disapproved_loa_info';
$route['hc-provider-front-desk/loa-requests/completed/view/(:any)'] = 'hc_provider_front_desk/loa_controller/get_completed_loa_info';
$route['hc-provider-front-desk/loa-requests/billed/view/(:any)'] = 'hc_provider_front_desk/loa_controller/get_billed_loa_info';


// NOA Pages hc_provider_front_desk 
$route['hc-provider-front-desk/noa-requests/pending'] = 'hc_provider_front_desk/pages_controller/pending_noa_requests';
$route['hc-provider-front-desk/noa-requests/approved'] = 'hc_provider_front_desk/pages_controller/approved_noa_requests';
$route['hc-provider-front-desk/noa-requests/disapproved'] = 'hc_provider_front_desk/pages_controller/disapproved_noa_requests';
$route['hc-provider-front-desk/noa-requests/completed'] = 'hc_provider_front_desk/pages_controller/completed_noa_requests';
$route['hc-provider-front-desk/noa-requests/billed'] = 'hc_provider_front_desk/pages_controller/billed_noa_requests';

// Noa Datatables fetch data routes hc_provider_front_desk
$route['hc-provider-front-desk/noa-requests/pending/fetch'] = 'hc_provider_front_desk/noa_controller/fetch_pending_noa_requests';
$route['hc-provider-front-desk/noa-requests/approved/fetch'] = 'hc_provider_front_desk/noa_controller/fetch_approved_noa_requests';
$route['hc-provider-front-desk/noa/requested-noa/generate-printable-noa/(:any)'] = 'hc_provider_front_desk/noa_controller/generate_printable_noa';
$route['hc-provider-front-desk/noa-requests/disapproved/fetch'] = 'hc_provider_front_desk/noa_controller/fetch_disapproved_noa_requests';
$route['hc-provider-front-desk/noa-requests/completed/fetch'] = 'hc_provider_front_desk/noa_controller/fetch_completed_noa_requests';
$route['hc-provider-front-desk/noa-requests/billed/fetch'] = 'hc_provider_front_desk/noa_controller/fetch_billed_noa_requests';

// NOA modal view hc_provider_accounting
$route['hc-provider-accounting/noa-requests/view/(:any)'] = 'hc_provider_accounting/noa_controller/get_noa_info';
// LOA Pages hc-provider-accounting hc_provider_accounting
$route['hc-provider-accounting/loa-requests/pending'] = 'hc_provider_accounting/pages_controller/pending_loa_requests';
$route['hc-provider-accounting/loa-requests/approved'] = 'hc_provider_accounting/pages_controller/approved_loa_requests';
$route['hc-provider-accounting/loa-requests/disapproved'] = 'hc_provider_accounting/pages_controller/disapproved_loa_requests';
$route['hc-provider-accounting/loa-requests/completed'] = 'hc_provider_accounting/pages_controller/completed_loa_requests';
$route['hc-provider-accounting/loa-requests/billed'] = 'hc_provider_accounting/pages_controller/billed_loa_requests';

// LOA Datatables fetch data routes hc_provider_accounting
$route['hc-provider-accounting/loa-requests/pending/fetch'] = 'hc_provider_accounting/loa_controller/fetch_pending_loa_requests';
$route['hc-provider-accounting/loa-requests/approved/fetch'] = 'hc_provider_accounting/loa_controller/fetch_approved_loa_requests';
$route['hc-provider-accounting/loa/requested-loa/generate-printable-loa/(:any)'] = 'hc_provider_accounting/loa_controller/generate_printable_loa';
$route['hc-provider-accounting/loa-requests/disapproved/fetch'] = 'hc_provider_accounting/loa_controller/fetch_disapproved_loa_requests';
$route['hc-provider-accounting/loa-requests/completed/fetch'] = 'hc_provider_accounting/loa_controller/fetch_completed_loa_requests';
$route['hc-provider-accounting/loa-requests/billed/fetch'] = 'hc_provider_accounting/loa_controller/fetch_billed_loa_requests';

// LOA modal view hc_provider_accounting
$route['hc-provider-accounting/loa-requests/pending/view/(:any)'] = 'hc_provider_accounting/loa_controller/get_pending_loa_info';
$route['hc-provider-accounting/loa-requests/approved/view/(:any)'] = 'hc_provider_accounting/loa_controller/get_approved_loa_info';
$route['hc-provider-accounting/loa-requests/disapproved/view/(:any)'] = 'hc_provider_accounting/loa_controller/get_disapproved_loa_info';
$route['hc-provider-accounting/loa-requests/completed/view/(:any)'] = 'hc_provider_accounting/loa_controller/get_completed_loa_info';
$route['hc-provider-accounting/loa-requests/billed/view/(:any)'] = 'hc_provider_accounting/loa_controller/get_billed_loa_info';


// NOA Pages hc_provider_accounting 
$route['hc-provider-accounting/noa-requests/pending'] = 'hc_provider_accounting/pages_controller/pending_noa_requests';
$route['hc-provider-accounting/noa-requests/approved'] = 'hc_provider_accounting/pages_controller/approved_noa_requests';
$route['hc-provider-accounting/noa-requests/disapproved'] = 'hc_provider_accounting/pages_controller/disapproved_noa_requests';
$route['hc-provider-accounting/noa-requests/completed'] = 'hc_provider_accounting/pages_controller/completed_noa_requests';
$route['hc-provider-accounting/noa-requests/billed'] = 'hc_provider_accounting/pages_controller/billed_noa_requests';

// Noa Datatables fetch data routes hc_provider_accounting
$route['hc-provider-accounting/noa-requests/pending/fetch'] = 'hc_provider_accounting/noa_controller/fetch_pending_noa_requests';
$route['hc-provider-accounting/noa-requests/approved/fetch'] = 'hc_provider_accounting/noa_controller/fetch_approved_noa_requests';
$route['hc-provider-accounting/noa/requested-noa/generate-printable-noa/(:any)'] = 'hc_provider_accounting/noa_controller/generate_printable_noa';
$route['hc-provider-accounting/noa-requests/disapproved/fetch'] = 'hc_provider_accounting/noa_controller/fetch_disapproved_noa_requests';
$route['hc-provider-accounting/noa-requests/completed/fetch'] = 'hc_provider_accounting/noa_controller/fetch_completed_noa_requests';
$route['hc-provider-accounting/noa-requests/billed/fetch'] = 'hc_provider_accounting/noa_controller/fetch_billed_noa_requests';

// NOA modal view hc_provider_accounting
$route['hc-provider-accounting/noa-requests/view/(:any)'] = 'hc_provider_accounting/noa_controller/get_noa_info';

//Soa
$route['healthcare-provider/soa/create-soa'] = 'healthcare_provider/Soa_controller/soaCreate';
$route['healthcare-provider/soa/reprint-soa'] = 'healthcare_provider/Soa_controller/soaRequest';
//Cost Item
$route['healthcare-provider/cost-item/cost-item-requisistion'] = 'healthcare_provider/Cost_item_controller/costItemReq';
$route['healthcare-provider/cost-item/cost-item-requisistion-list/pending'] = 'healthcare_provider/Cost_item_controller/costItemReqListPending';
$route['healthcare-provider/cost-item/cost-item-requisistion-list/approved'] = 'healthcare_provider/Cost_item_controller/costItemReqListApproved';
//Reports
$route['healthcare-provider/reports/report-list'] = 'healthcare_provider/Report_controller/report_list';

//Ajax
$route['healthcare-provider/reports/report-list/ajax/addEquip/(:any)'] = 'healthcare_provider/Billing_controller/addEquip';
$route['healthcare-provider/reports/report-list/ajax/showAllEquipment'] = 'healthcare_provider/Billing_controller/showAllEquipment';

$route['healthcare-provider/reports/report-list/ajax/saveloacosttype'] = 'healthcare_provider/Billing_controller/saveloacosttype';
$route['healthcare-provider/reports/report-list/ajax/billPersonalCharges'] = 'healthcare_provider/Billing_controller/billPersonalCharges';

// $route['healthcare-provider/reports/report-list/ajax/getBillingLoa'] = 'healthcare_provider/Billing_controller/getBillingLoa';

$route['healthcare-provider/reports/report-list/ajax/postBillingLoa'] = 'healthcare_provider/Billing_controller/postBillingLoa';
$route['healthcare-provider/reports/report-list/ajax/addEquipments']['post'] = 'healthcare_provider/Billing_controller/addEquipments';
$route['healthcare-provider/reports/report-list/ajax/billLoaMember'] = 'healthcare_provider/Billing_controller/billLoaMember';
$route['healthcare-provider/reports/report-list/ajax/billingServicesMember'] = 'healthcare_provider/Billing_controller/billingServicesMember';

// HealthCare Provider List of Patient Routes
$route['healthcare-provider/patient/design'] = 'healthcare_provider/patient_controller/design';
$route['healthcare-provider/patient/fetch_all_patient/(:any)'] = 'healthcare_provider/patient_controller/fetch_all_patient';
$route['healthcare-provider/patient/view_information/(:any)'] = 'healthcare_provider/patient_controller/view_information';
$route['healthcare-provider/patient/fetch_all_patient_loa'] = 'healthcare_provider/patient_controller/fetch_all_patient_loa';
$route['healthcare-provider/patient/fetch_all_patient_noa'] = 'healthcare_provider/patient_controller/fetch_all_patient_noa';
$route['healthcare-provider/patient/get_takehome_meds'] = 'healthcare_provider/noa_controller/get_takehome_meds';
// hc-provider-front-desk List of Patient Routes 
$route['hc-provider-front-desk/patient/design'] = 'hc_provider_front_desk/patient_controller/design';
$route['hc-provider-front-desk/patient/fetch_all_patient/(:any)'] = 'hc_provider_front_desk/patient_controller/fetch_all_patient';
$route['hc-provider-front-desk/patient/view_information/(:any)'] = 'hc_provider_front_desk/patient_controller/view_information';
$route['hc-provider-front-desk/patient/fetch_all_patient_loa'] = 'hc_provider_front_desk/patient_controller/fetch_all_patient_loa';
$route['hc-provider-front-desk/patient/fetch_all_patient_noa'] = 'hc_provider_front_desk/patient_controller/fetch_all_patient_noa';
$route['hc-provider-front-desk/patient/get_takehome_meds'] = 'hc_provider_front_desk/noa_controller/get_takehome_meds';


// $route['head-office-iad/transaction/members'] = 'ho_iad/transaction_controller/members';
// $route['head-office-iad/transaction/fetch_all_members'] = 'ho_iad/transaction_controller/fetch_all_members';
// $route['head-office-iad/transaction/view_information/(:any)'] = 'ho_iad/transaction_controller/view_information';

// HealthCare Provider User Account Routes
$route['healthcare-provider/account-settings'] = 'healthcare_provider/account_controller/account_settings';
$route['healthcare-provider/account-settings/password/update'] = 'healthcare_provider/account_controller/update_account_password';
$route['healthcare-provider/account-settings/username/update'] = 'healthcare_provider/account_controller/update_account_username';
// HealthCare Provider User Account Routes hc-provider-front-desk
$route['hc-provider-front-desk/account-settings'] = 'hc_provider_front_desk/account_controller/account_settings';
$route['hc-provider-front-desk/account-settings/password/update'] = 'hc_provider_front_desk/account_controller/update_account_password';
$route['hc-provider-front-desk/account-settings/username/update'] = 'hc_provider_front_desk/account_controller/update_account_username';
// HealthCare Provider User Account Routes hc-provider-accounting
$route['hc-provider-accounting/account-settings'] = 'hc_provider_accounting/account_controller/account_settings';
$route['hc-provider-accounting/account-settings/password/update'] = 'hc_provider_accounting/account_controller/update_account_password';
$route['hc-provider-accounting/account-settings/username/update'] = 'hc_provider_accounting/account_controller/update_account_username';

// End of HealthCare Provider (Hospital, Clinics, and Laboratories Users) Routes
//====================================================================================================


// Start of Head Office Accounting Routes
// ===================================================================================================

$route['head-office-accounting/dashboard'] = 'ho_accounting/Pages_controller';
//Billed
$route['head-office-accounting/billing-list/billed/fetch'] = 'ho_accounting/main_controller/fetch_billed';
$route['head-office-accounting/billing-list/billed/view/(:any)'] = 'ho_accounting/main_controller/view_billed_details';
$route['head-office-accounting/billing-list/noa/view/(:any)'] = 'ho_accounting/main_controller/view_billed_details';
$route['head-office-accounting/billing-list/billed/hp_name'] = 'ho_accounting/main_controller/get_hp_name';
$route['head-office-accounting/billing-list/billed/sum'] = 'ho_accounting/main_controller/get_company_charge_total';
$route['head-office-accounting/billing-list/billed/payment-details'] = 'ho_accounting/main_controller/add_payment_details';
//Closed
$route['head-office-accounting/billing-list/closed/fetch'] = 'ho_accounting/main_controller/fetch_closed';
//Unbilled
$route['head-office-accounting/billing-list/unbilled_loa/fetch'] = 'ho_accounting/main_controller/fetch_unbilled_loa';
$route['healthcare-provider/loa-requests/unbilled_loa/view/(:any)'] = 'ho_accounting/main_controller/unbilled_loa_details';
$route['head-office-accounting/billing-list/unbilled_noa/fetch'] = 'ho_accounting/main_controller/fetch_unbilled_noa';
$route['head-office-accounting/billing-list/unbilled_noa/view/(:any)'] = 'ho_accounting/main_controller/unbilled_noa_details';
//Payment
$route['head-office-accounting/billing-list/payment-history/fetch'] = 'ho_accounting/main_controller/payment_history_fetch';
$route['head-office-accounting/billing-list/view-payment-details/(:any)'] = 'ho_accounting/main_controller/view_payment_details';
$route['head-office-accounting/billing-list/view-employee-payment/(:any)'] = 'ho_accounting/main_controller/view_employee_payment';
$route['head-office-accounting/bill/requests-list/fetch'] = 'ho_accounting/main_controller/fetch_for_payment';
$route['head-office-accounting/bill/billed-loa/fetch-payable/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/fetch_consolidated_bill';
$route['head-office-accounting/bill/monthly-bill/fetch'] = 'ho_accounting/main_controller/fetch_monthly_bill';
$route['head-office-accounting/bill/matched/total-bill/fetch'] = 'ho_accounting/main_controller/get_total_hp_bill';
$route['head-office-accounting/bill/paid-list/fetch'] = 'ho_accounting/main_controller/view_paid_loa_noa';
$route['head-office-accounting/bill/paid-loa/fetch-payable/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/fetch_consolidated_paid';
$route['head-office-accounting/bill/monthly-paid/fetch'] = 'ho_accounting/main_controller/fetch_monthly_paid_bill';
$route['head-office-accounting/bill/paid/total-bill/fetch'] = 'ho_accounting/main_controller/get_total_hp_paid_bill';
$route['head-office-accounting/bill/payment-details/fetch'] = 'ho_accounting/main_controller/get_payment_details';
$route['head-office-accounting/bill/billed-noa-loa/print/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/print_billed_loa_noa';
$route['head-office-accounting/bill/billed/fetch'] = 'ho_accounting/main_controller/fetch_all_for_payment';
$route['head-office-accounting/bill/charging/fetch'] = 'ho_accounting/main_controller/fetch_charging_billed';
$route['head-office-accounting/get-business-units'] = 'ho_accounting/main_controller/get_business_units';
$route['head-office-accounting/fetch-business-units'] = 'ho_accounting/main_controller/get_business_u';
$route['head-office-accounting/bill/submit-for-payment-bill'] = 'ho_accounting/main_controller/submit_for_payment_bill';
$route['head-office-accounting/bill/for-payment/fetch'] = 'ho_accounting/main_controller/fetch_for_payment_bill';
$route['head-office-accounting/bill/monthly-payment/fetch'] = 'ho_accounting/main_controller/fetch_payment_bill';
$route['head-office-accounting/bill/for-payment-details/fetch'] = 'ho_accounting/main_controller/fetch_for_pay_details';
$route['head-office-accounting/bill/paid-bill/fetch'] = 'ho_accounting/main_controller/fetch_paid_bills';
$route['head-office-accounting/reports/paid'] = 'ho_accounting/main_controller/fetch_paid_bill_report';
$route['head-office-accounting/charging/business-units/fetch'] = 'ho_accounting/main_controller/fetch_bu_charging';
$route['head-office-accounting/charging/business-units/details/fetch'] = 'ho_accounting/main_controller/fetch_charging_details';

$route['print/pdfbilling/(:any)/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/print_bills/$1/$2/$3/$4';
$route['printforpayment/pdfbilling/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/print_forPayment/$1/$2/$3/$4/$5';
$route['printpayment/pdfbilling/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/print_payment/$1/$2/$3';
$route['printpaid/pdfbilling/(:any)/(:any)/(:any)'] = 'ho_accounting/main_controller/print_paid/$1/$2/$3';

//Pages
$route['head-office-accounting/billing-list'] = 'ho_accounting/TableList';
$route['head-office-accounting/billing-list/billed'] = 'ho_accounting/TableList/billed_record';
$route['head-office-accounting/billing-list/closed'] = 'ho_accounting/TableList/closed_record';

// Pages Controller
$route['head-office-accounting/loa-request-list/loa-approved'] = 'ho_accounting/Pages_controller/approved_loa_requests';
$route['head-office-accounting/loa-request-list/loa-completed'] = 'ho_accounting/Pages_controller/completed_loa_requests';
$route['head-office-accounting/noa-request-list/noa-approved'] = 'ho_accounting/Pages_controller/approved_noa_requests';
$route['head-office-accounting/noa-request-list/noa-completed'] = 'ho_accounting/Pages_controller/completed_noa_requests';
$route['head-office-accounting/payment_history'] = 'ho_accounting/Pages_controller/show_payment_history_form';
$route['head-office-accounting/billing-list/unbilled/loa'] = 'ho_accounting/Pages_controller/unbilled_loa_form';
$route['head-office-accounting/billing-list/unbilled/noa'] = 'ho_accounting/Pages_controller/unbilled_noa_form';
$route['head-office-accounting/bill/billing-list/billed-loa-noa'] = 'ho_accounting/Pages_controller/view_billed_loa_noa';
$route['head-office-accounting/bill/billing-list/paid-loa-noa'] = 'ho_accounting/Pages_controller/view_paid_loa_noa';
$route['head-office-accounting/billing-list/for-payment'] = 'ho_accounting/Pages_controller/view_for_payments';
$route['head-office-accounting/bill/fetch_payments/(:any)'] = 'ho_accounting/Pages_controller/view_payments';
$route['head-office-accounting/billing-list/paid-bill'] = 'ho_accounting/Pages_controller/view_paid_bill';
$route['head-office-accounting/bill/fetch_paid/(:any)'] = 'ho_accounting/Pages_controller/view_monthly_paid_bill';
$route['head-office-accounting/reports'] = 'ho_accounting/Pages_controller/view_generate_reports';
$route['head-office-accounting/reports/cash-advance'] = 'ho_accounting/Pages_controller/view_cash_advances';
$route['head-office-accounting/reports/charging'] = 'ho_accounting/Pages_controller/view_charging';
$route['head-office-accounting/charging/business-unit'] = 'ho_accounting/Pages_controller/view_bu_charging';
$route['head-office-accounting/charging/member/(:any)'] = 'ho_accounting/Pages_controller/view_charging_details';
$route['head-office-accounting/loa-request-list/loa-billed'] = 'ho_accounting/Pages_controller/view_billed_loa';
$route['head-office-accounting/loa-request-list/loa-paid'] = 'ho_accounting/Pages_controller/view_paid_loa';
$route['head-office-accounting/noa-request-list/noa-billed'] = 'ho_accounting/Pages_controller/view_billed_noa';
$route['head-office-accounting/noa-request-list/noa-paid'] = 'ho_accounting/Pages_controller/view_paid_noa';

// LOA datatables
$route['head-office-accounting/loa-request-list/loa-approved/fetch'] = 'ho_accounting/Loa_ho_controller/get_approved_loa';
$route['head-office-accounting/loa-request-list/loa-completed/fetch'] = 'ho_accounting/Loa_ho_controller/get_completed_loa';
$route['head-office-accounting/loa-request-list/loa-approved/view/(:any)'] = 'ho_accounting/Loa_ho_controller/get_approved_loa_info';
$route['head-office-accounting/loa-request-list/loa-completed/view/(:any)'] = 'ho_accounting/Loa_ho_controller/get_completed_loa_info';
$route['head-office-accounting/loa-request-list/loa-billed/fetch'] = 'ho_accounting/Loa_ho_controller/get_billed_loa';
$route['head-office-accounting/loa-request-list/loa-paid/fetch'] = 'ho_accounting/Loa_ho_controller/get_paid_loa';
// NOA datatables
$route['head-office-accounting/noa-request-list/noa-approved/fetch'] = 'ho_accounting/Noa_ho_controller/get_approved_noa';
$route['head-office-accounting/noa-request-list/noa-paid/fetch'] = 'ho_accounting/Noa_ho_controller/get_paid_noa';
$route['head-office-accounting/noa-request-list/noa-approved/view/(:any)'] = 'ho_accounting/Noa_ho_controller/get_approved_noa_info';
$route['head-office-accounting/noa-request-list/noa-completed/view/(:any)'] = 'ho_accounting/Noa_ho_controller/get_completed_noa_info';
$route['head-office-accounting/noa-request-list/noa-billed/fetch'] = 'ho_accounting/Noa_ho_controller/get_billed_noa';


$route['head-office-accounting/list/hospital/(:any)/(:any)'] = 'ho_accounting/TableList/listByHopital';
$route['head-office-accounting/list/summary/(:any)'] = 'ho_accounting/TableList/listInfoSummary';

$route['head-office-accounting/list'] = 'ho_accounting/TableList';
$route['head-office-accounting/search-table-list'] = 'ho_accounting/TableList/searchTableList';

// HO - Accounting User Account Routes
$route['head-office-accounting/account-settings'] = 'ho_accounting/account_controller/account_settings';
$route['head-office-accounting/account-settings/password/update'] = 'ho_accounting/account_controller/update_account_password';
$route['head-office-accounting/account-settings/username/update'] = 'ho_accounting/account_controller/update_account_username';



$route['head-office-accounting/noa-request-list'] = 'ho_accounting/Noa_ho_controller/get_all_noa';

// End of Head Office Accounting Routes
// ===================================================================================================


// Start of Head Office Auditing Routes
// ===================================================================================================

$route['head-office-iad/dashboard'] = 'ho_iad/main_controller';

// End of Head Office Accounting Routes
// ===================================================================================================

//======================================================================================================
// HealthCare Coordinator Navigation Links Routes
//======================================================================================================
$route['healthcare-coordinator/dashboard'] = 'healthcare_coordinator/pages_controller';
$route['healthcare-coordinator/healthcare-providers'] = 'healthcare_coordinator/pages_controller/view_healthcare_providers';
$route['healthcare-coordinator/members'] = 'healthcare_coordinator/pages_controller/view_all_pending_members';
$route['healthcare-coordinator/members/approved'] = 'healthcare_coordinator/pages_controller/view_all_approved_members';
$route['healthcare-coordinator/members/approved/uploaded-scanned-id-form'] = 'healthcare_coordinator/pages_controller/healthcard_monitoring';
$route['healthcare-coordinator/accounts'] = 'healthcare_coordinator/pages_controller/view_all_accounts';
$route['healthcare-coordinator/accounts/register'] = 'healthcare_coordinator/pages_controller/register_account_form';
$route['healthcare-coordinator/setup/healthcare-providers'] = 'healthcare_coordinator/pages_controller/view_all_healthcare_providers';
$route['healthcare-coordinator/setup/company-doctors'] = 'healthcare_coordinator/pages_controller/view_all_company_doctors';
$route['healthcare-coordinator/setup/cost-types'] = 'healthcare_coordinator/pages_controller/view_all_cost_types';
$route['healthcare-coordinator/setup/room-types'] = 'healthcare_coordinator/pages_controller/view_all_room_types';
$route['healthcare-coordinator/loa/request-loa'] = 'healthcare_coordinator/pages_controller/view_request_loa_form';




$route['healthcare-coordinator/loa/requests-list/expired'] = 'healthcare_coordinator/pages_controller/view_expired_loa_list';
$route['healthcare-coordinator/loa/requests-list/cancelled'] = 'healthcare_coordinator/pages_controller/view_cancelled_loa_list';

$route['healthcare-coordinator/loa/cancellation-requests'] = 'healthcare_coordinator/pages_controller/view_loa_cancellation_list';
$route['healthcare-coordinator/loa/cancellation-requests/approved'] = 'healthcare_coordinator/pages_controller/view_loa_approved_cancellation';
$route['healthcare-coordinator/loa/cancellation-requests/disapproved'] = 'healthcare_coordinator/pages_controller/view_loa_disapproved_cancellation';
$route['healthcare-coordinator/loa/requests-list/rescheduled'] = 'healthcare_coordinator/pages_controller/view_all_rescheduled_loa';
$route['healthcare-coordinator/bill/requests-list/billed'] = 'healthcare_coordinator/pages_controller/view_all_billed_loa';
$route['healthcare-coordinator/bill/requests-list/for-charging'] = 'healthcare_coordinator/pages_controller/view_for_charging_lo';
$route['healthcare-coordinator/history'] = 'healthcare_coordinator/pages_controller/history';
$route['healthcare-coordinator/loa/reason_adjustment'] = 'healthcare_coordinator/loa_controller/reason_adjustment';

//Coordinator Ledger
$route['healthcare-coordinator/loa_controller/view_ledger'] = 'healthcare_coordinator/pages_controller/view_ledger';
$route['healthcare-coordinator/loa_controller/fetch_datatable'] = 'healthcare_coordinator/loa_controller/fetch_datatable';
$route['healthcare-coordinator/loa_controller/fetch_ledger/(:any)'] = 'healthcare_coordinator/pages_controller/fetch_ledger';
$route['healthcare-coordinator/loa_controller/fetch_ledger_data'] = 'healthcare_coordinator/loa_controller/fetch_ledger_data';
$route['healthcare-coordinator/loa_controller/view_record'] = 'healthcare_coordinator/loa_controller/view_record';
//end

$route['healthcare-coordinator/noa/requests-list'] = 'healthcare_coordinator/pages_controller/view_pending_noa_list';
$route['healthcare-coordinator/noa/requests-list/approved'] = 'healthcare_coordinator/pages_controller/view_approved_noa_list';
$route['healthcare-coordinator/noa/requests-list/disapproved'] = 'healthcare_coordinator/pages_controller/view_disapproved_noa_list';
$route['healthcare-coordinator/noa/requests-list/expired'] = 'healthcare_coordinator/pages_controller/view_expired_noa_list';
$route['healthcare-coordinator/noa/requests-list/completed'] = 'healthcare_coordinator/pages_controller/view_completed_noa_list';
$route['healthcare-coordinator/noa/request-noa'] = 'healthcare_coordinator/pages_controller/request_noa_form';
$route['healthcare-coordinator/bill/noa-requests/billed'] = 'healthcare_coordinator/pages_controller/view_final_billing';

$route['healthcare-coordinator/bill/noa-requests/billed/initial'] = 'healthcare_coordinator/pages_controller/view_initial_billing';
$route['healthcare-coordinator/noa/billed/final_billing'] = 'healthcare_coordinator/noa_controller/final_billing';
$route['healthcare-coordinator/bill/noa-requests/for_payment'] = 'healthcare_coordinator/pages_controller/billing_statement';

$route['healthcare-coordinator/setup/reset-mbl/(:any)'] = 'healthcare_coordinator/pages_controller/view_reset_mbl';


// HealthCare Coordinator User Account Routes
$route['healthcare-coordinator/account-settings'] = 'healthcare_coordinator/account_controller/account_settings';
$route['healthcare-coordinator/account-settings/password/update'] = 'healthcare_coordinator/account_controller/update_account_password';
$route['healthcare-coordinator/account-settings/username/update'] = 'healthcare_coordinator/account_controller/update_account_username';

// HealthCare Coordinator Applicants Routes - for Membership Application
$route['healthcare-coordinator/members/view/applicant/(:any)'] = 'healthcare_coordinator/applicants_controller/view_applicant_info';
$route['healthcare-coordinator/members/user-account/create'] = 'healthcare_coordinator/applicants_controller/create_member_user_account';

// HealthCare Coordinator Members Routes
$route['healthcare-coordinator/members/pending/fetch'] = 'healthcare_coordinator/members_controller/fetch_all_pending_members';
$route['healthcare-coordinator/members/approved/fetch'] = 'healthcare_coordinator/members_controller/fetch_all_approved_members';
$route['healthcare-coordinator/members/view/(:any)'] = 'healthcare_coordinator/members_controller/view_member_info';
// $route['healthcare-coordinator/members/user-account/create'] = 'healthcare_coordinator/members_controller/create_member_user_account';
$route['healthcare-coordinator/member/search'] = 'healthcare_coordinator/search_controller/search_autocomplete';
$route['healthcare-coordinator/members/approved/insert-hc-id'] = 'healthcare_coordinator/members_controller/insert_scanned_hc_id';
$route['healthcare-coordinator/members/approved/uploaded-scanned-id'] = 'healthcare_coordinator/members_controller/fetch_uploaded_hc_id';
$route['healthcare-coordinator/members/helthcard/view-id/(:any)'] = 'healthcare_coordinator/members_controller/get_hc_id';
// HealthCare Coordinator Accounts Routes
$route['healthcare-coordinator/accounts/register/submit'] = 'healthcare_coordinator/accounts_controller/register_user_account_validation';
$route['healthcare-coordinator/accounts/fetch'] = 'healthcare_coordinator/accounts_controller/fetch_all_accounts';
$route['healthcare-coordinator/accounts/member/search'] = 'healthcare_coordinator/search_controller/search_autocomplete';
$route['healthcare-coordinator/accounts/member/search/(:any)'] = 'healthcare_coordinator/search_controller/get_searched_member_details';
$route['healthcare-coordinator/accounts/change-password'] = 'healthcare_coordinator/accounts_controller/change_user_password';
$route['healthcare-coordinator/accounts/change-status/(:any)'] = 'healthcare_coordinator/accounts_controller/change_user_account_status';
$route['healthcare-coordinator/accounts/search-member'] = 'healthcare_coordinator/search_controller/search_member';
$route['healthcare-coordinator/accounts/edit/(:any)'] = 'healthcare_coordinator/accounts_controller/edit_user_account_details';
$route['healthcare-coordinator/accounts/update'] = 'healthcare_coordinator/accounts_controller/update_user_account_validation';
$route['healthcare-coordinator/accounts/view/(:any)'] = 'healthcare_coordinator/accounts_controller/get_user_account_details';
$route['healthcare-coordinator/accounts/reset-password/(:any)'] = 'healthcare_coordinator/accounts_controller/reset_user_password';
$route['healthcare-coordinator/accounts/delete/(:any)'] = 'healthcare_coordinator/accounts_controller/delete_user_account';

// HealthCare Coordinator LOA Routes
$route['healthcare-coordinator/loa/request-loa/submit'] = 'healthcare_coordinator/loa_controller/submit_loa_request';
$route['healthcare-coordinator/loa/requested-loa/edit/(:any)'] = 'healthcare_coordinator/loa_controller/edit_loa_request';
$route['healthcare-coordinator/loa/requested-loa/update/(:any)'] = 'healthcare_coordinator/loa_controller/update_loa_request';
$route['healthcare-coordinator/loa/requests-list/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_pending_loa';
$route['healthcare-coordinator/loa/requests-list/approved/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_approved_loa';
$route['healthcare-coordinator/loa/requests-list/disapproved/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_disapproved_loa';
$route['healthcare-coordinator/loa/requests-list/completed/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_completed_loa';
$route['healthcare-coordinator/loa/requests-list/expired/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_expired_loa';
$route['healthcare-coordinator/loa/requests-list/expired/backdate'] = 'healthcare_coordinator/loa_controller/backdate_expired_loa';


$route['healthcare-coordinator/loa/requests-list/cancelled/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_cancelled_loa';
$route['healthcare-coordinator/loa/requests-list/resched/fetch'] = 'healthcare_coordinator/loa_controller/fetch_all_rescheduled_loa';
$route['healthcare-coordinator/loa/pending/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_pending_loa_info';
$route['healthcare-coordinator/loa/approved/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_approved_loa_info';
$route['healthcare-coordinator/loa/disapproved/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_disapproved_loa_info';
$route['healthcare-coordinator/loa/cancelled/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_cancelled_loa_info';
$route['healthcare-coordinator/loa/completed/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_completed_loa_info';
$route['healthcare-coordinator/loa/resched/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_resched_loa_info';
$route['healthcare-coordinator/loa/requests-list/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_loa_details';
$route['healthcare-coordinator/loa/expired/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_expired_loa_info';
$route['healthcare-coordinator/loa/requests-list/approve/(:any)'] = 'healthcare_coordinator/loa_controller/approve_loa_request';
$route['healthcare-coordinator/loa/requests-list/disapprove/(:any)'] = 'healthcare_coordinator/loa_controller/disapprove_loa_request';
$route['healthcare-coordinator/loa/requests-list/set-charge-type'] = 'healthcare_coordinator/loa_controller/set_charge_type';
$route['healthcare-coordinator/loa/member/search/(:any)'] = 'healthcare_coordinator/search_controller/get_searched_member_details';
$route['healthcare-coordinator/loa/requested-loa/cancel/(:any)'] = 'healthcare_coordinator/loa_controller/cancel_loa_request';
$route['healthcare-coordinator/loa/requested-loa/generate-printable-loa/(:any)'] = 'healthcare_coordinator/loa_controller/generate_printable_loa';
$route['healthcare-coordinator/loa/scheduled-loa/generate-printable-loa/(:any)'] = 'healthcare_coordinator/loa_controller/generate_rescheduled_loa';
$route['healthcare-coordinator/loa/cancellation-requests/fetch'] = 'healthcare_coordinator/loa_controller/fetch_cancellation_requests';
$route['healthcare-coordinator/loa/cancellation-requests/confirm/(:any)'] = 'healthcare_coordinator/loa_controller/set_cancellation_approved';
$route['healthcare-coordinator/loa/approved-cancellation/fetch'] = 'healthcare_coordinator/loa_controller/fetch_approved_cancellations';
$route['healthcare-coordinator/loa/requested-loa/update-loa/(:any)'] = 'healthcare_coordinator/loa_controller/view_tag_loa_completed';
$route['healthcare-coordinator/loa/cancellation-requests/disapprove/(:any)'] = 'healthcare_coordinator/loa_controller/set_cancellation_disapproved';
$route['healthcare-coordinator/loa/disapproved-cancellation/fetch'] = 'healthcare_coordinator/loa_controller/fetch_disapproved_cancellations';
$route['healthcare-coordinator/loa-requests/approved/performed-loa-info/submit'] = 'healthcare_coordinator/loa_controller/submit_performed_diagnostic';
$route['healthcare-coordinator/loa-requests/approved/performed-loa-info/edit'] = 'healthcare_coordinator/loa_controller/submit_edited_loa_info';
$route['healthcare-coordinator/loa/performed-loa-info/view/(:any)'] = 'healthcare_coordinator/loa_controller/fetch_performed_loa_info';
$route['healthcare-coordinator/loa-requests/approved/performed-loa-consultation/submit'] = 'healthcare_coordinator/loa_controller/submit_performed_consultation';
$route['healthcare-coordinator/loa-requests/approved/performed-loa-consultation/submit-edited'] = 'healthcare_coordinator/loa_controller/submit_edited_consultation_loa';
$route['healthcare-coordinator/loa/performed-consult-loa-info/view/(:any)'] = 'healthcare_coordinator/loa_controller/fetch_performed_consult_loa';
$route['healthcare-coordinator/loa/requested-loa/add-loa-fees/(:any)'] = 'healthcare_coordinator/loa_controller/add_performed_loa_fees';
$route['healthcare-coordinator/loa/requested-loa/add-consult-fees/(:any)'] = 'healthcare_coordinator/loa_controller/add_performed_consult_fees';
$route['healthcare-coordinator/loa/requests-list/cancel-request/(:any)'] = 'healthcare_coordinator/loa_controller/cancel_approved_loa';
$route['healthcare-coordinator/loa/requested-loa/create_new_loa/(:any)/(:any)'] = 'healthcare_coordinator/loa_controller/create_rescheduled_to_new_loa';
$route['healthcare-coordinator/loa/requested-loa/submit'] = 'healthcare_coordinator/loa_controller/submit_rescheduled_loa_services';
$route['healthcare-coordinator/loa/rescheduled-loa/update-loa/(:any)'] = 'healthcare_coordinator/loa_controller/tag_resched_to_complete';
$route['healthcare-coordinator/loa/performed-loa-info/submit'] = 'healthcare_coordinator/loa_controller/submit_consultation_fees';
$route['healthcare-coordinator/loa/performed-loa-info/submit1'] = 'healthcare_coordinator/loa_controller/submit_diagnostic_fees';
$route['healthcare-coordinator/loa/requested-loa/match_with_billing/(:any)'] = 'healthcare_coordinator/loa_controller/match_loa_with_billing';
$route['healthcare-coordinator/loa/requests-list/billed/fetch'] = 'healthcare_coordinator/loa_controller/fetch_billed_loa';

$route['healthcare-coordinator/loa/requests-list/for-charging/fetch'] = 'healthcare_coordinator/loa_controller/fetch_for_payment_loa';
$route['healthcare-coordinator/loa/history'] = 'healthcare_coordinator/loa_controller/history';
$route['healthcare-coordinator/get-services/(:any)'] = 'healthcare_coordinator/loa_controller/get_hp_services';

$route['healthcare-coordinator/loa/total-bill/fetch'] = 'healthcare_coordinator/loa_controller/fetch_total_net_bill';
$route['healthcare-coordinator/loa/charging/fetch/(:any)'] = 'healthcare_coordinator/loa_controller/get_loa_charging';
$route['healthcare-coordinator/loa/charging/confirm'] = 'healthcare_coordinator/loa_controller/confirm_loa_charging';
$route['healthcare-coordinator/loa/billing/fetch/(:any)'] = 'healthcare_coordinator/loa_controller/fetch_coordinator_billing';
$route['healthcare-coordinator/bill/billed/fetch-payable/(:any)'] = 'healthcare_coordinator/loa_controller/fetch_monthly_payable';
$route['healthcare-coordinator/loa/monthly-bill/fetch/(:any)'] = 'healthcare_coordinator/loa_controller/fetch_monthly_bill';
$route['healthcare-coordinator/loa/matched/total-bill/fetch'] = 'healthcare_coordinator/loa_controller/get_matched_total_bill';
$route['healthcare-coordinator/bill/billed/charging/(:any)'] = 'healthcare_coordinator/loa_controller/get_bill_for_charging';
$route['healthcare-coordinator/loa/monthly-bill/charging/(:any)'] = 'healthcare_coordinator/loa_controller/fetch_billing_for_charging';

//HealthCare Coordinator NOA Routes
$route['healthcare-coordinator/noa/requests-list/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_pending_noa';
$route['healthcare-coordinator/noa/requests-list/approved/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_approved_noa';
$route['healthcare-coordinator/noa/requests-list/disapproved/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_disapproved_noa';
$route['healthcare-coordinator/noa/requests-list/expired/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_expired_noa';
$route['healthcare-coordinator/noa/requests-list/expired/backdate'] = 'healthcare_coordinator/noa_controller/backdate_expired_noa';
$route['healthcare-coordinator/noa/requests-list/completed/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_completed_noa';
$route['healthcare-coordinator/noa/pending/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_pending_noa_info';
$route['healthcare-coordinator/noa/approved/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_approved_noa_info';
$route['healthcare-coordinator/noa/disapproved/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_disapproved_noa_info';
$route['healthcare-coordinator/noa/expired/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_expired_noa_info';
$route['healthcare-coordinator/noa/completed/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_completed_noa_info';
$route['healthcare-coordinator/noa/request-noa/submit'] = 'healthcare_coordinator/noa_controller/submit_noa_request';
$route['healthcare-coordinator/noa/requested-loa/edit/(:any)'] = 'healthcare_coordinator/noa_controller/edit_noa_request';
$route['healthcare-coordinator/noa/requested-noa/update/(:any)'] = 'healthcare_coordinator/noa_controller/update_noa_request';
$route['healthcare-coordinator/noa/requested-noa/cancel/(:any)'] = 'healthcare_coordinator/noa_controller/cancel_noa_request';
$route['healthcare-coordinator/noa/requested-noa/generate-printable-noa/(:any)'] = 'healthcare_coordinator/noa_controller/generate_printable_noa';
$route['healthcare-coordinator/noa/requests-list/set-charge-type'] = 'healthcare_coordinator/noa_controller/set_charge_type';
$route['healthcare-coordinator/noa/billed/initial_billing'] = 'healthcare_coordinator/noa_controller/initial_billing';
$route['healthcare-coordinator/noa/billed/initial_billing2/(:any)'] = 'healthcare_coordinator/noa_controller/initial_billing2';
$route['healthcare-coordinator/noa/total-bill/fetch'] = 'healthcare_coordinator/noa_controller/get_total_hp_bill';
$route['healthcare-coordinator/noa/matched-bill/submit'] = 'healthcare_coordinator/noa_controller/submit_final_billing';
$route['healthcare-coordinator/noa/requests-list/payable/fetch'] = 'healthcare_coordinator/noa_controller/fetch_payable_noa';
$route['healthcare-coordinator/bill/billed-noa/fetch-payable/(:any)'] = 'healthcare_coordinator/noa_controller/fetch_monthly_payable';
$route['healthcare-coordinator/noa/monthly-bill/fetch/(:any)'] = 'healthcare_coordinator/noa_controller/fetch_monthly_bill';
$route['healthcare-coordinator/noa/matched/total-bill/fetch'] = 'healthcare_coordinator/noa_controller/fetch_total_hp_bill';
$route['healthcare-coordinator/bill/billed-noa/charging/(:any)'] = 'healthcare_coordinator/noa_controller/fetch_monthly_charging';
$route['healthcare-coordinator/noa/monthly-bill/charging/(:any)'] = 'healthcare_coordinator/noa_controller/fetch_billing_for_charging';


// HealthCare Coordinator Setup Routes
// * setup for healthcare providers
$route['healthcare-coordinator/setup/healthcare-providers/register/submit'] = 'healthcare_coordinator/setup_controller/register_healthcare_provider';
$route['healthcare-coordinator/setup/healthcare-providers/fetch'] = 'healthcare_coordinator/setup_controller/fetch_all_healthcare_providers';
$route['healthcare-coordinator/setup/healthcare-providers/view/(:any)'] = 'healthcare_coordinator/setup_controller/get_healthcare_provider_info';
$route['healthcare-coordinator/setup/healthcare-providers/edit/(:any)'] = 'healthcare_coordinator/setup_controller/get_healthcare_provider_info';
$route['healthcare-coordinator/setup/healthcare-providers/update'] = 'healthcare_coordinator/setup_controller/update_healthcare_provider';
$route['healthcare-coordinator/setup/healthcare-providers/delete/(:any)'] = 'healthcare_coordinator/setup_controller/delete_healthcare_provider';
// * setup for company doctors
$route['healthcare-coordinator/setup/company-doctors/fetch'] = 'healthcare_coordinator/setup_controller/fetch_all_company_doctors';
$route['healthcare-coordinator/setup/company-doctors/register/submit'] = 'healthcare_coordinator/setup_controller/register_company_doctor';
$route['healthcare-coordinator/setup/company-doctors/delete/(:any)'] = 'healthcare_coordinator/setup_controller/delete_company_doctor';
$route['healthcare-coordinator/setup/company-doctors/edit/(:any)'] = 'healthcare_coordinator/setup_controller/get_doctor_info';
$route['healthcare-coordinator/setup/company-doctors/update'] = 'healthcare_coordinator/setup_controller/update_company_doctor';
// * setup for cost types
$route['healthcare-coordinator/setup/cost-types/fetch'] = 'healthcare_coordinator/setup_controller/fetch_all_cost_types';
$route['healthcare-coordinator/setup/cost-types/register/submit'] = 'healthcare_coordinator/setup_controller/register_cost_type';
$route['healthcare-coordinator/setup/cost-types/delete/(:any)'] = 'healthcare_coordinator/setup_controller/delete_cost_type';
$route['healthcare-coordinator/setup/cost-types/edit/(:any)'] = 'healthcare_coordinator/setup_controller/get_cost_type_info';
$route['healthcare-coordinator/setup/cost-types/update'] = 'healthcare_coordinator/setup_controller/update_cost_type';
// * setup for room types
$route['healthcare-coordinator/setup/room-types/fetch'] = 'healthcare_coordinator/setup_controller/fetch_room_types';
$route['healthcare-coordinator/setup/room-types/register/submit'] = 'healthcare_coordinator/setup_controller/register_room_type';
$route['healthcare-coordinator/setup/room-types/edit/(:any)'] = 'healthcare_coordinator/setup_controller/get_room_type_info';
$route['healthcare-coordinator/setup/room-types/update'] = 'healthcare_coordinator/setup_controller/update_room_type';
$route['healthcare-coordinator/setup/room-types/delete/(:any)'] = 'healthcare_coordinator/setup_controller/delete_room_type';

// managers key
$route['healthcare-coordinator/managers-key/check'] = 'healthcare_coordinator/account_controller/check_manager_username';
$route['healthcare-coordinator/reschedule/managers-key/check'] = 'healthcare_coordinator/account_controller/check_manager_key';
$route['healthcare-coordinator/reset-mbl/managers-key/check'] = 'healthcare_coordinator/account_controller/check_mbl_mgr_key';
// End of HealthCare Coordinator Routes
//========================================================================================================


//======================================================================================================
// Company Doctor Navigation Links Routes
$route['company-doctor/dashboard'] = 'company_doctor/pages_controller';
$route['company-doctor/healthcare-providers'] = 'company_doctor/pages_controller/view_healthcare_providers';
$route['company-doctor/view_all_patient'] = 'company_doctor/pages_controller/view_all_patient';
$route['company-doctor/accounts'] = 'company_doctor/pages_controller/view_all_accounts';
$route['company-doctor/accounts/register'] = 'company_doctor/pages_controller/register_account_form';
$route['company-doctor/setup/affiliate-hospitals'] = 'company_doctor/pages_controller/view_all_affiliate_hospitals';
$route['company-doctor/loa/requests-list'] = 'company_doctor/pages_controller/view_pending_loa_list';
$route['company-doctor/loa/requests-list/approved'] = 'company_doctor/pages_controller/view_approved_loa_list';
$route['company-doctor/loa/requests-list/expired/backdate_expired'] = 'company_doctor/loa_controller/backdate_expired';
$route['company-doctor/loa/requests-list/disapproved'] = 'company_doctor/pages_controller/view_disapproved_loa_list';
$route['company-doctor/loa/requests-list/completed'] = 'company_doctor/pages_controller/view_completed_loa_list';
$route['company-doctor/loa/requests-list/referral'] = 'company_doctor/pages_controller/view_referral_loa_list';
$route['company-doctor/loa/requests-list/expired'] = 'company_doctor/pages_controller/view_expired_loa_list';
$route['company-doctor/noa/requests-list'] = 'company_doctor/pages_controller/view_pending_noa_list';
$route['company-doctor/noa/requests-list/approved'] = 'company_doctor/pages_controller/view_approved_noa_list';
$route['company-doctor/noa/requests-list/disapproved'] = 'company_doctor/pages_controller/view_disapproved_noa_list';
$route['company-doctor/noa/requests-list/completed'] = 'company_doctor/pages_controller/view_completed_noa_list';
$route['company-doctor/loa/requests-list/cancelled'] = 'company_doctor/pages_controller/view_cancelled_loa_list';
$route['company-doctor/member/view/files/(:any)'] = 'company_doctor/pages_controller/view_employee_files';
$route['company-doctor/members/final-diagnosis/(:any)/(:any)'] = 'company_doctor/pages_controller/view_member_files';
$route['company-doctor/members/medical-abstract/(:any)/(:any)'] = 'company_doctor/pages_controller/view_medical_abstract';
$route['company-doctor/members/take-home-meds/(:any)/(:any)'] = 'company_doctor/pages_controller/view_take_home_meds';
$route['company-doctor/members/soa/(:any)/(:any)'] = 'company_doctor/pages_controller/view_billed_soa';
$route['company-doctor/loa/requests-list/billed'] = 'company_doctor/pages_controller/view_billed_loa_list';
$route['company-doctor/noa/requests-list/billed'] = 'company_doctor/pages_controller/view_billed_noa_list';
$route['company-doctor/noa/requests-list/paid'] = 'company_doctor/pages_controller/view_paid_noa_list';
$route['company-doctor/loa/requests-list/paid'] = 'company_doctor/pages_controller/view_paid_loa_list';

// Company Doctor User Account Routes
$route['company-doctor/account-settings'] = 'company_doctor/account_controller/account_settings';
$route['company-doctor/account-settings/password/update'] = 'company_doctor/account_controller/update_account_password';
$route['company-doctor/account-settings/username/update'] = 'company_doctor/account_controller/update_account_username';


// Company Doctor Members Routes
$route['company-doctor/members'] = 'company_doctor/pages_controller/view_all_members';
$route['company-doctor/members/fetch'] = 'company_doctor/members_controller/fetch_all_members';
$route['company-doctor/member/view/(:any)'] = 'company_doctor/members_controller/view_member_info';



// Company Doctor LOA Routes
$route['company-doctor/loa/requests-list/fetch'] = 'company_doctor/loa_controller/fetch_all_pending_loa';
$route['company-doctor/loa/requests-list/approved/fetch'] = 'company_doctor/loa_controller/fetch_all_approved_loa';
$route['company-doctor/loa/requests-list/disapproved/fetch'] = 'company_doctor/loa_controller/fetch_all_disapproved_loa';
$route['company-doctor/loa/requests-list/completed/fetch'] = 'company_doctor/loa_controller/fetch_all_completed_loa';
$route['company-doctor/loa/requests-list/referral/fetch'] = 'company_doctor/loa_controller/fetch_all_referral_loa';
$route['company-doctor/loa/requests-list/expired/fetch'] = 'company_doctor/loa_controller/fetch_all_expired_loa';
$route['company-doctor/loa/requests-list/view/(:any)'] = 'company_doctor/loa_controller/get_loa_info';
// $route['company-doctor/loa/requests-list/approve/(:any)'] = 'company_doctor/loa_controller/approve_loa_request';
$route['company-doctor/loa/requests-list/approve-request'] = 'company_doctor/loa_controller/approve_loa_request';
$route['company-doctor/loa/requests-list/disapprove/(:any)'] = 'company_doctor/loa_controller/disapprove_loa_request';
$route['company-doctor/loa/requested-loa/generate-printable-loa/(:any)'] = 'company_doctor/loa_controller/generate_printable_loa';
$route['company-doctor/loa/requests-list/cancelled/fetch'] = 'company_doctor/loa_controller/fetch_all_cancelled_loa';
$route['company-doctor/loa/requests-list/billed/fetch'] = 'company_doctor/loa_controller/fetch_all_billed_loa';
$route['company-doctor/loa/requests-list/paid/fetch'] = 'company_doctor/loa_controller/fetch_all_paid_loa';

// Company Doctor NOA Routes
$route['company-doctor/noa/requests-list/fetch'] = 'company_doctor/noa_controller/fetch_all_pending_noa';
$route['company-doctor/noa/requests-list/approved/fetch'] = 'company_doctor/noa_controller/fetch_all_approved_noa';
$route['company-doctor/noa/requests-list/disapproved/fetch'] = 'company_doctor/noa_controller/fetch_all_disapproved_noa';
$route['company-doctor/noa/requests-list/completed/fetch'] = 'company_doctor/noa_controller/fetch_all_completed_noa';
$route['company-doctor/noa/requests-list/view/(:any)'] = 'company_doctor/noa_controller/get_noa_info';
// $route['company-doctor/noa/requests-list/approve/(:any)'] = 'company_doctor/noa_controller/approve_noa_request';
$route['company-doctor/noa/requests-list/approve-request'] = 'company_doctor/noa_controller/approve_noa_request';
$route['company-doctor/noa/requests-list/disapprove/(:any)'] = 'company_doctor/noa_controller/disapprove_noa_request';
$route['company-doctor/noa/requested-noa/generate-printable-noa/(:any)'] = 'company_doctor/noa_controller/generate_printable_noa';
$route['company-doctor/noa/requests-list/billed/fetch'] = 'company_doctor/noa_controller/fetch_billed_noa';
$route['company-doctor/noa/requests-list/paid/fetch'] = 'company_doctor/noa_controller/fetch_paid_noa';


// End of Company Doctor Routes
//========================================================================================================


//======================================================================================================
// Super Admin Navigation Links Routes
$route['super-admin/dashboard'] = 'super_admin/pages_controller';
$route['super-admin/healthcare-providers'] = 'super_admin/pages_controller/view_healthcare_providers';
$route['super-admin/members'] = 'super_admin/pages_controller/view_all_pending_members';
$route['super-admin/members/approved'] = 'super_admin/pages_controller/view_all_approved_members';
$route['super-admin/accounts'] = 'super_admin/pages_controller/view_all_accounts';
$route['super-admin/accounts/register'] = 'super_admin/pages_controller/register_account_form';
$route['super-admin/setup/healthcare-providers'] = 'super_admin/pages_controller/view_all_healthcare_providers';
$route['super-admin/setup/company-doctors'] = 'super_admin/pages_controller/view_all_company_doctors';
$route['super-admin/setup/cost-types'] = 'super_admin/pages_controller/view_all_cost_types';
$route['super-admin/setup/room-types'] = 'super_admin/pages_controller/view_all_room_types';
$route['super-admin/loa/request-loa'] = 'super_admin/pages_controller/view_request_loa_form';
$route['super-admin/loa/requests-list'] = 'super_admin/pages_controller/view_pending_loa_list';
$route['super-admin/loa/requests-list/approved'] = 'super_admin/pages_controller/view_approved_loa_list';
$route['super-admin/loa/requests-list/disapproved'] = 'super_admin/pages_controller/view_disapproved_loa_list';
$route['super-admin/loa/requests-list/completed'] = 'super_admin/pages_controller/view_completed_loa_list';
$route['super-admin/loa/requests-list/cancelled'] = 'super_admin/pages_controller/view_cancelled_loa_list';
$route['super-admin/loa/requests-list/expired'] = 'super_admin/pages_controller/view_expired_loa_list';
$route['super-admin/noa/requests-list'] = 'super_admin/pages_controller/view_pending_noa_list';
$route['super-admin/noa/requests-list/approved'] = 'super_admin/pages_controller/view_approved_noa_list';
$route['super-admin/noa/requests-list/disapproved'] = 'super_admin/pages_controller/view_disapproved_noa_list';
$route['super-admin/noa/requests-list/completed'] = 'super_admin/pages_controller/view_completed_noa_list';
$route['super-admin/noa/request-noa'] = 'super_admin/pages_controller/request_noa_form';

// Super Admin User Account Routes
$route['super-admin/account-settings'] = 'super_admin/account_controller/account_settings';
$route['super-admin/account-settings/password/update'] = 'super_admin/account_controller/update_account_password';
$route['super-admin/account-settings/username/update'] = 'super_admin/account_controller/update_account_username';

// Super Admin Applicants Routes - for Membership Application
$route['super-admin/members/view/applicant/(:any)'] = 'super_admin/applicants_controller/view_applicant_info';
$route['super-admin/members/user-account/create'] = 'super_admin/applicants_controller/create_member_user_account';
$route['super-admin/members/pending/update/profile-picture'] = 'super_admin/applicants_controller/update_profile_pic';

// Super Admin Members Routes
$route['super-admin/members/pending/fetch'] = 'super_admin/members_controller/fetch_all_pending_members';
$route['super-admin/members/approved/fetch'] = 'super_admin/members_controller/fetch_all_approved_members';
$route['super-admin/members/view/(:any)'] = 'super_admin/members_controller/view_member_info';
$route['super-admin/members/update/profile-picture'] = 'super_admin/members_controller/update_profile_pic';
// $route['super-admin/members/user-account/create'] = 'super_admin/members_controller/create_member_user_account';
$route['super-admin/member/search'] = 'super_admin/search_controller/search_autocomplete';

// Super Admin Accounts Routes
$route['super-admin/accounts/register/submit'] = 'super_admin/accounts_controller/register_user_account_validation';
$route['super-admin/accounts/fetch'] = 'super_admin/accounts_controller/fetch_all_accounts';
$route['super-admin/accounts/member/search'] = 'super_admin/search_controller/search_autocomplete';
$route['super-admin/accounts/member/search/(:any)'] = 'super_admin/search_controller/get_searched_member_details';
$route['super-admin/accounts/change-password'] = 'super_admin/accounts_controller/change_user_password';
$route['super-admin/accounts/change-status/(:any)'] = 'super_admin/accounts_controller/change_user_account_status';
$route['super-admin/accounts/search-member'] = 'super_admin/search_controller/search_member';
$route['super-admin/accounts/edit/(:any)'] = 'super_admin/accounts_controller/edit_user_account_details';
$route['super-admin/accounts/update'] = 'super_admin/accounts_controller/update_user_account_validation';
$route['super-admin/accounts/view/(:any)'] = 'super_admin/accounts_controller/get_user_account_details';
$route['super-admin/accounts/reset-password/(:any)'] = 'super_admin/accounts_controller/reset_user_password';
$route['super-admin/accounts/delete/(:any)'] = 'super_admin/accounts_controller/delete_user_account';

// Super Admin LOA Routes
$route['super-admin/loa/request-loa/submit'] = 'super_admin/loa_controller/submit_loa_request';
$route['super-admin/loa/requested-loa/edit/(:any)'] = 'super_admin/loa_controller/edit_loa_request';
$route['super-admin/loa/requested-loa/update/(:any)'] = 'super_admin/loa_controller/update_loa_request';
$route['super-admin/loa/requests-list/fetch'] = 'super_admin/loa_controller/fetch_all_pending_loa';
$route['super-admin/loa/requests-list/approved/fetch'] = 'super_admin/loa_controller/fetch_all_approved_loa';
$route['super-admin/loa/requests-list/disapproved/fetch'] = 'super_admin/loa_controller/fetch_all_disapproved_loa';
$route['super-admin/loa/requests-list/completed/fetch'] = 'super_admin/loa_controller/fetch_all_completed_loa';
$route['super-admin/loa/requests-list/cancelled/fetch'] = 'super_admin/loa_controller/fetch_all_cancelled_loa';
$route['super-admin/loa/requests-list/expired/fetch'] = 'super_admin/loa_controller/fetch_all_expired_loa';
$route['super-admin/loa/pending/view/(:any)'] = 'super_admin/loa_controller/get_pending_loa_info';
$route['super-admin/loa/approved/view/(:any)'] = 'super_admin/loa_controller/get_approved_loa_info';
$route['super-admin/loa/disapproved/view/(:any)'] = 'super_admin/loa_controller/get_disapproved_loa_info';
$route['super-admin/loa/completed/view/(:any)'] = 'super_admin/loa_controller/get_completed_loa_info';
$route['super-admin/loa/cancelled/view/(:any)'] = 'super_admin/loa_controller/get_cancelled_loa_info';
$route['super-admin/loa/expired/view/(:any)'] = 'super_admin/loa_controller/get_expired_loa_info';
$route['super-admin/loa/requests-list/approve/(:any)'] = 'super_admin/loa_controller/approve_loa_request';
$route['super-admin/loa/requests-list/disapprove/(:any)'] = 'super_admin/loa_controller/disapprove_loa_request';
$route['super-admin/loa/member/search/(:any)'] = 'super_admin/search_controller/get_searched_member_details';
$route['super-admin/loa/requested-loa/cancel/(:any)'] = 'super_admin/loa_controller/cancel_loa_request';
$route['super-admin/loa/requested-loa/generate-printable-loa/(:any)'] = 'super_admin/loa_controller/generate_printable_loa';


// Super Admin NOA Routes
$route['super-admin/noa/requests-list/fetch'] = 'super_admin/noa_controller/fetch_all_pending_noa';
$route['super-admin/noa/requests-list/approved/fetch'] = 'super_admin/noa_controller/fetch_all_approved_noa';
$route['super-admin/noa/requests-list/disapproved/fetch'] = 'super_admin/noa_controller/fetch_all_disapproved_noa';
$route['super-admin/noa/pending/view/(:any)'] = 'super_admin/noa_controller/get_pending_noa_info';
$route['super-admin/noa/approved/view/(:any)'] = 'super_admin/noa_controller/get_approved_noa_info';
$route['super-admin/noa/disapproved/view/(:any)'] = 'super_admin/noa_controller/get_disapproved_noa_info';
$route['super-admin/noa/completed/view/(:any)'] = 'super_admin/noa_controller/get_completed_noa_info';
$route['super-admin/noa/requests-list/approve/(:any)'] = 'super_admin/noa_controller/approve_noa_request';
$route['super-admin/noa/requests-list/disapprove/(:any)'] = 'super_admin/noa_controller/disapprove_noa_request';
$route['super-admin/noa/request-noa/submit'] = 'super_admin/noa_controller/submit_noa_request';
$route['super-admin/noa/requested-loa/edit/(:any)'] = 'super_admin/noa_controller/edit_noa_request';
$route['super-admin/noa/requested-noa/update/(:any)'] = 'super_admin/noa_controller/update_noa_request';
$route['super-admin/noa/requested-noa/cancel/(:any)'] = 'super_admin/noa_controller/cancel_noa_request';
$route['super-admin/noa/requested-noa/generate-printable-noa/(:any)'] = 'super_admin/noa_controller/generate_printable_noa';


// Super Admin Setup Routes
// * setup for healthcare providers
$route['super-admin/setup/healthcare-providers/register/submit'] = 'super_admin/setup_controller/register_healthcare_provider';
$route['super-admin/setup/healthcare-providers/fetch'] = 'super_admin/setup_controller/fetch_all_healthcare_providers';
$route['super-admin/setup/healthcare-providers/view/(:any)'] = 'super_admin/setup_controller/get_healthcare_provider_info';
$route['super-admin/setup/healthcare-providers/edit/(:any)'] = 'super_admin/setup_controller/get_healthcare_provider_info';
$route['super-admin/setup/healthcare-providers/update'] = 'super_admin/setup_controller/update_healthcare_provider';
$route['super-admin/setup/healthcare-providers/delete/(:any)'] = 'super_admin/setup_controller/delete_healthcare_provider';
// * setup for company doctors
$route['super-admin/setup/company-doctors/fetch'] = 'super_admin/setup_controller/fetch_all_company_doctors';
$route['super-admin/setup/company-doctors/register/submit'] = 'super_admin/setup_controller/register_company_doctor';
$route['super-admin/setup/company-doctors/delete/(:any)'] = 'super_admin/setup_controller/delete_company_doctor';
$route['super-admin/setup/company-doctors/edit/(:any)'] = 'super_admin/setup_controller/get_doctor_info';
$route['super-admin/setup/company-doctors/update'] = 'super_admin/setup_controller/update_company_doctor';
// * setup for cost types
$route['super-admin/setup/cost-types/fetch'] = 'super_admin/setup_controller/fetch_all_cost_types';
$route['super-admin/setup/cost-types/register/submit'] = 'super_admin/setup_controller/register_cost_type';
$route['super-admin/setup/cost-types/delete/(:any)'] = 'super_admin/setup_controller/delete_cost_type';
$route['super-admin/setup/cost-types/edit/(:any)'] = 'super_admin/setup_controller/get_cost_type_info';
$route['super-admin/setup/cost-types/update'] = 'super_admin/setup_controller/update_cost_type';
// * setup for room types
$route['super-admin/setup/room-types/fetch'] = 'super_admin/setup_controller/fetch_room_types';
$route['super-admin/setup/room-types/register/submit'] = 'super_admin/setup_controller/register_room_type';
$route['super-admin/setup/room-types/edit/(:any)'] = 'super_admin/setup_controller/get_room_type_info';
$route['super-admin/setup/room-types/update'] = 'super_admin/setup_controller/update_room_type';
$route['super-admin/setup/room-types/delete/(:any)'] = 'super_admin/setup_controller/delete_room_type';

$route['super-admin/database-backup'] ='super_admin/backup_controller/database_backup';


// End of Super Admin Routes
//========================================================================================================



//=============================================================================================
//COORDINATOR
//=============================================================================================
	//Letter of Authorization
		//[pending]
		$route['healthcare-coordinator/loa/requests-list'] = 'healthcare_coordinator/pages_controller/view_pending_loa_list';
		$route['healthcare-coordinator/loa/requests-list/approved'] = 'healthcare_coordinator/pages_controller/view_approved_loa_list';
		$route['healthcare-coordinator/loa/requests-list/disapproved'] = 'healthcare_coordinator/pages_controller/view_disapproved_loa_list';
		$route['healthcare-coordinator/loa/requests-list/completed'] = 'healthcare_coordinator/pages_controller/view_completed_loa_list';
	//End

	//Final Billing
	$route['healthcare-coordinator/loa/billed/datatable_final_billing'] = 'healthcare_coordinator/loa_controller/datatable_final_billing';
	$route['healthcare-coordinator/loa/billed/submit_final_billing'] = 'healthcare_coordinator/loa_controller/submit_final_billing';
	$route['healthcare-coordinator/loa/billed/consultation_fees/(:any)'] = 'healthcare_coordinator/loa_controller/consultation_fees';
	$route['healthcare-coordinator/loa/billed/update_consultation_fees'] = 'healthcare_coordinator/loa_controller/update_consultation_fees';
	$route['healthcare-coordinator/loa/billed/consultation_fees1/(:any)'] = 'healthcare_coordinator/loa_controller/consultation_fees1';
	$route['healthcare-coordinator/loa/billed/submit_consultation'] = 'healthcare_coordinator/loa_controller/submit_consultation';
	$route['healthcare-coordinator/loa/billed/diagnostic_fees/(:any)'] = 'healthcare_coordinator/loa_controller/diagnostic_fees';
	$route['healthcare-coordinator/loa/billed/update_diagnostic_fees'] = 'healthcare_coordinator/loa_controller/update_diagnostic_fees';
	$route['healthcare-coordinator/loa/billed/diagnostic_fees1/(:any)'] = 'healthcare_coordinator/loa_controller/diagnostic_fees1';
	$route['healthcare-coordinator/loa/billed/submit_diagnostic'] = 'healthcare_coordinator/loa_controller/submit_diagnostic';
	//end

	//Healthcare Advance
	$route['healthcare-coordinator/healthcare_advance/view_healthcare_advance_pending'] = 'healthcare_coordinator/pages_controller/view_healthcare_advance_pending';
	$route['healthcare-coordinator/healthcare_advance/healthcare_advance_datatable_pending'] = 'healthcare_coordinator/loa_controller/healthcare_advance_datatable_pending';
	$route['healthcare-coordinator/healthcare_advance/healthcare_advance_modal_pending/(:any)'] = 'healthcare_coordinator/loa_controller/healthcare_advance_modal_pending';
	$route['healthcare-coordinator/healthcare_advance/view_healthcare_advance_approved'] = 'healthcare_coordinator/pages_controller/view_healthcare_advance_approved';
	$route['healthcare-coordinator/healthcare_advance/healthcare_advance_datatable_approved'] = 'healthcare_coordinator/loa_controller/healthcare_advance_datatable_approved';
	$route['healthcare-coordinator/healthcare_advance/healthcare_advance_modal_approved/(:any)'] = 'healthcare_coordinator/loa_controller/healthcare_advance_modal_approved';
	$route['healthcare-coordinator/healthcare_advance/view_healthcare_advance_disapproved'] = 'healthcare_coordinator/pages_controller/view_healthcare_advance_disapproved';
	$route['healthcare-coordinator/healthcare_advance/healthcare_advance_datatable_disapproved'] = 'healthcare_coordinator/loa_controller/healthcare_advance_datatable_disapproved';
	$route['healthcare-coordinator/healthcare_advance/healthcare_advance_modal_disapproved/(:any)'] = 'healthcare_coordinator/loa_controller/healthcare_advance_modal_disapproved';

	//end
//=============================================================================================
//END
//=============================================================================================


//=============================================================================================
//IAD
//=============================================================================================
// 	$route['head-office-iad/dashboard'] = 'ho_iad/pages_controller';
// 	//Summary of Billing
// 	$route['head-office-iad/transaction/search'] = 'ho_iad/transaction_controller/search';
// 	$route['head-office-iad/transaction/search_by_id']  = 'ho_iad/transaction_controller/search_by_id';
// 	$route['head-office-iad/transaction/search_by_healthcard']  = 'ho_iad/transaction_controller/search_by_healthcard';
// 	$route['head-office-iad/transaction/search_by_name'] = 'ho_iad/transaction_controller/search_by_name';
// 	$route['head-office-iad/transaction/(:any)/view_receipt/(:any)'] = 'ho_iad/transaction_controller/view_receipt';
// 	//end

// 	//Payment Details
// 	$route['head-office-iad/transaction/payment-details/(:any)'] = 'ho_iad/transaction_controller/view_payment_details';
// 	//end

// 	//Account Setting
// 	$route['head-office-iad/transaction/account_setting'] = 'ho_iad/transaction_controller/account_settings';
// 	$route['head-office-iad/transaction/update_password'] = 'ho_iad/transaction_controller/update_password';
// 	$route['head-office-iad/transaction/update_username'] = 'ho_iad/transaction_controller/update_username';
// 	//end

// 	//Members
// 	$route['head-office-iad/transaction/members'] = 'ho_iad/transaction_controller/members';
// 	$route['head-office-iad/transaction/fetch_all_members'] = 'ho_iad/transaction_controller/fetch_all_members';
// 	$route['head-office-iad/transaction/view_information/(:any)'] = 'ho_iad/transaction_controller/view_information';
// 	//end
// //=============================================================================================
// //END
// //=============================================================================================







// IAD=====================================================================================================
$route['head-office-iad/dashboard'] = 'ho_iad/pages_controller';
$route['head-office-iad/biling/audit'] = 'ho_iad/pages_controller/view_billing_list';
$route['head-office-iad/biling/for-audit-list/(:any)'] = 'ho_iad/pages_controller/view_for_audit';
$route['head-office-iad/biling/audited'] = 'ho_iad/pages_controller/view_audited';
$route['head-office-iad/biling/audited-list/(:any)'] = 'ho_iad/pages_controller/view_audited_list';

// Summary of Billing
$route['head-office-iad/transaction/search'] = 'ho_iad/transaction_controller/search';
$route['head-office-iad/transaction/search_by_id']  = 'ho_iad/transaction_controller/search_by_id';
$route['head-office-iad/transaction/search_by_healthcard']  = 'ho_iad/transaction_controller/search_by_healthcard';
$route['head-office-iad/transaction/search_by_name'] = 'ho_iad/transaction_controller/search_by_name';
$route['head-office-iad/transaction/(:any)/view_receipt/(:any)'] = 'ho_iad/transaction_controller/view_receipt';
$route['head-office-iad/biling/for-audit/fetch'] = 'ho_iad/transaction_controller/fetch_for_payment_bill';
$route['head-office-iad/biling/audit/fetch'] = 'ho_iad/transaction_controller/fetch_payment_bill';
$route['head-office-iad/biling/submit-audited'] = 'ho_iad/transaction_controller/submit_audited';
$route['head-office-iad/biling/audited/fetch'] = 'ho_iad/transaction_controller/fetch_audited_bill';
//end
// Payment Details
$route['head-office-iad/transaction/payment-details/(:any)'] = 'ho_iad/transaction_controller/view_payment_details';
$route['head-office-iad/transaction/view_pd/(:any)'] = 'ho_iad/transaction_controller/view_pd';
// end
// Account Setting
$route['head-office-iad/transaction/account_setting'] = 'ho_iad/transaction_controller/account_settings';
$route['head-office-iad/transaction/update_password'] = 'ho_iad/transaction_controller/update_password';
$route['head-office-iad/transaction/update_username'] = 'ho_iad/transaction_controller/update_username';
// end
// Members
$route['head-office-iad/transaction/members'] = 'ho_iad/transaction_controller/members';
$route['head-office-iad/transaction/fetch_all_members'] = 'ho_iad/transaction_controller/fetch_all_members';
$route['head-office-iad/transaction/view_information/(:any)'] = 'ho_iad/transaction_controller/view_information';
// end
// IAD======================================================================================================
// $route['head-office-accounting/billing-list/closed/fetch'] = 'ho_accounting/main_controller/fetch_closed';
// $route['head-office-accounting/billing-list/view-employee-payment/(:any)'] = 'ho_accounting/main_controller/view_employee_payment';








// QR Code Routes
// $route['qrcode'] = 'home';
// $route['qrcode/read'] = 'home/read_qrcode';
// $route['codes/generate'] = 'qrcode_controller/generate';

$route['get-hmo/details/cash_advance'] 	= 'Api_controller/get_hmo_CA_data';
$route['get-hmo/details/update_apprv']	= 'Api_controller/update_incorp_apprv';

$route['404_override'] = 'auth_controller/page_not_found';
$route['translate_uri_dashes'] = FALSE;
