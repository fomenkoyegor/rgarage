<template>
    <div class="container mt-2">
        <div class="row">
            <div class="col-12">
                <div class="projects bg-primary">
                    <div class="title row p-2 align-content-center">
                        <div class="col-1">
                            <button class="btn" @click="isShowTasks = !isShowTasks">
                                <i class="fa fa-calendar" aria-hidden="true"></i>
                            </button>

                        </div>
                        <div class="col-9 text-light">
                            <span v-if="!isEdit">
                                {{project.name}}
                            </span>
                            <span class="form-group" v-if="isEdit">
                           <input
                               type="text"
                               class="form-control"
                               id="name"
                               placeholder="Enter name "
                               v-model.trim="$v.projectName.$model"
                               :class="{
                                        'is-invalid':$v.projectName.$error,
                                        'is-valid':!$v.projectName.$invalid
                                    }"
                           >
                                <!--                                <span class="valid-feedback badge-dark text-success">name is valid</span>-->
                                <span class="invalid-feedback badge-dark text-danger">
                                    <span v-if="!$v.projectName.required">name is required</span>
                                    <span v-if="!$v.projectName.minLength">
                    name is min length
                    {{$v.projectName.$params.minLength.min}}
                </span>
                                    <span v-if="!$v.projectName.maxLength">
                    name must have at
                    {{$v.projectName.$params.maxLength.max}}
                </span>
                                </span>
                            </span>

                        </div>
                        <div class="col-2 controls row justify-content-around">

                            <button class="edit btn  btn-sm"
                                    v-if="!isEdit"
                                    @click.stop="onEdit"
                            >
                                <i class="fa fa-pencil" aria-hidden="true"></i>
                            </button>
                            <button class="del btn  btn-sm"
                                    v-if="!isEdit"
                                    data-toggle="modal" :data-target="'#deleteProjectModal'+project.id"
                            >
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>


                            <button class="ok btn  btn-sm"
                                    v-if="isEdit"
                                    @click.stop="onSave"
                            >
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </button>

                            <button class="cancel btn  btn-sm"
                                    v-if="isEdit"
                                    @click.stop="onCancel"
                            >
                                <i class="fa fa-ban" aria-hidden="true"></i>
                            </button>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <Tasks v-if="isShowTasks"  :project="project"/>


        <!-- ModalDelete -->
        <div class="modal fade" :id="'deleteProjectModal'+project.id" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{project.created_at.toString()}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        DELETE <strong>{{project.name}}</strong> ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="onDeleteProject"
                        >
                            delete
                        </button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
    import {required, maxLength, minLength} from 'vuelidate/lib/validators';
    import Tasks from "./Tasks";

    export default {
        name: "Project",
        components: {Tasks},
        data: () => ({
            isEdit: false,
            projectName: '',
            isShowTasks: true
        }),
        validations: {
            projectName: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(255),
            }
        },
        props: {
            project: {
                id: Number,
                name: String,
                created_at: Date
            }
        },
        methods: {
            onUpdate(project) {
                axios.patch('/projects/' + project.id, project)
                    .then(res => {
                        if (res.data) {
                            console.log(res.data);
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
            },
            onSave() {
                this.$v.$touch();
                if (!this.$v.$invalid) {
                    if (this.projectName === this.project.name) {
                        this.onCancel();
                    } else {
                        this.onUpdate({...this.project, name: this.projectName});
                    }

                }
            },
            onCancel() {
                this.isEdit = false;
                this.projectName = '';
            },
            onEdit() {
                this.isEdit = true;
                this.projectName = this.project.name;
            },
            modalToggle() {
                $('#deleteProjectModal' + this.project.id).modal('toggle')
            },
            onDeleteProject() {
                axios.delete('/projects/' + this.project.id)
                    .then(res => {
                        console.log(res);
                        if (res.data) {
                            this.$emit('delete', res.data);
                            this.modalToggle();
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
    .projects {
        user-select: none;
        cursor: pointer;

        .title {
            background-color: #1abc9c;

            &:hover .controls {
                visibility: visible;
            }

            .controls {
                visibility: hidden;
            }
        }
    }


</style>
