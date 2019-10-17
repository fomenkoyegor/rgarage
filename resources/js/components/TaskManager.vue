<template>
    <div>
        <div class="container">
            <h2 class="text-center text-uppercase">
                simple todo list
            </h2>
        </div>
        <AddProject/>
        <ul v-if="projects.length">
            <li v-for="project in projects" :key="project.id">
                {{project.name}}
            </li>
        </ul>
        <p v-else>not found projects....</p>
    </div>
</template>

<script>
    import AddProject from "./AddProject";

    export default {
        components: {AddProject},
        data: () => ({
            projects: []
        }),
        mounted() {
            this.onGetProjects();
        },
        methods: {
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
