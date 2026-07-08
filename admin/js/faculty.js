//=======================================
// SUBJECT ASSIGNMENT
//=======================================

let selectedFacultySubjects = [];
let facultySubjectSelectionsCache = {};

function getAssignmentContextKey() {
  const facultyId = $("#assignmentFacultyId").val();
  const schoolYear = $("#assignmentSchoolYear").val();
  const trimester = $("#assignmentTrimester").val();
  const course = $("#assignmentCourse").val();
  const year = $("#assignmentYearLevel").val();

  if (!facultyId || !schoolYear || !trimester || !course || !year) {
    return null;
  }

  const section = $("#assignmentSection").val();

  /*if (!section) {
    return null;
  }*/

  return `${facultyId}|${schoolYear}|${trimester}|${course}|${year}|${section}`;
}

function saveCurrentFacultySelections() {
  const key = getAssignmentContextKey();

  if (!key) {
    return;
  }

  facultySubjectSelectionsCache[key] = [...selectedFacultySubjects];
}

function getPendingFacultyAssignments() {
  saveCurrentFacultySelections();

  const facultyId = $("#assignmentFacultyId").val();
  const schoolYear = $("#assignmentSchoolYear").val();
  const trimester = $("#assignmentTrimester").val();
  const prefix = `${facultyId}|${schoolYear}|${trimester}|`;
  const assignments = [];

  Object.keys(facultySubjectSelectionsCache).forEach((key) => {
    if (!key.startsWith(prefix)) {
      return;
    }

    const [, , , courseId, yearLevel, sectionId] = key.split("|");

    assignments.push({
      course_id: courseId,

      year_level: yearLevel,

      section_id: sectionId === "NULL" ? null : sectionId,

      subjects: facultySubjectSelectionsCache[key],
    });
  });

  return assignments;
}

function countPendingFacultySubjects(assignments) {
  return assignments.reduce((total, assignment) => {
    return total + (assignment.subjects?.length || 0);
  }, 0);
}

//=======================================
// LOAD COURSE CHECKBOXES
//=======================================

function loadFacultyCourses() {
  $.getJSON("ajax/get_courses_dropdown.php", function (courses) {
    let html = "";

    let filterOptions = '<option value="">All Courses</option>';

    courses.forEach((course) => {
      html += `
          <div class="form-check">

            <input
                type="checkbox"
                class="form-check-input facultyCourse"
                name="courses[]"
            value="${course.id}">

            <label class="form-check-label">

              ${course.course_code}
              -
              ${course.course_name}

            </label>

          </div>
        `;

      filterOptions += `
          <option value="${course.id}">
            ${course.course_code} -  ${course.course_name}
          </option>
        `;
    });

    $("#facultyCoursesContainer").html(html);

    $("#facultyCourseFilter").html(filterOptions);
  });
}

function updateFacultyCourseCount() {
  const count = $(".facultyCourse:checked").length;

  $("#facultyCourseDropdownText").text(
    count > 0 ? `${count} Course(s) Selected` : "Select Courses",
  );
}

$(document).on("change", ".facultyCourse", function () {
  updateFacultyCourseCount();
});

//=======================================
// FACULTY TABLE LOADER
//=======================================

function loadFaculty(page = 1) {
  Loader.show("Loading faculty...");

  $.getJSON(
    "ajax/get_faculty.php",
    {
      search: $("#facultySearch").val(),
      status: $("#facultyStatusFilter").val(),
      course_id: $("#facultyCourseFilter").val(),
      page: page,
    },
    function (response) {
      let html = "";

      if (!response.faculty.length) {
        html = `
    <tr>
      <td colspan="6" class="text-center py-5">

        <div class="empty-state">

          <i class="fa-solid fa-user-graduate empty-icon"></i>

          <h6 class="mt-3 mb-1">
            No faculty found
          </h6>

          <p class="text-muted mb-0">
            No matching faculty were found.
          </p>

        </div>

      </td>
    </tr>
  `;

        $("#facultyTableBody").html(html);
        $("#facultyPagination").html("");

        return;
      }

      response.faculty.forEach((faculty) => {
        const statusBadge =
          faculty.status === "active"
            ? '<span class="badge bg-success text-uppercase">Active</span>'
            : '<span class="badge bg-secondary text-uppercase">Inactive</span>';

        const courses = faculty.courses.length
          ? faculty.courses
              .map(
                (c) =>
                  `<span class="badge bg-primary me-1">
                      ${c.course_code}
                    </span>`,
              )
              .join("")
          : `<span class="badge bg-warning">
                No Courses
              </span>`;

        html += `
          <tr>

             <td>
  ${
    faculty.employee_number
      ? faculty.employee_number
      : `<span class="badge bg-warning text-uppercase">
            Not Assigned
         </span>`
  }
</td>

            <td>
              ${faculty.last_name},
              ${faculty.first_name}
            </td>

            <td>
              ${faculty.gender}
            </td>

            <td>
              ${courses}
            </td>

            <td>
              ${statusBadge}
            </td>

            <td>

<button
    class="btn btn-sm btn-outline-primary editFacultyBtn"
    data-id="${faculty.id}">

    <i class="fa-solid fa-pencil"></i>

</button>

<button
    class="btn btn-sm btn-outline-success assignSubjectsBtn"
    data-id="${faculty.id}"
    data-name="${faculty.first_name} ${faculty.last_name}">

    <i class="fa-solid fa-book-open-reader"></i>

</button>

<button
    class="btn btn-sm btn-outline-warning resetFacultyPasswordBtn"
    data-id="${faculty.id}">

    <i class="fa-solid fa-key"></i>

</button>

            </td>

          </tr>
        `;
      });

      $("#facultyTableBody").html(html);

      renderFacultyPagination(response.current_page, response.total_pages);
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

function renderFacultyPagination(currentPage, totalPages) {
  let html = "";

  if (totalPages <= 1) {
    $("#facultyPagination").html("");
    return;
  }

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button
        class="btn btn-sm ${
          i === currentPage ? "btn-primary" : "btn-outline-primary"
        }
        facultyPageBtn mx-1"
        data-page="${i}">

        ${i}

      </button>
    `;
  }

  $("#facultyPagination").html(html);
}

$(document).on("click", ".facultyPageBtn", function () {
  loadFaculty($(this).data("page"));
});

//=======================================
// SEARCH EVENT
//=======================================

$(document).on("click", "#applyFacultyFiltersBtn", function () {
  loadFaculty(1);
});

//=======================================
// OPEN MODAL
//=======================================

$(document).on("click", "#btnAddFaculty", function () {
  $("#facultyForm")[0].reset();

  $("#facultyId").val("");

  $(".facultyCourse").prop("checked", false);

  updateFacultyCourseCount();

  $("#facultyModal .modal-title").text("Add Faculty");

  new bootstrap.Modal(document.getElementById("facultyModal")).show();
});

//=======================================
// SAVE FACULTY
//=======================================

$(document).on("click", "#saveFacultyBtn", function () {
  const formData = new FormData(document.getElementById("facultyForm"));

  /*$(".facultyCourse:checked").each(function () {
    formData.append("courses[]", $(this).val());
  });*/

  $.ajax({
    url: "ajax/save_faculty.php",

    type: "POST",

    data: formData,

    processData: false,

    contentType: false,

    dataType: "json",

    success: function (response) {
      if (response.success) {
        Notification.success(response.message);

        bootstrap.Modal.getInstance(
          document.getElementById("facultyModal"),
        ).hide();

        loadFaculty();
      } else {
        AlertService.error(response.message);
      }
    },

    error: function () {
      AlertService.error("Unable to connect to server.");
    },
  });
});

//=======================================
// EDIT FACULTY
//=======================================

$(document).on("click", ".editFacultyBtn", function () {
  const id = $(this).data("id");

  Loader.show("Loading faculty...");

  $.getJSON(
    "ajax/get_single_faculty.php",
    { id: id },

    function (faculty) {
      $("#facultyId").val(faculty.id);

      $('[name="employee_number"]').val(faculty.employee_number);

      $('[name="email"]').val(faculty.email);

      $('[name="first_name"]').val(faculty.first_name);

      $('[name="middle_name"]').val(faculty.middle_name);

      $('[name="last_name"]').val(faculty.last_name);

      $('[name="gender"]').val(faculty.gender);

      $('[name="contact_number"]').val(faculty.contact_number);

      $('[name="address"]').val(faculty.address);

      $('[name="status"]').val(faculty.status);

      $(".facultyCourse").prop("checked", false);

      faculty.courses.forEach((id) => {
        $(`.facultyCourse[value="${id}"]`).prop("checked", true);
      });

      updateFacultyCourseCount();

      $("#facultyModal .modal-title").text("Edit Faculty");

      new bootstrap.Modal(document.getElementById("facultyModal")).show();
    },
  ).always(function () {
    Loader.hide();
  });
});

//=======================================
// RESET PASSWORD
//=======================================

$(document).on("click", ".resetFacultyPasswordBtn", function () {
  const id = $(this).data("id");

  AlertService.confirm(
    "Reset Password",
    "Reset password to Faculty123! ?",
  ).then((result) => {
    if (!result.isConfirmed) return;

    $.post(
      "ajax/reset_faculty_password.php",
      { id: id },

      function (response) {
        if (response.success) {
          Notification.success(response.message);
        } else {
          AlertService.error(response.message);
        }
      },

      "json",
    );
  });
});

//=======================================
// CHECKBOX HANDLER
//=======================================

$(document).on("change", ".assignmentSubject", function () {
  const id = $(this).val();

  if ($(this).is(":checked")) {
    if (!selectedFacultySubjects.includes(id)) {
      selectedFacultySubjects.push(id);
    }
  } else {
    selectedFacultySubjects = selectedFacultySubjects.filter(
      (subjectId) => subjectId !== id,
    );
  }

  saveCurrentFacultySelections();
});

//=======================================
// LOAD ASSIGNMENT SCHOOL YEARS
//=======================================

function loadAssignmentSchoolYears() {
  $.getJSON("ajax/get_assignment_school_years.php", function (rows) {
    let html = "";

    rows.forEach(function (year) {
      html += `
                    <option value="${year}">
                        ${year}
                    </option>
                `;
    });

    $("#assignmentSchoolYear").html(html);

    $("#assignmentSchoolYear").prop("selectedIndex", 0);
  });
}

//=======================================
// LOAD SUBJECTS FOR ASSIGNMENT
//=======================================

function loadAssignmentSubjects() {
  const course = $("#assignmentCourse").val();
  const year = $("#assignmentYearLevel").val();
  const trimester = $("#assignmentTrimester").val();
  const schoolYear = $("#assignmentSchoolYear").val();

  if (!course || !year || !trimester || !schoolYear) {
    $("#assignmentSubjectsContainer").html(`
      <div class="text-center py-4 text-muted">
        Select course, year level, and trimester first.
      </div>
    `);
    return;
  }

  Loader.show("Loading subjects...");

  $.when(
    $.getJSON("ajax/get_assignable_subjects.php", {
      course_id: course,
      year_level: year,
      trimester: trimester,
      section_id: $("#assignmentSection").val() || "",
    }),

    $.getJSON("ajax/get_faculty_subjects.php", {
      faculty_id: $("#assignmentFacultyId").val(),
      school_year: schoolYear,
      trimester: trimester,
      course_id: course,
      year_level: year,
      section_id: $("#assignmentSection").val() || "",
    }),
  )
    .done(function (subjectResponse, assignedResponse) {
      const subjects = subjectResponse[0];
      const assigned = assignedResponse[0].map((id) => id.toString());
      const contextKey = getAssignmentContextKey();
      const cached =
        contextKey && contextKey in facultySubjectSelectionsCache
          ? facultySubjectSelectionsCache[contextKey]
          : null;

      if (cached !== null) {
        selectedFacultySubjects = cached.map((id) => id.toString());
      } else if (selectedFacultySubjects.length === 0) {
        selectedFacultySubjects = [...assigned];

        if (contextKey) {
          facultySubjectSelectionsCache[contextKey] = [
            ...selectedFacultySubjects,
          ];
        }
      }

      let html = "";

      if (!subjects.length) {
        html = `
          <div class="text-center py-4 text-muted">
            No subjects found in curriculum for this selection.
          </div>
        `;
      } else {
        subjects.forEach((subject) => {
          const id = subject.id.toString();
          const checked =
            assigned.includes(id) || selectedFacultySubjects.includes(id)
              ? "checked"
              : "";

          html += `
<label class="assignment-card">

<input
type="checkbox"
class="assignmentSubject form-check-input"
value="${subject.id}"
${checked}>

<div class="flex-grow-1 ms-3">

<div class="fw-semibold">
${subject.subject_code}
</div>

<small class="text-muted">
${subject.subject_name}
</small>

</div>

<span class="badge bg-primary">
${subject.units} Units
</span>

</label>
`;
        });
      }

      $("#assignmentSubjectsContainer").html(html);
    })
    .always(function () {
      Loader.hide();
    });
}
//=======================================
// LOAD ASSIGNMENT COURSES
//=======================================

function loadAssignmentCourses() {
  $.getJSON(
    "ajax/get_courses_dropdown.php",

    function (courses) {
      let html = `
                <option value="">
                    Select Course
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

      $("#assignmentCourse").html(html);
    },
  );
}

//=======================================
// LOAD ASSIGNMENT COURSES SECTIONS
//=======================================

function loadAssignmentSections() {
  const course = $("#assignmentCourse").val();
  const year = $("#assignmentYearLevel").val();

  if (!course || !year) {
    $("#assignmentSection").html(`
            <option value="">
                Select Section
            </option>
        `);

    return;
  }

  $.getJSON(
    "ajax/get_course_sections_dropdown.php",

    {
      course_id: course,
      year_level: year,
    },

    function (rows) {
      let html = `
                <option value="">
                    Select Section
                </option>
            `;

      rows.forEach((section) => {
        html += `
                    <option value="${section.id}">
                        ${section.section_name}
                    </option>
                `;
      });

      $("#assignmentSection").html(html);
    },
  );
}

//=======================================
// OPEN SUBJECT ASSIGNMENT
//=======================================

$(document).on("click", ".assignSubjectsBtn", function () {
  selectedFacultySubjects = [];
  facultySubjectSelectionsCache = {};

  $("#assignmentFacultyId").val($(this).data("id"));
  $("#assignmentFacultyName").val($(this).data("name"));
  $("#assignmentTrimester").val("1");
  $("#assignmentYearLevel").val("1");

  const $modal = $("#subjectAssignmentModal").appendTo("body");
  const modal = bootstrap.Modal.getOrCreateInstance($modal[0]);

  Loader.show("Loading assignment...");

  $.when(
    $.getJSON("ajax/get_assignment_school_years.php"),
    $.getJSON("ajax/get_courses_dropdown.php"),
  )
    .done(function (yearsResponse, coursesResponse) {
      const years = yearsResponse[0];
      const courses = coursesResponse[0];

      let yearsHtml = "";
      years.forEach((year) => {
        yearsHtml += `<option value="${year}">${year}</option>`;
      });
      $("#assignmentSchoolYear").html(yearsHtml);

      let coursesHtml = '<option value="">Select Course</option>';
      courses.forEach((course) => {
        coursesHtml += `
          <option value="${course.id}">
            ${course.course_code} - ${course.course_name}
          </option>
        `;
      });
      $("#assignmentCourse").html(coursesHtml);

      loadAssignmentSections();
      loadAssignmentSubjects();
      loadTeachingLoad();
    })
    .always(function () {
      Loader.hide();
    });

  modal.show();
});

$(document).on(
  "focus",
  "#assignmentCourse, #assignmentYearLevel, #assignmentTrimester, #assignmentSchoolYear",
  function () {
    saveCurrentFacultySelections();
  },
);

$(document).on("change", "#assignmentCourse,#assignmentYearLevel", function () {
  loadAssignmentSections();

  selectedFacultySubjects = [];

  loadAssignmentSubjects();

  loadTeachingLoad();
});

$(document).on(
  "change",
  "#assignmentSection,#assignmentTrimester,#assignmentSchoolYear",
  function () {
    selectedFacultySubjects = [];

    loadAssignmentSubjects();

    loadTeachingLoad();
  },
);

//=======================================
// SAVE ASSIGN SUBJECTS
//=======================================

$(document).on("click", "#saveSubjectAssignmentBtn", function () {
  const facultyId = $("#assignmentFacultyId").val();
  const schoolYear = $("#assignmentSchoolYear").val();
  const trimester = $("#assignmentTrimester").val();
  const assignments = getPendingFacultyAssignments();

  if (!facultyId || !schoolYear || !trimester) {
    AlertService.warning("Please complete all required fields.");
    return;
  }

  if (!assignments.length) {
    AlertService.warning(
      "Please select at least one course, year level, and section.",
    );
    return;
  }

  /*if (!assignments.length) {
    AlertService.warning("Please select a course and assign subjects.");
    return;
  }

  if (countPendingFacultySubjects(assignments) === 0) {
    AlertService.warning("Please assign at least one subject.");
    return;
  }*/

  AlertService.saveConfirm("subject assignment").then(function (result) {
    if (!result.isConfirmed) return;

    Loader.show("Saving assignments...");

    $.ajax({
      url: "ajax/save_faculty_subjects.php",

      type: "POST",

      dataType: "json",

      data: {
        faculty_id: facultyId,
        school_year: schoolYear,
        trimester: trimester,
        assignments: assignments,
      },

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          loadTeachingLoad();

          bootstrap.Modal.getInstance(
            document.getElementById("subjectAssignmentModal"),
          ).hide();
        } else {
          AlertService.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to connect to server.");
      },

      complete: function () {
        Loader.hide();
      },
    });
  });
});

//=======================================
// TEACHING LOAD DISPLAY
//=======================================

function loadTeachingLoad() {
  $.getJSON(
    "ajax/get_faculty_teaching_load.php",

    {
      faculty_id: $("#assignmentFacultyId").val(),

      school_year: $("#assignmentSchoolYear").val(),

      trimester: $("#assignmentTrimester").val(),
    },

    function (rows) {
      let html = "";

      if (!rows.length) {
        html = `
            <div class="text-muted">

                No assigned subjects.

            </div>
        `;
      } else {
        let currentCourse = "";
        let currentYear = "";
        let currentSection = "";

        rows.forEach(function (row) {
          if (
            currentCourse !== row.course_code ||
            currentYear !== row.year_level ||
            currentSection !== row.section_name
          ) {
            currentCourse = row.course_code;
            currentYear = row.year_level;
            currentSection = row.section_name ?? "No Section";

            html += `

<div class="mt-3 fw-bold text-primary">

    ${currentCourse}
    • Year ${currentYear}

    <br>

    <small class="text-secondary">

        ${currentSection}

    </small>

</div>

`;
          }

          html += `

        <div class="d-flex justify-content-between border-bottom py-2">

            <span>

                ${row.subject_code}

            </span>

            <small class="text-muted">

                ${row.subject_name}

            </small>

        </div>

    `;
        });
      }

      $("#facultyTeachingLoad").html(html);
    },
  );
}
