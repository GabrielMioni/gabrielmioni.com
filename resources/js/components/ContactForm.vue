<template>
    <form>
        <h2>Contact me</h2>
        <div class="form-group">
            <label for="name">Your Name *</label>
            <input type="text" class="form-control" id="name">
        </div>
        <div class="form-group">
            <label for="email">Your Email *</label>
            <input type="email" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label for="company">Company</label>
            <input type="text" class="form-control" id="company">
        </div>
        <div class="form-group">
            <label for="message">Message *</label>
            <textarea class="form-control" id="message" name="message"></textarea>
        </div>
        <button type="button" class="btn btn-control"></button>
    </form>
</template>

<script>
    export default {
        name: "ContactForm"
    }
</script>
