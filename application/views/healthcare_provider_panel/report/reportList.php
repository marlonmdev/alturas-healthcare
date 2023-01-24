<main id="main" class="main">

    <div class="pagetitle">
        <h1>Overview</h1>
    </div>
    <section class="section dashboard">
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-blue">
                    <div class="inner">
                        <h3><?= $billingCount ?></h3>
                        <p>Serviced</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-hospital-fill" aria-hidden="true"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-green">
                    <div class="inner">
                        <h3><?= $loa_pending_count + $noa_pending_count ?></h3>
                        <p>Total Members</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-people-fill" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-orange">
                    <div class="inner">
                        <h3><?= $loa_pending_count ?></h3>
                        <p>LOA Requests</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-file-earmark-ruled-fill" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card-box bg-red">
                    <div class="inner">
                        <h3><?= $noa_pending_count ?></h3>
                        <p>NOA Requests</p>
                    </div>
                    <div class="icon">
                        <i class="bi bi-file-earmark-person-fill" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>