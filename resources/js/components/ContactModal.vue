<template>
    <div>
        <h3>{{ title }}</h3>
        <div class="message-complete">{{ message }}</div>
    </div>
</template>

<script>
    export default {
        name: "ContactModal",
        props: ['title', 'message']
    }
</script>
