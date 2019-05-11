<template>
    <div>
        <table class="table tags-table">
            <thead class="thead-control-container">
            <tr>
                <th></th>
                <th></th>
                <th class="button-container">
                    <button
                        v-bind:class="{ 'disabled': updatedTagIndexes.length <= 0 }"
                        v-html="showUpdatingStatus()"
                        @click="updateTags"
                        class="btn btn-primary" type="button">
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
                    v-on:showModule="showModuleHandler"
                ></AdminTagsRow>
            </transition-group>
            <tr>
                <th></th>
                <th></th>
                <th class="button-container">
                    <button
                        @click="newTag"
                        class="btn btn-primary" type="button">
                        New Tag
                    </button>
                </th>
            </tr>
        </table>
        <AdminTagsProjectsModule
            :tagId="addProjectsTagId"
            :tagName="addProjectsTagName"
            :projectIds="addProjectsTagProjectIds"
            :submittingProjectIds="submittingProjectIds"
            v-on:closeModule="closeModuleHandler"
            v-on:submitProjectIds="submitProjectIdsHandler"
        ></AdminTagsProjectsModule>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios';
    import AdminTagsRow from "./AdminTagsRow";
    import AdminTagsProjectsModule from "./AdminTagsAddProjectModule";

    export default {
        name: "AdminTags",
        components: {AdminTagsProjectsModule, AdminTagsRow},
        data() {
            return {
                tagsProjects: [],
                updatedTagIndexes: [],
                updating: false,
                addProjectsTagId: null,
                addProjectsTagProjectIds: [],
                addProjectsTagName: null,
                submittingProjectIds: false
            }
        },
        methods: {
            getTagsProjectsData() {
                callAxios(this.$options.tagsProjectsData, (dataObj) => {
                    this.tagsProjects = dataObj;
                });
            },
            deleteTagHandler(data) {
                const self = this;

                const tagId = data.tagId;
                const tagIndex = data.index;
                const isNew = isNaN(tagId);
                
                if (isNew === true) {
                    this.removeTagData(tagIndex);
                    return;
                }
                let formData = new FormData();
                formData.append('tagId', tagId);

                axios.post(self.$options.deleteTagEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        const deleted = response.data === 1;
                        const adminRowComponent = self.retrieveRef(tagId);
                        adminRowComponent.setDeleteStatus(true);
                        setTimeout(()=>{
                            if (deleted === true) {
                                // self.tagsProjects.splice(tagIndex, 1);
                                // self.addRemoveTagIndex(tagIndex, false);
                                self.removeTagData(tagIndex);
                            }
                            adminRowComponent.setDeleteStatus(false);
                        }, 1000);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            removeTagData(tagIndex) {
                this.tagsProjects.splice(tagIndex, 1);
                this.addRemoveTagIndex(tagIndex, false);
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
                        const adminRowComponent = self.retrieveRef(tagId);
                        adminRowComponent.setDetachStatus(projectId);
                        setTimeout(()=>{
                            if (detached === true) {
                                self.tagsProjects[tagIndex].projects.splice(projectIndex, 1);
                            }
                            adminRowComponent.setDetachStatus(projectId);
                        }, 1000);
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
            },
            updateTags() {
                if (this.updatedTagIndexes.length <= 0) {
                    return;
                }

                const self = this;
                let tagData = [];

                this.updatedTagIndexes.forEach((tagIndex)=>{
                    let tagPacket = {};
                    tagPacket.tagId = self.tagsProjects[tagIndex].id;
                    tagPacket.tagName = self.tagsProjects[tagIndex].tag;
                    tagData.push(tagPacket);
                });

                tagData = JSON.stringify(tagData);

                let formData = new FormData();
                formData.append('tagData', tagData);

                axios.post(self.$options.updateTagEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        const updated = response.data.updated === 1;
                        const tagIds = response.data.tagIds;
                        self.updating = true;
                        setTimeout(()=>{
                            self.updating = false;
                            if (updated === true) {
                                self.updateOriginals();
                                self.updatedTagIndexes = [];
                                self.updateTagIds(tagIds);
                            }
                        }, 1000);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            showUpdatingStatus() {
                return this.updating === false ? 'Update Tags' : this.$options.spinner;
            },
            showModuleHandler(data) {
                this.addProjectsTagId = data.tagId;
                this.addProjectsTagProjectIds = data.projectIds;
                this.addProjectsTagName = data.tagName;
            },
            retrieveRef(tagId) {
                return this.$refs['tagRef-'+tagId][0];
            },
            updateOriginals(tagIndex = null) {
                if (tagIndex !== null) {
                    this.triggerUpdateOriginal(this.tagsProjects[tagIndex].id);
                    return;
                }
                this.updatedTagIndexes.forEach((tagIndex)=>{
                    this.triggerUpdateOriginal(this.tagsProjects[tagIndex].id);
                });
            },
            updateTagIds(tagIdsObj) {
                const idKeys = Object.keys(tagIdsObj);
                const idValues = Object.values(tagIdsObj);

                this.tagsProjects.forEach((tag, tagIndex)=>{
                    const tagId = tag.id;
                    if (!idKeys.includes(tagId)) {
                        return;
                    }
                    const keyIndex = idKeys.indexOf(tagId);
                    this.tagsProjects[tagIndex].id = idValues[keyIndex];
                });
            },
            triggerUpdateOriginal(tagId) {
                const adminRowComponent = this.retrieveRef(tagId);
                adminRowComponent.copyTag();
            },
            closeModuleHandler() {
                this.addProjectsTagId = null;
            },
            submitProjectIdsHandler(data) {
                const self = this;
                const tagId = data.tagId;
                const tagIndex = this.getTagIndexByTagId(tagId);
                const projectIds = JSON.stringify(data.projectIds);
                let formData = new FormData();
                formData.append('tagId', tagId);
                formData.append('projectIds', projectIds);

                formData.append('tagName', this.addProjectsTagName);

                axios.post(self.$options.editTagProjectsEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        this.submittingProjectIds = true;
                        setTimeout(()=>{

                            let tagIdObject = {};
                            tagIdObject[tagId] = response.data.tagId;
                            console.log(tagIdObject);

                            self.updateTagProjects(tagIndex, response.data.projectData);
                            self.updateOriginals(tagIndex);
                            self.submittingProjectIds = false;
                            self.updateTagIds(tagIdObject);
                        }, 1000);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            getTagIndexByTagId(tagId) {
                for (let i = 0 ; i < this.tagsProjects.length ; ++i) {
                    if (this.tagsProjects[i].id === tagId) {
                        return i;
                    }
                }
            },
            updateTagProjects(tagIndex, projects) {
                this.tagsProjects[tagIndex].projects = projects;

                let newProjectsTagProjectIds = [];
                projects.forEach((project)=>{
                    newProjectsTagProjectIds.push(project.id);
                });

                this.addProjectsTagProjectIds = newProjectsTagProjectIds;
            },
            newTag() {
                let tagObj = {};
                tagObj.id = this.tagsProjects.length + '-new';
                tagObj.projects = [];
                tagObj.tag = '';

                this.tagsProjects.push(tagObj);
            }
        },
        mounted() {
            this.getTagsProjectsData();
        },
        created() {
            this.$options.tagsProjectsData = '/tags-projects';
            this.$options.deleteTagEndpoint = '/tag-delete';
            this.$options.detachTagEndpoint = '/tag-detach';
            this.$options.updateTagEndpoint = '/tag-update';
            this.$options.editTagProjectsEndpoint = '/tag-projects-edit';
            this.$options.spinner = '<i class="fas fa-circle-notch fa-spin"></i>';
        }
    }
</script>
