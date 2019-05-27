<template>
    <div class="profile">
        <div
            v-bind:style="{ backgroundImage: 'url(' + heroUrl() + ')' }"
            class="hero mb-3">
            <div class="hero-cta">
                <div class="row">
                    <div class="col-md-12">
                        <a
                            @click="clickHeroImage"
                            href="#">Choose a hero image</a>
                    </div>
                </div>
            </div>
            <a
                v-if="typeof hero === 'object'"
                @click="undoHero"
                class="undo-hero" href="#">Undo</a>
        </div>
        <input type="file" accept="image/x-png,image/jpg,image/jpeg"
               v-on:input="updateFile($event, 'hero')"
               :name="'file'"
               ref="heroFile" id="profile-hero-uploader" style="display: none">
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
                    <div class="form-group">
                        <label for="linkedin">
                            <span>LinkedIn</span>
                        </label>
                        <input
                            v-model="linkedIn"
                            type="url" class="form-control" id="linkedin">
                    </div>
                    <div class="form-group">
                        <label for="github">
                            <span>GitHub</span>
                        </label>
                        <input
                            v-model="github"
                            type="url" class="form-control" id="github">
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
                            v-if="avatar.toString() !== '' && typeof avatar !== 'object'"
                            ref="deleteButton"
                            type="button" class="btn btn-danger mt-3">Delete Image</button>
                        <button
                            @click="undoImage"
                            v-if="typeof avatar === 'object'"
                            ref="undoImageButton"
                            type="button" class="btn btn-primary mt-3">Undo</button>
                        <input type="file" accept="image/x-png,image/jpg,image/jpeg"
                               v-on:input="updateFile($event, 'avatar')"
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
                github: '',
                linkedIn: '',
                name: '',
                submitting: false,
                avatar: '',
                hero: '',
                avatarOriginal: '',
                heroOriginal: '',
                tagLine: '',
                initialized: false
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

                if (typeof this.avatar.fileObj === 'object') {
                    formData.append('file[0]', this.avatar.fileObj);
                }
                if (typeof this.hero.fileObj === 'object') {
                    formData.append('file[1]', this.hero.fileObj);
                }

                this.submitting = true;

                axios.post(this.$options.profileUpdateEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        console.log(response);
                        setTimeout(()=>{
                            /*const imageData = response.data.image;
                            if (imageData !== false) {
                                self.avatar = imageData;
                            }*/
                            const newAvatar = response.data.avatar;
                            const newHero   = response.data.hero;

                            if (newAvatar !== null) {
                                self.avatar = newAvatar;
                            }
                            if (newHero !== null) {
                                self.hero = newHero;
                            }
                            self.submitting = false;
                        }, 1000);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            heroUrl() {
                if (this.initialized === false) {
                    return '';
                }
                if (typeof this.hero === 'object') {
                    return this.hero.fileUrl;
                }
                if (this.hero.trim() === '') {
                    return setImageUrl('background', 'nature-forest-trees-fog', 'jpeg');
                }
                return setImageUrl('background', this.hero, 'jpg');
            },
            updateFile(e, imageType) {
                let file = e.target.files[0];
                if (typeof file === 'undefined') {
                    return;
                }
                let imgData = {};
                imgData.fileObj = file;
                imgData.fileUrl = URL.createObjectURL(file);

                if (imageType === 'avatar') {
                    this.avatar = imgData;
                    this.$refs.file.value = '';
                }
                if (imageType === 'hero') {
                    this.hero = imgData;
                    this.$refs.heroFile.value = '';
                }
            },
            clickHeroImage(e) {
                e.preventDefault();
                this.$refs.heroFile.click();
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
            undoImage() {
                this.avatar = this.avatarOriginal;
                //this.avatarOriginal = '';
            },
            undoHero(e) {
                e.preventDefault();
                this.hero = this.heroOriginal;
            },
            setUrl() {
                const self = this;
                return setImageUrl('images', self.avatar, 'jpg');
            }
        },
        mounted() {
            const self = this;
            callAxios(this.$options.proifleDataEndpoint, (response) => {
                console.log('profile response:', response);
                const dataObj = response.data;
                console.log('Status:', typeof response.status, response.status);

                if (response.status === 204) {
                    self.initialized = true;
                }

                const keys = Object.keys(dataObj);
                const values = Object.values(dataObj);

                keys.forEach((property, index)=>{
                    const value = values[index];
                    self[property] = value;

                    if (['avatar','hero'].indexOf(property) !== -1) {
                        self[property + 'Original'] = value;
                    }

                    if (index+1 === keys.length) {
                        self.initialized = true;
                    }
                });
            });
        },
        created() {
            this.$options.proifleDataEndpoint = '/profile-data';
            this.$options.profileImageEndpoint = '/profile-image';
            this.$options.profileUpdateEndpoint = '/profile-store';
        }
    }
</script>
