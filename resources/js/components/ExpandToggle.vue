<template>
    <div class="btn expand-toggle"
        v-bind:class="{ open : value === true }"
        @click="toggle">
        <span v-if="value === true">Hide Details</span>
        <span v-else-if="value === false">Show Details</span>
        <template v-if="value === true">
            <i class="fas fa-minus"></i>
        </template>
        <template v-else>
            <i class="fas fa-plus"></i>
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
