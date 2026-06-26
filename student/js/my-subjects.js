//=======================================
// LOAD CURRENT SUBJECTS
//=======================================

function loadCurrentSubjects() {
  Loader.show("Loading subjects...");

  $.getJSON("ajax/get_current_subjects.php", function (response) {
    if (!response.enrollment) {
      $("#mySubjectsTableBody").html(`
          <tr>
            <td colspan="3" class="text-center py-4">

              No active enrollment found.

            </td>
          </tr>
        `);

      return;
    }

    const e = response.enrollment;

    const trimesterText =
      e.trimester == 1
        ? "1st Trimester"
        : e.trimester == 2
          ? "2nd Trimester"
          : "3rd Trimester";

    $("#currentSubjectSummary").html(`
        <div class="row">

          <div class="col-md-4">

            <strong>
              School Year
            </strong>

            <br>

            ${e.school_year}

          </div>

          <div class="col-md-4">

            <strong>
              Year Level
            </strong>

            <br>

            ${e.year_level}

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

    let html = "";

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

    $("#mySubjectsTableBody").html(html);

    $("#subjectUnitTotal").html(`
        Total Units:
        ${totalUnits}
      `);
  }).always(function () {
    Loader.hide();
  });
}

//=======================================
// MY SUBJECTS
//=======================================

function loadMySubjects() {
  Loader.show("Loading subjects...");

  $.getJSON("ajax/get_my_subjects.php", function (rows) {
    let grouped = {};

    rows.forEach((row) => {
      const key = row.school_year + "|" + row.year_level + "|" + row.trimester;

      if (!grouped[key]) {
        grouped[key] = {
          school_year: row.school_year,
          year_level: row.year_level,
          trimester: row.trimester,
          subjects: [],
        };
      }

      grouped[key].subjects.push(row);
    });

    let html = `
        <div class="accordion"
             id="subjectsAccordion">
      `;

    let counter = 0;

    Object.values(grouped).forEach((group) => {
      counter++;

      const trimesterText =
        group.trimester == 1
          ? "1st Trimester"
          : group.trimester == 2
            ? "2nd Trimester"
            : "3rd Trimester";

      let subjectRows = "";

      let totalUnits = 0;

      group.subjects.forEach((subject) => {
        totalUnits += Number(subject.units);

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

      html += `
          <div class="accordion-item">

            <h2 class="accordion-header">

              <button
                class="accordion-button ${counter > 1 ? "collapsed" : ""}"
                data-bs-toggle="collapse"
                data-bs-target="#subject${counter}">

                ${group.school_year}
                |
                ${group.year_level} Year
                |
                ${trimesterText}

              </button>

            </h2>

            <div
                id="subject${counter}"
                class="accordion-collapse collapse ${
                  counter === 1 ? "show" : ""
                }">

              <div class="accordion-body">

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

                      ${subjectRows}

                    </tbody>

                  </table>

                </div>

                <div class="fw-bold text-end">

                    Total Units:
                    ${totalUnits}

                </div>

              </div>

            </div>

          </div>
        `;
    });

    html += "</div>";

    $("#mySubjectsContainer").html(html);
  }).always(function () {
    Loader.hide();
  });
}
