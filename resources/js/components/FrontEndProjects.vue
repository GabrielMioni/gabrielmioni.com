<template>
    <div>
        <ProjectFilter
            :allTags = allTags
            v-on:updateFilter="updateFilter"
        ></ProjectFilter>
        <div>
            <div v-for="(tag, index) in filterTags" :key="index">
                {{tag}}
            </div>
        </div>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios'
    import ProjectFilter from "./ProjectFilter";

    export default {
        name: "front-end-projects",
        components: {ProjectFilter},
        data() {
            return {
                allTags: [],
                projects: [],
                filterTags: [],
            }
        },
        methods: {
            getTags() {
                const self = this;
                callAxios(self.$options.tags_url, (data_obj) => {
                    self.allTags = data_obj;
                });
            },
            updateFilter(data) {
                const tagName = data.tagName;
                const value = data.value;

                if (value === true && !this.filterTags.includes(tagName)) {
                    this.filterTags.push(tagName);
                }
                if (value === false && this.filterTags.includes(tagName)) {
                    const index = this.filterTags.indexOf(tagName);
                    this.filterTags.splice(index, 1);
                }
            }
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
