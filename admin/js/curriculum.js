//=======================================
// GLOBAL VARIABLES
//=======================================
let selectedSubjects = [];
let subjectUnitsMap = {};

//=======================================
// LOAD COURSES
//=======================================

function loadCurriculumCourses() {
  $.ajax({
    url: "ajax/get_courses_dropdown.php",
    type: "GET",
    dataType: "json",

    success: function (courses) {
      let html = '<option value="">Select Course</option>';

      courses.forEach((course) => {
        html += `
            <option value="${course.id}">
                ${course.course_code} - ${course.course_name}
            </option>
            `;
      });

      $("#courseSelect").html(html);
    },
  });
}

//=======================================
// LOAD SUBJECTS
//=======================================

function loadSubjectsChecklist(search = "", units = "", page = 1) {
  const courseId = $("#courseSelect").val();
  const yearLevel = $("#curriculumYearLevel").val();
  const trimester = $("#curriculumTrimester").val();

  //Loading.show("Loading subjects...");

  $.when(
    $.getJSON("ajax/get_subjects_dropdown.php", {
      search,
      units,
      page,
    }),

    $.getJSON("ajax/get_curriculum_subject_ids.php", {
      course_id: courseId,
      year_level: yearLevel,
      trimester: trimester,
    }),
  ).done(function (subjectsResponse, assignedResponse) {
    const response = subjectsResponse[0];

    const subjects = response.subjects;

    const assigned = assignedResponse[0];

    if (page === 1 && selectedSubjects.length === 0) {
      selectedSubjects = assigned.map((id) => id.toString());
    }

    renderCurriculumPagination(response.current_page, response.total_pages);

    let html = "";

    if (!subjects.length) {
      $("#subjectsContainer").html(`
        <div class="text-center py-5 text-muted">

        No subjects found.

        </div>
    `);

      $("#curriculumPagination").html("");

      return;
    }

    subjects.forEach((subject) => {
      const checked =
        assigned.includes(subject.id.toString()) ||
        selectedSubjects.includes(subject.id.toString())
          ? "checked"
          : "";

      html += `
<div class="subject-item">

    <input
      type="checkbox"
      class="form-check-input curriculum-subject"
      value="${subject.id}"
      ${checked}>

    <label class="subject-inline">

        <span class="subject-code">
            ${subject.subject_code}
        </span>

        <span class="subject-name">
            ${subject.subject_name}
        </span>

        <span class="subject-units">
            ${subject.units} Units
        </span>

    </label>

</div>
`;
    });

    $("#subjectsContainer").html(html);

    updateSelectedUnits();
  });
  /*.always(function () {
      Loading.hide();
    });*/
}

//=======================================
// LOAD SUBJECTS BUTTON
//=======================================

$(document).on("click", "#loadSubjectsBtn", function () {
  selectedSubjects = [];
  subjectUnitsMap = {};

  const courseId = $("#courseSelect").val();
  const yearLevel = $("#curriculumYearLevel").val();
  const trimester = $("#curriculumTrimester").val();

  if (!courseId || !yearLevel || !trimester) {
    AlertService.warning("Please complete all selections.");

    return;
  }

  loadCurrentCurriculum();
});

//=======================================
// LOAD CURRICULUM
//=======================================

function loadCurrentCurriculum() {
  Loader.show("Loading curriculum...");
  /*subjectUnitsMap = {};
  selectedSubjects = [];*/

  const courseId = $("#courseSelect").val();
  const yearLevel = $("#curriculumYearLevel").val();
  const trimester = $("#curriculumTrimester").val();

  if (!courseId || !yearLevel || !trimester) {
    return;
  }

  $.ajax({
    url: "ajax/get_curriculum.php",

    type: "GET",

    dataType: "json",

    data: {
      course_id: courseId,
      year_level: yearLevel,
      trimester: trimester,
    },

    success: function (subjects) {
      selectedSubjects = [];
      subjectUnitsMap = {};
      if (subjects.length === 0) {
        updateSelectedUnits();
        $("#curriculumTableContainer").html(`
            <div class="text-center py-5 text-muted">
                No subjects assigned yet.
            </div>
            `);

        return;
      }

      let totalUnits = 0;

      let html = `
            <div class="mb-3">

                <strong>
                    Total Subjects:
                </strong>

                ${subjects.length}

                &nbsp; | &nbsp;

                <strong>
                    Total Units:
                </strong>

                <span id="totalUnits">
        `;

      subjects.forEach((subject) => {
        totalUnits += parseInt(subject.units);

        const id = subject.subject_id.toString();

        subjectUnitsMap[id] = parseInt(subject.units);

        if (!selectedSubjects.includes(id)) {
          selectedSubjects.push(id);
        }
      });

      html += `${totalUnits}</span>

            </div>

            <div class="table-responsive">

            <table class="table align-middle">

                <thead>

                    <tr>
                        <th>Code</th>
                        <th>Subject</th>
                        <th>Units</th>
                        <th width="80">Action</th>
                    </tr>

                </thead>

                <tbody>
        `;

      subjects.forEach((subject) => {
        html += `
            <tr>

                <td>${subject.subject_code}</td>

                <td>${subject.subject_name}</td>

                <td>${subject.units}</td>

                <td>

                    <button
                    class="btn btn-sm btn-outline-danger removeCurriculumBtn"
                    data-id="${subject.id}">

                    <i class="fa-solid fa-trash"></i>

                    </button>

                </td>

            </tr>
            `;
      });

      html += `
                </tbody>
            </table>
            </div>
        `;

      $("#curriculumTableContainer").html(html);
    },

    error: function () {
      AlertService.error("Unable to load curriculum.");
    },

    complete: function () {
      loadSubjectsChecklist(
        $("#curriculumSubjectSearch").val(),
        $("#curriculumUnitsFilter").val(),
        1,
      );

      setTimeout(() => {
        Loader.hide();
      }, 500);
    },
  });
}

//=======================================
// SAVE CURRICULUM
//=======================================

$(document).on("click", "#saveCurriculumBtn", function () {
  /*let subjects = [];

  $(".curriculum-subject:checked").each(function () {
    subjects.push($(this).val());
  });*/

  let subjects = [...selectedSubjects];

  if (subjects.length === 0) {
    AlertService.warning("Please select subjects.");

    return;
  }

  $.getJSON(
    "ajax/check_curriculum_exists.php",
    {
      course_id: $("#courseSelect").val(),
      year_level: $("#curriculumYearLevel").val(),
      trimester: $("#curriculumTrimester").val(),
    },
    function (check) {
      const proceedSave = () => {
        $.ajax({
          url: "ajax/save_curriculum.php",

          type: "POST",

          dataType: "json",

          data: {
            course_id: $("#courseSelect").val(),
            year_level: $("#curriculumYearLevel").val(),
            trimester: $("#curriculumTrimester").val(),
            subjects: subjects,
          },

          success: function (response) {
            if (response.success) {
              selectedSubjects = [...subjects];

              Notification.success(response.message);

              /*setTimeout(() => {
                loadCurrentCurriculum();

                loadSubjectsChecklist(
                  $("#curriculumSubjectSearch").val(),
                  $("#curriculumUnitsFilter").val(),
                  1,
                );
              }, 500);*/
              loadCurrentCurriculum();
            } else {
              AlertService.error(response.message);
            }
          },

          error: function () {
            AlertService.error("Unable to connect to server.");
          },
        });
      };

      if (check.exists) {
        AlertService.confirm(
          "Replace Curriculum",
          `This curriculum already contains ${check.count} subject(s). Saving will replace the existing curriculum. Continue?`,
        ).then((result) => {
          if (!result.isConfirmed) return;

          proceedSave();
        });
      } else {
        AlertService.confirm("Save Curriculum", "Save selected subjects?").then(
          (result) => {
            if (!result.isConfirmed) return;

            proceedSave();
          },
        );
      }
    },
  );
});

//=======================================
// DELETE CURRICULUM SUBJECT
//=======================================

$(document).on("click", ".removeCurriculumBtn", function () {
  const id = $(this).data("id");

  AlertService.deleteConfirm("subject").then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: "ajax/delete_curriculum_subject.php",

      type: "POST",

      dataType: "json",

      data: { id },

      success: function (response) {
        if (response.success) {
          selectedSubjects = selectedSubjects.filter(
            (id) => id !== response.subject_id.toString(),
          );

          Notification.success(response.message);

          updateSelectedUnits();

          /*loadSubjectsChecklist(
            $("#curriculumSubjectSearch").val(),
            $("#curriculumUnitsFilter").val(),
            1,
          );*/

          loadCurrentCurriculum();
        } else {
          AlertService.error(response.message);
        }
      },
    });
  });
});

//=======================================
// SEARCH BUTTON FUNCTION
//=======================================

$(document).on("click", "#filterCurriculumSubjectsBtn", function () {
  loadSubjectsChecklist(
    $("#curriculumSubjectSearch").val(),
    $("#curriculumUnitsFilter").val(),
    1,
  );
});
//=======================================
// UNIT CALCULATOR
//=======================================

function updateSelectedUnits() {
  let total = 0;

  selectedSubjects.forEach((id) => {
    total += Number(subjectUnitsMap[id] || 0);
  });

  $("#selectedUnits").text(total);
}

$(document).on("change", ".curriculum-subject", function () {
  const id = $(this).val();

  if ($(this).is(":checked")) {
    if (!selectedSubjects.includes(id)) {
      selectedSubjects.push(id);
    }
  } else {
    selectedSubjects = selectedSubjects.filter((subjectId) => subjectId !== id);
  }

  updateSelectedUnits();
});

//=======================================
// RENDER PAGINATIONS
//=======================================

function renderCurriculumPagination(currentPage, totalPages) {
  let html = "";

  if (totalPages <= 1) {
    $("#curriculumPagination").html("");

    return;
  }

  for (let i = 1; i <= totalPages; i++) {
    html += `
        <button
            class="btn btn-sm ${
              i === currentPage ? "btn-primary" : "btn-outline-primary"
            }
            curriculumPageBtn mx-1"
            data-page="${i}">

            ${i}

        </button>
        `;
  }

  $("#curriculumPagination").html(html);
}

// CLICK EVENT
$(document).on("click", ".curriculumPageBtn", function () {
  loadSubjectsChecklist(
    $("#curriculumSubjectSearch").val(),
    $("#curriculumUnitsFilter").val(),
    $(this).data("page"),
  );
});
