//=======================================
// GLOBAL VARIABLES
//=======================================
let selectedEnrollmentStudent = null;

//=======================================
// LOAD ENROLLMENT COURSES
//=======================================

function loadEnrollmentCourses() {
  $.getJSON("ajax/get_courses_dropdown.php", function (courses) {
    let html = `
      <option value="">
        All Courses
      </option>
    `;

    courses.forEach((course) => {
      html += `
        <option value="${course.id}">
          ${course.course_code}
          -
          ${course.course_name}
        </option>
      `;
    });

    $("#enrollmentCourseFilter").html(html);
  });
}

//=======================================
// LOAD CURRICULUM SUBJECTS
//=======================================

function loadCurriculumSubjects() {
  Loader.show("Loading subjects...");
  const studentId = $("#selectedStudentId").val();

  const yearLevel = $("#yearLevel").val();

  const trimester = $("#trimester").val();

  if (!studentId) return;

  $.getJSON(
    "ajax/get_curriculum_subjects.php",
    {
      student_id: studentId,
      year_level: yearLevel,
      trimester: trimester,
    },

    function (subjects) {
      let html = "";

      let totalUnits = 0;

      if (!subjects.length) {
        $("#curriculumSubjectsContainer").html(`
          <div class="alert alert-warning mb-0">

            No curriculum subjects found.

          </div>
        `);

        return;
      }

      subjects.forEach((subject) => {
        totalUnits += Number(subject.units);

        html += `
  <div class="form-check mb-2">

    <input
      class="form-check-input enrollmentSubject"
      type="checkbox"
      name="subject_ids[]"
      value="${subject.id}"
      data-units="${subject.units}"
      checked>

    <label class="form-check-label">

  <div class="text-truncate" style="max-width:220px;">

    <strong>
        ${subject.subject_code}
    </strong>

    <div class="small text-muted">

        ${subject.subject_name}

    </div>

</div>

      <span class="text-muted">
        (${subject.units} units)
      </span>

    </label>

  </div>
`;
      });

      html += `
  <hr>

  <div class="d-flex gap-2 mb-3">

      <button
          type="button"
          class="btn btn-sm btn-outline-primary"
          id="selectAllSubjects">

          Select All

      </button>

      <button
          type="button"
          class="btn btn-sm btn-outline-secondary"
          id="deselectAllSubjects">

          Deselect All

      </button>

  </div>

  <div class="fw-bold">

      Total Units:
      <span id="totalUnitsDisplay">

          ${totalUnits}

      </span>

  </div>
`;

      $("#curriculumSubjectsContainer").html(html);
    },
  ).always(function () {
    setTimeout(() => {
      Loader.hide();
    }, 500);
  });
}

//=======================================
// UPDATE TOTAL UNITS
//=======================================

function updateTotalUnits() {
  let total = 0;

  $(".enrollmentSubject:checked").each(function () {
    total += Number($(this).data("units"));
  });

  $("#totalUnitsDisplay").text(total);
}

//=======================================
// UPDATE MANUAL SUBJECT COUNT
//=======================================

function updateManualSubjectCount() {
  $("#manualSubjectCount").text($(".manualSubject").length);
}

//=======================================
// OPEN ENROLLMENT DETAILS
//=======================================
$(document).on("click", ".selectEnrollmentStudentBtn", function () {
  selectedEnrollmentStudent = $(this).data("id");

  loadPage("enrollment-details");
});

//=======================================
// OPEN MODAL
//=======================================

$(document).on("click", "#btnAddEnrollment", function () {
  loadPage("enrollment-create");
});

//=======================================
// BACK TO ENROLLMENT
//=======================================

$(document).on(
  "click",
  "#backToEnrollment, #backToEnrollmentBtn",
  function (e) {
    e.preventDefault();

    loadPage("enrollment");
  },
);

$(document).on("click", "#backToEnrollmentCreate", function (e) {
  e.preventDefault();

  loadPage("enrollment-create");
});

//=======================================
// SEARCH TABLE LOADER
//=======================================

function loadEnrollmentStudentsPage(page = 1) {
  Loader.show("Loading enrollment...");
  $.getJSON(
    "ajax/get_enrollment_students.php",
    {
      search: $("#enrollmentStudentSearch").val(),
      course_id: $("#enrollmentCourseFilter").val(),
      year_level: $("#enrollmentYearFilter").val(),
      page: page,
    },
    function (response) {
      let html = "";

      if (!response.students.length) {
        html = `
          <tr>
            <td colspan="7" class="text-center py-5">
                <div class="empty-state">

                 <i class="fa-regular fa-circle-user empty-icon"></i>

                    <h6 class="mt-3 mb-1">
                        No students found
                    </h6>

                <p class="text-muted mb-0">
                  No matching students were found.
                </p>

              </div>
            </td>
          </tr>
        `;

        $("#enrollmentStudentTableBody").html(html);

        renderEnrollmentPagination(response.current_page, response.total_pages);

        return;
      }

      response.students.forEach((student) => {
        // FOR STATUS BADGE
        const statusBadge =
          student.status === "active"
            ? '<span class="badge bg-success text-uppercase">Active</span>'
            : student.status === "inactive"
              ? '<span class="badge bg-secondary text-uppercase">Inactive</span>'
              : student.status === "graduated"
                ? '<span class="badge bg-primary text-uppercase">Graduated</span>'
                : student.status === "dropped"
                  ? '<span class="badge bg-danger text-uppercase">Dropped</span>'
                  : '<span class="badge bg-warning text-uppercase">Unknown</span>';
        html += `
          <tr>

            <td>
              ${student.student_number ?? "-"}
            </td>

            <td>
              ${student.last_name},
              ${student.first_name}
            </td>

            <td>
              ${student.course_code} -
              ${student.course_name}
            </td>

            <td>
              ${statusBadge}
            </td>

            <td>

<button
  class="btn btn-sm btn-primary selectEnrollmentStudentBtn"
  data-id="${student.id}">

  Select

</button>

            </td>

          </tr>
        `;
      });

      $("#enrollmentStudentTableBody").html(html);
    },
  ).always(function () {
    setTimeout(() => {
      Loader.hide();
    }, 500);
  });
}

//=======================================
// LOAD STUDENT INFO
//=======================================

function loadEnrollmentDetails() {
  if (!selectedEnrollmentStudent) {
    loadPage("enrollment-create");
    return;
  }

  Loader.show("Loading details...");

  $.getJSON(
    "ajax/get_student.php",
    {
      id: selectedEnrollmentStudent,
    },
    function (student) {
      $("#selectedStudentId").val(student.id);
      const currentYear = new Date().getFullYear();

      $("#schoolYear").val(`${currentYear}-${currentYear + 1}`);

      $("#selectedStudentInfo").html(`
        <div class="row">

          <div class="col-md-4">

            <strong>
              Student No.
            </strong>

            <br>

            ${student.student_number ?? "Not Assigned"}

          </div>

          <div class="col-md-4">

            <strong>
              Name
            </strong>

            <br>

            ${student.last_name},
            ${student.first_name}

          </div>

          <div class="col-md-4">

            <strong>
              Course
            </strong>

            <br>

            ${student.course_name ?? student.course_code}

          </div>

        </div>
      `);

      $("#manualSubjectsContainer").html("");

      updateManualSubjectCount();

      loadCurriculumSubjects();
      loadAdditionalSubjects();
    },
  ).always(function () {
    setTimeout(() => {
      Loader.hide();
    }, 500);
  });
}

//=======================================
// PAGINATION
//=======================================
function renderEnrollmentPagination(currentPage, totalPages) {
  let html = "";

  if (totalPages <= 1) {
    $("#enrollmentStudentsPagination").html("");
    return;
  }

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button
        class="btn btn-sm ${
          i === currentPage ? "btn-primary" : "btn-outline-primary"
        }
        enrollmentPageBtn mx-1"
        data-page="${i}">

        ${i}

      </button>
    `;
  }

  $("#enrollmentStudentsPagination").html(html);
}

//=======================================
// LOAD ENROLLMENTS
//=======================================
function loadEnrollments(page = 1) {
  Loader.show("Loading enrollments...");

  $.getJSON(
    "ajax/get_enrollments.php",
    {
      page: page,
    },
    function (response) {
      let html = "";

      if (!response.enrollments.length) {
        html = `
          <tr>
            <td colspan="7" class="text-center py-5">

              <div class="empty-state">

                <i class="fa-solid fa-user-check empty-icon"></i>

                <h6 class="mt-3 mb-1">
                  No enrollments found
                </h6>

                <p class="text-muted mb-0">
                  No enrollment records available.
                </p>

              </div>

            </td>
          </tr>
        `;

        $("#enrollmentTableBody").html(html);

        return;
      }

      response.enrollments.forEach((e) => {
        const trimesterText =
          e.trimester == 1
            ? "1st Trimester"
            : e.trimester == 2
              ? "2nd Trimester"
              : "3rd Trimester";

        html += `
          <tr>

            <td>${e.student_number ?? "-"}</td>

            <td>
              ${e.last_name},
              ${e.first_name}
            </td>

            <td>
              ${e.course_code} - ${e.course_name}
            </td>

            <td>
              ${e.year_level}
            </td>

            <td>
              ${trimesterText}
            </td>

            <td>
              ${e.school_year}
            </td>

            <td>
              <span class="badge bg-success">
                ${e.status}
              </span>
            </td>

            <td>

        <button
            class="btn btn-sm btn-outline-primary viewEnrollmentBtn"
            data-id="${e.id}">

            <i class="fa-solid fa-eye"></i>

        </button>

    </td>


          </tr>
        `;
      });

      $("#enrollmentTableBody").html(html);
    },
  ).always(function () {
    setTimeout(() => {
      Loader.hide();
    }, 500);
  });
}

//=======================================
// SAVE ENROLLMENT
//=======================================

$(document).on("click", "#saveEnrollmentBtn", function () {
  AlertService.confirm(
    "Save Enrollment",
    "Do you want to save this enrollment?",
  ).then((result) => {
    if (!result.isConfirmed) return;

    Loader.show("Saving enrollment...");

    $.ajax({
      url: "ajax/save_enrollment.php",

      type: "POST",

      data: $("#saveEnrollmentForm").serialize(),

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          selectedEnrollmentStudent = null;

          loadPage("enrollment");
        } else {
          AlertService.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to save enrollment.");
      },

      complete: function () {
        Loader.hide();
      },
    });
  });
});

//=======================================
// VIEW ENROLLMENT
//=======================================

$(document).on("click", ".viewEnrollmentBtn", function () {
  const id = $(this).data("id");

  Loader.show("Loading enrollment...");

  $.getJSON(
    "ajax/get_enrollment_details.php",
    {
      id: id,
    },

    function (response) {
      const e = response.enrollment;

      let units = 0;

      let subjectRows = "";

      response.subjects.forEach((subject) => {
        units += Number(subject.units);

        subjectRows += `
          <tr>

            <td>
              ${subject.subject_code}
            </td>

            <td>
              ${subject.subject_name}
            </td>

            <td>
              ${subject.units}
            </td>

          </tr>
        `;
      });

      $("#viewEnrollmentContent").html(`
        <div class="row mb-4">

          <div class="col-md-4">

            <strong>Student</strong>

            <br>

            ${e.last_name},
            ${e.first_name}

          </div>

          <div class="col-md-4">

            <strong>Course</strong>

            <br>

            ${e.course_code}

          </div>

          <div class="col-md-4">

            <strong>School Year</strong>

            <br>

            ${e.school_year}

          </div>

        </div>

        <div class="table-responsive">

          <table class="table">

            <thead>

              <tr>

                <th>Subject Code</th>
                <th>Subject Name</th>
                <th>Units</th>

              </tr>

            </thead>

            <tbody>

              ${subjectRows}

            </tbody>

          </table>

        </div>

        <div class="fw-bold text-end">

          Total Units:
          ${units}

        </div>
      `);

      new bootstrap.Modal(
        document.getElementById("viewEnrollmentModal"),
      ).show();
    },
  ).always(function () {
    Loader.hide();
  });
});

//=======================================
// ENTER KEY EVENT
//=======================================
$(document).on("keypress", "#enrollmentStudentSearch", function (e) {
  if (e.which === 13) {
    $("#searchEnrollmentStudentsBtn").click();
  }
});

$(document).on("keypress", "#additionalSubjectSearch", function (e) {
  if (e.which === 13) {
    loadAdditionalSubjects();
  }
});

//=======================================
// SEARCH STUDENTS
//=======================================

$(document).on("click", "#searchEnrollmentStudentsBtn", function () {
  loadEnrollmentStudentsPage();
});

//=======================================
// SEARCH ADDITIONAL SUBJECTS
//=======================================

$(document).on("click", "#searchAdditionalSubjectsBtn", function () {
  loadAdditionalSubjects();
});

//=======================================
// LOAD CURRICULUM SUBJECTS
//=======================================
$(document).on("change", "#yearLevel, #trimester", function () {
  loadCurriculumSubjects();
});

//=======================================
// SUBJECT UNIT RECALCULATION
//=======================================

$(document).on("change", ".enrollmentSubject", function () {
  updateTotalUnits();
});

$(document).on("click", ".enrollmentPageBtn", function () {
  loadEnrollmentStudentsPage($(this).data("page"));
});

$(document).on("click", "#selectAllSubjects", function () {
  $(".enrollmentSubject").prop("checked", true);

  updateTotalUnits();
});

$(document).on("click", "#deselectAllSubjects", function () {
  $(".enrollmentSubject").prop("checked", false);

  updateTotalUnits();
});

//=======================================
// ADDITIONAL SUBJECTS LOADER
//=======================================

function loadAdditionalSubjects() {
  Loader.show("Loading subjects...");

  $.getJSON(
    "ajax/get_additional_subjects.php",
    {
      search: $("#additionalSubjectSearch").val(),
    },

    function (subjects) {
      let html = "";

      if (!subjects.length) {
        html = `
          <div class="text-muted">
            No subjects found.
          </div>
        `;
      }

      subjects.forEach((subject) => {
        html += `
    <div class="card border-0 shadow-sm mb-2">

      <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">

        <div class="flex-grow-1 pe-2">

          <strong>
            ${subject.subject_code}
          </strong>

          <div class="small text-muted">

            ${subject.subject_name}

          </div>

        </div>

        <button
          type="button"
          class="btn btn-sm btn-outline-primary addManualSubjectBtn"
          title="Add Subject"
          data-id="${subject.id}"
          data-code="${subject.subject_code}"
          data-name="${subject.subject_name}"
          data-units="${subject.units}">

          <i class="fa-solid fa-plus"></i>

        </button>

      </div>

    </div>
  `;
      });

      $("#additionalSubjectsList").html(html);
    },
  ).always(function () {
    Loader.hide();
  });
}

//=======================================
// ADDITIONAL SUBJECTS MANUAL BUTTON
//=======================================

$(document).on("click", ".addManualSubjectBtn", function () {
  const id = $(this).data("id");

  if ($(`.manualSubject[value="${id}"]`).length) {
    return;
  }

  $("#manualSubjectsContainer").append(`
  <div
      class="card border-success shadow-sm mb-2 manualSubjectRow"
      data-id="${id}">

      <div class="card-body py-2 px-3 d-flex justify-content-between align-items-center">

          <div>

              <input
                  type="hidden"
                  class="manualSubject"
                  name="manual_subject_ids[]"
                  value="${id}">

              <strong>

                  ${$(this).data("code")}

              </strong>

              <br>

<div class="small text-muted">
    ${$(this).data("name")}
</div>

          </div>

          <button
              type="button"
              class="btn btn-sm btn-outline-danger removeManualSubjectBtn">

              <i class="fa-solid fa-trash"></i>

          </button>

      </div>

  </div>
`);

  updateManualSubjectCount();
});

//=======================================
// ADDITIONAL SUBJECTS REMOVE BUTTON
//=======================================

$(document).on("click", ".removeManualSubjectBtn", function () {
  $(this).closest(".manualSubjectRow").remove();

  updateManualSubjectCount();
});
