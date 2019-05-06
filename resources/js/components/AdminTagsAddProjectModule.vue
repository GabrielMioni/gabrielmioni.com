<template>
    <div v-bind:class="{'show': checkTagId()}" class="admin-tags-projects container">
        <div class="admin-tags-projects-module">
            <button
                @click="closeModule"
                class="close-button btn">
                <i class="fas fa-times"></i>
            </button>
            <div class="col-md-12">
                <div class="admin-tags-title">
                    <div>Editing {{tagName}} Projects</div>
                    <div class="checkbox-control">
                        <div class="checkbox-container">
                            <input
                                @change="toggleCheckAll($event)"
                                :ref="'toggleCheckBox'"
                                type="checkbox">
                        </div>
                    </div>
                </div>
                <div
                    :ref="'availableProjects'"
                    class="available-projects">
                    <div
                        v-for="project in projects"
                        class="project-option">
                        <div class="checkbox-container">
                            <input
                                v-bind:checked="updateProjectsIds.includes(project.id)"
                                @change="setUpdateProjectIds($event, project.id)"
                                type="checkbox" v-bind:id="'project-add-'+ tagId+'-'+project.id">
                        </div>
                        <label v-bind:for="'project-add-'+ tagId+'-'+project.id">{{project.title}}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="button-container">
                    <button
                        v-bind:class="{'button-hidden' : !projectIdsAreUpdated()}"
                        @click="undo"
                        class="btn btn-secondary button-undo" type="button">Undo</button>
                    <button
                        v-bind:class="{'disabled': !projectIdsAreUpdated()}"
                        @click="submitProjectIds"
                        class="btn btn-primary" type="button">Update Tagged Projects</button>
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
        props: ['tagId', 'tagName', 'projectIds'],
        data() {
            return {
                projects: [],
                updateProjectsIds: [],
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
                this.undo();
                this.$refs.availableProjects.scrollTop = 0;
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
            },
            projectIdsAreUpdated() {
                let copiedProjectIds = JSON.stringify(this.sortedProjectIds);
                let copiedUpdateProjectIds = JSON.stringify(this.sortedUpdateProjectsIds);

                return copiedProjectIds !== copiedUpdateProjectIds;
            },
            copyProjectIdsToUpdateProjectIds() {
                this.updateProjectsIds = [];
                const clonedProjectIds = JSON.parse(JSON.stringify(this.projectIds));
                for (let i = 0 ; i < clonedProjectIds.length ; ++i) {
                    this.updateProjectsIds.push(clonedProjectIds[i]);
                }
            },
            undo() {
                this.copyProjectIdsToUpdateProjectIds();
                this.$refs['toggleCheckBox'].checked = false;
            },
            toggleCheckAll(event) {
                const checked = event.target.checked;

                if (checked === false) {
                    this.updateProjectsIds = [];
                    return;
                }

                this.projects.forEach((project)=>{
                    const projectId = project.id;
                    if (!this.updateProjectsIds.includes(projectId)) {
                        this.updateProjectsIds.push(projectId);
                    }
                })
            },
            submitProjectIds() {
                if (!this.projectIdsAreUpdated()) {
                    return;
                }
                const projectIds = JSON.stringify(this.updateProjectsIds);
                this.$emit('submitProjectIds', {'projectIds': projectIds});
            }
        },
        watch: {
            projectIds() {
                this.copyProjectIdsToUpdateProjectIds();
            }
        },
        computed: {
            sortedProjectIds() {
                return this.projectIds.sort();
            },
            sortedUpdateProjectsIds() {
                return this.updateProjectsIds.sort();
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
