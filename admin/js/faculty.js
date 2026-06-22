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
      ?  faculty.employee_number
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
