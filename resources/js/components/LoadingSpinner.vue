<template>
    <div v-if="loading" class="spin-wrapper">
        <i class="fas fa-circle-notch fa-spin"></i>
    </div>
</template>

<script>
    export default {
        name: "LoadingSpinner",
        props: ['loading']
    }
</script>
