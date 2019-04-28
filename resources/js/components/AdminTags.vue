<template>
    <div class="col-sm-12">
        <table class="table">
            <thead>
                <tr>
                    <th>Tag Name</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(tag, tagIndex) in tagsProjects">
                    <tr :key="tag.id">
                        <td>
                            <input
                                v-model="tagsProjects[tagIndex].tag"
                                v-bind:name="'tag-name-'+tag.id"
                                type="text" class="form-control">
                        </td>
                        <td class="tag-created align-middle">{{tag.created | dateFormat }}</td>
                        <td></td>
                    </tr>
                    <tr
                        v-if="tag.projects.length > 0"
                        class="projects-row">
                        <td class="project-td-container" colspan="3">
                            <div class="project-table-container">
                                <table class="table">
                                    <thead class="thead-light">
                                    <tr>
                                        <th>Project</th>
                                        <th>Description</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tr
                                        v-for="(project, projectIndex) in tag.projects">
                                        <td>{{ project.title }}</td>
                                        <td>{{ project.description }}</td>
                                        <td></td>
                                    </tr>
                                </table>
                            </div>
                            <!--<div
                                v-for="(project, projectIndex) in tag.projects"
                                class="row">
                                <div class="col-sm-12">
                                    {{project.title}}
                                </div>
                            </div>-->
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios';
    import moment from 'moment';

    export default {
        name: "AdminTags",
        data() {
            return {
                tagsProjects: [],
            }
        },
        methods: {
            getTagsProjectsData() {
                callAxios(this.$options.tagsProjectsData, (dataObj) => {
                    console.log(dataObj);
                    this.tagsProjects = dataObj;
                });
            }
        },
        filters: {
            dateFormat: function (date) {
                return moment(date).format('MMMM Do YYYY, h:mm a');
            }
        },
        mounted() {
            this.getTagsProjectsData();
        },
        created() {
            this.$options.tagsProjectsData = '/tags-projects';
        }
    }
</script>
