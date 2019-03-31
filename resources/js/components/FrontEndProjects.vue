<template>
    <div class="row">
        <div class="col-sm-12">
            <ProjectFilter
                :allTags = allTags
                v-on:updateFilter="updateFilter"
            ></ProjectFilter>
            <!--<div>
                <div v-for="(tag, index) in filterTags" :key="index">
                    {{tag}}
                </div>
            </div>-->
            <ProjectDisplay v-for="(project, index) in filteredProjects" :project="project" :key="project.id"></ProjectDisplay>
        </div>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios'
    import ProjectFilter from "./ProjectFilter";
    import ProjectDisplay from "./ProjectDisplay";

    export default {
        name: "front-end-projects",
        components: {ProjectDisplay, ProjectFilter},
        data() {
            return {
                allTags: [],
                projects: [],
                filterTags: [],
            }
        },
        methods: {
            getTags() {
                callAxios(this.$options.tags_url, (data_obj) => {
                    this.allTags = data_obj;
                });
            },
            getProjects() {
                callAxios(this.$options.projects_url, (data_obj) => {
                    this.projects = data_obj;
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
        computed: {
            filteredProjects() {
                const self = this;

                if (this.filterTags.length <= 0) {
                    return this.projects;
                }

                return this.projects.filter((project)=>{

                    let tagPresent = false;

                    self.filterTags.forEach((tag)=>{
                        const checkTags = project.tags.includes(tag);

                        if (tagPresent === false && checkTags === true) {
                            tagPresent = true;
                        }
                    });

                    return tagPresent;
                });
            }
        },
        created() {
            this.$options.projects_url = '/projects-json';
            this.$options.tags_url = '/all-tags';
        },
        mounted() {
            this.getProjects();
            this.getTags();
        }
    }
</script>
