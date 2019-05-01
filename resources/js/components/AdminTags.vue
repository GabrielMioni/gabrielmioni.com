<template>
    <div class="col-sm-12">
        <table class="table tags-table">
            <thead>
                <tr>
                    <th>Tag Name</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <template v-for="(tag, index) in tagsProjects">
                <AdminTagsRow
                    :tag="tag"
                    :index="index"
                    v-on:undo="undoHandler"
                    ></AdminTagsRow>
            </template>
        </table>
    </div>
</template>

<script>
    import { callAxios } from '../call-axios';
    import AdminTagsRow from "./AdminTagsRow";

    export default {
        name: "AdminTags",
        components: {AdminTagsRow},
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
            },
            undoHandler(data) {
                this.tagsProjects[data.index].tag = data.original;
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
