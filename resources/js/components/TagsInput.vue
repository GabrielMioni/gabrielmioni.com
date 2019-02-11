<template>
    <div class="form-group tags">
        <label class="justify-content-start" :for="'tags-' + id">Tags</label>
        <div class="tag-area form-control" @click="focusTagInput">
            <div class="tag-wrap">
                <div class="project-tag" v-for="tag in tags">{{tag.tag}}</div>
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
                     @click="console.log('click')"
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
                //this.$emit("addTag", !this.toggled)
                this.$emit("addTag", false)
            },
            addTag() {
                if (this.search.length > 0) {
                    const newTag = this.filteredAllTags;
                    //this.tags.push({'tag': newTag[0]});
                    this.search = '';
                    this.$emit('tagUpdate', {'id': this.id, 'tag': newTag[0]});
                }
            },
            removeTag() {
                console.log('clicky');
            }
            /*searchTags() {
                console.log('I am sure looking for tags right now I hope you believe me');
            }*/
        },
    }
</script>
