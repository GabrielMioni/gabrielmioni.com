<template>
    <div class="btn expand-toggle"
        v-bind:class="{ open : value === true }"
        @click="toggle">
        <template v-if="value === true">
            -
        </template>
        <template v-else>
            +
        </template>
    </div>
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