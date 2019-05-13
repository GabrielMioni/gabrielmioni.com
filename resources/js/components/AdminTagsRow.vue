<template>
    <tbody v-bind:class="{ 'is-updated': isUpdated() }">
        <tr>
            <td class="td-tag-name">
                <input
                    v-model="tag.tag"
                    v-bind:name="'tag-name-'+tag.id"
                    @input="checkUpdated(tag.id)"
                    type="text" class="form-control">
            </td>
            <td class="tag-created align-middle">{{tag.created_at | dateFormat }}</td>
            <td class="button-container">
                <button
                    @click="toggleOpen"
                    v-html="showOrHide()"
                    type="button" class="tags-project-toggle btn btn-dark">
                </button>
            </td>
        </tr>
        <tr class="projects-row">
            <td class="project-td-container" colspan="3">
                <div class="project-table-container" v-bind:class="{ 'open' : projectsOpen === true }">
                    <table class="table">
                        <thead class="thead-light">
                        <tr>
                            <th>Project</th>
                            <th class="th-description">Description</th>
                            <th></th>
                        </tr>
                        </thead>
                        <transition-group name="tagProjects" v-bind:class="'span-transition-group'">
                            <tr
                                v-for="(project, projectIndex) in tag.projects"
                                :key="project.id">
                                <td>{{ project.title }}</td>
                                <td>{{ project.description }}</td>
                                <td class="td-control button-container">
                                    <button
                                        @click="detachProject(project.id, projectIndex)"
                                        :tabindex="setTabIndex()"
                                        v-html="showButtonStatus('Detach Project', project.id)"
                                        type="button" class="btn btn-dark">
                                        <!--Detach Project-->
                                    </button>
                                </td>
                            </tr>
                        </transition-group>
                        <tr v-if="tag.projects.length <= 0">
                            <td class="no-projects" colspan="3">
                                <div class="alert alert-danger">This tag has no attached projects</div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="td-tag-delete button-container">
                                <button
                                    @click="showAddProjectsModule"
                                    :tabindex="setTabIndex()"
                                    type="button" class="btn btn-success mb-3">
                                    Add / Remove<br>Projects
                                </button>
                                <button
                                    @click="deleteTag()"
                                    v-html="showButtonStatus('Delete Tag')"
                                    :tabindex="setTabIndex()"
                                    type="button" class="btn btn-danger">
                                </button>
                                <button
                                    v-bind:class="{ 'button-hidden': isUpdated() === false }"
                                    @click="undo"
                                    :tabindex="setTabIndex()"
                                    type="button" class="btn btn-secondary button-undo mt-3">
                                    Undo
                                </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </tbody>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "AdminTagsRow",
        props: ['tag', 'index', 'tagId'],
        model: {
            prop: "tag",
        },
        data() {
            return {
                projectsOpen: false,
                original: '',
                deleting: false,
                detachIds: [],
            }
        },
        methods: {
            toggleOpen() {
                this.projectsOpen = !this.projectsOpen;
            },
            copyTag() {
                const tagName = this.tag.tag === null ? '' : this.tag.tag;
                this.original = tagName.slice(0, tagName.length);
            },
            showOrHide() {
                const faClass = this.projectsOpen === false ? 'fa-plus': 'fa-minus';
                let out = this.projectsOpen === true ? 'Hide ' : 'Show ';
                out += '<br> Details';
                out += `<i class="fas ${faClass}"></i>`;

                return out;
            },
            isUpdated() {
                //console.log('trim: ', this.tag.tag);
                const tagName = this.tag.tag == null ? '' : this.tag.tag;
                return tagName.trim() !== this.original.trim()
            },
            checkUpdated() {
                const isUpdated = this.isUpdated();
                this.$emit('isUpdated', {'tagId': this.tagId, 'isUpdated': isUpdated});
            },
            showButtonStatus(defaultString, projectId = null) {
                if (projectId === null) {
                    return this.deleting === false ? defaultString : this.$options.spinner;
                }
                if (Number.isInteger(projectId)) {
                    return !this.detachIds.includes(projectId) ? defaultString : this.$options.spinner;
                }
            },
            setDeleteStatus(status) {
                this.deleting = status;
            },
            setDetachStatus(id) {
                const index = this.detachIds.indexOf(id);
                if (index === -1) {
                    this.detachIds.push(id);
                    return;
                }
                this.detachIds.splice(index, id);

            },
            deleteTag() {
                if (!confirm('You\'re about to delete this tag. Are you sure you want to do that?')) {
                    return;
                }
                this.$emit('deleteTag', {'index': this.index, 'tagId':this.tag.id});
            },
            detachProject(projectId, projectIndex) {
                this.$emit('detachProject', {'tagIndex': this.index, 'projectIndex': projectIndex});
            },
            undo() {
                this.$emit('undo', {'index': this.index, 'original':this.original});
            },
            showAddProjectsModule() {
                let projectIds = [];

                this.tag.projects.forEach((project)=>{
                    projectIds.push(project.id);
                });

                this.$emit('showModule', {'tagId': this.tag.id, 'tagName':this.tag.tag, 'projectIds': projectIds});
            },
            setTabIndex() {
                if (this.projectsOpen === true) {
                    return '0';
                }
                return '-1';
            }
        },
        filters: {
            dateFormat: function (date) {
                return moment(date).format('MMMM Do YYYY, h:mm a');
            }
        },
        mounted() {
            this.copyTag();
        },
        created() {
            this.$options.spinner = '<i class="fas fa-circle-notch fa-spin"></i>';
        },
    }
</script>
