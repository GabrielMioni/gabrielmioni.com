<template>
    <div class="profile">
        <form class="profile-form">
            <div class="row">
                <div class="col-md-9">
                    <div class="form-group">
                        <label for="name">
                            <span>Name</span>
                        </label>
                        <input
                            v-model="name"
                            type="text" class="form-control" id="name">
                    </div>
                    <div class="form-group">
                        <label for="about-me">
                            <span>Tag Line</span>
                        </label>
                        <textarea
                            v-model="tagLine"
                            class="form-control" id="tagLine"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="about-me">
                            <span>About Me</span>
                        </label>
                        <textarea
                            v-model="aboutMe"
                            class="form-control" id="about-me"></textarea>
                    </div>
                </div>
                <div class="col-md-3 about">
                    <div class="form-group about-content">
                        <label for="profile-image-uploader">
                            <span>Profile Image</span>
                        </label>
                        <div class="about-image-container">
                            <div
                                @click="clickProfileImage"
                                v-bind:style="[avatar !== null ? {'backgroundImage': 'url(' + setUrl() + ')'} : {'backgroundImage': ''}]"
                                class="about-image form-control"></div>
                        </div>
                        <button
                            @click="deleteImage"
                            v-if="avatar.toString() !== ''"
                            ref="deleteButton"
                            type="button" class="btn btn-danger mt-3">Delete Image</button>
                        <input type="file" accept="image/x-png,image/jpg,image/jpeg"
                               v-on:input="updateFile"
                               :name="'file'"
                               ref="file" id="profile-image-uploader" style="display: none">
                    </div>
                </div>
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
    import { callAxios } from '../call-axios';
    import { setImageUrl } from '../set-image-url';
    import SubmitButton from "./SubmitButton";
    import LoadingSpinner from "./LoadingSpinner";
    export default {
        name: "Profile",
        components: {LoadingSpinner, SubmitButton},
        data() {
            return {
                aboutMe: '',
                gitHub: '',
                linkedIn: '',
                name: '',
                submitting: false,
                avatar: '',
                tagLine: ''
            }
        },
        methods: {
            submit() {
                const self = this;
                let profileData = {};

                const restricted = ['submitting', 'avatar'];

                for (const property in this.$data) {
                    if (this.$data.hasOwnProperty(property) && !restricted.includes(property)) {
                        profileData[property] = self.$data[property];
                    }
                }
                let formData = new FormData();
                formData.append('profileData', JSON.stringify(profileData));

                if (this.avatar !== null) {
                    formData.append('file', this.avatar.fileObj);
                }

                this.submitting = true;

                axios.post(this.$options.profileUpdateEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        console.log(response);
                        setTimeout(()=>{
                            self.submitting = false;
                        }, 1000);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            updateFile(e) {
                console.log(e);
                let file = e.target.files[0];
                console.log(file);
                let file_url;
                if (typeof file === 'undefined') {
                    file = '';
                    file_url = '';
                } else {
                    let imgData = {};
                    imgData.fileObj = file;
                    imgData.fileUrl = URL.createObjectURL(file);
                    console.log(imgData);
                    this.avatar = imgData
                }
            },
            clickProfileImage() {
                this.$refs.file.click();
            },
            deleteImage() {
                const self = this;
                if (!confirm('You\'re about to delete this profile image. Are you sure?')) {
                    return;
                }

                const sendDelete = typeof this.avatar !== 'object';

                const deleteButton = this.$refs.deleteButton;
                const buttonText = deleteButton.textContent || deleteButton.innerText;

                deleteButton.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';

                if (sendDelete === false) {
                    setTimeout(()=>{
                        deleteButton.innerHTML = buttonText;
                    },1000);
                    return;
                }

                let formData = new FormData();
                formData.append('file', null);

                axios.post(this.$options.profileImageEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        console.log(response);
                        setTimeout(()=>{
                            deleteButton.innerHTML = buttonText;
                            self.avatar = '';
                        },1000);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            setUrl() {
                const self = this;
                return setImageUrl('images', self.avatar, 'jpg');
            }
        },
        mounted() {
            const self = this;
            callAxios(this.$options.proifleDataEndpoint, (dataObj) => {
                console.log(dataObj);
                for (const property in dataObj) {
                    if (!dataObj.hasOwnProperty(property)) {
                        return;
                    }
                    self[property] = dataObj[property];
                }
            });
        },
        created() {
            this.$options.proifleDataEndpoint = '/profile-data';
            this.$options.profileImageEndpoint = '/profile-image';
            this.$options.profileUpdateEndpoint = '/profile-store';
        }
    }
</script>
