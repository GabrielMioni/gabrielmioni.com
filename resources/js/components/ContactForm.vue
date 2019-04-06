<template>
    <form class="contact-form">
        <h2>Contact me</h2>
        <div class="form-group">
            <label for="name">
                <span>Your Name *</span>
                <span v-if="checkError('name')" class="error-message">{{errors.name}}</span>
            </label>
            <input
                v-model="name"
                v-bind:class="{ 'error' : checkError('name') }"
                @input="clearErrors"
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
                @input="clearErrors"
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
                @input="clearErrors"
                class="form-control" id="message" name="message"></textarea>
        </div>
        <div class="row submit-row">
            <div class="col-sm-12">
                <button @click="submitEmail" type="button" class="btn btn-cta">Submit</button>
                <div class="spin-wrapper">
                    <i v-if="submitting" class="fas fa-circle-notch fa-spin"></i>
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
                email: '',
                message: '',
                name: '',
                errors: {
                    email: '',
                    message: '',
                    name: ''
                },
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
            },
            checkError(type) {
                return this.errors[type].trim().length > 0
            },
            clearErrors(e) {
                const type = e.target.id;
                if (type === 'email') {
                    const check = this.validateEmail();

                    if (check === true && this.errors.email.trim().length > 0 ) {
                        this.errors.email = '';
                    }
                    return;
                }
                if (this.errors[type].trim().length > 0) {
                    this.errors[type] = '';
                }
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

                callAxios(this.$options.emailController, ()=>{
                    setTimeout(()=>{
                        self.submitting = false;
                    },
                    1000);
                });
            },
            mounted() {
                this.$options.emailController = 'unknown_api_endpoint';
            }
        }
    }
</script>
