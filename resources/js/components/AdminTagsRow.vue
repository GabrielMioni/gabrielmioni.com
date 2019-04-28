<template>
    <tbody>
        <tr>
            <td>
                <input
                    v-model="tag.tag"
                    v-bind:name="'tag-name-'+tag.id"
                    type="text" class="form-control">
            </td>
            <td class="tag-created align-middle">{{tag.created | dateFormat }}</td>
            <td>
                <button v-if="tag.projects.length > 0"
                    class="tags-project-toggle btn btn-primary"
                    @click="toggleOpen"
                    type="button">
                    <!--<i class="fas fa-plus"></i>-->
                    <i class="fas" v-bind:class="{ 'fa-plus': projectsOpen === false, 'fa-minus': projectsOpen === true }"></i>
                </button>
            </td>
        </tr>
        <tr
            v-if="tag.projects.length > 0"
            class="projects-row">
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
                        <tr
                            v-for="project in tag.projects">
                            <td>{{ project.title }}</td>
                            <td>{{ project.description }}</td>
                            <td class="td-control">
                                <button type="button">
                                    Detach Project
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
            }
        },
        methods: {
            toggleOpen() {
                this.projectsOpen = !this.projectsOpen;
            }
        },
        filters: {
            dateFormat: function (date) {
                return moment(date).format('MMMM Do YYYY, h:mm a');
            }
        },
    }
</script>
