//=======================================
// LOAD SUBJECTS FUNCTION
//=======================================

function loadSubjects(
  courseId = "",
  yearLevel = "",
  trimester = "",
  search = "",
  page = 1,
) {
  //Loader.show("Loading subjects...");

  $.ajax({
    url: "ajax/get_subjects.php",

    dataType: "json",

    data: {
      course_id: courseId,
      year_level: yearLevel,
      trimester: trimester,
      search: search,
      page: page,
    },

    success: function (response) {
      let html = "";

      const subjects = response.subjects;

      if (!subjects.length) {
        html = `
          <tr>
            <td colspan="6" class="text-center py-5">

              <div class="empty-state">

                <i class="fa-solid fa-book-open empty-icon"></i>

                <h6 class="mt-3 mb-1">
                  No subjects found
                </h6>

                <p class="text-muted mb-0">
                  No matching subjects were found.
                </p>

              </div>

            </td>
          </tr>
        `;

        $("#subjectsTableBody").html(html);

        $("#subjectsPagination").html("");

        return;
      }

      subjects.forEach((subject) => {
        let usage =
          subject.curriculum_count > 0
            ? `${subject.curriculum_count} Curricula`
            : "Not Used";

        html += `
          <tr>

            <td>${subject.subject_code}</td>
            <td>${subject.subject_name}</td>
            <td>${subject.units}</td>
            <td>

                            <span class="badge bg-${
                              subject.status === "active"
                                ? "success"
                                : "warning"
                            } text-uppercase">

                                ${subject.status}

                            </span>

                        </td>
            <td>${usage}</td>

            <td>

              <button
                class="btn btn-sm btn-outline-primary editSubjectBtn"
                data-subject='${JSON.stringify(subject)}'>

                <i class="fa-solid fa-pencil"></i>

              </button>

              <button
                class="btn btn-sm btn-outline-danger deleteSubjectBtn"
                data-id="${subject.id}">

                <i class="fa-solid fa-trash"></i>

              </button>

            </td>

          </tr>
        `;
      });

      $("#subjectsTableBody").html(html);

      renderPagination(response.current_page, response.total_pages);
    },

    error: function () {
      $("#subjectsTableBody").html(`
        <tr>
          <td colspan="6" class="text-center text-danger py-4">
            Unable to load subjects.
          </td>
        </tr>
      `);
    },

    complete: function () {
      Loader.hide();
    },
  });
}

//=======================================
// RENDER PAGINATIONS
//=======================================

function renderPagination(currentPage, totalPages) {
  let html = "";

  if (totalPages <= 1) {
    $("#subjectsPagination").html("");
    return;
  }

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button
        class="btn btn-sm ${
          i === currentPage ? "btn-primary" : "btn-outline-primary"
        } paginationBtn mx-1"
        data-page="${i}">

        ${i}

      </button>
    `;
  }

  $("#subjectsPagination").html(html);
}

// PAGINATION CLICKS
$(document).on("click", ".paginationBtn", function () {
  loadSubjects(
    $("#courseFilter").val(),
    $("#yearFilter").val(),
    $("#trimesterFilter").val(),
    $("#subjectSearch").val(),
    $(this).data("page"),
  );
});

//=======================================
// SUBJECT FILTERS (COMMENTED TO DISABLE AUTO FILTER)
//=======================================

/*
$(document).on(
  "change",
  "#courseFilter, #yearFilter, #trimesterFilter",
  function () {
    loadSubjects(
      $("#courseFilter").val(),
      $("#yearFilter").val(),
      $("#trimesterFilter").val(),
    );
  },
);
*/

//=======================================
// OPEN MODAL
//=======================================

$(document).on("click", "#btnAddSubject", function () {
  $("#subjectForm")[0].reset();

  $("#subjectId").val("");

  $(".modal-title").text("Add Subject");

  new bootstrap.Modal(document.getElementById("subjectModal")).show();
});

//=======================================
// EDIT SUBJECT
//=======================================

$(document).on("click", ".editSubjectBtn", function () {
  const subject = $(this).data("subject");

  $("#subjectId").val(subject.id);

  $('[name="subject_code"]').val(subject.subject_code);

  $('[name="subject_name"]').val(subject.subject_name);

  $('[name="units"]').val(subject.units);

  $('[name="description"]').val(subject.description);

  $('[name="status"]').val(subject.status);

  $(".modal-title").text("Edit Subject");

  new bootstrap.Modal(document.getElementById("subjectModal")).show();
});

//=======================================
// SAVE SUBJECT
//=======================================

$(document).on("click", "#saveSubjectBtn", function () {
  AlertService.confirm("Save Subject", "Save changes?").then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: "ajax/add_subject.php",

      type: "POST",

      data: $("#subjectForm").serialize(),

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          bootstrap.Modal.getInstance(
            document.getElementById("subjectModal"),
          ).hide();

          loadSubjects();
        } else {
          AlertService.error(response.message);
        }
      },
    });
  });
});

//=======================================
// DELETE SUBJECT
//=======================================

$(document).on("click", ".deleteSubjectBtn", function () {
  const id = $(this).data("id");

  AlertService.confirm("Delete Subject", "This action cannot be undone.").then(
    (result) => {
      if (!result.isConfirmed) return;

      $.post(
        "ajax/delete_subject.php",
        { id: id },
        function (response) {
          if (response.success) {
            Notification.success(response.message);

            loadSubjects(
              $("#courseFilter").val(),
              $("#yearFilter").val(),
              $("#trimesterFilter").val(),
              $("#subjectSearch").val(),
              1,
            );
          } else {
            AlertService.error(response.message);
          }
        },
        "json",
      );
    },
  );
});

//=======================================
// SEARCH SUBJECTS
//=======================================

/* REMOVED 
#filterSubjectsBtn
#searchSubjectsBtn
*/

$(document).on("keypress", "#subjectSearch", function (e) {
  if (e.which === 13) {
    $("#applyFiltersBtn").click();
  }
});

$(document).on("click", "#applyFiltersBtn", function () {
  Loader.show();
  setTimeout(() => {
    loadSubjects(
      $("#courseFilter").val(),
      $("#yearFilter").val(),
      $("#trimesterFilter").val(),
      $("#subjectSearch").val(),
      1,
    );

    Loader.hide();
  }, 500);
});

//=======================================
// LOAD COURSE FILTER
//=======================================

function loadCourseFilter() {
  $.getJSON("ajax/get_courses_dropdown.php", function (courses) {
    let html = `
      <option value="">
        All Courses
      </option>
    `;

    courses.forEach((course) => {
      html += `
    <option value="${course.id}">
        ${course.course_name} (${course.course_code})
    </option>
      `;
    });

    $("#courseFilter").html(html);
  });
}
