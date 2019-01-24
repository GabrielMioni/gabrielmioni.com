<template>
    <div class="col-sm-12 project mb-2">
        <div class="row">
            <div class="col-sm-6">
                <div class="project-image" v-bind:style="{ backgroundImage: 'url(' + setUrl(project['image_main'], project['image_main_ext']) + ')' }"></div>
            </div>
            <div class="col-sm-6">
                <div class="admin-edit">
                    <label :for="'title-'+project.id">Title</label>
                    <input type="text" class="form-control"
                           :name="'title-'+project.id"
                           v-model="project.title"
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "project-input",
        props: ['project'],
        methods: {
            setUrl(file, ext) {
                const filepath = file + '.' + ext;
                return '/images/' + filepath;
            }
        },
        created() {

        },
        mounted() {

        }
    }
</script>