<div class="container-fluid">

    <div class="welcome-banner mb-4">

        <h2>
            My Profile
        </h2>

        <p>
            Manage your account information and security.
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

        <!-- PROFILE -->

        <div class="col-lg-8">

            <div class="dashboard-card">

                <h5 class="mb-4">

                    Personal Information

                </h5>

                <form id="profileForm">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="modern-label">

                                Student Number

                            </label>

                            <input
                                class="form-control modern-input"
                                id="studentNumber"
                                readonly>

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">

                                Course

                            </label>

                            <input
                                class="form-control modern-input"
                                id="course"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">

                                First Name

                            </label>

                            <input
                                class="form-control modern-input"
                                id="firstName"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">

                                Middle Name

                            </label>

                            <input
                                class="form-control modern-input"
                                id="middleName"
                                readonly>

                        </div>

                        <div class="col-md-4">

                            <label class="modern-label">

                                Last Name

                            </label>

                            <input
                                class="form-control modern-input"
                                id="lastName"
                                readonly>

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">

                                Email

                            </label>

                            <input
                                name="email"
                                id="email"
                                class="form-control modern-input">

                        </div>

                        <div class="col-md-6">

                            <label class="modern-label">

                                Contact Number

                            </label>

                            <input
                                name="contact_number"
                                id="contactNumber"
                                class="form-control modern-input">

                        </div>

                        <div class="col-12">

                            <label class="modern-label">

                                Address

                            </label>

                            <textarea
                                name="address"
                                id="address"
                                rows="3"
                                class="form-control modern-input"></textarea>

                        </div>

                    </div>

                    <div class="mt-4">

                        <button
                            type="button"
                            class="module-action-btn"
                            id="saveProfileBtn">

                            Save Changes

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- PASSWORD -->

    <!-- PASSWORD -->

    <div class="dashboard-card mt-4">

        <h5 class="mb-4">

            Change Password

        </h5>

        <form id="passwordForm">

            <div class="row g-3">

                <!-- CURRENT PASSWORD -->

                <div class="col-md-4">

                    <label class="modern-label">

                        Current Password

                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="currentPassword"
                            name="current_password"
                            class="form-control modern-input"
                            placeholder="Current Password">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('currentPassword', this)">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- NEW PASSWORD -->

                <div class="col-md-4">

                    <label class="modern-label">

                        New Password

                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="newPassword"
                            name="new_password"
                            class="form-control modern-input"
                            placeholder="New Password">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('newPassword', this)">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

                <!-- CONFIRM PASSWORD -->

                <div class="col-md-4">

                    <label class="modern-label">

                        Confirm Password

                    </label>

                    <div class="input-group">

                        <input
                            type="password"
                            id="confirmPassword"
                            name="confirm_password"
                            class="form-control modern-input"
                            placeholder="Confirm Password">

                        <button
                            type="button"
                            class="btn btn-outline-secondary"
                            onclick="togglePassword('confirmPassword', this)">

                            <i class="fa-solid fa-eye"></i>

                        </button>

                    </div>

                </div>

            </div>

            <button
                type="button"
                class="module-action-btn mt-4"
                id="changePasswordBtn">

                Update Password

            </button>

        </form>

    </div>

</div>