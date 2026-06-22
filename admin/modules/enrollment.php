<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Enrollment
        </h2>

        <p class="text-muted mb-0">
            Manage student enrollment records
        </p>

    </div>

    <button
        class="module-action-btn"
        id="btnAddEnrollment">

        <div class="btn-icon">
            <i class="fa-solid fa-user-check"></i>
        </div>

        <span>
            Enroll Student
        </span>

    </button>

</div>

<div class="dashboard-card">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Student No.</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Trimester</th>
                    <th>School Year</th>
                    <th>Status</th>
                    <th width="120">Actions</th>

                </tr>

            </thead>

            <tbody id="enrollmentTableBody">

            </tbody>

        </table>

        <div
            id="enrollmentPagination"
            class="d-flex justify-content-center mt-3">
        </div>

    </div>

</div>

<div
    class="modal fade"
    id="viewEnrollmentModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-scrollable">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">

                    Enrollment Details

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <div id="viewEnrollmentContent">

                    Loading...

                </div>

            </div>

        </div>

    </div>

</div>