
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
        <div class="col">
          <h6>NOA Details</h6>
        </div>
        <div class="col">
          <h6>STATUS: <strong><span id="nstatus" class="text-primary"></span></strong></h6>
        </div>
      </div>

      <div class="col">
        <div class="row">
          <div class="col">
            <ul class="list-unstyled ms-0 ps-0" id="noa_details_1">
              <!-- List items go here -->
            </ul>
          </div>
          <div class="col">
            <ul class="list-unstyled ms-0 ps-0" id="noa_details_2">
              <!-- List items go here -->
            </ul>
          </div>
        </div>
      </div>
    </li>

          <li class="list-group-item">
            <h6>Chief Complaint</h6>
            <p id="complaint-noa"></p>
          </li>
          <li class="list-group-item" id="p_services">
            <h6>Services Utilized</h6>
            <ul id="services">
             
            </ul>
          </li>
          <li class="list-group-item" id="p_disaproved">
            <h6>Reason for Disapproval</h6>
            <p id="disaproved-noa"></p>
          </li>
          <li class="list-group-item" id="p_documents">
            <h6>Uploaded Documents</h6>
            <ul id="documents-noa">
            </ul>
          </li>
          
          <li class="list-group-item" id="p_physician">
            <h6>Attending Doctors</h6>
            <ul id="physician-noa">
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