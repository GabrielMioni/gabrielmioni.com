<template>
    <div class="project-filter mb-5" v-bind:class="{'expanded' : expanded}">
        <div class="col-sm-12">
            <div class="project-filter-inner">
                <div class="filter-control"
                    @click="toggleExpanded">
                    <h5>Filter Projects</h5>
                    <i class="fas fa-sort-down"></i>
                </div>
                <div class="project-tags">
                    <ul>
                        <li v-for="(tag, index) in allTags" :key="index">
                            <label><input
                                @change="checkboxUpdate"
                                type="checkbox"
                                :name="tag"
                            />{{tag}}</label>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProjectFilter",
        props: ['allTags'],
        data() {
            return {
                expanded: false,
            }
        },
        methods: {
            toggleExpanded() {
                this.expanded = !this.expanded;
            },
            checkboxUpdate(e) {
                const checkbox = e.target;
                const isChecked = checkbox.checked;
                const name = checkbox.name;
                this.$emit("updateFilter", {'tagName': name, 'value' : isChecked});
            }
        }
    }
</script>
