<!-- Page wrapper  -->
 <div class="page-wrapper">
    <!-- internal scripts -->
    <script src="<?php echo base_url(); ?>assets/js/lone/axios.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/vue3.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/sweetalert2v11.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/lone/jqueryv3.js"></script>
    <!-- Bread crumb and right sidebar toggle -->
    <div class="page-breadcrumb">
      <div class="row">
        <div class="col-12 d-flex no-block align-items-center">
          <h4 class="page-title ls-2">Billing</h4>
          <div class="ms-auto text-end">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item">Healthcare Provider</li>
                <li class="breadcrumb-item active" aria-current="page">
                  Billing
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </div>
    <!-- End Bread crumb and right sidebar toggle -->
    <?php
        $csrf = array(
            'name' => $this->security->get_csrf_token_name(),
            'hash' => $this->security->get_csrf_hash()
        );
    ?>
    <!-- Container fluid  -->
    <div class="container-fluid">        
        <div id="app">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="container">
                            <div class="row d-flex justify-content-center align-items-center">
                                <div class="col-md-7 px-4 py-4">
                                    <div class="text-center" v-if="isSubmit">
                                        <em class="text-danger">
                                            His MBL has been exceeded! The <h4>{{ Number(exceedingBalance).toLocaleString('en-US', {
                                            style: 'currency',
                                            currency: 'PHP',
                                            })}}</h4> exceeding balance will be added to his personal charges.
                                        </em>
                                    </div>
                                    <div>
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>
                                                    <strong>Patient Name: </strong>
                                                </td>
                                                <td>
                                                    <strong>
                                                        <?php
                                                            echo $user_info['member']->first_name . ' ' . $user_info['member']->middle_name. ' ' . $user_info['member']->last_name 
                                                        ?>
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>MBL Balance: </strong></td>
                                                <td>
                                                    <strong>
                                                        {{Number(remaining_balance).toLocaleString('en-US', {
                                                        style: 'currency',
                                                        currency: 'PHP',
                                                        })}}
                                                    </strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Billing Amount:</strong></td>
                                                <td class="t">
                                                    <strong>
                                                        {{Number(totalCostLoa).toLocaleString('en-US', {
                                                            style: 'currency',
                                                            currency: 'PHP',
                                                        })}}
                                                    </strong>
                                                </td>
                                            </tr>
                                        </table> 
                                    </div>
                                </div>
                                <div class="col-md-5 px-4 py-4 text-center">
                                    <strong><h4>Total</h4></strong>
                                    <span>
                                        <h3>{{Number(totalCostLoa).toLocaleString('en-US', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })}}</h3>
                                        <div v-if="!isReview">
                                            <button class="btn btn-dark" :disabled='isSubmitBilling' @click="activeReview()">Review</button>
                                        </div>
                                        <div v-else>
                                            <button class="btn btn-info" :disabled='isSubmitBilling' @click="addToDb()"><i class="mdi mdi-file-check me-1"></i>Bill Now</button>
                                        </div>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="isReview">
                <div class="card">
                    <div class="cantainer">
                        <h1 style="margin: 5%;">Review</h1>
                        <form action="<?php echo base_url('healthcare-provider/billing/billing-person/finish'); ?>" class="needs-validation" method="post" novalidate>
                            <input type="hidden" name="token" value="">
                            <input type="hidden" name="hp_id" value="">
                            <input type="hidden" name="member_id" value="">
                            <input type="hidden" name="equipment_array_string" value=''>

                            <div class="row" style="margin-left: 7%; margin-right: 7%; margin-top:3%">
                                <!-- <div class="col-sm">
                                    <div class="mb-3">
                                        <label class="form-label"><b>Hospital</b></label>
                                        <input type="text" class="form-control" name="hospital_name" readonly value="">
                                    </div>
                                </div> -->
                                <div class="col-sm">
                                    <div class="mb-3">
                                        <label for="inputLastName" class="form-label"><b>Remaining MBL</b></label>
                                        <input type="text" class="form-control" name="remaining_balance" :value="Number(remaining_balance).toLocaleString('en-US', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })" readonly>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="mb-3">
                                        <label for="inputLastName" class="form-label"><b>Billing #</b></label>
                                        <input type="text" name="billing_number" class="form-control" readonly value="<?php echo $user_info['billing_number']  ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="margin-left: 7%; margin-right: 7%;">
                                <div class="col-sm">
                                    <div class="mb-3">
                                        <label for="inputFirstName" class="form-label"><b>Employer Name</b></label>
                                        <input type="text" name="requesting_company" class="form-control" readonly value="Alturas Supermarket Corp." aria-describedby="emailHelp">
                                    </div>
                                </div>

                                <div class="col-sm">
                                    <div class="mb-3">
                                        <label for="inputLastName" class="form-label"><b>Date of Service</b></label>
                                        <input type="date" class="form-control" name="date_service" readonly value="<?php echo date("Y-m-d"); ?>">
                                    </div>
                                </div>

                            </div>
                            <div class="row" style="margin-left: 7%; margin-right: 7%;">
                                <div class="col-sm">
                                    <div class="mb-3">
                                        <label for="inputFirstName" class="form-label"><b>Member Name</b></label>
                                        <input type="text" class="form-control" name="full_name" readonly value="<?php echo $user_info['member']->first_name . ' ' . $user_info['member']->last_name  ?>" aria-describedby="emailHelp">
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <div class="mb-3">
                                        <label for="inputLastName" class="form-label"><b>Health Card #</b></label>
                                        <input type="text" class="form-control" name="health_card_no" readonly value="<?php echo $user_info['health_card_no']  ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="container" style="padding-left: 8%;margin-top:3%; padding-right:8%;">
                                <table id="myTable" class="table table-hover">
                                    <tr class="header">
                                        <th scope="col">Name</th>
                                        <th scope="col">Cost</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                    <!-- <tr>
                                        <th>Consultation</th>
                                        <th>{{ Number(consultation).toLocaleString('en-US', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })}}</th>
                                        <th>
                                            <button class="btn btn-success" type="button" @click="inActiveReview()">
                                                <i class="mdi mdi-pencil me-1"></i>Edit
                                            </button>
                                        </th>
                                    </tr> -->
                                    <tr v-for="(ls, index) in loaService">
                                        <th>{{ls.cost_type}}</th>
                                        <th>{{Number(loaService[index].cost).toLocaleString('en-US', {
                                                style: 'currency',
                                                currency: 'PHP',
                                            })}}</th>
                                        <th>
                                            <button class="btn btn-success" type="button" @click="inActiveReview()">
                                               <i class="mdi mdi-pencil me-1"></i>Edit
                                            </button>
                                        </th>
                                    </tr>

                                </table>
                            </div>
                            <div class="row" style="margin-left: 2%; margin-bottom:2%; margin-right: 7%;">
                                <div class="col-sm">
                                    <div class="row">
                                        <div class="col">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <div v-else>

                <!-- <div class="cart-item d-md-flex justify-content-between">
                    <div class="px-3 my-3 d-flex  align-items-center">

                        <a class="cart-item-product" href="#">
                            <div class="cart-item-product-info">
                                <h3 class="cart-item-product-title">Consultation</h3>
                            </div>
                        </a>
                    </div>

                    <div class="px-3 my-3 text-center">
                        <div class="cart-item-label">Subtotal</div><span class="text-xl font-weight-medium"> <input class="form-control form-control-sm my-2 mr-3" type="number" placeholder="Amount" required="" v-model="consultation"></span>
                    </div>
                </div> -->

                <div class="cart-item d-md-flex justify-content-between" v-for="(ls, index) in loaService">
                    <div class="px-3 my-3 d-flex  align-items-center">
                        <a class="cart-item-product" href="#">
                            <div class="cart-item-product-info">
                                <h3 class="cart-item-product-title">{{ls.cost_type}}</h3>
                            </div>
                        </a>
                    </div>
                    <div class="px-3 my-3 text-center">
                        <div class="cart-item-label">Quantity</div>
                        <div class="count-input">
                            <input class="form-control form-control-sm my-2 mr-3" type="number" v-model="loaService[index].quantity">
                        </div>
                    </div>
                    <div class="px-3 my-3 text-center">
                        <div class="cart-item-label" @click="billingLoaPost()">Subtotal</div>
                        <span class="text-xl font-weight-medium">
                            <input class="form-control form-control-sm my-2 mr-3" type="number" placeholder="Amount" v-model="loaService[index].cost">
                        </span>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>


<script>
    const {
        createApp
    } = Vue

    createApp({
        data() {
            return {
                message: '<?= $csrf['hash']; ?>',
                token_name: '<?= $csrf['name']; ?>',
                token_hash: '<?= $csrf['hash']; ?>',
                loaService: [],
                $remaining_balance: 0,
                loaRequestType: null,
                total: 0,
                consultation: 0,
                isSubmit: true,
                isSubmitBilling: false,
                isReview: false
            }
        },
        methods: {

            activeReview() {
                this.isReview = true;
            },
            inActiveReview() {
                this.isReview = false;
            },

            async getData() {

                var bodyFormData = new FormData();
                bodyFormData.append(this.token_name, this.token_hash);
                bodyFormData.append('id', '<?php echo $this->uri->segment(6) ?>');
                await axios.post("<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/getBillingLoa", bodyFormData)
                    .then(response => {

                        // this.loaService = response.data.loaService;
                        var recivedResponse = response.data.loaService;

                        console.log(response.data)
                        this.remaining_balance = response.data.memberMBL.remaining_balance;
                        console.log(this.remaining_balance);
                        recivedResponse.forEach(rr => {
                            var insertData = {
                                ctype_id: rr.ctype_id,
                                cost_type: rr.cost_type,
                                date_added: rr.date_added,
                                quantity: 1,
                                cost: 0,
                                consultation: 0
                            };
                            this.loaService.push(insertData);
                            console.log(insertData);
                        }, );

                    }, );

            },

            billingLoaPost() {
                var bodyFormData = new FormData();


                bodyFormData.append(this.token_name, this.token_hash);
                bodyFormData.append('id', '<?php echo $this->uri->segment(6) ?>');
                axios.post("<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/postBillingLoa", bodyFormData)
                    .then(response => {
                        // this.loaService = response.data.loaService;
                        var recivedResponse = response.data.loaService;



                        recivedResponse.forEach(rr => {

                            this.loaService.push(insertData);
                            console.log(insertData);
                        }, );

                    }, );
            },


            addToDb() {



                // **********'billing_no' => "TEST",
                // **********'emp_id' => "TEST",
                // **********'noa_no' => 'TESST',
                // **********'hp_id' => 'TEST',
                // **********'billing_img' => 'TEST',
                // **********'billed_by' => 'TEST',
                // ***********'billing_date' => 'TEST'
                // **********'total_bill' => 'TEST',
                // **********'loa_id' => 'TEST',
                // **********'mbr_remaining_bal' => 'TEST',
                // **********'personal_charges' => 'TEST',


                // ********** bsv_id	
                // ********** billing_no	
                // ********** bsv_cost_types	
                // ********** bsv_ct_fee	
                // ********** emp_id	
                // ********** billing_date	

                //********** emp_id	
                //********** billing_no	
                //********** pcharge_amount	
                //********** notes	
                //********** date_created	
                //********** status	





                var bodyFormData = new FormData();

                bodyFormData.append(this.token_name, this.token_hash);
                bodyFormData.append('mbr_remaining_bal', (this.totalCostLoa > this.remaining_balance) ? 0 : this.remaining_balance - this.totalCostLoa);
                bodyFormData.append('loa_id', '<?php echo $this->uri->segment(6) ?>');
                bodyFormData.append('total_bill', this.totalCostLoa);
                bodyFormData.append('personal_charges', Math.max(this.exceedingBalance, 0));


                if (this.exceedingBalance > 0) {
                    Swal.fire({
                            title: 'This Person has exceed ' + Number(this.exceedingBalance).toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'PHP',
                            }),
                            width: 600,
                            showCancelButton: true,
                            icon: 'warning',
                            confirmButtonText: 'confirm',
                            denyButtonText: `Don't confirm`,
                            padding: '3em',
                            color: '#f8d7da',
                            background: '#fff',
                            backdrop: `
                            rgba(0,0,250,0.4)
                            left top
                            no-repeat
                        `
                        })
                        .then((result) => {
                            /* Read more about isConfirmed, isDenied below */
                            if (result.isConfirmed) {


                                var personalChargeFormData = new FormData();
                                personalChargeFormData.append(this.token_name, this.token_hash);
                                personalChargeFormData.append('emp_id', '<?php echo $this->uri->segment(5) ?>');
                                personalChargeFormData.append('loa_id', '<?php echo $this->uri->segment(6) ?>');
                                personalChargeFormData.append('pcharge_amount', this.exceedingBalance);


                                axios.post("<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/billPersonalCharges", personalChargeFormData)
                                    .then(response => {
                                        console.log(response);
                                    }, );


                                let pushConsultation = {
                                    ctype_id: '1',
                                    cost_type: 'Urinalysis',
                                    date_added: '2022-09-21',
                                    quantity: 1,
                                    cost: 234,
                                }

                                let insertData = {
                                    ctype_id: 0,
                                    cost_type: 'Consultation',
                                    date_added: '<?php echo date("Y-m-d") ?>',
                                    quantity: 1,
                                    cost: this.consultation,
                                    bsv_ct_fee: this.consultation
                                };



                                this.loaService.push(insertData);
                                this.loaService.forEach(element => {

                                    var loaCtBodyFormData = new FormData();
                                    loaCtBodyFormData.append(this.token_name, this.token_hash);
                                    loaCtBodyFormData.append('bsv_cost_types', element.ctype_id);
                                    loaCtBodyFormData.append('bsv_ct_fee', element.cost);

                                    axios.post("<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/saveloacosttype", loaCtBodyFormData)
                                        .then(response => {});
                                });


                                saveData();
                            } else {
                                Swal.fire('Bill are not saved', '', 'info')
                            }

                        })
                } else {
                    Swal.fire({
                        title: 'Bill Confirmation',
                        showCancelButton: true,
                        confirmButtonText: 'confirm',
                        denyButtonText: `Don't confirm`,
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            saveData();

                            let insertData = {
                                ctype_id: 0,
                                cost_type: 'Consultation',
                                date_added: '<?php echo date("Y-m-d") ?>',
                                quantity: 1,
                                cost: this.consultation,
                                bsv_ct_fee: this.consultation
                            };


                            this.loaService.push(insertData);
                            this.loaService.forEach(element => {

                                var loaCtBodyFormData = new FormData();
                                loaCtBodyFormData.append(this.token_name, this.token_hash);
                                loaCtBodyFormData.append('bsv_cost_types', element.ctype_id);
                                loaCtBodyFormData.append('bsv_ct_fee', element.cost);


                                axios.post("<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/saveloacosttype", loaCtBodyFormData)
                                    .then(response => {

                                    });
                            });
                        } else {
                            Swal.fire('Bill are not saved', '', 'info')
                        }

                    })

                }

                function saveData() {
                    axios.post("<?php echo base_url() ?>healthcare-provider/reports/report-list/ajax/billLoaMember", bodyFormData)
                        .then(response => {
                            this.isSubmitBilling = true
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Billing Successful',
                                showConfirmButton: false,
                                timer: 2000
                            })
                            setTimeout(() => {
                                window.location.href = "<?php echo base_url() ?>healthcare-provider/billing/billing-person";
                            }, 2000);

                        }, );
                }

            }

        },
        computed: {

            exceedingBalance() {
                return this.totalCostLoa - this.remaining_balance;
            },
            totalCostLoa() {
                var total = this.loaService.reduce((acc, curr) => {
                    return acc + Number(curr.cost) * Number(curr.quantity);

                }, 0) + Number(this.consultation);
                if (total > 0) {
                    this.isSubmitBilling = false;
                } else {
                    this.isSubmitBilling = true;
                }


                if (total > this.remaining_balance) {
                    this.isSubmit = true;
                } else {
                    this.isSubmit = false;
                }
                return total;
            },

            publishedBooksMessage() {
                return 7 + 7
            }
        },
        async mounted() {
            this.getData();
        }
    }).mount('#app')
</script>


<style>
    .product-card {
        position: relative;
        max-width: 380px;
        padding-top: 12px;
        padding-bottom: 43px;
        transition: all 0.35s;
        border: 1px solid #e7e7e7;
    }

    .product-card .product-head {
        padding: 0 15px 8px;
    }

    .product-card .product-head .badge {
        margin: 0;
    }

    .product-card .product-thumb {
        display: block;
    }

    .product-card .product-thumb>img {
        display: block;
        width: 100%;
    }

    .product-card .product-card-body {
        padding: 0 20px;
        text-align: center;
    }

    .product-card .product-meta {
        display: block;
        padding: 12px 0 2px;
        transition: color 0.25s;
        color: rgba(140, 140, 140, .75);
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .product-card .product-meta:hover {
        color: #8c8c8c;
    }

    .product-card .product-title {
        margin-bottom: 8px;
        font-size: 16px;
        font-weight: bold;
    }

    .product-card .product-title>a {
        transition: color 0.3s;
        color: #343b43;
        text-decoration: none;
    }

    .product-card .product-title>a:hover {
        color: #ac32e4;
    }

    .product-card .product-price {
        display: block;
        color: #404040;
        font-family: 'Montserrat', sans-serif;
        font-weight: normal;
    }

    .product-card .product-price>del {
        margin-right: 6px;
        color: rgba(140, 140, 140, .75);
    }

    .product-card .product-buttons-wrap {
        position: absolute;
        bottom: -20px;
        left: 0;
        width: 100%;
    }

    .product-card .product-buttons {
        display: table;
        margin: auto;
        background-color: #fff;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .11);
    }

    .product-card .product-button {
        display: table-cell;
        position: relative;
        width: 50px;
        height: 40px;
        border-right: 1px solid rgba(231, 231, 231, .6);
    }

    .product-card .product-button:last-child {
        border-right: 0;
    }

    .product-card .product-button>a {
        display: block;
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        transition: all 0.3s;
        color: #404040;
        font-size: 16px;
        line-height: 40px;
        text-align: center;
        text-decoration: none;
    }

    .product-card .product-button>a:hover {
        background-color: #ac32e4;
        color: #fff;
    }

    .product-card:hover {
        border-color: transparent;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .09);
    }

    .product-category-card {
        display: block;
        max-width: 400px;
        text-align: center;
        text-decoration: none !important;
    }

    .product-category-card .product-category-card-thumb {
        display: table;
        width: 100%;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .09);
    }

    .product-category-card .product-category-card-body {
        padding: 20px;
        padding-bottom: 28px;
    }

    .product-category-card .main-img,
    .product-category-card .thumblist {
        display: table-cell;
        padding: 15px;
        vertical-align: middle;
    }

    .product-category-card .main-img>img,
    .product-category-card .thumblist>img {
        display: block;
        width: 100%;
    }

    .product-category-card .main-img {
        width: 65%;
        padding-right: 10px;
    }

    .product-category-card .thumblist {
        width: 35%;
        padding-left: 10px;
    }

    .product-category-card .thumblist>img:first-child {
        margin-bottom: 6px;
    }

    .product-category-card .product-category-card-meta {
        display: block;
        padding-bottom: 9px;
        color: rgba(140, 140, 140, .75);
        font-size: 11px;
        font-weight: 600;
    }

    .product-category-card .product-category-card-title {
        margin-bottom: 0;
        transition: color 0.3s;
        color: #343b43;
        font-size: 18px;
    }

    .product-category-card:hover .product-category-card-title {
        color: #ac32e4;
    }

    .product-gallery {
        position: relative;
        padding: 45px 15px 0;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .09);
    }

    .product-gallery .gallery-item::before {
        display: none !important;
    }

    .product-gallery .gallery-item::after {
        box-shadow: 0 8px 24px 0 rgba(0, 0, 0, .26);
    }

    .product-gallery .video-player-button,
    .product-gallery .badge {
        position: absolute;
        z-index: 5;
    }

    .product-gallery .badge {
        top: 15px;
        left: 15px;
        margin-left: 0;
    }

    .product-gallery .video-player-button {
        top: 0;
        right: 15px;
        width: 60px;
        height: 60px;
        line-height: 60px;
    }

    .product-gallery .product-thumbnails {
        display: block;
        margin: 0 -15px;
        padding: 12px;
        border-top: 1px solid #e7e7e7;
        list-style: none;
        text-align: center;
    }

    .product-gallery .product-thumbnails>li {
        display: inline-block;
        margin: 10px 3px;
    }

    .product-gallery .product-thumbnails>li>a {
        display: block;
        width: 94px;
        transition: all 0.25s;
        border: 1px solid transparent;
        background-color: #fff;
        opacity: 0.75;
    }

    .product-gallery .product-thumbnails>li:hover>a {
        opacity: 1;
    }

    .product-gallery .product-thumbnails>li.active>a {
        border-color: #ac32e4;
        cursor: default;
        opacity: 1;
    }

    .product-meta {
        padding-bottom: 10px;
    }

    .product-meta>a,
    .product-meta>i {
        display: inline-block;
        margin-right: 5px;
        color: rgba(140, 140, 140, .75);
        vertical-align: middle;
    }

    .product-meta>i {
        margin-top: 2px;
    }

    .product-meta>a {
        transition: color 0.25s;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .product-meta>a:hover {
        color: #8c8c8c;
    }

    .cart-item {
        position: relative;
        margin-bottom: 30px;
        padding: 0 50px 0 10px;
        background-color: #fff;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .09);
    }

    .cart-item .cart-item-label {
        display: block;
        margin-bottom: 15px;
        color: #8c8c8c;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .cart-item .cart-item-product {
        display: table;
        width: 420px;
        text-decoration: none;
    }

    .cart-item .cart-item-product-thumb,
    .cart-item .cart-item-product-info {
        display: table-cell;
        vertical-align: top;
    }

    .cart-item .cart-item-product-thumb {
        width: 110px;
    }

    .cart-item .cart-item-product-thumb>img {
        display: block;
        width: 100%;
    }

    .cart-item .cart-item-product-info {
        padding-top: 5px;
        padding-left: 15px;
    }

    .cart-item .cart-item-product-info>span {
        display: block;
        margin-bottom: 2px;
        color: #404040;
        font-size: 12px;
    }

    .cart-item .cart-item-product-title {
        margin-bottom: 8px;
        transition: color, 0.3s;
        color: #343b43;
        font-size: 16px;
        font-weight: bold;
    }

    .cart-item .cart-item-product:hover .cart-item-product-title {
        color: #ac32e4;
    }

    .cart-item .count-input {
        display: inline-block;
        width: 85px;
    }

    .cart-item .remove-item {
        right: -10px !important;
    }

    @media (max-width: 991px) {
        .cart-item {
            padding-right: 30px;
        }

        .cart-item .cart-item-product {
            width: auto;
        }
    }

    @media (max-width: 768px) {
        .cart-item {
            padding-right: 10px;
            padding-bottom: 15px;
        }

        .cart-item .cart-item-product {
            display: block;
            width: 100%;
            text-align: center;
        }

        .cart-item .cart-item-product-thumb,
        .cart-item .cart-item-product-info {
            display: block;
        }

        .cart-item .cart-item-product-thumb {
            margin: 0 auto 10px;
        }

        .cart-item .cart-item-product-info {
            padding-left: 0;
        }

        .cart-item .cart-item-label {
            margin-bottom: 8px;
        }
    }

    .comparison-table {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: -ms-autohiding-scrollbar;
    }

    .comparison-table table {
        min-width: 750px;
        table-layout: fixed;
    }

    .comparison-table .comparison-item {
        position: relative;
        margin-bottom: 10px;
        padding: 13px 12px 18px;
        background-color: #fff;
        text-align: center;
        box-shadow: 0 12px 20px 1px rgba(64, 64, 64, .09);
    }

    .comparison-table .comparison-item .comparison-item-thumb {
        display: block;
        width: 80px;
        margin-right: auto;
        margin-bottom: 12px;
        margin-left: auto;
    }

    .comparison-table .comparison-item .comparison-item-thumb>img {
        display: block;
        width: 100%;
    }

    .comparison-table .comparison-item .comparison-item-title {
        display: block;
        margin-bottom: 14px;
        transition: color 0.25s;
        color: #404040;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .comparison-table .comparison-item .comparison-item-title:hover {
        color: #ac32e4;
    }

    .remove-item {
        display: block;
        position: absolute;
        top: -5px;
        right: -5px;
        width: 22px;
        height: 22px;
        padding-left: 1px;
        border-radius: 50%;
        background-color: #ff5252;
        color: #fff;
        line-height: 23px;
        text-align: center;
        box-shadow: 0 3px 12px 0 rgba(255, 82, 82, .5);
        cursor: pointer;
    }

    .card-wrapper {
        margin: 30px -15px;
    }

    @media (max-width: 576px) {
        .card-wrapper .jp-card-container {
            width: 260px !important;
        }

        .card-wrapper .jp-card {
            min-width: 250px !important;
        }
    }
</style>