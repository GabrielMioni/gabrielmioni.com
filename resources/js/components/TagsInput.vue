<template>
    <div class="form-group" v-bind:class="{ 'form-inline inline-input no-gutters' : isInline }">
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
        model: {
            prop: "tags",
            event: "addTag",
        },
        name: "tags-input",
        props: ['tags', 'availableTags'],
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
