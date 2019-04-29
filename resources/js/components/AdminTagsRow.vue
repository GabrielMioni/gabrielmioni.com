<template>
    <tbody v-bind:class="{ 'is-updated': tag.tag !== original}">
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
                    type="button" class="tags-project-toggle btn btn-primary">
                    <i class="fas" v-bind:class="{ 'fa-plus': projectsOpen === false, 'fa-minus': projectsOpen === true }"></i>
                </button>
            </td>
        </tr>
        <tr class="projects-row">
            <td class="project-td-container" colspan="3">
                <div class="project-table-container" v-bind:class="{ 'open' : projectsOpen === true }">
                    <table class="table">
                        <template v-if="tag.projects.length > 0">
                            <thead class="thead-light">
                                <tr>
                                    <th>Project</th>
                                    <th class="th-description">Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tr
                                v-for="project in tag.projects">
                                <td>{{ project.title }}</td>
                                <td>{{ project.description }}</td>
                                <td class="td-control button-container">
                                    <button type="button" class="btn btn-dark">
                                        Detach Project
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <template v-if="tag.projects.length <= 0">
                            <thead class="thead-empty">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                        </template>
                        <tr>
                            <td></td>
                            <td></td>
                            <td class="td-tag-delete button-container">
                                <button type="button" class="btn btn-danger">
                                    Delete Tag
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
        props: ['tag'],
        model: {
            prop: "tag",
        },
        data() {
            return {
                projectsOpen: false,
                original: ''
                //original: Vue.util.extend({}, this.tag.tag)
            }
        },
        methods: {
            toggleOpen() {
                this.projectsOpen = !this.projectsOpen;
            },
            copyTag() {
                this.original = this.tag.tag.slice(0, this.tag.tag.length);
            }
        },
        filters: {
            dateFormat: function (date) {
                return moment(date).format('MMMM Do YYYY, h:mm a');
            }
        },
        mounted() {
            this.copyTag();
        }
    }
</script>
