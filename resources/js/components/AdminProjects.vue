<template>
    <form>
        <sortable-list v-model="projects">
            <div class="project-list" slot-scope="{ items: projects }" tabindex="-1">
                <sortable-item v-for="(project, index) in projects" :key="project.id">
                    <project-input
                            v-model="project[index]"
                            v-on:tagUpdate="tagUpdate"
                            v-on:tagRemove="tagRemove"
                            :key="project.id"
                            :index="index"
                            :project="project"
                            :allTags="allTags">
                    </project-input>
                </sortable-item>
            </div>
        </sortable-list>
    </form>
</template>

<script>
    import ProjectInput from "./ProjectInput";
    import SortableList from "./SortableList";
    import SortableItem from "./SortableItem";
    export default {
        name: "admin-projects",
        components: {SortableItem, SortableList, ProjectInput},
        data() {
            return {
                projects : [],
                allTags: [],
                initialized: false,
                loading: true,
            };
        },
        methods: {
            callAxios(url, callback) {
                axios.get(url)
                    .then((data) => {
                        const data_obj = data.data;
                        callback(data_obj);
                    })
            },
            getProjects() {
                const self = this;
                this.callAxios(self.$options.projects_url, (data_obj)=>{
                    setTimeout(() => {
                        self.loading = false;
                        self.projects = data_obj;
                    }, 1000);
                });
            },
            getTags() {
                const self = this;
                this.callAxios(self.$options.tags_url, (data_obj)=>{
                    self.allTags = data_obj;
                });
            },
            tagUpdate(data) {
                this.projects[data.index].tags.push(data.tag);
            },
            tagRemove(data) {
                const index = this.projects[data.index].tags.indexOf(data.tag);
                if (index > -1) {
                    this.projects[data.index].tags.splice(index, 1);
                }

            }
        },
        created() {
            this.$options.projects_url = '/projects-json';
            this.$options.tags_url = '/all-tags';
        },
        mounted() {
            this.getProjects();
            this.getTags();
        }
    }
</script>
