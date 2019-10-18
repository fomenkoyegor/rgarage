<template>
    <div class="row">
        <div class="col-12">
            <div class="tasks bg-danger">
                <div class="title row p-2 align-content-center">
                    <div class="col-1 row justify-content-center align-content-center">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                    </div>
                    <div class="col-9 text-light">

                            <span class="form-group">
                           <input
                               type="text"
                               class="form-control"
                               id="name"
                               placeholder="Enter name "
                               v-model.trim="$v.name.$model"
                               :class="{
                                        'is-invalid':$v.name.$error,
                                        'is-valid':!$v.name.$invalid
                                    }"
                           >
                                <!--                                <span class="valid-feedback badge-dark text-success">name is valid</span>-->
                                <span class="invalid-feedback badge-dark text-danger">
<!--                                    <span v-if="!$v.name.required">name is required</span>-->
                                    <span v-if="!$v.name.minLength">
                    name is min length
                    {{$v.name.$params.minLength.min}}
                </span>
                                    <span v-if="!$v.name.maxLength">
                    name must have at
                    {{$v.name.$params.maxLength.max}}
                </span>
                                </span>
                            </span>

                    </div>
                    <div class="col-2 controls row justify-content-around">

                        <button class="edit btn btn-success  btn-sm"
                                style="width: 100%"
                                :disabled="$v.$invalid"
                                @click="onAddTask"
                        >
                            add Task
                        </button>


                    </div>
                </div>
            </div>
        </div>

<!--        <Task v-for="(task,index) in tasks"-->
<!--              :key="task.id"-->
<!--              :task="task"-->
<!--              :index="index"-->
<!--              @update="onUpdateTask"-->
<!--              @delete="onDeleteTask"-->
<!--        />-->


            <draggable
                :list="tasks"
                class="col-12"
                style="margin: 0!important;padding: 0!important;"
                @start="dragging = true"
                @end="dragging = false"
            >
                <Task v-for="(task,index) in tasks"
                      :key="task.id"
                      :task="task"
                      :index="index"
                      @update="onUpdateTask"
                      @delete="onDeleteTask"
                />
            </draggable>



    </div>
</template>

<script>
    import draggable from 'vuedraggable';
    import {required, maxLength, minLength} from 'vuelidate/lib/validators';
    import Task from "./Task";

    export default {
        components: {Task, draggable},
        props: ['project'],
        data: () => ({
            name: '',
            tasks: []
        }),
        methods: {
            checkMove(){
                window.console.log("Future index: " + e.draggedContext.futureIndex);
            },
            onDeleteTask(task) {
                this.tasks = this.tasks.filter(t => t.id !== task.id);
            },
            onUpdateTask(task) {
                this.tasks = this.tasks.map(t => t.id === task.id ? task : t);
            },
            addTask(task) {
                axios.post('/projects/' + this.project.id + '/tasks', task)
                    .then(res => {
                        if (res.data) {
                            // this.tasks.push(res.data)
                            this.onGetTasks();
                            this.name = '';
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
                this.$v.$touch();
                if (!this.$v.$invalid) {
                    this.addTask({name: this.name});
                }
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
            }
        },
        mounted() {
            this.onGetTasks();
        },
        validations: {
            name: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(255),
            }
        },
    }
</script>

<style scoped lang="scss">
    .tasks .title {
        background-color: #6574cd;
    }


    *,body,html{
        /*margin: 0;*/
        /*padding: 0;*/
        box-sizing: border-box;
        col{
            padding: 0;
        }
    }


</style>
