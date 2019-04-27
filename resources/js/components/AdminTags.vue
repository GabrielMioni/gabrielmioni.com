<template>
    <div>
        <div v-for="tag in tagsProjects" :key="tag.id" class="tag-row">
            {{tag.tag}}
        </div>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios'

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
        mounted() {
            this.getTagsProjectsData();
        },
        created() {
            this.$options.tagsProjectsData = '/tags-projects';
        }
    }
</script>
