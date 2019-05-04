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
            <transition-group name="tags" v-bind:class="'span-transition-group'">
                <AdminTagsRow
                    v-for="(tag, index) in tagsProjects"
                    :tag="tag"
                    :index="index"
                    :ref="'tagRef-'+tag.id"
                    :key="tag.id"
                    v-on:undo="undoHandler"
                    v-on:detachProject="detachProjectHandler"
                    v-on:deleteTag="deleteTagHandler"
                ></AdminTagsRow>
            </transition-group>
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
            deleteTagHandler(data) {
                const self = this;
                console.log(data);

                const tagId = data.tagId;
                const tagIndex = data.index;
                let formData = new FormData();
                formData.append('tagId', tagId);

                axios.post(self.$options.deleteTagEndpoint, formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        const deleted = response.data === 1;
                        const adminRowComponent = self.$refs['tagRef-'+tagId][0];
                        adminRowComponent.setDeleteStatus(true);
                        setTimeout(()=>{
                            if (deleted === true) {
                                self.tagsProjects.splice(tagIndex, 1);
                            }
                            adminRowComponent.setDeleteStatus(false);
                        }, 1000);
                        console.log(response);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            detachProjectHandler(data) {
                console.log(data);
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
            this.$options.deleteTagEndpoint = '/tag-delete';
        }
    }
</script>
