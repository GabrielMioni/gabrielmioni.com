<template>
    <form>
        <sortable-list
            v-model="projects"
            v-on:move="moveHandler">
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
                            v-on:updateSingle="updateSingleHandler"
                            :key="project.id"
                            :ref="project.id"
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
                projectsLoading: [],
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
                    console.log(data_obj);
                    setTimeout(() => {
                        self.loading = false;

                        if (data_obj.length > 0) {
                            self.projects = data_obj;
                        }
                        if (data_obj.length <= 0) {
                            self.projectAdd(0);
                        }
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
                    'order_column' : '',
                    'tags' : []
                };

                const index = data.index;

                this.projects.splice(index, 0, newProject);
                this.projects = move(this.projects, index, index);
            },
            projectRemove(data) {
                let project = this.getProjectAtIndex(data.index);
                const hasData = this.checkProjectData(project);
                const self = this;

                if (hasData === true) {
                    if (!confirm('You\'re about to delete this entire project. Are you sure you want to do that?')) {
                        return;
                    }
                }
                if (project.id !== parseInt(project.id, 10)) {
                    self.projects.splice(data.index, 1);
                    return;
                }

                let formData = new FormData();
                formData.append('id', project.id);

                axios.post('/project-delete', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        self.projects.splice(data.index, 1);
                        console.log(response);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
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

                this.projects[index].description = state.description;
                this.projects[index].documentation = state.documentation;
                this.projects[index].image_main = state.image_main;
                this.projects[index].image_main_ext = state.image_main_ext;
                this.projects[index].tags = state.tags;
                this.projects[index].title = state.title;
                this.projects[index].wordpress = state.wordpress;
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

            },
            updateSingleHandler(data) {
                const index = data.index;
                const id = data.id;

                if (!this.updated.includes(id)) {
                    return;
                }
                const projectInput = this.$refs[id][0];
                projectInput.toggleLoading();

                const projectArray = [this.projects[index]];
                this.updateProjects(projectArray, () => {
                    setTimeout(() => {
                        projectInput.toggleLoading();
                        projectInput.updateState();
                    }, 1000);
                });
            },
            moveHandler(data) {
                console.log('Move Handler: ', data);
                const index = data.index;
                const id = this.projects[index].id;
                const orderColumn = this.projects[index].order_column;

                this.sendSortOrder(id, orderColumn);
            },
            sendSortOrder(id, orderColumn) {

                let ids = [];

                this.projects.forEach((project)=>{
                    if (project.order_column < orderColumn) {
                        ids.push(project.id)
                    }
                });

                ids.push(id);

                let resortData = {'ids': ids, 'orderColumn' : orderColumn};
                let formData = new FormData();

                formData.append('resortData', JSON.stringify(resortData));

                axios.post('/project-store-sort-order', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        console.log(response);
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            updateProjects(projectArray, callback = null) {
                let formData = new FormData();
                formData.append('projects', JSON.stringify(projectArray));
                //formData.append('resort', JSON.stringify(this.resort));
                const self = this;

                projectArray.forEach( (project) => {
                    const projectId = project['id'];

                    if (typeof project.image_main === 'object') {
                        formData.append('file['+projectId+']', project.image_main.fileObj);
                    }
                });

                axios.post('/project-store', formData, { headers: { 'Content-Type': 'multipart/form-data' } })
                    .then((response) => {
                        console.log(response);
                        self.checkNewProjectId(response.data);
                        if (typeof callback === 'function') {
                            callback();
                        }
                    }).catch( (error) => {
                    console.log('errors: ', error);
                });
            },
            checkNewProjectId(data) {
                const self = this;
                if (data.length <= 0) {
                    return;
                }
                for (const key in data){
                    if (!data.hasOwnProperty(key)) {
                        return;
                    }
                    self.updateNewProjectId(key, data[key]);
                }
            },
            updateNewProjectId(oldId, newId) {
                const self = this;
                const BreakException = {};

                try {
                    this.projects.forEach((project, index)=> {
                        if (project.id === oldId) {
                            self.projects[index].id = newId;
                            throw BreakException;
                        }
                    });
                } catch(e) {
                    if (e !== BreakException) throw e;
                }
            },
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
