<template>
    <div class="project-filter">
        <h3>Filter Projects</h3>
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
</template>

<script>
    export default {
        name: "ProjectFilter",
        props: ['allTags'],
        methods: {
            checkboxUpdate(e) {
                const checkbox = e.target;
                const isChecked = checkbox.checked;
                const name = checkbox.name;
                this.$emit("updateFilter", {'tagName': name, 'value' : isChecked});
            }
        }
    }
</script>
