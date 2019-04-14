<template>
    <div class="profile">
        <form class="profile-form">
            <div class="form-group">
                <label for="name">
                    <span>Name</span>
                </label>
                <input
                    v-model="name"
                    type="text" class="form-control" id="name">
            </div>
            <div class="form-group">
                <label for="email">
                    <span>Email</span>
                </label>
                <input
                    v-model="email"
                    type="email" class="form-control" id="email">
            </div>
            <div class="form-group">
                <label for="contact-email">
                    <span>Contact Email</span>
                </label>
                <input
                    v-model="contactEmail"
                    type="email" class="form-control" id="contact-email">
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "Profile",
        data() {
            return {
                aboutMe: '',
                contactEmail: '',
                email: '',
                gitHub: '',
                linkedIn: '',
                name: '',
            }
        }
    }
</script>
