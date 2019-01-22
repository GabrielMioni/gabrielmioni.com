<template>
    
</template>

<script>
    export default {
        name: "admin-projects",

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
                    .then(function (data) {

                        const data_obj = data.data;
                        console.log(data_obj);

                        setTimeout(function () {
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