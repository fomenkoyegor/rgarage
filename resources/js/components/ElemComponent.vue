<template>
    <div>
       {{task.name}} {{index}}
    </div>
</template>

<script>
    export default {
        props:{
            task:{
                id:Number,
                name: String,
                priority: Number,
                status: Boolean,
            },
            index:Number
        }
    }
</script>

<style scoped>

</style>
