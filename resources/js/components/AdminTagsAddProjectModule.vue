<template>
    <div v-bind:class="{'show': checkTagId()}"
        class="admin-tags-projects">
        <div class="admin-tags-projects-module">
            <div class="col-md-12">
                <div class="available-projects">
                    <div
                        v-for="project in projects"
                        class="project-option">
                        <div class="checkbox-container">
                            <input
                                v-bind:checked="projectIds.includes(project.id)"
                                @change="setUpdateProjectIds($event, project.id)"
                                type="checkbox" v-bind:id="'project-add-'+ tagId+'-'+project.id">
                        </div>
                        <label v-bind:for="'project-add-'+ tagId+'-'+project.id">{{project.title}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div
            @click="closeModule"
            class="admin-tags-projects-backdrop"></div>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios'

    export default {
        name: "AdminTagsProjectsModule",
        props: ['tagId', 'projectIds'],
        data() {
            return {
                projects: [],
                updateProjectsIds: [],
                //updateProjectsIds: JSON.parse(JSON.stringify(this.projectIds))
            }
        },
        methods: {
            checkTagId() {
                const parsedTagId = parseInt(this.tagId);
                const isNan = isNaN(parsedTagId);
                return !isNan;
            },
            getProjects() {
                callAxios(this.$options.getProjectsEndpoint, (dataObj) => {
                    this.projects = dataObj;
                });
            },
            closeModule() {
                this.$emit('closeModule');
            },
            setUpdateProjectIds(event, projectId) {
                const isChecked = event.target.checked;

                if (isChecked === true && !this.updateProjectsIds.includes(projectId)) {
                    this.updateProjectsIds.push(projectId);
                }
                if (isChecked === false && this.updateProjectsIds.includes(projectId)) {
                    const projectIdIndex = this.updateProjectsIds.indexOf(projectId);
                    this.updateProjectsIds.splice(projectIdIndex, 1);
                }
            }
        },
        watch: {
            projectIds() {
                this.updateProjectsIds = [];
                const clonedProjectIds = JSON.parse(JSON.stringify(this.projectIds));
                for (let i = 0 ; i < clonedProjectIds.length ; ++i) {
                    this.updateProjectsIds.push(clonedProjectIds[i]);
                }
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
