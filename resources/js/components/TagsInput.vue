<template>
    <div class="form-group tags">
        <label class="justify-content-start" :for="'tags-' + id">Tags</label>
        <div class="tag-area form-control" @click="focusTagInput">
            <div class="tag-wrap">
                <div class="project-tag"
                     v-for="tag in tags">{{tag}}
                    <i class="fas fa-times"
                    v-on:click="removeTag(tag)"></i>
                </div>
                <div class="project-tag possible-tag"
                     v-if="search.length > 0"
                     v-for="tag in filteredAllTags"
                >{{tag}}</div>
                <input type="text"
                       v-model="search"
                       :ref="'tags-' + id"
                       :name="'tags-' + id"
                       @keydown.enter="addTag"
                >
            </div>
        </div>
        <!--<div class="tag-wrap">
            <template v-if="search.length > 0">
                <div class="project-tag"
                     v-for="tag in filteredAllTags"
                >{{tag}}</div>
            </template>
        </div>-->
    </div>
</template>

<script>
    export default {
        model: {
            addTag: '',
            prop: "tags",
            event: "addTag",
        },
        name: "tags-input",
        props: ['tags', 'id', 'allTags'],
        data() {
            return {
                tagInput : '',
                search: ""
            };
        },
        computed: {
            filteredAllTags() {
                return this.allTags.filter(allTags =>
                    allTags.startsWith(this.search)
                );
            }
        },
        methods: {
            focusTagInput() {
                const inputRef = 'tags-' + this.id;
                const ref_obj = this.$refs[inputRef];
                ref_obj.focus();
            },
            toggle() {
                this.$emit("addTag", false)
            },
            addTag() {
                const newTag = this.filteredAllTags.length > 0 ? this.filteredAllTags[0] : this.search;
                if (!this.tags.includes(newTag)) {
                    this.$emit('tagUpdate', {'tag': newTag});
                }
                this.search = '';
            },
            removeTag(tag) {
                this.$emit('tagRemove', {'tag': tag});
            }
        },
    }
</script>
