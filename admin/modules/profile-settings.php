<div class="container-fluid">

    <div class="welcome-banner mb-4">

        <h2>My Profile</h2>

        <p>
            Manage your administrator account.
        </p>

    </div>

    <div class="row g-4">

        <!-- PHOTO -->

        <div class="col-lg-4">

            <div class="dashboard-card text-center">

                <img
                    id="profilePhotoPreview"
                    src="../uploads/profile/default.png"
                    class="rounded-circle mb-3"
                    style="
                        width:180px;
                        height:180px;
                        object-fit:cover;
                        border:5px solid var(--sjb-primary);
                    ">

                <input
                    type="file"
                    id="profilePhoto"
                    accept=".jpg,.jpeg,.png,.webp"
                    class="form-control mb-3">

                <button
                    class="module-action-btn w-100"
                    id="saveProfilePhoto">

                    Upload Photo

                </button>

            </div>

        </div>

        <!-- INFORMATION -->

        <div class="col-lg-8">

            <div class="dashboard-card">

                <h5 class="mb-4">

                    Personal Information

                </h5>

                <form id="profileForm">

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="modern-label">

                                First Name

                            </label>

                            <input
                                id="firstName"
                                name="first_name"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">

                                Middle Name

                            </label>

                            <input
                                id="middleName"
                                name="middle_name"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">

                                Last Name

                            </label>

                            <input
                                id="lastName"
                                name="last_name"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-12">

                            <label class="modern-label">

                                Email

                            </label>

                            <input
                                id="email"
                                name="email"
                                class="form-control modern-input">

                        </div>

                    </div>

                    <button
                        type="button"
                        class="module-action-btn mt-4"
                        id="saveProfileBtn">

                        Save Changes

                    </button>

                </form>

            </div>

        </div>

    </div>

    <!-- PASSWORD -->

    <?php include '../../includes/partials/change-password.php'; ?>

</div>