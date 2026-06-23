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

    <div class="row g-3 mb-3">

        <div class="col-md-4">

            <input
                type="text"
                id="enrollmentSearch"
                class="form-control modern-input"
                placeholder="Search student...">

        </div>

        <div class="col-md-3">

            <select
                id="enrollmentCourseFilter"
                class="form-select modern-input">

                <option value="">
                    All Courses
                </option>

            </select>

        </div>

        <div class="col-md-2">

            <select
                id="enrollmentStatusFilter"
                class="form-select modern-input">

                <option value="">
                    All Status
                </option>

                <option value="active">
                    Active
                </option>

                <option value="inactive">
                    Inactive
                </option>

                <option value="graduated">
                    Graduated
                </option>

                <option value="dropped">
                    Dropped
                </option>

            </select>

        </div>

        <div class="col-md-1">

            <button
                class="module-subject-btn w-100 justify-content-center"
                id="searchEnrollmentBtn">

                <div class="btn-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>

            </button>

        </div>

    </div>

    <hr>

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Student No.</th>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Enrollments</th>
                    <th>Latest Record</th>
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

<!-- HISTORY ENROLLMENT MODAL -->

<div
    class="modal fade"
    id="enrollmentHistoryModal"
    tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">

                    Enrollment History

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div
                class="modal-body"
                id="enrollmentHistoryContent">

                Loading...

            </div>

        </div>

    </div>

</div>