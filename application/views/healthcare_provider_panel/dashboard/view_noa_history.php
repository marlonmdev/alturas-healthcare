
<div class="modal fade" id="viewNoaModal" tabindex="-1" aria-labelledby="patientHistoryModalLabel" aria-hidden="true" data-bs-backdrop="static">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="patientHistoryModalLabel">Patient History</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      <ul class="list-group">
      <li class="list-group-item">
      <div class="row">
        <div class="col-md-6">
          <h6>NOA Details</h6>
          <p class="no-margin">NOA #: <strong><span id="noa-no" class="text-primary"></span></strong></p>
          <p class="no-margin">Issued on: <strong><span id="approved-date-noa" class="text-primary"></span></strong></p>
        </div>
        <div class="col-md-6">
          <h6>&nbsp;</h6>
          <p class="no-margin">Validity: <strong><span id="expire-noa" class="text-primary"></span></strong></p>
          <p class="no-margin">Status: <strong><span id="status-noa" class="text-primary"></span></strong></p>
        </div>
      </div>
    </li>

          <li class="list-group-item">
            <h6>Services Utilized</h6>
            <ul id="services-noa">
             
            </ul>
          </li>
          <li class="list-group-item">
            <h6>Uploaded Documents</h6>
            <ul id="documents-noa">
            </ul>
          </li>
          <li class="list-group-item">
            <h6>Attending Physician</h6>
            <ul id="physician-noa">
              <li>
                <strong><span class="text-primary"></span></strong>
              </li>
            </ul>
          </li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>