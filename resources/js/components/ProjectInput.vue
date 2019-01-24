<template>
    <div class="col-sm-12 project card p-3 mb-3" v-bind:class="{reverse : checkOddEven() }">
        <div class="row">
            <div class="col-sm-6 image-holder">
                <div class="project-image form-control" v-bind:style="{ backgroundImage: 'url(' + setUrl(project['image_main'], project['image_main_ext']) + ')' }"></div>
            </div>
            <div class="col-sm-6 text-holder">
                <div class="admin-edit">
                    <label :for="setInputName('title')">Title</label>
                    <input type="text" class="form-control"
                           :name="setInputName('title')"
                           v-model="project.title"
                    >
                    <label :for="setInputName('description')">Description</label>
                    <textarea class="form-control"
                              :name="setInputName('description')"
                              v-model="project.description"
                    ></textarea>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "project-input",
        props: ['project', 'index'],
        methods: {
            setUrl(file, ext) {
                const filepath = file + '.' + ext;
                return '/images/' + filepath;
            },
            setInputName(name) {
                return `${name}-${this.project.id}`;
            },
            checkOddEven() {
                console.log(this.index);
                console.log(this.index %2 === 0);
                return this.index %2 !== 0;
            }
        },
        created() {

        },
        mounted() {

        },
        filters: {
        }
    }
</script>