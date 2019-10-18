<template>
    <div class="col-12 task bg-white" :class="{'complite':task.status}">
        <div>
            <div class="title row  align-content-center">
                <div class="col-1 row justify-content-center align-content-center">
                    <input type="checkbox" :checked="task.status" @change="onComplite">
                </div>
                <div class="col-9 task__name">
                    {{task.name}}
                </div>
                <div class="col-2 controls row justify-content-around">

                    <button class="del btn  btn-sm"

                    >
                        {{task.priority}}
                    </button>

                    <button class="edit btn  btn-sm"
                            data-toggle="modal" :data-target="'#updateModal'+task.id"
                    >
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>

                    <button class="del btn  btn-sm"
                            data-toggle="modal" :data-target="'#deleteTaskModal'+task.id"
                    >
                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>

                    <button class="showdate btn btn-dark  btn-sm"
                            v-if="task.date"
                            data-toggle="tooltip" data-placement="top" :title="task.date"
                    >
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                    </button>


                </div>
            </div>
            <div class="modal fade" :id="'deleteTaskModal'+task.id" tabindex="-1" role="dialog"
                 aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">{{task.created_at.toString()}}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            DELETE <strong>{{task.name}}</strong> ?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="onDeleteTask"
                            >
                                delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" :id="'updateModal'+task.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">update task</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>

                            <div class="form-group">
                                <label for="name">
                                    name: <strong>{{task.name}}</strong>
                                </label>
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
                                <div class="valid-feedback">nameis valid</div>
                                <div class="invalid-feedback">
                                    <span v-if="!$v.name.required">name is required</span>
                                    <span v-if="!$v.name.minLength">
                    name is min length
                    {{$v.name.$params.minLength.min}}
                </span>
                                    <span v-if="!$v.name.maxLength">
                    name must have at
                    {{$v.name.$params.maxLength.max}}
                </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="date">
                                deadline <strong>{{task.date}}</strong>
                            </label>
                            <input type="date"
                                   v-model="date"
                                   id="date"
                                   class="form-control"
                            >
                        </div>

                        <input :disabled="$v.$invalid" @click="save" type="submit" class="btn btn-primary">
                        <button class="btn btn-secondary " @click="onCancel">cancel</button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required, maxLength, minLength} from 'vuelidate/lib/validators';

    export default {

        data: () => ({
            name: '',
            date: ''
        }),
        validations: {
            name: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(255),
            },
        },
        mounted(){
          this.name = this.task.name;
          this.date = this.task.date;
        },
        props: {
            task: {
                id: Number,
                name: String,
                status: Boolean,
                priority: Number,
                date: Date,
                created_at: Date
            }
        },
        methods: {
            save() {
                this.update({
                    ...this.task,
                    name:this.name,
                    date:this.date
                })
            },
            modalDeleteToggle() {
                $('#deleteTaskModal' + this.task.id).modal('toggle');
            },
            onCancel(){
                this.name = this.task.name;
                this.date = this.task.date;
                this.modalUpdateToggle();
            },
            modalUpdateToggle() {
                $('#updateModal' + this.task.id).modal('toggle');

            },
            onDeleteTask() {
                axios.delete('/tasks/' + this.task.id)
                    .then(res => {
                        if (res.data) {
                            this.$emit('delete', res.data);
                            this.modalDeleteToggle();
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
            onComplite() {
                this.update({...this.task, status: !this.task.status})
            },
            update(task) {
                axios.patch('/tasks/' + task.id, task)
                    .then(res => {
                        if (res.data) {
                            console.log(res.data);
                            this.modalUpdateToggle();
                            this.$emit('update', res.data);

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

<style lang="scss">
    .task {
        border-bottom: 1px solid;
        padding: 0.4em;

        &:last-of-type {
            border: none;
            padding-bottom: 1em;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
            overflow: hidden;
        }
    }

    .task.complite {
        background-color: lightblue !important;
    }

    .task.complite .task__name {
        text-decoration: line-through;
    }
</style>
