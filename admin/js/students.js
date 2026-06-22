//=======================================
// LOAD COURSES
//=======================================

function loadStudentCourses() {
  $.getJSON("ajax/get_courses_dropdown.php", function (courses) {
    let courseOptions = "";

    courses.forEach((course) => {
      courseOptions += `
    <option value="${course.id}">
        ${course.course_code} - ${course.course_name}
    </option>
  `;
    });

    // Modal dropdown
    $("#studentCourse").html(
      '<option value="">Select Course</option>' + courseOptions,
    );

    // Filter dropdown
    $("#studentCourseFilter").html(
      '<option value="">All Courses</option>' + courseOptions,
    );
  });
}

//=======================================
// OPEN MODAL
//=======================================

$(document).on("click", "#btnAddStudent", function () {
  $("#studentForm")[0].reset();

  $("#studentId").val("");

  $("#studentModal .modal-title").text("Add Student");

  $("#studentCourse").val("");

  new bootstrap.Modal(document.getElementById("studentModal")).show();
});

//=======================================
// LOAD STUDENTS
//=======================================

function loadStudents(page = 1) {
  Loader.show("Loading students...");

  $.getJSON(
    "ajax/get_students.php",
    {
      search: $("#studentSearch").val(),
      status: $("#studentStatusFilter").val(),
      student_type: $("#studentTypeFilter").val(),
      course_id: $("#studentCourseFilter").val(),
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

        $("#studentsTableBody").html(html);

        $("#studentsPagination").html("");

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

        // FOR TYPE BADGE
        const typeBadge =
          student.student_type === "new"
            ? '<span class="badge bg-success text-uppercase"">New</span>'
            : student.student_type === "old"
              ? '<span class="badge bg-primary text-uppercase"">Old</span>'
              : student.student_type === "transferee"
                ? '<span class="badge bg-info text-uppercase"">Transferee</span>'
                : student.student_type === "returnee"
                  ? '<span class="badge bg-warning text-dark text-uppercase"">Returnee</span>'
                  : '<span class="badge bg-secondary text-uppercase"">Dropped</span>';

        // FOR GENDER BADGE
        const genderBadge =
          student.gender === "male"
            ? '<span class="badge bg-primary text-uppercase"">Male</span>'
            : '<span class="badge bg-danger text-uppercase"">Female</span>';

        // HTML SECTION
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
              ${student.course_code ?? "-"}
            </td>

            <td>
              ${student.gender}
            </td>

            <td>
              ${typeBadge}
            </td>

            <td>
              ${statusBadge}
            </td>


<td>

<button
  class="btn btn-sm btn-outline-primary editStudentBtn"
  data-id="${student.id}"
  title="Edit Student">

  <i class="fa-solid fa-pencil"></i>

</button>

<button
  class="btn btn-sm btn-outline-warning resetPasswordBtn"
  data-id="${student.id}"
  title="Reset Password">

  <i class="fa-solid fa-key"></i>

</button>

</td>

          </tr>
        `;
      });

      $("#studentsTableBody").html(html);

      renderStudentsPagination(response.current_page, response.total_pages);
    },
  ).always(function () {
    setTimeout(() => {
      Loader.hide();
    }, 500);
  });
}

//=======================================
// SEARCH FILTER
//=======================================

$(document).on("click", "#applyStudentFiltersBtn", function () {
  loadStudents(1);
});

//=======================================
// PAGINATION
//=======================================

function renderStudentsPagination(currentPage, totalPages) {
  let html = "";

  if (totalPages <= 1) {
    $("#studentsPagination").html("");
    return;
  }

  for (let i = 1; i <= totalPages; i++) {
    html += `
      <button
        class="btn btn-sm ${
          i === currentPage ? "btn-primary" : "btn-outline-primary"
        }
        studentPageBtn mx-1"
        data-page="${i}">

        ${i}

      </button>
    `;
  }

  $("#studentsPagination").html(html);
}

$(document).on("click", ".studentPageBtn", function () {
  loadStudents($(this).data("page"));
});

//=======================================
// RESET PASSWORD
//=======================================

$(document).on("click", ".resetPasswordBtn", function () {
  const studentId = $(this).data("id");

  AlertService.confirm(
    "Reset Password",
    "Reset this student's password to Student123! ?",
  ).then((result) => {
    if (!result.isConfirmed) return;

    $.ajax({
      url: "ajax/reset_student_password.php",

      type: "POST",

      dataType: "json",

      data: {
        student_id: studentId,
      },

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);
        } else {
          AlertService.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to connect to server.");
      },
    });
  });
});

//=======================================
// EDIT STUDENT
//=======================================

$(document).on("click", ".editStudentBtn", function () {
  console.log("Edit clicked");
  const id = $(this).data("id");

  Loader.show("Loading student...");

  $.getJSON(
    "ajax/get_student.php",
    { id: id },

    function (student) {
      $("#studentId").val(student.id);

      $('[name="student_number"]').val(student.student_number);
      $('[name="email"]').val(student.email);

      $('[name="first_name"]').val(student.first_name);
      $('[name="middle_name"]').val(student.middle_name);
      $('[name="last_name"]').val(student.last_name);

      $('[name="gender"]').val(student.gender);
      $('[name="birth_date"]').val(student.birth_date);

      $('[name="contact_number"]').val(student.contact_number);
      $('[name="address"]').val(student.address);

      $('[name="student_type"]').val(student.student_type);

      $('[name="status"]').val(student.status);

      $("#studentCourse").val(student.course_id);

      $("#studentModal .modal-title").text("Edit Student");

      new bootstrap.Modal(document.getElementById("studentModal")).show();
    },
  ).always(function () {
    Loader.hide();
  });
});

//=======================================
// SAVE STUDENT DETAILS
//=======================================

$(document).on("click", "#saveStudentBtn", function () {
  $.ajax({
    url: "ajax/save_student.php",

    type: "POST",

    data: $("#studentForm").serialize(),

    dataType: "json",

    success: function (response) {
      if (response.success) {
        Notification.success(response.message);

        bootstrap.Modal.getInstance(
          document.getElementById("studentModal"),
        ).hide();

        loadStudents();
      } else {
        AlertService.error(response.message);
      }
    },
  });
});
