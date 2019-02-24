<template>
    <!--<div class="col-sm-12 project card p-3 mb-3" v-bind:class="{reverse : checkOddEven() }">-->
    <div class="col-sm-12 project card p-3 mb-3">
        <div class="form-row justify-content-end mr-0">
            <div class="col-sm-1">
                <sortable-handle>
                    <div class="project-handle">
                        <i class="fas fa-grip-vertical"></i>
                    </div>
                </sortable-handle>
            </div>
        </div>
        <div class="form-row">
            <div
                @click="clickFile"
                :ref="'dropFile'"
                class="col-sm-6 image-holder">
                <div v-if="project['image_main'] !== ''" class="project-image form-control" v-bind:style="{ backgroundImage: 'url(' + setUrl() + ')' }"></div>
                <div v-else class="project-image form-control" v-bind:style=""></div>
                <input type="file" accept="image/x-png,image/jpg,image/jpeg"
                       v-on:input="updateFile"
                       :class="['hidden-file-' + index]"
                       :name="'file-' + index"
                       ref="file" style="display: none">
                <button
                    v-if="project['image_main'] !== ''"
                    @click.stop="deleteImage"
                    type="button" tabindex="-1" class="btn-control delete-image btn btn-dark"><i class="fas fa-trash-alt"></i></button>
            </div>
            <div class="col-sm-6 text-holder">
                <div class="admin-edit row">
                    <div class="col-sm-11">
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
                    <div class="col-sm-1 add-remove-holder">
                        <div class="add-remove-controls">
                            <button
                                @click="projectAdd"
                                type="button" tabindex="-1" class="btn-control btn btn-primary mb-3"><i class="fas fa-plus"></i></button>
                            <button
                                @click="projectRemove"
                                type="button" tabindex="-1" class="btn-control btn btn-dark"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="col-sm-12">
                <div class="details-control">
                    <span v-if="expanded === true">Hide Details</span>
                    <span v-else-if="expanded === false">Show Details</span>
                    <expand-toggle v-model="expanded"></expand-toggle>
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
    import dragDrop from "drag-drop";

    export default {
        name: "project-input",
        components: {TagsInput, ExpandToggle, FormTextInput, SortableHandle},
        props: ['project', 'index', 'allTags'],
        data() {
            return {
                expanded : true,
            };
        },
        // inject: ["allTags"],
        methods: {
            setUrl() {
                const imgData = this.project['image_main'];
                if (imgData === '') {
                    return;
                }
                if (typeof imgData === 'object') {
                    return imgData['fileUrl'];
                }
                if (typeof imgData === 'string') {
                    return '/images/' + imgData + '.' + this.project['image_main_ext'];
                }
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
                this.$emit('tagRemove', {'index': this.index, 'tag':data.tag});
            },
            projectAdd() {
                this.$emit('projectAdd', {'index': this.index+1});
            },
            projectRemove() {
                this.$emit('projectRemove', {'index': this.index});
            },
            deleteImage() {
                this.$emit('deleteImage', {'index': this.index});
            },
            clickFile() {
                this.$refs.file.click();
            },
            updateFile(e, drop = false) {
                let file = drop === false ? e.target.files[0] : e;
                let file_url;
                if (typeof file === 'undefined') {
                    file = '';
                    file_url = '';
                } else {
                    file_url = URL.createObjectURL(file);
                }
                console.log('file', file);
                console.log('url', file_url);
                this.$emit('updateFile', {'index': this.index, 'fileUrl': file_url, 'fileObj': file});
            },
        },
        created() {

        },
        mounted() {
            const dropArea = this.$refs.dropFile;

            dragDrop(dropArea, (files) => {
                console.log(files);
                //self.updateFile(files[0], true);
            })

        },
        filters: {
        }
    }
</script>
