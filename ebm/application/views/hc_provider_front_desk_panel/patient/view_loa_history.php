
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
        <div class="col">
          <h6>LOA Details</h6>
        </div>
        <div class="col">
          <h6>STATUS: <strong><span id="status" class="text-primary"></span></strong></h6>
        </div>
      </div>

      <div class="col">
        <div class="row">
          <div class="col">
            <ul class="list-unstyled ms-0 ps-0" id="loa_details_1">
              <!-- List items go here -->
            </ul>
          </div>
          <div class="col">
            <ul class="list-unstyled ms-0 ps-0" id="loa_details_2">
              <!-- List items go here -->
            </ul>
          </div>
        </div>
      </div>
      
    </li>
          <li class="list-group-item">
            <h6>Chief Complaint</h6>
            <p id="complaint"></p>
          </li>

          <li class="list-group-item">
            <h6>Services Utilized</h6>
            <ul id="services">
             
            </ul>
          </li>
          <li class="list-group-item" id="p-disaproved">
            <h6>Reason for Disapproval</h6>
            <p id="disaproved"></p>
          </li>
          <li class="list-group-item" id="p-documents">
            <h6>Uploaded Documents</h6>
            <ul id="documents">
            </ul>
          </li>
          <li class="list-group-item" id="p-physician">
            <h6>Attending Physician</h6>
            <ul id="physician">
            
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