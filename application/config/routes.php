<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Default Route
$route['default_controller'] = 'page_controller';
// Authentication Routes
$route['check-login'] = 'auth_controller/check_login';
$route['redirect-to-dashboard'] = 'auth_controller/redirect_to_dashboard';
$route['logout'] = 'auth_controller/logout';
$route['import/members'] = 'masterfile_controller';

// Import Members Data Routes
$route['import/members'] = 'import_controller';
$route['members/import'] = 'import_controller/import_csv_to_database';
$route['members/format-download'] = 'import_controller/csv_format_download';

// Import Text Files
$route['import/txt'] = 'import_controller/import_txt_file_page';
$route['import/txt/upload'] = 'import_controller/db_upload_txt_file';

$route['import/spreadsheet_import'] = 'masterfile_controller/spreadsheet_import';
$route['import/spreadhseet_format_download'] = 'masterfile_controller/spreadhseet_format_download';
$route['import/spreadsheet_export'] = 'masterfile_controller/spreadsheet_export';
//================================================================================================


//================================================================================================
// Member Navigation Links Routes
$route['member/dashboard'] = 'member/pages_controller';
$route['member/hmo-policy'] = 'member/pages_controller/hmo_policy';
$route['member/healthcare-providers'] = 'member/pages_controller/healthcare_providers';
$route['member/request-loa'] = 'member/pages_controller/request_loa_form';
$route['member/request-noa'] = 'member/pages_controller/request_noa_form';
$route['member/requested-loa/pending'] = 'member/pages_controller/pending_requested_loa';
$route['member/requested-loa/approved'] = 'member/pages_controller/approved_requested_loa';
$route['member/requested-loa/disapproved'] = 'member/pages_controller/disapproved_requested_loa';
$route['member/requested-loa/completed'] = 'member/pages_controller/completed_requested_loa';
$route['member/requested-noa/pending'] = 'member/pages_controller/pending_requested_noa';
$route['member/requested-noa/approved'] = 'member/pages_controller/approved_requested_noa';
$route['member/requested-noa/disapproved'] = 'member/pages_controller/disapproved_requested_noa';
$route['member/requested-noa/completed'] = 'member/pages_controller/completed_requested_noa';
$route['member/personal-charges'] = 'member/pages_controller/unpaid_personal_charges';
$route['member/personal-charges/paid'] = 'member/pages_controller/paid_personal_charges';
$route['member/profile'] = 'member/pages_controller/user_profile';


// Member User Account Routes
$route['member/account-settings'] = 'member/account_controller/account_settings';
$route['member/account-settings/password/update'] = 'member/account_controller/update_account_password';
$route['member/account-settings/username/update'] = 'member/account_controller/update_account_username';

// Member LOA Routes
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

// Member Personal Charges Routes
$route['member/personal-charges/unpaid/fetch'] = 'member/pcharges_controller/fetch_unpaid_personal_charges';
$route['member/personal-charges/paid/fetch'] = 'member/pcharges_controller/fetch_paid_personal_charges';

// End of Member Routes
//======================================================================================================


//======================================================================================================
// Start of HealthCare Provider (Hospital, Clinics, and Laboratories Users) Routes
$route['healthcare-provider/dashboard'] = 'healthcare_provider/pages_controller';

// Search Member
$route['healthcare-provider/search-member/healthcard'] = 'healthcare_provider/search_controller/search_member_by_healthcard';
$route['healthcare-provider/search-member/name'] = 'healthcare_provider/search_controller/search_member_by_name';


// Patient's Billing
$route['healthcare-provider/billing'] = 'healthcare_provider/billing_controller/billing_search_member';
$route['healthcare-provider/billing/search-by-healthcard']  = 'healthcare_provider/billing_controller/search_member_by_healthcard';
$route['healthcare-provider/billing/search-by-name'] = 'healthcare_provider/billing_controller/search_member_by_name';
$route['healthcare-provider/billing/bill-loa/(:any)'] = 'healthcare_provider/billing_controller/bill_patient_loa';
$route['healthcare-provider/billing/bill-loa/fetch/loa'] = 'healthcare_provider/billing_controller/fetch_loa_to_bill';
$route['healthcare-provider/billing/bill-loa/diagnostic-test/submit/(:any)'] = 'healthcare_provider/billing_controller/diagnostic_loa_final_billing';
$route['healthcare-provider/billing/bill-loa/consultation/submit/(:any)'] = 'healthcare_provider/billing_controller/consultation_loa_final_billing';
$route['healthcare-provider/billing/bill-loa/(:any)/success/(:any)'] = 'healthcare_provider/billing_controller/loa_billing_success';
// viewing of loa and noa billing receipt
$route['healthcare-provider/billing/loa/view-receipt/(:any)'] = 'healthcare_provider/billing_controller/view_request_billing';
$route['healthcare-provider/billing/noa/view-receipt/(:any)'] = 'healthcare_provider/billing_controller/view_request_billing';



$route['healthcare-provider/billing/bill-noa/request/(:any)']  = 'healthcare_provider/billing_controller/bill_patient_noa';
$route['healthcare-provider/billing/bill-noa/submit/(:any)'] = 'healthcare_provider/billing_controller/noa_final_billing';
$route['healthcare-provider/billing/bill-noa/success/(:any)'] = 'healthcare_provider/billing_controller/noa_billing_success';

$route['healthcare-provider/billing/billing-person/finalBilling']  = 'healthcare_provider/billing_controller/billing3NoaReview';
$route['healthcare-provider/billing/billing-person/finish']['post']  = 'healthcare_provider/billing_controller/billing5Final';

// LOA Pages
$route['healthcare-provider/loa-requests/pending'] = 'healthcare_provider/pages_controller/pending_loa_requests';
$route['healthcare-provider/loa-requests/approved'] = 'healthcare_provider/pages_controller/approved_loa_requests';
$route['healthcare-provider/loa-requests/disapproved'] = 'healthcare_provider/pages_controller/disapproved_loa_requests';
$route['healthcare-provider/loa-requests/completed'] = 'healthcare_provider/pages_controller/completed_loa_requests';

// LOA Datatables fetch data routes
$route['healthcare-provider/loa-requests/pending/fetch'] = 'healthcare_provider/loa_controller/fetch_pending_loa_requests';
$route['healthcare-provider/loa-requests/approved/fetch'] = 'healthcare_provider/loa_controller/fetch_approved_loa_requests';
$route['healthcare-provider/loa-requests/disapproved/fetch'] = 'healthcare_provider/loa_controller/fetch_disapproved_loa_requests';
$route['healthcare-provider/loa-requests/completed/fetch'] = 'healthcare_provider/loa_controller/fetch_completed_loa_requests';

// LOA modal view
$route['healthcare-provider/loa-requests/pending/view/(:any)'] = 'healthcare_provider/loa_controller/get_pending_loa_info';
$route['healthcare-provider/loa-requests/approved/view/(:any)'] = 'healthcare_provider/loa_controller/get_approved_loa_info';
$route['healthcare-provider/loa-requests/disapproved/view/(:any)'] = 'healthcare_provider/loa_controller/get_disapproved_loa_info';
$route['healthcare-provider/loa-requests/completed/view/(:any)'] = 'healthcare_provider/loa_controller/get_completed_loa_info';


// NOA Pages
$route['healthcare-provider/noa-requests/pending'] = 'healthcare_provider/pages_controller/pending_noa_requests';
$route['healthcare-provider/noa-requests/approved'] = 'healthcare_provider/pages_controller/approved_noa_requests';
$route['healthcare-provider/noa-requests/disapproved'] = 'healthcare_provider/pages_controller/disapproved_noa_requests';
$route['healthcare-provider/noa-requests/completed'] = 'healthcare_provider/pages_controller/completed_noa_requests';

// Noa Datatables fetch data routes
$route['healthcare-provider/noa-requests/pending/fetch'] = 'healthcare_provider/noa_controller/fetch_pending_noa_requests';
$route['healthcare-provider/noa-requests/approved/fetch'] = 'healthcare_provider/noa_controller/fetch_approved_noa_requests';
$route['healthcare-provider/noa-requests/disapproved/fetch'] = 'healthcare_provider/noa_controller/fetch_disapproved_noa_requests';
$route['healthcare-provider/noa-requests/completed/fetch'] = 'healthcare_provider/noa_controller/fetch_completed_noa_requests';

// NOA modal view
$route['healthcare-provider/noa-requests/view/(:any)'] = 'healthcare_provider/noa_controller/get_noa_info';

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






// HealthCare Provider User Account Routes
$route['healthcare-provider/account-settings'] = 'healthcare_provider/account_controller/account_settings';
$route['healthcare-provider/account-settings/password/update'] = 'healthcare_provider/account_controller/update_account_password';
$route['healthcare-provider/account-settings/username/update'] = 'healthcare_provider/account_controller/update_account_username';

// End of HealthCare Provider (Hospital, Clinics, and Laboratories Users) Routes
//====================================================================================================


// Start of Head Office Accounting Routes
// ===================================================================================================

$route['head-office-accounting/dashboard'] = 'ho_accounting/Pages_controller';
$route['head-office-accounting/billing-list/billed/fetch'] = 'ho_accounting/main_controller/fetch_billed';
$route['head-office-accounting/billing-list/billed/view/(:any)'] = 'ho_accounting/main_controller/view_billed_details';
$route['head-office-accounting/billing-list/unbilled_loa/fetch'] = 'ho_accounting/main_controller/fetch_unbilled_loa';
$route['healthcare-provider/loa-requests/unbilled_loa/view/(:any)'] = 'ho_accounting/main_controller/unbilled_loa_details';
$route['head-office-accounting/billing-list/unbilled_noa/fetch'] = 'ho_accounting/main_controller/fetch_unbilled_noa';
$route['head-office-accounting/billing-list/unbilled_noa/view/(:any)'] = 'ho_accounting/main_controller/unbilled_noa_details';
$route['head-office-accounting/billing-list/billed/hp_name'] = 'ho_accounting/main_controller/get_hp_name';

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
// LOA datatables
$route['head-office-accounting/loa-request-list/loa-approved/fetch'] = 'ho_accounting/Loa_ho_controller/get_approved_loa';
$route['head-office-accounting/loa-request-list/loa-completed/fetch'] = 'ho_accounting/Loa_ho_controller/get_completed_loa';
$route['head-office-accounting/loa-request-list/loa-approved/view/(:any)'] = 'ho_accounting/Loa_ho_controller/get_approved_loa_info';
$route['head-office-accounting/loa-request-list/loa-completed/view/(:any)'] = 'ho_accounting/Loa_ho_controller/get_completed_loa_info';
// NOA datatables
$route['head-office-accounting/noa-request-list/noa-approved/fetch'] = 'ho_accounting/Noa_ho_controller/get_approved_noa';
$route['head-office-accounting/noa-request-list/noa-completed/fetch'] = 'ho_accounting/Noa_ho_controller/get_completed_noa';
$route['head-office-accounting/noa-request-list/noa-approved/view/(:any)'] = 'ho_accounting/Noa_ho_controller/get_approved_noa_info';
$route['head-office-accounting/noa-request-list/noa-completed/view/(:any)'] = 'ho_accounting/Noa_ho_controller/get_completed_noa_info';

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
$route['healthcare-coordinator/dashboard'] = 'healthcare_coordinator/pages_controller';
$route['healthcare-coordinator/healthcare-providers'] = 'healthcare_coordinator/pages_controller/view_healthcare_providers';
$route['healthcare-coordinator/members'] = 'healthcare_coordinator/pages_controller/view_all_pending_members';
$route['healthcare-coordinator/members/approved'] = 'healthcare_coordinator/pages_controller/view_all_approved_members';
$route['healthcare-coordinator/accounts'] = 'healthcare_coordinator/pages_controller/view_all_accounts';
$route['healthcare-coordinator/accounts/register'] = 'healthcare_coordinator/pages_controller/register_account_form';
$route['healthcare-coordinator/setup/healthcare-providers'] = 'healthcare_coordinator/pages_controller/view_all_healthcare_providers';
$route['healthcare-coordinator/setup/company-doctors'] = 'healthcare_coordinator/pages_controller/view_all_company_doctors';
$route['healthcare-coordinator/setup/cost-types'] = 'healthcare_coordinator/pages_controller/view_all_cost_types';
$route['healthcare-coordinator/loa/request-loa'] = 'healthcare_coordinator/pages_controller/view_request_loa_form';
$route['healthcare-coordinator/loa/requests-list'] = 'healthcare_coordinator/pages_controller/view_pending_loa_list';
$route['healthcare-coordinator/loa/requests-list/approved'] = 'healthcare_coordinator/pages_controller/view_approved_loa_list';
$route['healthcare-coordinator/loa/requests-list/disapproved'] = 'healthcare_coordinator/pages_controller/view_disapproved_loa_list';
$route['healthcare-coordinator/loa/requests-list/completed'] = 'healthcare_coordinator/pages_controller/view_completed_loa_list';
$route['healthcare-coordinator/noa/requests-list'] = 'healthcare_coordinator/pages_controller/view_pending_noa_list';
$route['healthcare-coordinator/noa/requests-list/approved'] = 'healthcare_coordinator/pages_controller/view_approved_noa_list';
$route['healthcare-coordinator/noa/requests-list/disapproved'] = 'healthcare_coordinator/pages_controller/view_disapproved_noa_list';
$route['healthcare-coordinator/noa/requests-list/completed'] = 'healthcare_coordinator/pages_controller/view_completed_noa_list';
$route['healthcare-coordinator/noa/request-noa'] = 'healthcare_coordinator/pages_controller/request_noa_form';

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
$route['healthcare-coordinator/loa/pending/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_pending_loa_info';
$route['healthcare-coordinator/loa/approved/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_approved_loa_info';
$route['healthcare-coordinator/loa/disapproved/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_disapproved_loa_info';
$route['healthcare-coordinator/loa/completed/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_completed_loa_info';
$route['healthcare-coordinator/loa/requests-list/view/(:any)'] = 'healthcare_coordinator/loa_controller/get_loa_details';
$route['healthcare-coordinator/loa/requests-list/approve/(:any)'] = 'healthcare_coordinator/loa_controller/approve_loa_request';
$route['healthcare-coordinator/loa/requests-list/disapprove/(:any)'] = 'healthcare_coordinator/loa_controller/disapprove_loa_request';
$route['healthcare-coordinator/loa/requests-list/set-charge-type'] = 'healthcare_coordinator/loa_controller/set_charge_type';
$route['healthcare-coordinator/loa/member/search/(:any)'] = 'healthcare_coordinator/search_controller/get_searched_member_details';
$route['healthcare-coordinator/loa/requested-loa/cancel/(:any)'] = 'healthcare_coordinator/loa_controller/cancel_loa_request';
$route['healthcare-coordinator/loa/requested-loa/generate-printable-loa/(:any)'] = 'healthcare_coordinator/loa_controller/generate_printable_loa';


// HealthCare Coordinator NOA Routes
$route['healthcare-coordinator/noa/requests-list/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_pending_noa';
$route['healthcare-coordinator/noa/requests-list/approved/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_approved_noa';
$route['healthcare-coordinator/noa/requests-list/disapproved/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_disapproved_noa';
$route['healthcare-coordinator/noa/requests-list/completed/fetch'] = 'healthcare_coordinator/noa_controller/fetch_all_completed_noa';
$route['healthcare-coordinator/noa/pending/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_pending_noa_info';
$route['healthcare-coordinator/noa/approved/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_approved_noa_info';
$route['healthcare-coordinator/noa/disapproved/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_disapproved_noa_info';
$route['healthcare-coordinator/noa/completed/view/(:any)'] = 'healthcare_coordinator/noa_controller/get_completed_noa_info';
$route['healthcare-coordinator/noa/request-noa/submit'] = 'healthcare_coordinator/noa_controller/submit_noa_request';
$route['healthcare-coordinator/noa/requested-loa/edit/(:any)'] = 'healthcare_coordinator/noa_controller/edit_noa_request';
$route['healthcare-coordinator/noa/requested-noa/update/(:any)'] = 'healthcare_coordinator/noa_controller/update_noa_request';
$route['healthcare-coordinator/noa/requested-noa/cancel/(:any)'] = 'healthcare_coordinator/noa_controller/cancel_noa_request';
$route['healthcare-coordinator/noa/requested-noa/generate-printable-noa/(:any)'] = 'healthcare_coordinator/noa_controller/generate_printable_noa';
$route['healthcare-coordinator/noa/requests-list/set-charge-type'] = 'healthcare_coordinator/noa_controller/set_charge_type';


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

// End of HealthCare Coordinator Routes
//========================================================================================================


//======================================================================================================
// Company Doctor Navigation Links Routes
$route['company-doctor/dashboard'] = 'company_doctor/pages_controller';
$route['company-doctor/healthcare-providers'] = 'company_doctor/pages_controller/view_healthcare_providers';
$route['company-doctor/members'] = 'company_doctor/pages_controller/view_all_members';
$route['company-doctor/accounts'] = 'company_doctor/pages_controller/view_all_accounts';
$route['company-doctor/accounts/register'] = 'company_doctor/pages_controller/register_account_form';
$route['company-doctor/setup/affiliate-hospitals'] = 'company_doctor/pages_controller/view_all_affiliate_hospitals';
$route['company-doctor/loa/requests-list'] = 'company_doctor/pages_controller/view_pending_loa_list';
$route['company-doctor/loa/requests-list/approved'] = 'company_doctor/pages_controller/view_approved_loa_list';
$route['company-doctor/loa/requests-list/disapproved'] = 'company_doctor/pages_controller/view_disapproved_loa_list';
$route['company-doctor/loa/requests-list/completed'] = 'company_doctor/pages_controller/view_completed_loa_list';
$route['company-doctor/noa/requests-list'] = 'company_doctor/pages_controller/view_pending_noa_list';
$route['company-doctor/noa/requests-list/approved'] = 'company_doctor/pages_controller/view_approved_noa_list';
$route['company-doctor/noa/requests-list/disapproved'] = 'company_doctor/pages_controller/view_disapproved_noa_list';
$route['company-doctor/noa/requests-list/completed'] = 'company_doctor/pages_controller/view_completed_noa_list';


// Company Doctor User Account Routes
$route['company-doctor/account-settings'] = 'company_doctor/account_controller/account_settings';
$route['company-doctor/account-settings/password/update'] = 'company_doctor/account_controller/update_account_password';
$route['company-doctor/account-settings/username/update'] = 'company_doctor/account_controller/update_account_username';


// Company Doctor Members Routes
$route['company-doctor/members/fetch'] = 'company_doctor/members_controller/fetch_all_members';
$route['company-doctor/member/view/(:any)'] = 'company_doctor/members_controller/view_member_info';


// Company Doctor LOA Routes
$route['company-doctor/loa/requests-list/fetch'] = 'company_doctor/loa_controller/fetch_all_pending_loa';
$route['company-doctor/loa/requests-list/approved/fetch'] = 'company_doctor/loa_controller/fetch_all_approved_loa';
$route['company-doctor/loa/requests-list/disapproved/fetch'] = 'company_doctor/loa_controller/fetch_all_disapproved_loa';
$route['company-doctor/loa/requests-list/completed/fetch'] = 'company_doctor/loa_controller/fetch_all_completed_loa';
$route['company-doctor/loa/requests-list/view/(:any)'] = 'company_doctor/loa_controller/get_loa_info';
$route['company-doctor/loa/requests-list/approve/(:any)'] = 'company_doctor/loa_controller/approve_loa_request';
$route['company-doctor/loa/requests-list/disapprove/(:any)'] = 'company_doctor/loa_controller/disapprove_loa_request';


// Company Doctor NOA Routes
$route['company-doctor/noa/requests-list/fetch'] = 'company_doctor/noa_controller/fetch_all_pending_noa';
$route['company-doctor/noa/requests-list/approved/fetch'] = 'company_doctor/noa_controller/fetch_all_approved_noa';
$route['company-doctor/noa/requests-list/disapproved/fetch'] = 'company_doctor/noa_controller/fetch_all_disapproved_noa';
$route['company-doctor/noa/requests-list/completed/fetch'] = 'company_doctor/noa_controller/fetch_all_completed_noa';
$route['company-doctor/noa/requests-list/view/(:any)'] = 'company_doctor/noa_controller/get_noa_info';
$route['company-doctor/noa/requests-list/approve/(:any)'] = 'company_doctor/noa_controller/approve_noa_request';
$route['company-doctor/noa/requests-list/disapprove/(:any)'] = 'company_doctor/noa_controller/disapprove_noa_request';

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
$route['super-admin/loa/request-loa'] = 'super_admin/pages_controller/view_request_loa_form';
$route['super-admin/loa/requests-list'] = 'super_admin/pages_controller/view_pending_loa_list';
$route['super-admin/loa/requests-list/approved'] = 'super_admin/pages_controller/view_approved_loa_list';
$route['super-admin/loa/requests-list/disapproved'] = 'super_admin/pages_controller/view_disapproved_loa_list';
$route['super-admin/loa/requests-list/completed'] = 'super_admin/pages_controller/view_completed_loa_list';
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
$route['super-admin/loa/pending/view/(:any)'] = 'super_admin/loa_controller/get_pending_loa_info';
$route['super-admin/loa/approved/view/(:any)'] = 'super_admin/loa_controller/get_approved_loa_info';
$route['super-admin/loa/disapproved/view/(:any)'] = 'super_admin/loa_controller/get_disapproved_loa_info';
$route['super-admin/loa/completed/view/(:any)'] = 'super_admin/loa_controller/get_completed_loa_info';
$route['super-admin/loa/requests-list/approve/(:any)'] = 'super_admin/loa_controller/approve_loa_request';
$route['super-admin/loa/requests-list/disapprove/(:any)'] = 'super_admin/loa_controller/disapprove_loa_request';
$route['super-admin/loa/member/search/(:any)'] = 'super_admin/search_controller/get_searched_member_details';
$route['super-admin/loa/requested-loa/cancel/(:any)'] = 'super_admin/loa_controller/cancel_loa_request';
$route['super-admin/loa/requested-loa/generate-printable-loa/(:any)'] = 'super_admin/loa_controller/generate_printable_loa';


// Super Admin NOA Routes
$route['super-admin/noa/requests-list/fetch'] = 'super_admin/noa_controller/fetch_all_pending_noa';
$route['super-admin/noa/requests-list/approved/fetch'] = 'super_admin/noa_controller/fetch_all_approved_noa';
$route['super-admin/noa/requests-list/disapproved/fetch'] = 'super_admin/noa_controller/fetch_all_disapproved_noa';
$route['super-admin/noa/requests-list/completed/fetch'] = 'super_admin/noa_controller/fetch_all_completed_noa';
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

$route['super-admin/database-backup'] ='super_admin/backup_controller/database_backup';


// End of Super Admin Routes
//========================================================================================================


// QR Code Routes
// $route['qrcode'] = 'home';
// $route['qrcode/read'] = 'home/read_qrcode';
// $route['codes/generate'] = 'qrcode_controller/generate';


$route['404_override'] = 'auth_controller/page_not_found';
$route['translate_uri_dashes'] = FALSE;
