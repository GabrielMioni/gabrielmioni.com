<template>
    <div>
        Thy destiny is at hand!
    </div>
</template>

<script>
    import { callAxios } from '../call-axios'

    export default {
        name: "front-end-projects",
        data() {
            return {
                allTags: [],
                projects: [],
            }
        },
        methods: {
            getTags() {
                const self = this;
                callAxios(self.$options.tags_url, (data_obj) => {
                    self.allTags = data_obj;
                });
            },
        },
        created() {
            this.$options.projects_url = '/projects-json';
            this.$options.tags_url = '/all-tags';
        },
        mounted() {
            this.getTags();
        }
    }
</script>
