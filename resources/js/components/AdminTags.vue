<template>
    <div>
        <table class="table tags-table">
            <thead class="thead-control-container">
            <tr>
                <th></th>
                <th></th>
                <th class="button-container">
                    <button
                        v-bind:class="{ 'disabled': updatedTagIds.length <= 0 }"
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
            <!--<transition-group :name="setTransitionActiveStatus()" v-bind:class="'span-transition-group'">-->
            <transition-group :name="setTransitionActiveStatus()" v-bind:class="{'span-transition-group': true, 'break': transitionActive === false}">
                <AdminTagsRow
                    v-for="(tag, index) in tagsProjects"
                    :tag="tag"
                    :index="index"
                    :tagId="tag.id"
                    :ref="'tagRef-'+tag.id"
                    :key="'tag-'+index"
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
                updatedTagIds: [],
                updating: false,
                addProjectsTagId: null,
                addProjectsTagProjectIds: [],
                addProjectsTagName: null,
                submittingProjectIds: false,
                transitionActive: true,
            }
        },
        methods: {
            getTagsProjectsData() {
                callAxios(this.$options.tagsProjectsData, (dataObj) => {
                    this.tagsProjects = dataObj;
                });
            },
            setTransitionActiveStatus() {
                return this.transitionActive === true ? 'tags' : 'break';
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
                const tagId = data.tagId;
                const isUpdated = data.isUpdated;

                this.addRemoveTagIndex(tagId, isUpdated);
            },
            addRemoveTagIndex(tagId, isUpdated) {
                const tagIdIsPresent = this.updatedTagIds.includes(tagId);
                if (isUpdated === true && tagIdIsPresent === false) {
                    this.updatedTagIds.push(tagId);
                }
                if (isUpdated === false && tagIdIsPresent === true) {
                    const tagIdIndex = this.updatedTagIds.indexOf(tagId);
                    this.updatedTagIds.splice(tagIdIndex, 1);
                }
            },
            updateTags() {
                if (this.updatedTagIds.length <= 0) {
                    return;
                }

                const self = this;
                let tagData = [];

                this.updatedTagIds.forEach((tagId)=>{
                    const tagIndex = self.getTagIndexByTagId(tagId);
                    let tagPacket = {};
                    tagPacket.tagId = tagId;
                    tagPacket.tagName = self.tagsProjects[tagIndex].tag;
                    tagData.push(tagPacket);
                });

                tagData = JSON.stringify(tagData);

                let formData = new FormData();
                formData.append('tagData', tagData);

                axios.post(self.$options.updateTagEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        this.transitionActive = false;
                        self.updating = true;
                        self.processUpdateTags(response).then(()=>{
                            this.transitionActive = true;
                        });
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            processUpdateTags(response) {
                const self = this;
                const updated = response.data.updated === 1;
                const tagIds = response.data.tagIds;
                return new Promise((resolve)=>{
                    setTimeout(()=>{
                        self.updating = false;
                        if (updated === true) {
                            self.updateOriginals();
                            self.updatedTagIds = [];
                            self.updateTagIds(tagIds);
                        }
                        resolve(true);
                    }, 1000);
                })
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
                const self = this;
                this.updatedTagIds.forEach((tagId)=>{
                    const tagIndex = self.getTagIndexByTagId(tagId);
                    this.triggerUpdateOriginal(this.tagsProjects[tagIndex].id);
                });
            },
            updateTagIds(tagIdsObj) {
                console.log(tagIdsObj);
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
                        this.transitionActive = false;
                        this.processSubmitProjectIds(tagId, tagIndex, response).then(()=>{
                            this.transitionActive = true;
                        });
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            processSubmitProjectIds(tagId, tagIndex, response) {

                const self = this;
                return new Promise((resolve)=>{
                    setTimeout(()=>{
                        let tagIdObject = {};
                        tagIdObject[tagId] = response.data.tagId;

                        self.updateTagProjects(tagIndex, response.data.projectData);
                        self.updateOriginals(tagIndex);
                        self.submittingProjectIds = false;
                        self.updateTagIds(tagIdObject);
                        resolve(true);
                    }, 1000);
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
                tagObj.created_at = 'now';
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
