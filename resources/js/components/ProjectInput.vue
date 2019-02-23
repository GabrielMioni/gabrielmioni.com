<template>
    <!--<div class="col-sm-12 project card p-3 mb-3" v-bind:class="{reverse : checkOddEven() }">-->
    <div class="col-sm-12 project card p-3 mb-3">
        <div class="form-row justify-content-end mr-0">
            <expand-toggle v-model="expanded"></expand-toggle>
            <div class="col-sm-1">
                <sortable-handle>
                    <div class="project-handle">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                </sortable-handle>
            </div>
        </div>
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
        <div class="form-row links" v-bind:class="{ open : expanded }">
            <div class="col-sm-12">
                <tags-input
                    v-model="project.tags" :id="project.id"
                    :allTags="allTags"
                    v-on:tagUpdate="tagUpdate"
                    v-on:tagRemove="tagRemove"
                ></tags-input>
                <form-text-input
                        :inputTitle="'github'"
                        v-model="project.github"
                        :setInputName="setInputName"
                        :isInline="true"
                        :expanded="expanded"
                ></form-text-input>
                <form-text-input
                        :inputTitle="'docs'"
                        v-model="project.documentation"
                        :setInputName="setInputName"
                        :isInline="true"
                        :expanded="expanded"
                ></form-text-input>
                <form-text-input
                        :inputTitle="'wordpress'"
                        v-model="project.wordpress"
                        :setInputName="setInputName"
                        :isInline="true"
                        :expanded="expanded"
                ></form-text-input>
            </div>
        </div>
    </div>
</template>

<script>
    import FormTextInput from "./FormTextInput";
    import ExpandToggle from "./expandToggle";
    import SortableHandle from "./SortableHandle";
    import TagsInput from "./TagsInput";
    export default {
        name: "project-input",
        components: {TagsInput, ExpandToggle, FormTextInput, SortableHandle},
        props: ['project', 'index', 'allTags'],
        data() {
            return {
                expanded : false,
            };
        },
        // inject: ["allTags"],
        methods: {
            setUrl(file, ext) {
                const filepath = file + '.' + ext;
                return '/images/' + filepath;
            },
            setInputName(name) {
                return `${name}-${this.project.id}`;
            },
            checkOddEven() {
                return this.index %2 !== 0;
            },
            tagUpdate(data) {
                this.$emit('tagUpdate', {'index': this.index, 'tag':data.tag});
            },
            tagRemove(data) {
                console.log(data);
                this.$emit('tagRemove', {'index': this.index, 'tag':data.tag});
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
