<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Example Component
                        <input type="text" v-model="projectName">
                        <button @click="addProject">
                            <span>
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </span>
                            <span>
                                add
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
        <Project v-for="project in projects"
                 :key="project.id"
                 :project="project"
                 @del="onDelProject"
                 @update="onUpdateProject"
        >
        </Project>
    </div>
</template>

<script>
    import Project from "./ProjectTest";

    export default {
        components: {Project},
        data: () => ({
            projects: [],
            projectName: ''
        }),
        methods: {
            onUpdateProject(project) {
                this.projects = this.projects.map(p => p.id === project.id ? project : p);
            },
            onDelProject(project) {
                axios.delete('/projects/' + project.id)
                    .then(res => {
                        console.log(res);
                        if (res.data) {
                            this.projects = this.projects.filter(p => p.id !== res.data.id)
                        }

                    })
                    .catch(err => {
                        console.log(err)
                        console.log(err.response.data.errors);
                        if (err.response.data.errors.name) {
                            console.log(err.response.data.errors.name);
                        }
                    })
            },
            addProject() {
                console.log(this.projectName);

                axios.post('/projects', {name: this.projectName})
                    .then(res => {

                        if (res.data) {
                            console.log(res.data);
                            this.projects.unshift(res.data)
                        }

                    })
                    .catch(err => {
                        console.log(err)
                        console.log(err.response.data.errors);
                        if (err.response.data.errors.name) {
                            console.log(err.response.data.errors.name);
                        }
                    })
            }
        },
        mounted() {
            axios.get('/projects')
                .then(res => res.data)
                .then(res => this.projects = res);

        }
    }
</script>
