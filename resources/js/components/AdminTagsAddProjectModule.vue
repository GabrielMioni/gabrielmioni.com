<template>
    <div v-bind:class="{'show': checkTagId()}"
        class="admin-tags-projects">
        <div class="admin-tags-projects-module">
            <div class="col-md-12">
                <div v-for="project in filteredProjects">
                    {{project.description}}
                </div>
            </div>
        </div>
        <div class="admin-tags-projects-backdrop"></div>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios'

    export default {
        name: "AdminTagsProjectsModule",
        props: ['tagId'],
        data() {
            return {
                projects: [],
            }
        },
        methods: {
            checkTagId() {
                const parsedTagId = parseInt(this.tagId);
                const isNan = isNaN(parsedTagId);
                console.log(isNan);
                return !isNan;
            },
            getProjects() {
                callAxios(this.$options.getProjectsEndpoint, (dataObj) => {
                    this.projects = dataObj;
                });
            },
        },
        computed: {
            filteredProjects() {
                const self = this;

                return this.projects.filter((project)=>{
                    return project.id !== self.tagId;
                });
            }
        },
        mounted() {
            this.getProjects();
        },
        created() {
            this.$options.getProjectsEndpoint = '/projects-json';
        }
    }
</script>
