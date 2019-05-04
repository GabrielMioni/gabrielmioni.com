<template>
    <tbody v-bind:class="{ 'is-updated': isUpdated() }">
        <tr>
            <td class="td-tag-name">
                <input
                    v-model="tag.tag"
                    v-bind:name="'tag-name-'+tag.id"
                    type="text" class="form-control">
            </td>
            <td class="tag-created align-middle">{{tag.created | dateFormat }}</td>
            <td class="button-container">
                <button
                    @click="toggleOpen"
                    v-html="showOrHide()"
                    type="button" class="tags-project-toggle btn btn-primary">
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
                                    @click="deleteTag()"
                                    v-html="showButtonStatus('Delete Tag')"
                                    type="button" class="btn btn-danger">
                                    <!--Delete Tag-->

                                </button>
                                <button
                                    v-bind:class="{ 'button-hidden': isUpdated() === false }"
                                    @click="undo"
                                    type="button" class="btn btn-secondary button-undo">
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
        props: ['tag', 'index'],
        model: {
            prop: "tag",
        },
        data() {
            return {
                projectsOpen: false,
                original: '',
                deleting: false,
                detachIds: [],
                //original: Vue.util.extend({}, this.tag.tag)
            }
        },
        methods: {
            toggleOpen() {
                this.projectsOpen = !this.projectsOpen;
            },
            copyTag() {
                this.original = this.tag.tag.slice(0, this.tag.tag.length);
            },
            showOrHide() {
                const faClass = this.projectsOpen === false ? 'fa-plus': 'fa-minus';
                let out = this.projectsOpen === true ? 'Hide ' : 'Show ';
                out += '<br> Details';
                out += `<i class="fas ${faClass}"></i>`;

                return out;
            },
            isUpdated() {
                return this.tag.tag.trim() !== this.original.trim()
            },
            showButtonStatus(defaultString, check = null) {
                if (check === null) {
                    return this.deleting === false ? defaultString : this.$options.spinner;
                }
                if (Number.isInteger(check)) {
                    return !this.detachIds.includes(check) ? defaultString : this.$options.spinner;
                }
            },
            setDeleteStatus(status) {
                this.deleting = status;
            },
            /*setDetachStatus(id, push) {
                if (push === true) {
                    this.detachIds.push(id);
                }
                if (push === false) {
                    const index = this.detachIds.indexOf(id);
                    this.detachIds.splice(index, id);
                }
            },*/
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
                //this.$emit('detachProject', {'tagIndex': this.index, 'projectIndex': projectIndex, 'tagId':this.tag.id, 'projectId': projectId});
                this.$emit('detachProject', {'tagIndex': this.index, 'projectIndex': projectIndex});
            },
            undo() {
                this.$emit('undo', {'index': this.index, 'original':this.original});
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
