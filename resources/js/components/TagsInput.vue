<template>
    <div class="form-group form-inline inline-input no-gutters">
        <label class="justify-content-start">Tags</label>
        <div class="tag-area">
            <div class="tag" v-for="tag in tags">{{tag}}</div>
        </div>
        <input v-model="tagInput" >
        <div class="tag-suggestions">
            <div class="tag" v-for="tag in tags">{{tag}}</div>
        </div>
    </div>
</template>

<script>
    export default {
        inject: ["allTags"],
        model: {
            prop: "tags",
            event: "addTag",
        },
        name: "tags-input",
        props: ['tags'],
        data() {
            return {
                tagInput : '',
            };
        },
        methods: {
            toggle() {
                //this.$emit("addTag", !this.toggled)
                this.$emit("addTag", false)
            }
        },
    }
</script>
