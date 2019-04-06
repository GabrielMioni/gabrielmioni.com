<template>
    <div class="col-sm-12 mb-5 project-display">
        <div class="row">
            <div class="col-md-6">
                <div class="project-image-wrap">
                    <img v-bind:src="setUrl()">
                </div>
            </div>
            <div class="col-md-6">
                <h2 class="mb-3">{{ project.title }}</h2>
                <div class="project-description">
                    {{ project.description}}
                </div>
                <div v-if="checkLinks()" class="project-links mt-3">
                    <a v-if="checkLinks('github')" v-bind:href="project.github"><i class="fab fa-github"></i>Github</a>
                    <a v-if="checkLinks('wordpress')" v-bind:href="project.wordpress"><i class="fab fa-wordpress-simple"></i>Wordpress</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ProjectDisplay",
        props: ['project'],
        methods: {
            setUrl() {
                if (this.project.image_main.trim() === '' || this.project.image_main_ext.trim() === '') {
                    return '';
                }
                return '/images/' + this.project.image_main + '.' + this.project.image_main_ext;
            },
            checkLinks(type = null) {
                if (type !== null) {
                    return this.project[type].trim().length > 0;
                }
                return this.project.github.length > 0 || this.project.wordpress.length > 0;
            }
        }
    }
</script>
