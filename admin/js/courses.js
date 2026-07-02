//=======================================
// LOAD COURSES
//=======================================

function loadCourses() {
  $.get("ajax/get_courses.php", function (response) {
    const courses = JSON.parse(response);

    let html = "";

    courses.forEach((course) => {
      html += `
                    <tr>
                        <td>${course.course_code}</td>
                        <td>${course.course_name}</td>
                        <td>${course.description ?? ""}</td>

                        <td>

                            <span class="badge bg-${
                              course.status === "active" ? "success" : "warning"
                            } text-uppercase">

                                ${course.status}

                            </span>

                        </td>

<td>

    <button
        class="btn btn-sm btn-outline-primary editCourseBtn"
        data-course='${JSON.stringify(course)}'>

        <i class="fa-solid fa-pencil"></i>

    </button>

    <button
        class="btn btn-sm btn-outline-danger deleteCourseBtn"
        data-id="${course.id}">

        <i class="fa-solid fa-trash"></i>

    </button>

    <button
      class="btn btn-sm btn-outline-success manageSectionsBtn"
      data-id="${course.id}"
      data-name="${course.course_code}">

      <i class="fa-solid fa-layer-group"></i>

      </button>

</td>

                    </tr>
                `;
    });

    $("#coursesTableBody").html(html);
  });
}

//=======================================
// OPEN ADD COURSE MODAL
//=======================================

$(document).on("click", "#btnAddCourse", function () {
  $("#courseForm")[0].reset();

  $("#courseId").val("");

  $("#courseModal .modal-title").text("Add Course");

  const modal = new bootstrap.Modal(document.getElementById("courseModal"));

  modal.show();
});

//=======================================
// OPEN MANAGE SECTIONS MODAL
//=======================================

$(document).on("click", ".manageSectionsBtn", function () {
  const id = $(this).data("id");

  $("#sectionCourseId").val(id);

  loadCourseSections();

  new bootstrap.Modal(document.getElementById("sectionsModal")).show();
});

//=======================================
// LOAD COURSE SECTIONS
//=======================================

function loadCourseSections() {
  $.getJSON(
    "ajax/get_course_sections.php",

    {
      course_id: $("#sectionCourseId").val(),
    },

    function (rows) {
      let html = "";

      for (let year = 1; year <= 4; year++) {
        html += `

<div class="dashboard-card mb-4">

<h5 class="mb-3">

Year ${year}

</h5>

`;

        if (rows[year]) {
          rows[year].forEach((section) => {
            html += `

<div class="border rounded p-3 mb-2 d-flex justify-content-between align-items-center">

<div>

<h6 class="mb-1">

${section.section_name}

</h6>

<small class="text-muted">

Display Order :
${section.display_order}

</small>

</div>

<div>

<button

class="btn btn-outline-primary btn-sm editSectionBtn"

data-section='${JSON.stringify(section)}'>

<i class="fa-solid fa-pencil"></i>

</button>

<button

class="btn btn-outline-danger btn-sm deleteSectionBtn"

data-id="${section.id}">

<i class="fa-solid fa-trash"></i>

</button>

</div>

</div>

`;
          });
        } else {
          html += `

<div class="text-muted">

No sections.

</div>

`;
        }

        html += `

</div>

`;
      }

      $("#courseSectionsContainer").html(html);
    },
  );
}

//=======================================
// EDIT COURSE
//=======================================

$(document).on("click", ".editCourseBtn", function () {
  const course = $(this).data("course");

  $("#courseId").val(course.id);

  $('[name="course_code"]').val(course.course_code);

  $('[name="course_name"]').val(course.course_name);

  $('[name="description"]').val(course.description);

  $('[name="status"]').val(course.status);

  $("#courseModal .modal-title").text("Edit Course");

  new bootstrap.Modal(document.getElementById("courseModal")).show();
});

//=======================================
// EDIT SECTION
//=======================================

$(document).on("click", ".editSectionBtn", function () {
  const section = $(this).data("section");

  $("#sectionId").val(section.id);

  $('[name="year_level"]').val(section.year_level);

  $('[name="section_name"]').val(section.section_name);

  $('[name="display_order"]').val(section.display_order);

  $('[name="status"]').val(section.status);
});

//=======================================
// SAVE COURSE
//=======================================

$(document).on("click", "#saveCourseBtn", function () {
  AlertService.saveConfirm("course").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    $.ajax({
      url: "ajax/add_course.php",
      type: "POST",
      data: $("#courseForm").serialize(),
      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          bootstrap.Modal.getInstance(
            document.getElementById("courseModal"),
          ).hide();

          loadCourses();
        } else {
          Notification.error(response.message);
        }
      },

      error: function () {
        AlertService.error("Unable to connect to server.");
      },
    });
  });
});

//=======================================
// SAVE SECTION
//=======================================

$(document).on("click", "#saveSectionBtn", function () {
  AlertService.saveConfirm("section")

    .then((result) => {
      if (!result.isConfirmed) {
        return;
      }

      $.ajax({
        url: "ajax/save_course_section.php",

        type: "POST",

        data: $("#sectionForm").serialize(),

        dataType: "json",

        success(res) {
          if (res.success) {
            Notification.success("Section saved.");

            $("#sectionForm")[0].reset();

            $("#sectionId").val("");

            loadCourseSections();
          }
        },
      });
    });
});

//=======================================
// DELETE COURSE
//=======================================

$(document).on("click", ".deleteCourseBtn", function () {
  const id = $(this).data("id");

  AlertService.deleteConfirm("course").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    $.ajax({
      url: "ajax/delete_course.php",

      type: "POST",

      data: {
        id: id,
      },

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          loadCourses();
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
// DELETE SECTION
//=======================================

$(document).on("click", ".deleteSectionBtn", function () {
  const id = $(this).data("id");

  AlertService.deleteConfirm("section")

    .then((result) => {
      if (!result.isConfirmed) {
        return;
      }

      $.post(
        "ajax/delete_course_section.php",

        {
          id: id,
        },

        function (res) {
          if (res.success) {
            Notification.success("Section deleted.");

            loadCourseSections();
          }
        },

        "json",
      );
    });
});
