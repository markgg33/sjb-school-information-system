//=======================================
// GLOBAL VARIABLES
//=======================================
let selectedEnrollmentStudent = null;
let selectedEnrollmentId = null;
let enrollmentEditMode = false;
let selectedStudentCourse = null;

let enrolledSubjectIds = [];

//=======================================
// TOGGLE ENROLLMENT
//=======================================

function toggleEnrollmentMode() {
  if (enrollmentEditMode) {
    $("#leftPanelTitle").text("Currently Enrolled Subjects");

    $("#curriculumSubjectsContainer").hide();

    $("#currentEnrollmentSubjects").show();

    //MAKE INPUT FIELDS IN EDIT READ ONLY
    $("#schoolYear").prop("readonly", true);
    $("#yearLevel").prop("disabled", true);
    $("#trimester").prop("disabled", true);
    $("#sectionId").prop("disabled", true);

    $("#enrollmentPageTitle").html(`
  <i class="fa-solid fa-pen-to-square me-2"></i>
  Update Enrollment
  <span class="badge bg-warning ms-2">
    EDIT MODE
  </span>
`);

    $("#saveEnrollmentBtn").html(`
    <i class="fa-solid fa-floppy-disk me-2"></i>
    Update Enrollment
`);
  } else {
    $("#leftPanelTitle").text("Curriculum Subjects");

    $("#currentEnrollmentSubjects").hide();

    $("#curriculumSubjectsContainer").show();

    $("#schoolYear").prop("readonly", false);
    $("#yearLevel").prop("disabled", false);
    $("#trimester").prop("disabled", false);
    $("#sectionId").prop("disabled", false);

    $("#enrollmentPageTitle").html(`
  <i class="fa-solid fa-user-check me-2"></i>
  New Enrollment
`);

    $("#saveEnrollmentBtn").html(`
    <i class="fa-solid fa-floppy-disk me-2"></i>
    Save Enrollment
`);
  }
}

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
// LOAD COURSE SECTIONS
//=======================================

function loadEnrollmentSections(selectedSection = "") {
  if (!selectedStudentCourse) {
    $("#sectionId").html(`
            <option value="">
                Select Section
            </option>
        `);

    return;
  }

  Loader.show("Loading sections...");

  $.getJSON(
    "ajax/get_course_sections_dropdown.php",
    {
      course_id: selectedStudentCourse,
      year_level: $("#yearLevel").val(),
    },
    function (rows) {
      let html = "";

      rows.forEach((section) => {
        html += `
                    <option
                        value="${section.id}"
                        ${selectedSection == section.id ? "selected" : ""}>

                        ${section.section_name}

                    </option>
                `;
      });

      if (rows.length === 0) {
        html = `
        <option value="">
            No Section Required
        </option>
    `;

        $("#sectionId").prop("disabled", true);
      } else {
        $("#sectionId").prop("disabled", false);
      }

      $("#sectionId").html(html);

      /*if (!selectedSection && rows.length) {
        $("#sectionId").val(rows[0].id);
      }*/

      loadCurriculumSubjects();
    },
  ).always(function () {
    Loader.hide();
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

  if (!studentId) {
    Loader.hide();
    return;
  }

  $.getJSON(
    "ajax/get_curriculum_subjects.php",
    {
      student_id: studentId,
      year_level: yearLevel,
      trimester: trimester,
      section_id: $("#sectionId").val(),
    },

    function (subjects) {
      let html = `
<option value="">
    Select Section
</option>
`;

      let totalUnits = 0;

      if (!subjects.length) {
        $("#curriculumSubjectsContainer").html(`
          <div class="alert alert-warning mb-0 text-center">

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

  $(".manualSubject").each(function () {
    total += Number($(this).data("units"));
  });

  $("#totalUnitsDisplay").text(total);
}

//=======================================
// UPDATE MANUAL SUBJECT COUNT
//=======================================

function updateManualSubjectCount() {
  const count = $(".manualSubject").length;

  $("#manualSubjectCount").text(count);

  const placeholder = `
    <div class="emptyManualPlaceholder text-muted small">
      No additional subjects selected.
    </div>
  `;

  if (count === 0) {
    if ($("#manualSubjectsContainer .emptyManualPlaceholder").length === 0) {
      $("#manualSubjectsContainer").html(placeholder);
    }
  } else {
    $("#manualSubjectsContainer .emptyManualPlaceholder").remove();
  }
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
  enrollmentEditMode = false;
  selectedEnrollmentId = null;
  selectedEnrollmentStudent = null;

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
              ${
                student.student_number
                  ? student.student_number
                  : `<span class="badge bg-warning text-uppercase">
            Not Assigned
         </span>`
              }
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
  //TOGGLE ENROLLMENT MODE
  toggleEnrollmentMode();

  if (enrollmentEditMode) {
    loadEnrollmentEdit();
    return;
  }

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
      selectedStudentCourse = student.course_id;
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

      loadEnrollmentSections();
      loadAdditionalSubjects();
    },
  ).always(function () {
    setTimeout(() => {
      Loader.hide();
    }, 500);
  });
}

//=======================================
// LOAD ENROLLMENT FOR EDITING
//=======================================

function loadEnrollmentEdit() {
  Loader.show("Loading enrollment...");

  $.getJSON(
    "ajax/get_enrollment_edit.php",
    {
      id: selectedEnrollmentId,
    },
    function (response) {
      const e = response.enrollment;

      enrolledSubjectIds = response.subjects.map((id) => id.toString());

      $("#enrollmentId").val(e.id);

      $("#selectedStudentId").val(e.student_id);

      $("#schoolYear").val(e.school_year);

      $("#yearLevel").val(e.year_level);

      selectedStudentCourse = e.course_id;

      $("#trimester").val(e.trimester);

      loadEnrollmentSections(e.section_id);

      $("#selectedStudentInfo").html(`
        <div class="row">

          <div class="col-md-4">
            <strong>Student No.</strong><br>
            ${e.student_number}
          </div>

          <div class="col-md-4">
            <strong>Name</strong><br>
            ${e.last_name}, ${e.first_name}
          </div>

          <div class="col-md-4">
            <strong>Course</strong><br>
            ${e.course_name}
          </div>

        </div>
      `);

      loadAdditionalSubjects();
      loadEnrollmentSubjects();
    },
  ).always(function () {
    Loader.hide();
  });
}

//=======================================
// LOAD ENROLLMENT SUBJECTS
//=======================================

function loadEnrollmentSubjects() {
  $.getJSON(
    "ajax/get_enrollment_subjects.php",
    {
      enrollment_id: selectedEnrollmentId,
    },

    function (subjects) {
      let html = "";

      if (!subjects.length) {
        $("#currentEnrollmentSubjects").html(`
          <div class="text-muted">
            No subjects found.
          </div>
        `);

        return;
      }

      let totalUnits = 0;

      subjects.forEach((subject) => {
        totalUnits += Number(subject.units);

        html += `
        <div
            class="card border-success shadow-sm mb-2 enrolledSubjectRow">

            <input
                type="hidden"
                class="enrolledSubject"
                name="subject_ids[]"
                value="${subject.subject_id}"
                data-units="${subject.units}">

            <div
                class="card-body py-2 px-3 d-flex justify-content-between align-items-center">

                <div>

                    <strong>
                        ${subject.subject_code}
                    </strong>

                    <div class="small text-muted">
                        ${subject.subject_name}
                    </div>

                </div>

                <button
                    type="button"
                    class="btn btn-sm btn-outline-danger removeEnrolledSubjectBtn">

                    <i class="fa-solid fa-trash"></i>

                </button>

            </div>

        </div>
    `;
      });

      html += `
    <hr>

    <div class="fw-bold">

        Total Units:
        <span id="editTotalUnitsDisplay">

            ${totalUnits}

        </span>

    </div>
`;

      $("#currentEnrollmentSubjects").html(html);
    },
  );
}

//=======================================
// REMOVE CURRENT ENROLLED SUBJECT
//=======================================

$(document).on("click", ".removeEnrolledSubjectBtn", function () {
  const row = $(this).closest(".enrolledSubjectRow");

  AlertService.confirm(
    "Remove Subject",
    "Remove this subject from the enrollment?",
  ).then((result) => {
    if (!result.isConfirmed) return;

    row.remove();
    updateEditTotalUnits();
  });
});

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
      search: $("#enrollmentSearch").val(),
      course_id: $("#enrollmentCourseFilter").val(),
      status: $("#enrollmentStatusFilter").val(),
    },
    function (response) {
      let html = "";

      if (!response.students.length) {
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

      response.students.forEach((s) => {
        const trimesterText =
          s.trimester == 1
            ? "1st Trimester"
            : s.trimester == 2
              ? "2nd Trimester"
              : "3rd Trimester";

        html += `
<tr>

    <td>
        ${
          s.student_number
            ? s.student_number
            : `<span class="badge bg-warning text-uppercase">
            Not Assigned
         </span>`
        }
    </td>

    <td>
        ${s.last_name},
        ${s.first_name}
    </td>

    <td>
        ${s.course_code} - ${s.course_name}

<div class="small text-primary fw-semibold">
    Section:
    ${s.section_name ?? "N/A"}
</div>
    </td>

    <td>
        ${s.enrollment_count}
    </td>

    <td>
        ${s.latest_enrollment ? s.latest_enrollment : "-"}
    </td>

    <td>

        <button
            class="btn btn-sm btn-outline-primary viewEnrollmentHistoryBtn"
            data-id="${s.id}">

            <i class="fa-solid fa-clock-rotate-left"></i>

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
  const title = enrollmentEditMode ? "Update Enrollment" : "Save Enrollment";

  let subjectCount;

  if (enrollmentEditMode) {
    subjectCount = $(".enrolledSubject").length + $(".manualSubject").length;
  } else {
    subjectCount =
      $(".enrollmentSubject:checked").length + $(".manualSubject").length;
  }

  AlertService.confirm(
    title,
    `You are about to save ${subjectCount} subject(s). Continue?`,
  ).then((result) => {
    if (!result.isConfirmed) return;

    Loader.show("Saving enrollment...");

    const saveUrl = enrollmentEditMode
      ? "ajax/update_enrollment_subjects.php"
      : "ajax/save_enrollment.php";

    // Allow disabled fields to be serialized
    $("#yearLevel").prop("disabled", false);
    $("#trimester").prop("disabled", false);
    $("#sectionId").prop("disabled", false);

    $.ajax({
      url: saveUrl,

      type: "POST",

      data: $("#saveEnrollmentForm").serialize(),

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          selectedEnrollmentStudent = null;
          selectedEnrollmentId = null;
          enrollmentEditMode = false;

          loadPage("enrollment");
        } else {
          AlertService.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to save enrollment.");
      },

      complete: function () {
        if (enrollmentEditMode) {
          $("#yearLevel").prop("disabled", true);
          $("#trimester").prop("disabled", true);
          $("#sectionId").prop("disabled", false);
        }

        Loader.hide();
      },
    });
  });
});

//=======================================
// DELETE ENROLLMENT
//=======================================

$(document).on("click", ".deleteEnrollmentBtn", function () {
  const enrollmentId = $(this).data("id");

  AlertService.confirm(
    "Delete Enrollment",
    `
    This will permanently delete:

    • Enrollment record
    • All enrolled subjects

    This action cannot be undone.
    `,
  ).then((result) => {
    if (!result.isConfirmed) return;

    Loader.show("Deleting enrollment...");

    $.ajax({
      url: "ajax/delete_enrollment.php",

      type: "POST",

      data: {
        enrollment_id: enrollmentId,
      },

      dataType: "json",

      success: function (response) {
        if (!response.success) {
          AlertService.error(response.message);
          return;
        }

        Notification.success(response.message);

        loadEnrollments();

        const historyModal = bootstrap.Modal.getInstance(
          document.getElementById("enrollmentHistoryModal"),
        );

        if (historyModal) {
          historyModal.hide();
        }

        $(".modal-backdrop").remove();
        $("body").removeClass("modal-open");
        $("body").css("padding-right", "");
      },

      error: function () {
        AlertService.error("Unable to delete enrollment.");
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

  const btn = $(this);

  if (btn.data("loading")) {
    return;
  }

  btn.data("loading", true);

  // CLOSE HISTORY MODAL FIRST
  const historyModal = bootstrap.Modal.getInstance(
    document.getElementById("enrollmentHistoryModal"),
  );

  if (historyModal) {
    historyModal.hide();
  }

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

      const historyModalEl = document.getElementById("enrollmentHistoryModal");

      if ($(historyModalEl).hasClass("show")) {
        $(historyModalEl).one("hidden.bs.modal", function () {
          new bootstrap.Modal(
            document.getElementById("viewEnrollmentModal"),
          ).show();
        });
      } else {
        new bootstrap.Modal(
          document.getElementById("viewEnrollmentModal"),
        ).show();
      }
    },
  ).always(function () {
    btn.data("loading", false);
    Loader.hide();
  });
});

//=======================================
// VIEW ENROLLMENT HISTORY
//=======================================

$(document).on("click", ".viewEnrollmentHistoryBtn", function () {
  selectedEnrollmentStudent = $(this).data("id");

  const studentId = $(this).data("id");

  Loader.show("Loading history...");

  $.getJSON(
    "ajax/get_student_enrollment_history.php",
    {
      student_id: studentId,
    },

    function (rows) {
      // FALLBACK FOR ENROLLMENT HISTORY
      if (!rows.length) {
        $("#enrollmentHistoryContent").html(`
    <div class="text-center py-5">

      <i class="fa-solid fa-user-graduate fa-3x text-muted mb-3"></i>

      <h6>No Enrollment History Found</h6>

      <p class="text-muted mb-0">
        This student does not have any enrollment records yet.
      </p>

    </div>
  `);

        new bootstrap.Modal(
          document.getElementById("enrollmentHistoryModal"),
        ).show();

        return;
      }

      let html = `
            <table class="table">

                <thead>
                    <tr>
                        <th>School Year</th>
                        <th>Year Level</th>
                        <th>Trimester</th>
                        <th>Section</th>
                        <th>Status</th>
                        <th width="140">Action</th>
                    </tr>
                </thead>

                <tbody>
        `;

      rows.forEach((e) => {
        html += `
            <tr>

    <td>${e.school_year}</td>

    <td>${e.year_level}</td>

<td>
    ${
      e.trimester == 1
        ? "1st Trimester"
        : e.trimester == 2
          ? "2nd Trimester"
          : "3rd Trimester"
    }
</td>

<td>${e.section_name ?? "N/A"}</td>

<td>${e.status}</td>

<td>

    <button
        class="btn btn-sm btn-outline-primary viewEnrollmentBtn"
        data-id="${e.id}"
        title="View Subjects">

        <i class="fa-solid fa-eye"></i>

    </button>

    <button
        class="btn btn-sm btn-outline-success editEnrollmentBtn"
        data-id="${e.id}"
        title="Edit">

        <i class="fa-solid fa-pen"></i>

    </button>

    <button
        class="btn btn-sm btn-outline-danger deleteEnrollmentBtn"
        data-id="${e.id}"
        title="Delete">

        <i class="fa-solid fa-trash"></i>

    </button>

</td>

</tr>
`;
      });

      html += `
                </tbody>
            </table>
        `;

      $("#enrollmentHistoryContent").html(html);

      new bootstrap.Modal(
        document.getElementById("enrollmentHistoryModal"),
      ).show();
    },
  ).always(() => Loader.hide());
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

$(document).on("keypress", "#enrollmentSearch", function (e) {
  if (e.which === 13) {
    loadEnrollments();
  }
});

//=======================================
// SEARCH STUDENTS
//=======================================

$(document).on("click", "#searchEnrollmentStudentsBtn", function () {
  loadEnrollmentStudentsPage();
});

$(document).on("click", "#searchEnrollmentBtn", function () {
  loadEnrollments();
});

//=======================================
// SEARCH ADDITIONAL SUBJECTS
//=======================================

$(document).on("click", "#searchAdditionalSubjectsBtn", function () {
  loadAdditionalSubjects();
});

//=======================================
// LOAD ENROLLMENT SUBJECTS
//=======================================
$(document).on("change", "#yearLevel", function () {
  loadEnrollmentSections();
});

$(document).on("change", "#sectionId,#trimester", function () {
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

  if (
    $(`.manualSubject[value="${id}"]`).length ||
    $(`.enrolledSubject[value="${id}"]`).length
  ) {
    AlertService.warning("Subject already exists in the enrollment.");

    return;
  }

  $("#manualSubjectsContainer .emptyManualPlaceholder").remove();

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
                  value="${id}"
                  data-units="${$(this).data("units")}">

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

  if (enrollmentEditMode) {
    updateEditTotalUnits();
  } else {
    updateTotalUnits();
  }
});

//=======================================
// ADDITIONAL SUBJECTS REMOVE BUTTON
//=======================================

$(document).on("click", ".removeManualSubjectBtn", function () {
  $(this).closest(".manualSubjectRow").remove();

  updateManualSubjectCount();

  if (enrollmentEditMode) {
    updateEditTotalUnits();
  } else {
    updateTotalUnits();
  }
});

//=======================================
// EDIT ENROLLMENT BUTTON
//=======================================

$(document).on("click", ".editEnrollmentBtn", function () {
  selectedEnrollmentId = $(this).data("id");

  enrollmentEditMode = true;

  const modal = bootstrap.Modal.getInstance(
    document.getElementById("enrollmentHistoryModal"),
  );

  if (modal) {
    modal.hide();
  }

  $(".modal-backdrop").remove();
  $("body").removeClass("modal-open");
  $("body").css("padding-right", "");

  setTimeout(() => {
    loadPage("enrollment-details");
  }, 300);
});

//=======================================
// HELPERS
//=======================================

function updateEditTotalUnits() {
  let total = 0;

  $(".enrolledSubject").each(function () {
    total += Number($(this).data("units"));
  });

  $(".manualSubject").each(function () {
    total += Number($(this).data("units"));
  });

  $("#editTotalUnitsDisplay").text(total);
}
