//=======================================
// LOAD PROFILE
//=======================================

function loadProfile() {
  Loader.show("Loading profile...");

  $.getJSON("../includes/profile/ajax/get_profile.php", function (profile) {
    $("#studentNumber").val(profile.student_number);

    $("#course").val(profile.course_code + " - " + profile.course_name);

    $("#firstName").val(profile.first_name);

    $("#middleName").val(profile.middle_name ?? "");

    $("#lastName").val(profile.last_name);

    $("#email").val(profile.email);

    $("#contactNumber").val(profile.contact_number ?? "");

    $("#birthDate").val(profile.birth_date ?? "");

    $("#address").val(profile.address ?? "");

    const photo = profile.photo
      ? "../uploads/profile/" + profile.photo
      : "../uploads/profile/default.png";

    $("#profilePhotoPreview").attr("src", photo);
  }).always(function () {
    Loader.hide();
  });
}

//=======================================
// SAVE PROFILE
//=======================================

$(document).on("click", "#saveProfileBtn", function () {
  AlertService.saveConfirm("profile").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    Loader.show("Saving profile...");

    $.ajax({
      url: "../includes/profile/ajax/update_profile.php",

      type: "POST",

      data: $("#profileForm").serialize(),

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);
        } else {
          Notification.error(response.message);
        }
      },

      error: function () {
        Notification.error("Unable to connect to server.");
      },

      complete: function () {
        setTimeout(() => {
          Loader.hide();
        }, 500);
      },
    });
  });
});

//=======================================
// UPLOAD PHOTO
//=======================================

$(document).on("click", "#saveProfilePhoto", function () {
  const file = $("#profilePhoto")[0].files[0];

  if (!file) {
    Notification.warning("Please select a photo.");
    return;
  }

  AlertService.saveConfirm("profile photo").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    Loader.show("Uploading photo...");

    const formData = new FormData();

    formData.append("photo", file);

    $.ajax({
      url: "../includes/profile/ajax/upload_profile_photo.php",

      type: "POST",

      data: formData,

      processData: false,

      contentType: false,

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success("Photo updated.");

          $("#profilePhotoPreview").attr(
            "src",
            "../uploads/profile/" +
              response.filename +
              "?t=" +
              new Date().getTime(),
          );
          const newPhoto =
            "../uploads/profile/" +
            response.filename +
            "?t=" +
            new Date().getTime();

          $("#profilePhotoPreview").attr("src", newPhoto);

          $("#topbarProfilePhoto").attr("src", newPhoto);
        } else {
          Notification.error(response.message);
        }
      },

      error: function () {
        Notification.error("Unable to connect to server.");
      },

      complete: function () {
        setTimeout(() => {
          Loader.hide();
        }, 500);
      },
    });
  });
});

//=======================================
// PHOTO PREVIEW
//=======================================

$(document).on("change", "#profilePhoto", function () {
  const file = this.files[0];

  if (!file) return;

  const reader = new FileReader();

  reader.onload = function (e) {
    $("#profilePhotoPreview").attr("src", e.target.result);
  };

  reader.readAsDataURL(file);
});

//=======================================
// CHANGE PASSWORD
//=======================================

$(document).on("click", "#changePasswordBtn", function () {
  AlertService.saveConfirm("password").then((result) => {
    if (!result.isConfirmed) {
      return;
    }

    Loader.show("Updating password...");

    $.ajax({
      url: "../includes/profile/ajax/change_password.php",

      type: "POST",

      data: $("#passwordForm").serialize(),

      dataType: "json",

      success: function (response) {
        if (response.success) {
          Notification.success(response.message);

          $("#passwordForm")[0].reset();
        } else {
          Notification.error(response.message);
        }
      },

      error: function () {
        Notification.error("Unable to connect to server.");
      },

      complete: function () {
        setTimeout(() => {
          Loader.hide();
        }, 500);
      },
    });
  });
});
