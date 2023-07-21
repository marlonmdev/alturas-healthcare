<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<main id="main" class="main">
    <div bgcolor="#f6f6f6" style="color: #333; height: 100%; width: 100%;" height="100%" width="100%">
        <table bgcolor="#f6f6f6" cellspacing="0" style="border-collapse: collapse; padding: 40px; width: 100%;" width="100%">
            <tbody>
                <tr>
                    <td width="5px" style="padding: 0;"></td>
                    <td style="clear: both; display: block; margin: 0 auto; max-width: 600px; padding: 10px 0;">
                        <table width="100%" cellspacing="0" style="border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <td style="padding: 0;">
                                        <div class="img-holder mr-md-4 mb-md-0 mb-4 mx-auto mx-md-0 d-md-none d-lg-flex">
                                            <img class="img-responsive" width="80" height="70" src="<?php echo base_url(); ?>uploads/ramiro-removebg-preview.png" alt="">
                                        </div>
                                    </td>
                                    <td style="color: #999; font-size: 12px; padding: 0; text-align: right;" align="right">
                                        Bootdey<br />
                                        Invoice #3440952<br />
                                        August 04, 2018
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="5px" style="padding: 0;"></td>
                </tr>

                <tr>
                    <td width="5px" style="padding: 0;"></td>
                    <td bgcolor="#FFFFFF" style="border: 1px solid #000; clear: both; display: block; margin: 0 auto; max-width: 600px; padding: 0;">
                        <table width="100%" style="background: #f9f9f9; border-bottom: 1px solid #eee; border-collapse: collapse; color: #999;">
                            <tbody>
                                <tr>
                                    <td width="50%" style="padding: 20px;"><strong style="color: #333; font-size: 24px;">$23.95</strong> Paid</td>
                                    <td align="right" width="50%" style="padding: 20px;">Thanks for using <span class="il">Bootdey.com</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td style="padding: 0;"></td>
                    <td width="5px" style="padding: 0;"></td>
                </tr>
                <tr>
                    <td width="5px" style="padding: 0;"></td>
                    <td style="border: 1px solid #000; border-top: 0; clear: both; display: block; margin: 0 auto; max-width: 600px; padding: 0;">
                        <table cellspacing="0" style="border-collapse: collapse; border-left: 1px solid #000; margin: 0 auto; max-width: 600px;">
                            <tbody>
                                <tr>
                                    <td valign="top" style="padding: 20px;">
                                        <h3 style="
                                            border-bottom: 1px solid #000;
                                            color: #000;
                                            font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif;
                                            font-size: 18px;
                                            font-weight: bold;
                                            line-height: 1.2;
                                            margin: 0;
                                            margin-bottom: 15px;
                                            padding-bottom: 5px;
                                        ">
                                            Summary
                                        </h3>
                                        <table cellspacing="0" style="border-collapse: collapse; margin-bottom: 40px;">
                                            <tbody>
                                                <tr>
                                                    <td style="padding: 5px 0;">Old Plan</td>
                                                    <td align="right" style="padding: 5px 0;">Free plan (10,000 msg/month)</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px 0;">New Plan</td>
                                                    <td align="right" style="padding: 5px 0;">Concept Plan</td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 5px 0;">Prorated subscription amount due</td>
                                                    <td align="right" style="padding: 5px 0;">$23.95</td>
                                                </tr>
                                                <tr>
                                                    <td style="border-bottom: 2px solid #000; border-top: 2px solid #000; font-weight: bold; padding: 5px 0;">Amount paid</td>
                                                    <td align="right" style="border-bottom: 2px solid #000; border-top: 2px solid #000; font-weight: bold; padding: 5px 0;">$23.95</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="5px" style="padding: 0;"></td>
                </tr>

                <tr style="color: #666; font-size: 12px;">
                    <td width="5px" style="padding: 10px 0;"></td>
                    <td style="clear: both; display: block; margin: 0 auto; max-width: 600px; padding: 10px 0;">
                        <table width="100%" cellspacing="0" style="border-collapse: collapse;">
                            <tbody>
                                <tr>
                                    <td width="40%" valign="top" style="padding: 10px 0;">
                                        <h4 style="margin: 0;">Questions?</h4>
                                        <p style="color: #666; font-size: 12px; font-weight: normal; margin-bottom: 10px;">
                                            Please visit our
                                            <a href="#" style="color: #666;" target="_blank">
                                                Support Center
                                            </a>
                                            with any questions.
                                        </p>
                                    </td>
                                    <td width="10%" style="padding: 10px 0;">&nbsp;</td>
                                    <td width="40%" valign="top" style="padding-left:100px">
                                        <div class="btn-group align-top">
                                            <a href="<?= base_url(); ?>head-office-accounting/list/hospital/1" class="btn btn-primary btn-block">Continue</a>
                                        </div>
                                        <div class="btn-group align-top">
                                            <button onclick="editSOA()" class="btn btn-warning btn-block">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="5px" style="padding: 10px 0;"></td>
                </tr>
            </tbody>
        </table>
    </div>
</main>

<style>
    body {
        margin-top: 20px;
    }
</style>
<script>
    function editSOA() {
        Swal.fire({
            title: 'Warning you are making Changes,',
            showCancelButton: true,
            confirmButtonText: 'Continue',
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {

            }
        })
    }
</script>