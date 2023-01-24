<!-- Start of Page wrapper  -->
<div class="page-wrapper">
  <!-- Bread crumb and right sidebar toggle -->
  <div class="page-breadcrumb">
    <div class="row">
      <div class="col-12 d-flex no-block align-items-center">
        <h4 class="page-title">Dashboard</h4>
        <div class="ms-auto text-end">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">Head Office Accounting</li>
              <li class="breadcrumb-item active" aria-current="page">
                Dashboard
              </li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
  <!-- End Bread crumb and right sidebar toggle -->
  <!-- Start of Container fluid  -->
  <div class="container-fluid">
    <div class="row">

      <div class="container bootstrap snippets bootdey">
        <section id="news" class="white-bg padding-top-bottom">
          <div class="container bootstrap snippets bootdey">
            <div class="timeline">

              <?php $countHospital = 0; ?>
              <?php foreach ($cutoffresult as $key => $values) { ?>
                <div>
                  <?php foreach ($values as $key => $value) { ?>
                    <?php $countHospital++; ?>
                    <?php if ($countHospital % 2 == 1) { ?>


                      <div class="date-title">
                        <span> <?php print_r($value[array_keys($value)[0]]['billing_date']) ?></span>
                      </div>
                      <div class="row">

                        <a href="<?= base_url(); ?>head-office-accounting/list/hospital/<?php print_r($value[array_keys($value)[0]]['hp_id']) ?>/<?php print_r($value[array_keys($value)[0]]['billing_date']) ?>">
                          <div class="col-sm-6 news-item">
                            <div class="news-content">
                              <div class="date">
                                <small>
                                  <div class="icon">
                                    <div class="row">
                                      <div class="col"><i class="bi bi-people" aria-hidden="true"></i></div>
                                      <div class="col" style="margin-left:-55px"><?= count($value) ?></div>
                                    </div>
                                  </div>
                                </small>
                              </div>
                              <h2 class="news-title">Ramiro Hospital</h2>
                              <div class="news-media">
                                <a class="colorbox cboxElement" href="#">
                                  <img class="img-responsive" width="80" height="70" src="<?php echo base_url(); ?>uploads/ramiro-removebg-preview.png" alt="">
                                </a>
                              </div>

                              <a class="read-more" href="#">Continue <i class="mdi mdi-arrow-right-drop-circle"></i></a>
                            </div>
                          </div>
                        </a>
                      <?php } else { ?>

                        <div class="col-sm-6 news-item right">
                          <a href=" <?= base_url(); ?>head-office-accounting/list/hospital/<?php print_r($value[array_keys($value)[0]]['hp_id']) ?>/<?php print_r($value[array_keys($value)[0]]['billing_date']) ?>">
                            <div class="news-content" style="background-color:#4CAF50">
                              <div class="date">
                                <small>
                                  <div class="icon">
                                    <div class="row">
                                      <div class="col"><i class="bi bi-people" style="color: white;" aria-hidden="true"></i></div>
                                      <div class="col" style="margin-left:-55px; color:white"><?= count($value) ?></div>
                                    </div>
                                  </div>
                                </small>
                              </div>
                              <h2 class="news-title">ACE Hospital</h2>
                              <div class="news-media gallery">
                                <a class="colorbox cboxElement" href="#">
                                  <img class="img-responsive" width="80" height="70" src="<?php echo base_url(); ?>uploads/ace_e7502d4588.jpg" alt="">
                                </a>
                                <a class="colorbox cboxElement" href="#"></a>
                              </div>
                              <a class="read-more" href="#" style="color:white">Continue <i class="mdi mdi-arrow-right-drop-circle"></i></a>
                            </div>
                          </a>
                        </div>





                      </div>
                    <?php } ?>
                  <?php } ?>
                </div>

              <?php } ?>

            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
<style>
    /*-------------------
-----News Styles-----
---------------------*/
    .timeline {
      position: relative;
      margin-bottom: 100px;
      z-index: 1;
    }

    .timeline:before {
      display: block;
      content: "";
      position: absolute;
      width: 50%;
      height: 100%;
      left: 1px;
      top: 0;
      border-right: 1px solid #5CC9DF;
      z-index: -1;
    }

    .timeline:after {
      display: block;
      content: "";
      position: absolute;
      width: 50%;
      height: 100px;
      left: 1px;
      bottom: -105px;
      border-right: 1px dashed #5CC9DF;
      z-index: -1;
    }

    .timeline .date-title {
      text-align: center;
      margin: 70px 0 50px;
    }

    .timeline .date-title span {
      padding: 15px 30px;
      font-size: 21px;
      font-weight: 400;
      color: #fff;
      background: #5CC9DF;
      border-radius: 5px;
    }

    .news-item {
      padding-bottom: 45px;
    }

    .news-item.right {
      float: right;
      margin-top: 40px;
    }

    .news-item .news-content {
      margin: 20px 30px 0 0;
      position: relative;
      padding: 30px;
      padding-left: 100px;
      background: #f5f5f5;
      border-radius: 10px;
      box-shadow: -5px 5px 0 rgba(0, 0, 0, 0.08);
      -webkit-transition: all .3s ease-out;
      transition: all .3s ease-out;
    }

    .news-item:hover .news-content {
      background: #5CC9DF;
      color: #fff;
    }

    .news-item.right .news-content {
      margin: 20px 0 0 30px;
      box-shadow: 5px 5px 0 rgba(0, 0, 0, 0.08);
    }

    .news-item .news-content:after {
      display: block;
      content: "";
      position: absolute;
      top: 50px;
      right: -40px;
      width: 0px;
      height: 0px;
      background: transparent;
      border: 20px solid transparent;
      border-left: 20px solid #f5f5f5;
      -webkit-transition: border-left-color .3s ease-out;
      transition: border-left-color .3s ease-out;
    }

    .news-item.right .news-content:after {
      position: absolute;
      left: -40px;
      right: auto;
      border-left: 20px solid transparent;
      border-right: 20px solid #f5f5f5;
      -webkit-transition: border-right-color .3s ease-out;
      transition: border-right-color .3s ease-out;
    }

    .news-item:hover .news-content:after {
      border-left-color: #5CC9DF;
    }

    .news-item.right:hover .news-content:after {
      border-left-color: transparent;
      border-right-color: #5CC9DF;
    }

    .news-item .news-content:before {
      display: block;
      content: "";
      position: absolute;
      width: 20px;
      height: 20px;
      right: -55px;
      top: 60px;
      background: #5CC9DF;
      border: 3px solid #fff;
      border-radius: 50%;
      -webkit-transition: background .3s ease-out;
      transition: background .3s ease-out;
    }

    .news-item.right .news-content:before {
      left: -55px;
      right: auto;
    }

    .news-content .date {
      position: absolute;
      width: 80px;
      height: 80px;
      left: 10px;
      text-align: center;
      color: #5CC9DF;
      -webkit-transition: color .3s ease-out;
      transition: color .3s ease-out;
    }

    .news-item:hover .news-content .date {
      color: #fff;
    }

    .news-content .date p {
      margin: 0;
      font-size: 48px;
      font-weight: 600;
      line-height: 48px;
    }

    .news-content .date small {
      margin: 0;
      font-size: 26px;
      font-weight: 300;
      line-height: 24px;
    }

    .news-content .news-title {
      font-size: 24px;
      font-weight: 300;
    }

    .news-content p {
      font-size: 16px;
      line-height: 24px;
      font-weight: 300;
      letter-spacing: 0.02em;
      margin-bottom: 10px;
    }

    .news-content .read-more,
    .news-content .read-more:hover,
    .news-content .read-more:active,
    .news-content .read-more:focus {
      padding: 10px 0;
      text-decoration: none;
      font-size: 16px;
      color: #7A7C7F;
      line-height: 24px;
    }

    .news-item:hover .news-content .read-more,
    .news-item:hover .news-content .read-more:hover,
    .news-item:hover .news-content .read-more:active,
    .news-item:hover .news-content .read-more:focus {
      color: #fff;
    }

    .news-content .read-more {
      -webkit-transition: padding .3s ease-out;
      transition: padding .3s ease-out;
    }

    .news-content .read-more:hover {
      padding-left: 7px;
    }

    .news-content .read-more:hover:after {
      padding-left: 20px;
    }

    .news-item:hover .news-content .read-more:after {
      color: #fff;
    }

    .news-content .news-media {
      position: absolute;
      width: 80px;
      bottom: -45px;
      right: 40px;
      border-radius: 8px;
    }

    .news-content .news-media img {
      border-radius: 8px;
      transform: scale(1);
      -webkit-transition: -webkit-transform .3s ease-out;
      transition: transform .3s ease-out;
    }

    .news-content .news-media a {
      display: block;
      text-decoration: none;
      background: #fff;
      border-radius: 8px;
      overflow: hidden;
    }

    .news-content .news-media a:hover img {
      -webkit-transform: scale(1.3);
      transform: scale(1.3);
    }

    .news-content .news-media a:after {
      content: '\f065';
      position: absolute;
      width: 100%;
      top: 0;
      left: 0;
      font-family: FontAwesome;
      font-size: 32px;
      line-height: 80px;
      text-align: center;
      color: #5CC9DF;
      -webkit-transform: scale(0);
      transform: scale(0);
      opacity: 0;
      -webkit-transition: all .2s ease-out .1s;
      transition: all .2s ease-out .1s;
    }

    .news-content .news-media.video a:after {
      content: '\f04b';
    }

    .news-content .news-media a:hover:after {
      -webkit-transform: scale(1);
      transform: scale(1);
      opacity: 1;
    }

    .news-content .news-media.gallery {
      box-shadow: 4px 4px 0 #bbb, 8px 8px 0 #ddd;
    }

    .doneTextCounting {
      color: white;
    }
</style>
