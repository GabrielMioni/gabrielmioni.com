<template>
    <div>
        <div v-for="tag in tagsProjects" :key="tag.id" class="tag-row">
            <div class="tag-name">{{tag.tag}}</div>
            <div class="tag-created">{{tag.created | dateFormat }}</div>
        </div>
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
