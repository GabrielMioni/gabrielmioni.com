<template>
    <form class="contact-form">
        <div class="form-group">
            <label for="name">
                <span>Your Name *</span>
                <span v-if="checkError('name')" class="error-message">{{errors.name}}</span>
            </label>
            <input
                v-model="name"
                v-bind:class="{ 'error' : checkError('name') }"
                @input="checkInput"
                type="text" class="form-control" id="name">
        </div>
        <div class="form-group">
            <label for="name">
                <span>Your Email *</span>
                <span v-if="checkError('email')" class="error-message">{{errors.email}}</span>
            </label>
            <input
                v-model="email"
                v-bind:class="{ 'error' : checkError('email') }"
                @input="checkInput"
                type="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="company">
                <span>Company</span>
            </label>
            <input
                v-model="company" type="text" class="form-control" id="company">
        </div>
        <div class="form-group">
            <label for="message">
                <span>Message *</span>
                <span v-if="errors.message.length > 0" class="error-message">{{errors.message}}</span>
            </label>
            <textarea
                v-model="message"
                v-bind:class="{ 'error' : checkError('message') }"
                @input="checkInput"
                class="form-control" id="message" name="message"></textarea>
        </div>
        <input v-model="covfefe" type="hidden" value = ''>
        <div class="row submit-row">
            <div class="col-sm-12">
                <button
                    @click="submitEmail"
                    v-bind:class="{ 'disabled' : !fieldsValid }"
                    type="button" class="btn btn-cta">Send</button>
                <div v-if="submitting" class="spin-wrapper">
                    <i class="fas fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>

    </form>
</template>

<script>
    import { callAxios } from '../call-axios';

    export default {
        name: "ContactForm",
        data() {
            return {
                company: '',
                covfefe: '',
                email: '',
                message: '',
                name: '',
                errors: {
                    email: '',
                    message: '',
                    name: ''
                },
                //fieldsValid: false,
                fieldsValid: true,
                submitting: false,
            }
        },
        methods: {
            validateEmail(message = null) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                const check = re.test(String(this.email).toLowerCase());
                if (check === false && message !== null) {
                    this.errors.email = message;
                }
                return check;
            },
            checkNotEmpty(type, message = null) {
                const value = this[type].trim();
                const check = value.length > 0;

                if (check === false && message !== null) {
                    this.errors[type] = message;
                }

                return check;
            },
            checkError(type) {
                return this.errors[type].trim().length > 0
            },
            requireFieldsPresent() {
                const checkEmail = this.validateEmail();
                const checkName  = this.checkNotEmpty('name');
                const checkMessage = this.checkNotEmpty('message');

                let valid = false;

                if (checkEmail === true && checkName === true && checkMessage === true) {
                    valid = true;
                }

                if (this.fieldsValid === false && valid === true) this.fieldsValid = true;
                if (this.fieldsValid === true && valid === false) this.fieldsValid = false;
            },
            checkInput(e) {
                const type = e.target.id;
                if (type === 'email') {
                    const check = this.validateEmail();

                    if (check === true && this.errors.email.trim().length > 0 ) {
                        this.errors.email = '';
                    }
                }
                if (type !== email) {
                    if (this.errors[type].trim().length > 0) {
                        this.errors[type] = '';
                    }
                }
                //this.requireFieldsPresent();
            },
            submitResponse(response) {
                this.$emit('submitResponse', {'response' : response });
            },
            submitEmail() {
                const self = this;
                const checkEmail = this.validateEmail('Please provide a valid email address');
                const checkName  = this.checkNotEmpty('name', 'Please include your name');
                const checkMessage = this.checkNotEmpty('message', 'Please include a message');

                if (checkEmail === false || checkName === false || checkMessage === false) {
                    return;
                }

                this.submitting = true;

                let formData = new FormData();

                let contactData = {};
                contactData.company = this.company;
                contactData.covfefe = this.covfefe;
                contactData.email   = this.email;
                contactData.message = this.message;
                contactData.name    = this.name;

                contactData = JSON.stringify(contactData);
                formData.append('contactData', contactData);

                axios.post('/contact-form', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        console.log(response);
                        setTimeout(()=>{
                            self.submitting = false;
                            self.submitResponse(true);
                        },
                        self.$options.timeoutSeconds);
                    }).catch( (error) => {
                        console.log(error);
                        setTimeout(()=>{
                            self.submitting = false;
                            self.submitResponse(false);
                        },
                        self.$options.timeoutSeconds);
                });
            },
            mounted() {
                this.$options.emailController = '/contact-form';
                this.$options.timeoutSeconds = 1000;
            }
        }
    }
</script>
