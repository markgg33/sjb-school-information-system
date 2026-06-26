function loadDashboardStats() {
  $.getJSON("ajax/get_dashboard_stats.php", function (data) {
    $("#currentSubjectsCount").text(data.current_subjects);

    $("#currentUnits").text(data.current_units);

    $("#completedSubjects").text(data.completed_subjects);

    $("#studentStatus").text(data.status);

    if (data.enrollment) {
      const trimesterText =
        data.enrollment.trimester == 1
          ? "1st Trimester"
          : data.enrollment.trimester == 2
            ? "2nd Trimester"
            : "3rd Trimester";

      $("#currentEnrollmentSummary").html(`
            <div class="row">

                <div class="col-md-4">

                    <strong>
                        School Year
                    </strong>

                    <br>

                    ${data.enrollment.school_year}

                </div>

                <div class="col-md-4">

                    <strong>
                        Year Level
                    </strong>

                    <br>

                    ${data.enrollment.year_level}

                </div>

                <div class="col-md-4">

                    <strong>
                        Trimester
                    </strong>

                    <br>

                    ${trimesterText}

                </div>

            </div>
        `);
    }
  });
}

//=======================================
// CURRENT SUBJECT BUTTON
//=======================================

$(document).on("click", "#viewCurrentSubjectsBtn", function () {
  Loader.show("Loading subjects...");

  $.getJSON("ajax/get_current_subjects.php", function (response) {
    let html = "";

    if (!response.subjects.length) {
      html = `
            <div class="alert alert-warning mb-0">

              No enrolled subjects found.

            </div>
          `;
    } else {
      html = `
            <div class="table-responsive">

              <table class="table">

                <thead>

                  <tr>

                    <th>Code</th>
                    <th>Subject</th>
                    <th>Units</th>

                  </tr>

                </thead>

                <tbody>
          `;

      let totalUnits = 0;

      response.subjects.forEach((subject) => {
        totalUnits += Number(subject.units);

        html += `
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

      html += `
                </tbody>

              </table>

              <div class="fw-bold text-end">

                Total Units:
                ${totalUnits}

              </div>

            </div>
          `;
    }

    $("#currentSubjectsContent").html(html);

    new bootstrap.Modal(document.getElementById("currentSubjectsModal")).show();
  }).always(function () {
    Loader.hide();
  });
});
