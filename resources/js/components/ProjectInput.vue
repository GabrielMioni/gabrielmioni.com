<template>
    <!--<div class="col-sm-12 project card p-3 mb-3" v-bind:class="{reverse : checkOddEven() }">-->
    <div class="col-sm-12 project card p-3 mb-3">
        <div class="form-row">
            <div class="col-sm-6 image-holder">
                <div class="project-image form-control" v-bind:style="{ backgroundImage: 'url(' + setUrl(project['image_main'], project['image_main_ext']) + ')' }"></div>
            </div>
            <div class="col-sm-6 text-holder">
                <div class="admin-edit">
                    <form-text-input
                            :inputTitle="'title'"
                            v-model="project.title"
                            :setInputName="setInputName"
                    ></form-text-input>
                    <form-text-input
                            :inputTitle="'description'"
                            v-model="project.description"
                            :setInputName="setInputName"
                            :isTextArea="true"
                    ></form-text-input>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-12">
                <form-text-input
                        :inputTitle="'github'"
                        v-model="project.github"
                        :setInputName="setInputName"
                        :isInline="true"
                ></form-text-input>
                <form-text-input
                        :inputTitle="'docs'"
                        v-model="project.documentation"
                        :setInputName="setInputName"
                        :isInline="true"
                ></form-text-input>
                <form-text-input
                        :inputTitle="'wordpress'"
                        v-model="project.wordpress"
                        :setInputName="setInputName"
                        :isInline="true"
                ></form-text-input>
            </div>
        </div>
    </div>
</template>

<script>
    import FormTextInput from "./FormTextInput";
    export default {
        name: "project-input",
        components: {FormTextInput},
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