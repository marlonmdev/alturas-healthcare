
<div class="modal fade" id="viewLoaModal" tabindex="-1" aria-labelledby="patientHistoryModalLabel" aria-hidden="true" data-bs-backdrop="static">
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
          <h6>LOA Details</h6>
          <p>LOA #: <strong><span id="loa-no" class="text-primary"></span></strong></p>
          <p>Issued on: <strong><span id="approved-date" class="text-primary"></span></strong></p>
        </div>
        <div class="col-md-6">
       <br>
          <p>Validity: <strong><span id="expire" class="text-primary"></span></strong></p>
          <p>Status: <strong><span id="status" class="text-primary"></span></strong></p>
        </div>
      </div>
    </li>
          <li class="list-group-item">
            <h6>Services Utilized</h6>
            <ul id="services">
             
            </ul>
          </li>
          <li class="list-group-item">
            <h6>Uploaded Documents</h6>
            <ul id="documents">
            </ul>
          </li>
          <li class="list-group-item">
            <h6>Attending Physician</h6>
            <ul>
              <li>
                <strong><span id="physician" class="text-primary"></span></strong>
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