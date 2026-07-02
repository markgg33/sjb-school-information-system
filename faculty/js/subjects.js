//=======================================
// LOAD MY SUBJECTS
//=======================================

function loadMySubjects() {
  Loader.show("Loading subjects...");

  $.getJSON(
    "ajax/get_my_subjects.php",

    function (rows) {
      let html = "";

      if (!rows.length) {
        html = `

                    <div class="col-12">

                        <div class="dashboard-card text-center py-5">

                            <i class="fa-solid fa-book-open fa-3x text-muted mb-3"></i>

                            <h5>

                                No Assigned Subjects

                            </h5>

                            <p class="text-muted mb-0">

                                You currently have no teaching assignments.

                            </p>

                        </div>

                    </div>

                `;
      }

      rows.forEach(function (subject) {
        console.log(subject);
        html += `

<div class="col-lg-6">

    <div class="dashboard-card h-100">

        <div class="d-flex justify-content-between">

            <div>

                <h5>

                    ${subject.subject_code}

                </h5>

                <div class="fw-semibold">

                    ${subject.subject_name}

                </div>

                <div class="text-muted mt-2">

                    ${subject.course_code}

                    •

                    Year ${subject.year_level}

                </div>

                <div class="text-muted">

                    ${subject.school_year}

                    •

                    Trimester ${subject.trimester}

                </div>

            </div>

            <div class="text-end">

                <div class="badge bg-primary">

                    ${subject.students}

                    Students

                </div>

            </div>

        </div>

        <hr>

        <button
    class="module-action-btn openWorkspaceBtn"

    data-faculty-subject="${subject.faculty_subject_id}"

data-subject="${subject.subject_id}"
    data-course="${subject.course_id}"
    data-year="${subject.year_level}"
    data-school="${subject.school_year}"
    data-trimester="${subject.trimester}"

    data-subject-code="${subject.subject_code}"
    data-subject-name="${subject.subject_name}"
    data-course-code="${subject.course_code}"
    data-course-name="${subject.course_name}">

            <div class="btn-icon">

                <i class="fa-solid fa-arrow-right"></i>

            </div>

            <span>

                Open Workspace

            </span>

        </button>

    </div>

</div>

`;
      });

      $("#facultySubjectsContainer").html(html);
    },
  ).always(function () {
    Loader.hide();
  });
}

//=======================================
// LOAD WORKSPACE
//=======================================

function loadSubjectWorkspace() {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  if (!workspace) {
    loadPage("my-subjects");

    return;
  }

  $("#workspaceHeader").html(`

<div class="dashboard-card">

    <button
        class="btn btn-outline-secondary mb-3"
        id="backToSubjects">

        <i class="fa-solid fa-arrow-left"></i>

        Back to My Subjects

    </button>

    <h2 class="mb-2">

        ${workspace.subject_code}

    </h2>

    <h5 class="text-muted">

        ${workspace.subject_name}

    </h5>

    <div class="mt-3">

        <span class="badge bg-primary">

            ${workspace.course_code}

        </span>

        <span class="badge bg-secondary">

            Year ${workspace.year_level}

        </span>

        <span class="badge bg-success">

            Trimester ${workspace.trimester}

        </span>

        <span class="badge bg-dark">

            ${workspace.school_year}

        </span>

    </div>

</div>

`);

  loadWorkspaceStudents();
  loadGradingWorkspace();
  loadWorkspaceSummary();
}

//=======================================
// OPEN EVENT WORKSPACE
//=======================================

$(document).on("click", ".openWorkspaceBtn", function () {
  sessionStorage.setItem(
    "facultyWorkspace",
    JSON.stringify({
      faculty_subject_id: $(this).data("faculty-subject"),

      subject_id: $(this).data("subject"),
      course_id: $(this).data("course"),
      year_level: $(this).data("year"),
      school_year: $(this).data("school"),
      trimester: $(this).data("trimester"),

      subject_code: $(this).data("subject-code"),
      subject_name: $(this).data("subject-name"),
      course_code: $(this).data("course-code"),
      course_name: $(this).data("course-name"),
    }),
  );

  loadPage("subject-workspace");
});

//=======================================
// GRADING WORKSPACE
//=======================================

function loadGradingWorkspace(period = "Prelim") {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  $.get(
    "ajax/grading/get_scheme.php",

    {
      faculty_subject_id: workspace.faculty_subject_id,

      period: period,
    },

    function (html) {
      $("#gradingWorkspace").html(html);
    },
  );
}

// GRADING PERIOD SWITCHING EVENT
$(document).on(
  "click",

  ".gradingPeriod",

  function () {
    $(".gradingPeriod").removeClass("active");

    $(this).addClass("active");

    const period = $(this).data("period");

    loadGradingWorkspace(period);
    loadWorkspaceSummary(period);

    setTimeout(() => {}, 150);
  },
);

// MODAL EVENT
$(document).on("click", "#addComponentBtn", function () {
  $("#componentName").val("");

  $("#maxScore").val("");

  $("#weight").val("");

  $("#componentType").val("Quiz");

  $("#componentPeriod").val($(this).data("period"));

  $("#componentModal").modal("show");
});

//=======================================
// WORKSPACE STUDENTS
//=======================================

function loadWorkspaceStudents() {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  $.getJSON("ajax/get_subject_students.php", workspace, function (rows) {
    let html = "";

    if (rows.length === 0) {
      html = `

<div class="dashboard-card text-center py-5">

    No students found.

</div>

`;
    } else {
      html = `

<div class="dashboard-card">

<div class="d-flex justify-content-between align-items-center mb-3">

    <input
        type="text"
        id="studentSearch"
        class="form-control w-25"
        placeholder="Search student...">

    <button
        class="btn btn-primary"
        id="printStudents">

        <i class="fa-solid fa-print me-2"></i>

        Print Class List

    </button>

</div>

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead>

<tr>

<th width="140">Student No.</th>

<th>Student</th>

<th width="100">Status</th>

<th width="120">Current Grade</th>

<th width="80"></th>

</tr>

</thead>

<tbody>

`;

      rows.forEach(function (student) {
        html += `

<tr>

<td>

${student.student_number}

</td>

<td>

${student.student_name}

</td>

<td>

<span class="badge bg-success">

${student.status}

</span>

</td>

<td class="fw-semibold text-primary">

${student.current_grade ?? "--"}

</td>

<td>

<button
class="btn btn-sm btn-outline-primary viewStudentBtn"

data-enrollment="${student.enrollment_subject_id}"

title="Student Profile">

<i class="fa-solid fa-eye"></i>

</button>

</td>

</tr>

`;
      });

      html += `

</tbody>

</table>

</div>

</div>

`;
    }

    $("#studentsTab").html(html);
  });
}

$(document).on("click", "#backToSubjects", function () {
  loadPage("my-subjects");
});

//=======================================
// VIEW STUDENT
//=======================================

$(document).on("click", ".viewStudentBtn", function () {
  const id = $(this).data("enrollment");

  $("#studentProfileContent").load(
    "ajax/get_student_profile.php",

    {
      enrollment_subject_id: id,
    },

    function () {
      $("#studentProfileModal").modal("show");
    },
  );
});

//=======================================
// PRINT CLASS LIST
//=======================================

$(document).on("click", "#printStudents", function () {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  window.open(
    "reports/print_class_list.php?faculty_subject_id=" +
      workspace.faculty_subject_id,

    "_blank",
  );
});

//=======================================
// LOAD GRADEBOOK
//=======================================

function loadGradebook(period = "Prelim") {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  console.log({
    faculty_subject_id: workspace.faculty_subject_id,
    period: period,
  });

  $("#gradebookContainer").load("ajax/grading/get_gradebook.php", {
    faculty_subject_id: workspace.faculty_subject_id,
    period: period,
  });
}

//=======================================
// LOAD WORKSPACE SUMMARY
//=======================================

function loadWorkspaceSummary(period = "Prelim") {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  $.getJSON(
    "ajax/get_workspace_summary.php",
    {
      faculty_subject_id: workspace.faculty_subject_id,
      period: period,
    },
    function (data) {
      $("#workspaceStudentCount").text(data.students || 0);

      $("#workspaceComponents").text(data.components || 0);

      $("#workspaceAverage").text(
        data.average_grade ? Number(data.average_grade).toFixed(2) : "--",
      );

      $("#workspacePassing").text(
        `${data.passed || 0} / ${data.students || 0}`,
      );
    },
  );
}

//=======================================
// SAVE EVENT WORKSPACE (save_scheme.php)
//=======================================

$(document).on("click", "#saveComponent", function () {
  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  $.ajax({
    url: "ajax/grading/save_scheme.php",

    type: "POST",

    contentType: "application/json",

    data: JSON.stringify({
      faculty_subject_id: workspace.faculty_subject_id,

      period: $("#componentPeriod").val(),

      component_name: $("#componentName").val(),

      component_type: $("#componentType").val(),

      max_score: $("#maxScore").val(),

      weight: $("#weight").val(),
    }),

    success: function (res) {
      if (res.success) {
        const modal = bootstrap.Modal.getInstance(
          document.getElementById("componentModal"),
        );

        modal.hide();

        Notification.success("Component added successfully.");

        const currentPeriod = $("#componentPeriod").val();

        loadGradingWorkspace(currentPeriod);

        setTimeout(function () {
          loadGradebook(currentPeriod);
        }, 200);
      } else {
        Notification.error(res.message);
      }
    },
  });
});

//=======================================
// DELETE COMPONENT (delete_component.php)
//=======================================

$(document).on("click", ".deleteComponentBtn", function () {
  const id = $(this).data("id");

  AlertService.deleteConfirm("grading component").then((result) => {
    if (!result.isConfirmed) return;

    $.post(
      "ajax/grading/delete_component.php",
      {
        id: id,
      },
      function (res) {
        if (res.success) {
          Notification.success("Component deleted.");

          loadGradingWorkspace($(".gradingPeriod.active").data("period"));
        }
      },
      "json",
    );
  });
});

//=======================================
// GRADEBOOK
//=======================================

$(document).on("click", "#saveScoresBtn", function () {
  const scores = [];

  const workspace = JSON.parse(sessionStorage.getItem("facultyWorkspace"));

  $(".gradeInput").each(function () {
    scores.push({
      component_id: $(this).data("component"),

      enrollment_subject_id: $(this).data("enrollment"),

      score: $(this).val(),
    });
  });

  AlertService.saveConfirm("scores").then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: "ajax/grading/save_scores.php",

      type: "POST",

      contentType: "application/json",

      dataType: "json",

      data: JSON.stringify({
        faculty_subject_id: workspace.faculty_subject_id,

        period: $(".gradingPeriod.active").data("period"),

        scores: scores,
      }),

      success(res) {
        if (res.success) {
          Notification.success("Scores saved successfully.");

          const period = $(".gradingPeriod.active").data("period");

          loadGradingWorkspace(period);

          setTimeout(() => {
            loadGradebook(period);
          }, 200);
        } else {
          Notification.error(res.message);
        }
      },
    });
  });
});
