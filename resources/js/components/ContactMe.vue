<template>
    <div class="contact-me">
        <transition name="contact">
            <contact-form
                v-if="!submitComplete()"
                v-on:submitResponse="responseHandler">
            </contact-form>
            <ContactModal
                v-if="submitComplete()"
                v-bind:title="submitTitle"
                v-bind:message="submitMessage"
            >
            </ContactModal>
        </transition>
    </div>
</template>

<script>
    import ContactForm from './ContactForm';
    import ContactModal from './ContactModal';
    export default {
        name: "ContactMe",
        components: {ContactModal, ContactForm},
        data() {
            return {
                submitTitle: '',
                submitMessage: '',
            }
        },
        methods: {
            submitComplete() {
                return this.submitTitle.length > 0 && this.submitMessage.length > 0;
            },
            responseHandler(data) {
                const response = data.response;

                let submitTitle = '';
                let submitMessage = '';

                if (response === true) {
                    submitTitle = 'Thank you!';
                    submitMessage = 'Your message was sent.';
                }
                if (response === false) {
                    submitTitle = 'Uh oh';
                    submitMessage = 'We were unable to send your message. Please try again later.'
                }
                this.submitTitle = submitTitle;
                this.submitMessage = submitMessage;
            },
        }
    }
</script>
