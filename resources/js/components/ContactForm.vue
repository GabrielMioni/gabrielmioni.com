<template>
    <form class="contact-form">
        <h2>Contact me</h2>
        <div class="form-group">
            <label for="name">
                <span>Your Name *</span>
                <span v-if="errors.message.length > 0" class="error-message">{{errors.message}}</span>
            </label>
            <input v-model="name" type="text" class="form-control" id="name">
        </div>
        <div class="form-group">
            <label for="name">
                <span>Your Email *</span>
                <span v-if="errors.email.length > 0" class="error-message">{{errors.email}}</span>
            </label>
            <input v-model="email" type="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="company">
                <span>Company</span>
            </label>
            <input v-model="company" type="text" class="form-control" id="company">
        </div>
        <div class="form-group">
            <label for="message">
                <span>Message *</span>
                <span v-if="errors.message.length > 0" class="error-message">{{errors.message}}</span>
            </label>
            <textarea v-model="message" class="form-control" id="message" name="message"></textarea>
        </div>
        <button @click="submitEmail" type="button" class="btn btn-cta">Submit</button>
    </form>
</template>

<script>
    import { callAxios } from '../call-axios';

    export default {
        name: "ContactForm",
        data() {
            return {
                name: '',
                email: '',
                company: '',
                message: '',
                errors: {
                    name: '',
                    email: '',
                    message: '',
                },
            }
        },
        methods: {
            validateEmail(message) {
                const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                const check = re.test(String(this.email).toLowerCase());
                if (check === false) {
                    this.errors.email = message;
                }
                return check;
            },
            checkEmpty(type, message) {
                const check = this[type].trim().length > 0;

                if (check === false) {
                    this.errors[type] = message;
                }
            },
            submitEmail() {
                const checkEmail = this.validateEmail('Please provide a valid email address');
                const checkName  = this.checkEmpty('name', 'Please include your name');
                const checkMessage = this.checkEmpty('message', 'Please include a message');

                if (checkEmail === false || checkName === false || checkMessage === false) {
                    return;
                }
            }
        }
    }
</script>
