<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="mb-1">
            Courses
        </h2>

        <p class="text-muted mb-0">
            Manage academic programs
        </p>

    </div>

    <button
        class="module-action-btn"
        id="btnAddCourse">

        <div class="btn-icon">

            <i class="fa-solid fa-plus"></i>

        </div>

        <span>

            Add Course

        </span>

    </button>

</div>

<div class="dashboard-card">

    <div class="table-responsive">

        <table class="table align-middle">

            <thead>

                <tr>

                    <th>Code</th>
                    <th>Course Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="150">Actions</th>

                </tr>

            </thead>

            <tbody id="coursesTableBody">

            </tbody>

        </table>

    </div>

</div>

<div
    class="modal fade"
    id="courseModal"
    tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content modern-modal">

            <div class="modal-header">

                <h5 class="modal-title">
                    Add Course
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <form id="courseForm">

                    <input
                        type="hidden"
                        id="courseId"
                        name="id">

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Course Code
                        </label>

                        <input
                            type="text"
                            class="form-control modern-input"
                            name="course_code"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Course Name
                        </label>

                        <input
                            type="text"
                            class="form-control modern-input"
                            name="course_name"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Description
                        </label>

                        <textarea
                            class="form-control modern-input"
                            name="description"></textarea>

                    </div>

                    <div class="mb-3">

                        <label class="form-label modern-label">
                            Status
                        </label>

                        <select
                            class="form-select modern-input"
                            name="status">

                            <option value="active">
                                Active
                            </option>

                            <option value="inactive">
                                Inactive
                            </option>

                        </select>

                    </div>

                </form>

            </div>

            <div class="modal-footer border-0">

                <button
                    type="button"
                    class="modal-btn modal-btn-secondary"
                    data-bs-dismiss="modal">

                    Cancel

                </button>

                <button
                    type="button"
                    class="modal-btn modal-btn-primary"
                    id="saveCourseBtn">

                    Save Course

                </button>

            </div>

        </div>

    </div>

</div>