<template>
    <div class="profile">
        <form class="profile-form">
            <div class="form-group">
                <label for="name">
                    <span>Name</span>
                </label>
                <input
                    v-model="name"
                    type="text" class="form-control" id="name">
            </div>
            <div class="form-group">
                <label for="email">
                    <span>Email</span>
                </label>
                <input
                    v-model="email"
                    type="email" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="contact-email">
                    <span>Contact Email</span>
                </label>
                <input
                    v-model="contactEmail"
                    type="email" class="form-control" id="contact-email">
            </div>
            <div class="form-group">
                <label for="about-me">
                    <span>About Me</span>
                </label>
                <textarea
                    v-model="aboutMe"
                    class="form-control" id="about-me"></textarea>
            </div>
            <div class="row submit-row">
                <div class="col-sm-12">
                    <submit-button
                        :buttonText="'Update Profile'"
                        :disabled="submitting"
                        v-on:buttonClick="submit"
                    ></submit-button>
                    <loading-spinner
                        :loading="submitting"
                    ></loading-spinner>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import SubmitButton from "./SubmitButton";
    import LoadingSpinner from "./LoadingSpinner";
    export default {
        name: "Profile",
        components: {LoadingSpinner, SubmitButton},
        data() {
            return {
                aboutMe: '',
                contactEmail: '',
                email: '',
                gitHub: '',
                linkedIn: '',
                name: '',
                submitting: false,
            }
        },
        methods: {
            submit() {
                console.log('click');
            }
        }
    }
</script>
