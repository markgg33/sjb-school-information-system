<div class="dashboard-card mb-4">

    <div class="d-flex align-items-center justify-content-between">

        <div>

            <div class="small text-muted mb-3">

                <a
                    href="#"
                    id="backToEnrollment"
                    class="text-decoration-none">

                    Enrollment

                </a>

                <i class="fa-solid fa-chevron-right mx-2"></i>

                <span class="fw-semibold">
                    New Enrollment
                </span>

            </div>

        </div>

        <button
            class="btn btn-outline-secondary"
            id="backToEnrollmentBtn">

            <i class="fa-solid fa-arrow-left me-2"></i>

            Back

        </button>

    </div>

</div>

<div class="dashboard-card">

    <div class="mb-4">

        <h4 class="mb-1">

            <i class="fa-solid fa-user-check me-2"></i>

            Student Enrollment

        </h4>

        <p class="text-muted mb-0">
            Search and enroll students.
        </p>

    </div>

    <div class="row g-3">

        <div class="col-md-4">

            <input
                type="text"
                id="enrollmentStudentSearch"
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
                id="enrollmentYearFilter"
                class="form-select modern-input">

                <option value="">
                    All Years
                </option>

                <option value="1">1st Year</option>
                <option value="2">2nd Year</option>
                <option value="3">3rd Year</option>

            </select>

        </div>

        <div class="col-md-1">

            <button
                class="module-subject-btn w-100 justify-content-center"
                id="searchEnrollmentStudentsBtn">

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
                    <th>Status</th>
                    <th width="120">Action</th>

                </tr>

            </thead>

            <tbody id="enrollmentStudentTableBody">

            </tbody>

        </table>

    </div>

    <div
        id="enrollmentStudentsPagination"
        class="d-flex justify-content-center mt-3">
    </div>

</div>