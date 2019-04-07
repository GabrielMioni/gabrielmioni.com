<template>
    <div>
        <h2>{{ title }}</h2>
        <div class="message-complete">{{ message }}</div>
    </div>
</template>

<script>
    export default {
        name: "ContactModal",
        props: ['title', 'message']
    }
</script>
