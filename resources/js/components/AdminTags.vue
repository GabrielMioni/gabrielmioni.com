<template>
    <div class="col-sm-12">
        <table class="table tags-table">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th class="button-container">
                        <button
                            v-bind:class="{ 'disabled': updatedTagIndexes.length <= 0 }"
                            class="btn btn-primary" type="button">
                            Update Tags
                        </button>
                    </th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th>Tag Name</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <transition-group name="tags" v-bind:class="'span-transition-group'">
                <AdminTagsRow
                    v-for="(tag, index) in tagsProjects"
                    :tag="tag"
                    :index="index"
                    :ref="'tagRef-'+tag.id"
                    :key="tag.id"
                    v-on:undo="undoHandler"
                    v-on:detachProject="detachProjectHandler"
                    v-on:deleteTag="deleteTagHandler"
                    v-on:isUpdated="isUpdatedHandler"
                ></AdminTagsRow>
            </transition-group>
        </table>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios';
    import AdminTagsRow from "./AdminTagsRow";

    export default {
        name: "AdminTags",
        components: {AdminTagsRow},
        data() {
            return {
                tagsProjects: [],
                updatedTagIndexes: [],
            }
        },
        methods: {
            getTagsProjectsData() {
                callAxios(this.$options.tagsProjectsData, (dataObj) => {
                    console.log(dataObj);
                    this.tagsProjects = dataObj;
                });
            },
            deleteTagHandler(data) {
                const self = this;
                console.log(data);

                const tagId = data.tagId;
                const tagIndex = data.index;
                let formData = new FormData();
                formData.append('tagId', tagId);

                axios.post(self.$options.deleteTagEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        const deleted = response.data === 1;
                        const adminRowComponent = self.$refs['tagRef-'+tagId][0];
                        adminRowComponent.setDeleteStatus(true);
                        setTimeout(()=>{
                            if (deleted === true) {
                                self.tagsProjects.splice(tagIndex, 1);
                                self.addRemoveTagIndex(tagIndex, false);
                            }
                            adminRowComponent.setDeleteStatus(false);
                        }, 1000);
                        console.log(response);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            detachProjectHandler(data) {
                const self = this;
                const tagIndex = data.tagIndex;
                const projectIndex = data.projectIndex;

                const tagObject = this.tagsProjects[tagIndex];
                const tagId = tagObject.id;
                const projectId = tagObject.projects[projectIndex].id;

                let formData = new FormData();
                formData.append('projectId', projectId);
                formData.append('tagId', tagId);

                axios.post(self.$options.detachTagEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        const detached = response.data === 1;
                        const adminRowComponent = self.$refs['tagRef-'+tagId][0];
                        adminRowComponent.setDetachStatus(projectId);
                        setTimeout(()=>{
                            if (detached === true) {
                                self.tagsProjects[tagIndex].projects.splice(projectIndex, 1);
                            }
                            adminRowComponent.setDetachStatus(projectId);
                        }, 1000);
                        console.log(response);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            undoHandler(data) {
                this.tagsProjects[data.index].tag = data.original;
            },
            isUpdatedHandler(data) {
                const tagIndex = data.tagIndex;
                const isUpdated = data.isUpdated;

                this.addRemoveTagIndex(tagIndex, isUpdated);
            },
            addRemoveTagIndex(tagIndex, addIndex) {
                const tagIndexIsPresent = this.updatedTagIndexes.includes(tagIndex);
                if (addIndex === true && tagIndexIsPresent === false) {
                    this.updatedTagIndexes.push(tagIndex);
                }
                if (addIndex === false && tagIndexIsPresent === true) {
                    const tagIdIndex = this.updatedTagIndexes.indexOf(tagIndex);
                    this.updatedTagIndexes.splice(tagIdIndex, 1);
                }
            }
        },
        mounted() {
            this.getTagsProjectsData();
        },
        created() {
            this.$options.tagsProjectsData = '/tags-projects';
            this.$options.deleteTagEndpoint = '/tag-delete';
            this.$options.detachTagEndpoint = '/tag-detach';
        }
    }
</script>
