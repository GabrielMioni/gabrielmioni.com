<template>
    <form>
        <sortable-list v-model="projects">
            <div class="project-list" slot-scope="{ items: projects }" tabindex="-1">
                <sortable-item v-for="(project, index) in projects" :key="project.id">
                    <project-input
                            v-model="project[index]"
                            v-on:tagUpdate="tagUpdate"
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
            updateProjectData(id, type, data) {
                const BreakException = {};

                let dataObj = {};
                dataObj[type] = data;

                try {
                    this.projects.forEach((project)=>{
                        if (project.id === id) {
                            project[type].push(data);
                            throw BreakException;
                        }
                    });
                } catch (e) {
                    if (e !== BreakException) throw e;
                }
            },
            tagUpdate(data) {
                const id = data.id;
                const tag = {'tag' : data.tag};

                this.updateProjectData(id, 'tags', tag);
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
