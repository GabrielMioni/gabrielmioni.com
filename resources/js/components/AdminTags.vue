<template>
    <div class="col-sm-12">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tag Name</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="tag in tagsProjects" :key="tag.id" class="tag-row">
                    <td class="tag-name">{{tag.tag}}</td>
                    <td class="tag-created">{{tag.created | dateFormat }}</td>
                    <td></td>
                </tr>
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
                return moment(date).format('MMMM Do YYYY, H:mm a');
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
