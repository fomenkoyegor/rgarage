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
                        <h5 class="modal-title text-uppercase" id="exampleModalLabel">create new project</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-primary"
                                @click="save"
                        >Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "AddProject",
        mounted(){
            this.modalToggle();
        },
        methods:{
            modalToggle(){
                $('#createModal').modal('toggle')
            },
            save(){

            }
        }
    }
</script>

<style scoped>

</style>
