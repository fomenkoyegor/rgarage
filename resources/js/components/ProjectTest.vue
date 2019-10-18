<template>
    <div>
        <div class="project">
            <div class="main">
                <input type="text" v-if="isEdit" v-model="newName">
                <p v-if="!isEdit">
                    {{project.name}}
                </p>
            </div>


            <div class="control">
                <button @click="onEdit" v-if="!isEdit">
            <span>
                <i class="fa fa-pencil" aria-hidden="true"></i>
            </span>
                    <span>
                edit
            </span>
                </button>

                <button @click="onDelete" v-if="!isEdit">
            <span>
                <i class="fa fa-trash-o" aria-hidden="true"></i>
            </span>
                    <span>
                del
            </span>
                </button>


                <button @click="onSuccess" v-if="isEdit">
            <span>
                <i class="fa fa-check" aria-hidden="true"></i>
            </span>
                    <span>
                ok
            </span>
                </button>

                <button @click="onCancel" v-if="isEdit">
            <span>
               <i class="fa fa-ban" aria-hidden="true"></i>
            </span>
                    <span>
                cancel
            </span>
                </button>


            </div>


        </div>

        <div class="add-task bg-info">
            <input type="text" v-model="newTaskName">
            <button @click="onAddTask">add</button>
        </div>

        <div class="bg-secondary">
            <ul>
                <li v-for="task in tasks" :key="task.id" :class="{'complite': task.status}">
                    {{task.name}}
                    <button @click="onDelTask(task)">del</button>
                    <input type="number" @change="onUpdateTask(task)" v-model="task.priority" min="0">
                    <input type="checkbox" v-model="task.status" @change="onUpdateTask(task)">
                    <input type="date" v-model="task.date" @change="onUpdateTask(task)">
                </li>
            </ul>

            <!--            <draggable v-model="tasks"-->
            <!--                       draggable=".item"-->
            <!--                       @change="onMove"-->
            <!--                       :sort="true"-->
            <!--            >-->
            <!--                <transition-group>-->
            <!--                    <div class="item" v-for="task in tasks" :key="task.id" style="border:1px solid">-->
            <!--                        {{task.name}} pr:{{task.priority}}-->
            <!--                        <button @click="onDelTask(task)">del</button>-->
            <!--                    </div>-->
            <!--                </transition-group>-->
            <!--            </draggable>-->
        </div>

    </div>
</template>

<script>
    import draggable from 'vuedraggable';

    export default {
        components: {
            draggable,
        },
        name: "Project",
        props: ['project'],
        data: () => ({
            newName: '',
            isEdit: false,
            tasks: [],
            newTaskName: ''
        }),
        mounted() {
            this.onGetTasks()
        },
        methods: {
            onPriotrityTask(e, task) {
                task.priority = e.target.value;
                if(task.priority>0) this.onUpdateTask(task);
            },
            onCompliteTask(e, task) {
                this.onUpdateTask({...task, status: !task.status})
            },
            updateTasks(task) {
                this.tasks = this.tasks.map(t => t.id === task.id ? task : t);
            },
            onUpdateTask(task) {

                axios.patch('/tasks/' + task.id, task)
                    .then(res => {
                        if (res.data) {
                            console.log(res.data);
                            this.updateTasks(task);
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
            onMove(v) {
                // this.onUpdateTask({...v.moved.element, priority: v.moved.newIndex});
            },
            onDelTask(task) {
                axios.delete('/tasks/' + task.id)
                    .then(res => {
                        if (res.data) {
                            this.tasks = this.tasks.filter(t => t.id !== res.data.id)
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
            onAddTask() {
                axios.post('/projects/' + this.project.id + '/tasks', {
                    name: this.newTaskName,
                })
                    .then(res => {
                        if (res.data) {
                            // this.tasks.push(res.data)
                            this.onGetTasks()
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
            onGetTasks() {
                axios.get('/projects/' + this.project.id + '/tasks')
                    .then(res => {
                        if (res.data) {
                            this.tasks = res.data;
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
            onDelete() {
                this.$emit('del', this.project)
            },
            onEdit() {
                this.isEdit = true;
                this.newName = this.project.name;

            },
            onCancel() {
                this.isEdit = false;
                this.newName = '';
            },
            onSuccess() {
                this.onUpdate({...this.project, name: this.newName});
            },
            onUpdate(project) {
                axios.patch('/projects/' + project.id, project)
                    .then(res => {
                        if (res.data) {
                            console.log(res)
                            this.$emit('update', res.data);
                            this.onCancel();
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        console.log(err.response.data.errors);
                        if (err.response.data.errors.name) {
                            console.log(err.response.data.errors.name);
                        }
                    })
            }
        }
    }
</script>

<style scoped lang="scss">
    .project {
        margin-bottom: 1em;
        background-color: white;
        box-shadow: 1px 1px 15px;
        display: flex;
        margin-top: 1em;

        .main {
            flex: 1;
        }

    }

    .complite {
        background-color: lightgreen;
    }
</style>
