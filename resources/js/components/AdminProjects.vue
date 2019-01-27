<template>
    <form>
        <sortable-list v-model="projects">
            <div class="project-list" slot-scope="{ items: projects }" tabindex="-1">
                <sortable-item v-for="(project, index) in projects" :key="project.id">
                    <project-input
                            v-model="project[index]"
                            :key="project.id"
                            :index="index"
                            :project="project">
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
                initialized: false,
                loading: true,
            };
        },
        methods: {
            getProjects() {
                const self = this;
                axios.get(self.$options.projects_url)
                    .then((data) => {

                        const data_obj = data.data;

                        setTimeout(() => {
                            self.loading = false;
                            self.projects = data_obj;
                        }, 1000);
                    })
            }
        },
        created() {
            this.$options.projects_url = '/projects-json';
        },
        mounted() {
            this.getProjects();
        }
    }
</script>