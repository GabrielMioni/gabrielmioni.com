<template>
    <div class="col-sm-12 mb-5 project-display">
        <div class="row">
            <div class="col-md-6">
                <div class="project-image-wrap">
                    <img v-bind:src="setUrl()">
                </div>
                <!--<div class="project-image" v-bind:style="{ backgroundImage: 'url(' + setUrl() + ')' }"></div>-->
            </div>
            <div class="col-md-6">
                <h2>{{ project.title }}</h2>
                <div class="project-description">
                    {{ project.description}}
                </div>
                <!--ID: {{project.id}} <br>
                Order: {{project.order_column}} <br>-->
                <!--Image: {{project.image_main}}.{{project.image_main_ext}} <br>-->
                <!--Description: {{project.description}} <br>-->
                <!--<div v-if="checkLinks()" class="project-links row justify-content-start">
                    <div v-if="checkLinks('github')" class="col-sm-3">
                        <a v-bind:href="project.github">Github</a>
                    </div>
                    <div v-if="checkLinks('wordpress')" class="col-sm-3">
                        <a v-bind:href="project.wordpress">Wordpress</a>
                    </div>
                </div>-->
                <div v-if="checkLinks()" class="project-links">
                    <a v-if="checkLinks('github')" v-bind:href="project.github">Github</a>
                    <a v-if="checkLinks('wordpress')" v-bind:href="project.wordpress">Wordpress</a>
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
