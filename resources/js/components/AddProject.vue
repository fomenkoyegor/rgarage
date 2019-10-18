<template>
    <div class="container">
        <div class="row justify-content-center">
            <button class="btn btn-primary"
                    data-toggle="modal" data-target="#createModal"
            >
                <span>
                    <i class="fa fa-plus" aria-hidden="true"></i>
                </span>
                <span>
                     add Todo List
            </span>
            </button>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">create project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div>

                            <div class="form-group">
                                <label for="name">
                                    name
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
                        <input :disabled="$v.$invalid" @click="save" type="submit" class="btn btn-primary">
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {required, maxLength, minLength} from 'vuelidate/lib/validators';

    export default {
        name: "AddProject",
        data: () => ({
            name: ''
        }),
        validations: {
            name: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(255),
            }
        },
        mounted() {
            // this.modalToggle()
        },
        methods: {
            test(){
                console.log('asdasdasd')
            },
            modalToggle() {
                $('#createModal').modal('toggle')
            },
            save() {
                this.$v.$touch();
                if (!this.$v.$invalid) {
                    this.onCreate(this.name);
                }
            },
            onCreate(name) {
                axios.post('/projects', {name})
                    .then(res => {

                        if (res.data) {
                            console.log(res.data);
                            this.$emit('create',res.data);
                            this.name = '';
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

<style scoped>

</style>
