<template>
    <button
        @click="clickAction"
        v-bind:class="{ 'disabled' : disabled }"
        type="button" class="btn btn-cta">{{ buttonText | setButtonText }}</button>
</template>

<script>
    export default {
        name: "SubmitButton",
        props: ['buttonText', 'disabled'],
        methods: {
            clickAction() {
                this.$emit('buttonClick');
            }
        },
        filters: {
            setButtonText(string) {
                if (string.trim() === '' && string === null) {
                    return 'Submit';
                }
                return string;
            }
        }
    }
</script>
