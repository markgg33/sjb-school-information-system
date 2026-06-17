const AlertService = {
  success(message) {
    Swal.fire({
      icon: "success",
      title: "Success",
      text: message,
      timer: 2000,
      showConfirmButton: false,
    });
  },

  error(message) {
    Swal.fire({
      icon: "error",
      title: "Error",
      text: message,
    });
  },

  warning(message) {
    Swal.fire({
      icon: "warning",
      title: "Warning",
      text: message,
    });
  },

  info(message) {
    Swal.fire({
      icon: "info",
      title: "Information",
      text: message,
    });
  },

  confirm(title, message) {
    return Swal.fire({
      icon: "question",
      title,
      text: message,
      showCancelButton: true,
      confirmButtonText: "Yes",
      cancelButtonText: "Cancel",
    });
  },

  saveConfirm(item = "record") {
    return Swal.fire({
      icon: "question",
      title: "Save Changes",
      text: `Do you want to save this ${item}?`,
      showCancelButton: true,
      confirmButtonText: "Save",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#2563eb",
    });
  },

  deleteConfirm(item = "record") {
    return Swal.fire({
      icon: "warning",
      title: "Delete Record",
      text: `Are you sure you want to delete this ${item}?`,
      showCancelButton: true,
      confirmButtonText: "Delete",
      cancelButtonText: "Cancel",
      confirmButtonColor: "#ef4444",
    });
  },

  logout() {
    return Swal.fire({
      icon: "question",
      title: "Sign Out",
      text: "You will need to login again to continue.",
      showCancelButton: true,
      confirmButtonText: "Sign Out",
      cancelButtonText: "Stay Logged In",
      confirmButtonColor: "#ef4444",
    });
  },
};

const Toast = Swal.mixin({
  toast: true,

  position: "top-end",

  showConfirmButton: false,

  timer: 3000,

  timerProgressBar: true,
});

const Notification = {
  success(message) {
    Toast.fire({
      icon: "success",
      title: message,
    });
  },

  error(message) {
    Toast.fire({
      icon: "error",
      title: message,
    });
  },
};

const Loading = {
  show(message = "Loading...") {
    Swal.fire({
      title: message,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  },

  hide() {
    Swal.close();
  },
};

//=======================================
// GLOBAL LOADER
//=======================================
const Loader = {
  show() {
    $("#globalLoader").addClass("show");
  },

  hide() {
    $("#globalLoader").removeClass("show");
  },
};
