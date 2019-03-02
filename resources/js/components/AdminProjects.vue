<template>
    <form>
        <sortable-list v-model="projects">
            <div class="project-list" slot-scope="{ items: projects }" tabindex="-1">
                <sortable-item v-for="(project, index) in projects" :key="project.id">
                    <project-input
                            v-model="project[index]"
                            v-on:tagUpdate="tagUpdate"
                            v-on:tagRemove="tagRemove"
                            v-on:updateFile="updateFile"
                            v-on:projectAdd="projectAdd"
                            v-on:projectRemove="projectRemove"
                            v-on:deleteImage="deleteImage"
                            v-on:projectIsUpdated="projectIsUpdated"
                            v-on:undo="undoHandler"
                            :key="project.id"
                            :index="index"
                            :project="project"
                            :allTags="allTags">
                    </project-input>
                </sortable-item>
            </div>
        </sortable-list>
    </form>
</template>

<script>
    import ProjectInput from "./ProjectInput";
    import SortableList from "./SortableList";
    import SortableItem from "./SortableItem";
    import { move } from '../move';

    export default {
        name: "admin-projects",
        components: {SortableItem, SortableList, ProjectInput},
        data() {
            return {
                projects : [],
                allTags: [],
                updated: [],
                tempIds: [],
                initialized: false,
                loading: true,
            };
        },
        methods: {
            callAxios(url, callback) {
                axios.get(url)
                    .then((data) => {
                        const data_obj = data.data;
                        callback(data_obj);
                    })
            },
            getProjects() {
                const self = this;
                this.callAxios(self.$options.projects_url, (data_obj)=>{
                    setTimeout(() => {
                        self.loading = false;
                        self.projects = data_obj;
                    }, 1000);
                });
            },
            getTags() {
                const self = this;
                this.callAxios(self.$options.tags_url, (data_obj)=>{
                    self.allTags = data_obj;
                });
            },
            tagUpdate(data) {
                this.projects[data.index].tags.push(data.tag);
            },
            tagRemove(data) {
                const index = this.projects[data.index].tags.indexOf(data.tag);
                if (index > -1) {
                    this.projects[data.index].tags.splice(index, 1);
                }

            },
            updateFile(data) {
                let img_data = {};
                img_data.fileObj = data.fileObj;
                img_data.fileUrl = data.fileUrl;
                this.projects[data.index].image_main = img_data;
            },
            makeTempId() {
                let random = 0;

                while (random === 0 && !this.tempIds.includes(random)) {
                    random = Math.floor(Math.random() * 300);
                    random = random + '-temp';

                    if (!this.tempIds.includes(random)) {
                        this.tempIds.push(random);
                        return random;
                    }
                }
                return random;
            },
            projectAdd(data) {
                let newProject = {
                    'id' : this.makeTempId(),
                    'title' : '',
                    'description' : '',
                    'github' : '',
                    'wordpress' : '',
                    'documentation' : '',
                    'image_main' : '',
                    'image_main_ext' : '',
                    'tags' : [],
                    'updatedProjects' : []
                };

                const index = data.index;

                this.projects.splice(index, 0, newProject);
            },
            projectRemove(data) {
                let project = this.getProjectAtIndex(data.index);
                const hasData = this.checkProjectData(project);

                if (hasData === true) {
                    if (!confirm('You\'re about to delete this entire project. Are you sure you want to do that?')) {
                        return;
                    }
                }
                this.projects.splice(data.index, 1);
            },
            checkProjectData(project) {
                for (const property in project) {
                    if (!project.hasOwnProperty(property)) {
                        return;
                    }
                    if (property === 'image_main' && typeof project[property] === 'object') {
                        return true;
                    }
                    if (property !== 'id' && project[property].length > 0) {
                        return true;
                    }
                }

                return false;
            },
            deleteImage(data) {
                let project = this.getProjectAtIndex(data.index);
                project.image_main = '';
                project.image_main_ext = '';
            },
            getProjectAtIndex(index) {
                return this.projects[index];
            },
            projectIsUpdated(data) {
                const id = data.id;
                const updated = data.updated;

                if (updated === true && !this.updated.includes(id)) {
                    this.updated.push(id);
                }
                if (updated === false && this.updated.includes(id)) {
                    const index = this.updated.indexOf(id);
                    this.updated.splice(index, 1);
                }
            },
            undoHandler(data) {
                const index = data.index;
                const state = JSON.parse(data.state);
                const self = this;

                for (const property in state) {
                    if (!state.hasOwnProperty(property)) {
                        return;
                    }
                    const propertyValue = state[property];

                    if (property === 'order_column' && this.projects[index][property] !== propertyValue) {
                        const mateIndex = self.findMovedPair(propertyValue);
                        const mateOrder = self.projects[mateIndex].order_column;
                        let moveProjects = move(self.projects, mateIndex, index);
                        console.log('stuff', index, mateOrder);
                        moveProjects[index][property] = mateOrder;
                        this.projects = moveProjects;
                    }

                    if (this.projects[index][property] !== propertyValue) {
                        this.projects[index][property] = propertyValue;
                    }
                }
            },
            findMovedPair(stateOrder) {
                const BreakException = {};
                let out = null;

                try {
                    this.projects.forEach((project, index)=> {
                        if (project.order_column === stateOrder) {
                            out = index;
                            throw BreakException;
                        }
                    });
                } catch (e) {
                    if (e !== BreakException) throw e;
                }

                return out;

            }
        },
        created() {
            this.$options.projects_url = '/projects-json';
            this.$options.tags_url = '/all-tags';
        },
        mounted() {
            this.getProjects();
            this.getTags();
        }
    }
</script>
