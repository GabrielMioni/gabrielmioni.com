<template>
    <form>
        <template v-for="(project, index) in projects">
            <project-input
                    v-model="project[index]"
                    :key="project.id"
                    :index="index"
                    :project="project">
            </project-input>
        </template>
    </form>
</template>

<script>
    import ProjectInput from "./ProjectInput";
    export default {
        name: "admin-projects",
        components: {ProjectInput},
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