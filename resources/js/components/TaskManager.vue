<template>
    <div>
        <div class="container">
            <h2 class="text-center text-uppercase">
                simple todo list
            </h2>
        </div>
        <AddProject @create="onAddProject"/>
        <Project v-for="project in projects"
                 :key="project.id"
                 :project="project"
                 @delete="onDeleteProject"
                 @update="onUpdateProject"
        />
        <div class="container" v-if="!projects.length">
            <h2 class="text-center">not have projects...</h2>
        </div>
    </div>
</template>

<script>
    import AddProject from "./AddProject";
    import Project from "./Project";

    export default {
        components: {Project, AddProject},
        data: () => ({
            projects: []
        }),
        mounted() {
            this.onGetProjects();
        },
        methods: {
            onUpdateProject(project) {
                this.projects = this.projects.map(p => p.id === project.id ? project : p);
            },
            onDeleteProject(project) {
                this.projects = this.projects.filter(p => p.id !== project.id);
            },
            onAddProject(project) {
                this.projects.unshift(project);
            },
            onGetProjects() {
                axios.get('/projects/')
                    .then(res => {
                        if (res.data) {
                            this.projects = res.data;
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        console.log(err.response.data.errors);
                        if (err.response.data.errors.name) {
                            console.log(err.response.data.errors.name);
                        }
                    })
            },
        }
    }
</script>
