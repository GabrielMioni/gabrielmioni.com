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
                <input type="text"
                       v-model="search"
                       :ref="'tags-' + id"
                       :name="'tags-' + id"
                       @keydown.enter="addTag"
                >
            </div>
        </div>
        <div class="tag-wrap">
            <template v-if="search.length > 0">
                <div class="project-tag"
                     v-for="tag in filteredAllTags"
                >{{tag}}</div>
            </template>
        </div>
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
                    allTags.toLowerCase().startsWith(this.search.toLowerCase())
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
                if (this.search.length > 0) {
                    const newTag = this.filteredAllTags;
                    this.search = '';
                    this.$emit('tagUpdate', {'tag': newTag[0]});
                }
            },
            removeTag(tag) {
                console.log('remove from TagsInput');
                this.$emit('tagRemove', {'tag': tag});
            }
        },
    }
</script>
