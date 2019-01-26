<template>
    <button type="button" class="btn btn-dark expand-toggle"
        v-bind:class="{ open : value === true }"
        @click="toggle">
        <template v-if="value === true">
            -
        </template>
        <template v-else>
            +
        </template>
    </button>
</template>

<script>
    export default {
        name: "expand-toggle",
        props: ['value'],
        methods: {
            toggle() {
                this.$emit('input', !this.value);
            },
        }
    }
</script>