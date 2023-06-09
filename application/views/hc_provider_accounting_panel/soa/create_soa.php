<head>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">





    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>


    <script src="https://cdn.apidelv.com/libs/awesome-functions/awesome-functions.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>



    <script type="text/javascript">
        $(document).ready(function($) {

            $(document).on('click', '.btn_print', function(event) {
                event.preventDefault();

                //credit : https://ekoopmans.github.io/html2pdf.js

                var element = document.getElementById('container_content');

                //easy
                //html2pdf().from(element).save();

                //custom file name
                //html2pdf().set({filename: 'code_with_mark_'+js.AutoCode()+'.pdf'}).from(element).save();


                //more custom settings
                var opt = {
                    margin: 0,
                    filename: 'pageContent_' + js.AutoCode() + '.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },

                    jsPDF: {
                        unit: 'in',
                        format: 'letter',
                        orientation: 'portrait'
                    }
                };

                // New Promise-based usage:
                html2pdf().set(opt).from(element).save();


            });



        });
    </script>



</head>


<main id="main" class="main">
    <?php
    //--->get app url > start

    if (
        isset($_SERVER['HTTPS']) &&
        ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
        isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
        $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
    ) {
        $ssl = 'https';
    } else {
        $ssl = 'http';
    }

    $app_url = ($ssl)
        . "://" . $_SERVER['HTTP_HOST']
        //. $_SERVER["SERVER_NAME"]
        . (dirname($_SERVER["SCRIPT_NAME"]) == DIRECTORY_SEPARATOR ? "" : "/")
        . trim(str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"])), "/");

    //--->get app url > end

    header("Access-Control-Allow-Origin: *");

    ?>




    <div class="text-center" style="padding:20px;">
        <input type="button" id="rep" value="Print" class="btn btn-info btn_print">
    </div>


    <div class="container_content" id="container_content">


        <div class="invoice-box">

            <div class="row" style="margin-top:7% ;">
                <div class="col-3">
                    <td class="title ">
                        <img src="" class="img-account-profile rounded-circle mb-2" style=" max-width: 150px; margin-left:20%" />
                    </td>

                </div>
                <div class="col-5 ">
                    <h4 class="text-center">Statement of Account</h4>
                    <p class="text-center"><b>Ramiro Community Hospital</b></p>
                    <p class="text-center pdf-normal-text">Celestino Gallares St, Tagbilaran City, Bohol</p>
                </div>
                <div class="col-4">
                    <div class="row" style="margin-top:50px;">
                        <div class="col-4 text-center pdf-normal-text" style="margin-top:3%;">
                            SOA No. :
                        </div>

                        <div class="col-6 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4 text-center">
                    <div class="row">
                        <div class="col-3 text-center pdf-normal-text text-left">
                            Name:
                        </div>
                        <div class="col-9 pdf-normal-text text-left" style=" min-width: 150px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>


                    <div class="text-center pdf-normal-text">
                        <div class="row">
                            <div class="col-3 text-center pdf-normal-text text-left">
                                Address:
                            </div>
                            <div class="col-9 pdf-normal-text text-left" style=" min-width: 150px;
                            border-bottom: 1px solid black;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="row">
                        <div class="col-3 text-center pdf-normal-text text-left">
                            Age:
                        </div>
                        <div class="col-6 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-center pdf-normal-text text-left">
                            Room No. :
                        </div>
                        <div class="col-3 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                </div>
                <div class="col-4 text-center">
                    <div class="row">
                        <div class="col-6 text-center pdf-normal-text text-left">
                            Date Admitted:
                        </div>
                        <div class="col-5 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center pdf-normal-text text-left">
                            Date Discharged:
                        </div>
                        <div class="col-5 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center pdf-normal-text text-left">
                            Final Diagnosis:
                        </div>
                        <div class="col-5 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6 text-center pdf-normal-text text-left">
                            Other Diagnosis:
                        </div>
                        <div class="col-5 pdf-normal-text text-left" style=" min-width: 100px;
                            border-bottom: 1px solid black;">
                        </div>
                    </div>
                </div>
            </div>

            <h5 class="text-center "><b>SUMMARY OF FEES</b></h5>
            <div class="container">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <td rowspan="2">
                                <div class="container" style="margin: 30px;">Particulars</div>
                            </td>
                            <td rowspan="2">
                                <div class="container">Actual Charges</div>
                            </td>
                            <td colspan="3">
                                <div class="container">Amount of Discounts</div>
                            </td>
                            <td colspan="2">
                                <div class="container">Philhealth Benefits</div>
                            </td>
                            <td rowspan="2">
                                <div class="container">Out of Pocket of Patient</div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="container">Vat Exempt</div>
                            </td>
                            <td>
                                <div class="container">Senior Citizen/PWD</div>
                            </td>
                            <td>
                                <div class="container">HMO</div>
                            </td>
                            <td>
                                <div class="container">First Case Rate Amount</div>
                            </td>
                            <td>
                                <div class="container">Second Case Rate Amount</div>
                            </td>
                        </tr>
                        <th><b>HCI Fees</b></th>
                    </thead>
                    <tbody>
                        <tr>
                            <th>fj2</th>
                            <td>j2</td>
                            <td>j2</td>
                            <td>j2</td>
                            <td>j2</td>
                            <td>j2</td>
                            <td>j2</td>
                            <td>j2</td>
                        </tr>

                        <tr>
                            <th>fj3</th>
                            <td>j3</td>
                            <td>j3</td>
                            <td>j3</td>
                            <td>j3</td>
                            <td>j3</td>
                            <td>j3</td>
                            <td>j3</td>
                        </tr>
                        <tr>
                            <th>fj4</th>
                            <td>j4</td>
                            <td>j4</td>
                            <td>j4</td>
                            <td>j4</td>
                            <td>j4</td>
                            <td>j4</td>
                            <td>j4</td>
                        </tr>
                        <tr>
                            <th><b>Other Deductions</b></th>
                        </tr>
                        <tr>
                            <th>fj5</th>
                            <td>j5</td>
                            <td>j5</td>
                            <td>j5</td>
                            <td>j5</td>
                            <td>j5</td>
                            <td>j5</td>
                            <td>j5</td>
                        </tr>
                        <tr>
                            <th>fj6</th>
                            <td>j6</td>
                            <td>j6</td>
                            <td>j6</td>
                            <td>j6</td>
                            <td>j6</td>
                            <td>j6</td>
                            <td>j6</td>
                        </tr>
                        <tr>
                            <th>fj7</th>
                            <td>j7</td>
                            <td>j7</td>
                            <td>j7</td>
                            <td>j7</td>
                            <td>j7</td>
                            <td>j7</td>
                            <td>j7</td>
                        </tr>
                        <tr>
                            <th><b>Professional fee/s</b></th>
                        </tr>
                        <tr>
                            <th>fj8</th>
                            <td>j8</td>
                            <td>j8</td>
                            <td>j8</td>
                            <td>j8</td>
                            <td>j8</td>
                            <td>j8</td>
                            <td>j8</td>
                        </tr>
                        <tr>
                            <th>fj9</th>
                            <td>j9</td>
                            <td>j9</td>
                            <td>j9</td>
                            <td>j9</td>
                            <td>j9</td>
                            <td>j9</td>
                            <td>j9</td>
                        </tr>

                    </tbody>

                </table>
            </div>
        </div>
    </div>
</main>
<style>
    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 12px;
        color: black;
        padding: 0;
        margin: 0;
    }

    .pdf-normal-text {
        font-size: 11px;
    }

    .invoice-box {
        max-width: 1000px;
        margin: auto;
        padding: 30px;
        border: 1px solid #eee;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
        font-size: 16px;
        line-height: 24px;
        font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color: #555;
    }
</style>