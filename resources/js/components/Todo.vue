<template>
    <div>
        <div class="row">
            <div class="col-md-12">
                <form class="form-inline" @submit.prevent="addNew">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="inputPassword2" class="sr-only">Todo</label>
                        <input type="text" class="form-control" id="content"
                               placeholder="Todo" v-model="todo" required>
                        <input type="hidden" class="form-control" id="content-id"
                               placeholder="ID" v-model="id" required>
                    </div>
                   <button type="submit" class="btn btn-success mb-2">Add Todo</button>
                </form>
            </div>
        </div>

         <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="todo in todos" :key="todo.id">
                <td>{{ todo.id }}</td>
                <td>{{ todo.content }}</td>
                <td>{{ (todo.completion_time !=null ? "Completed" : "Pending" ) }}</td>
                <td>
                    <div class="btn-group" role="group">
                        <button class="btn btn-success" @click="complete(todo)">{{ (todo.completion_time !=null ? "Mark As Pending" : "Mark As Completed" ) }} </button>
                        <button class="btn btn-primary" @click="editTodo(todo)">Edit</button>
                        <button class="btn btn-danger" @click="deleteTodo(todo)">Delete</button>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        name: "Todo.vue",
        data: function () {
            return {
                todo: '',
                id: null,
            }
        },
        methods: {
            complete(todo) {
                this.$store.dispatch('completeTodo', todo);
            },
            addNew() {
                if(this.id!=null)
                {
                    this.$store.dispatch('editTodo', {
                        content: this.todo,
                        id: this.id,
                    }).then(()=>{
                        this.todo = '';
                        this.id = null;
                    });
                }
                else
                {
                    this.$store.dispatch('newTodo', {
                        content: this.todo,
                    }).then(()=>{
                        this.todo = '';
                    });
                }
            },
            editTodo(todo) {
                this.todo = todo.content;
                this.id = todo.id;
            },
            deleteTodo(todo) {
                this.id = todo.id;
                this.$store.dispatch('deleteTodo', {
                    id: this.id,
                }).then(()=>{
                    this.id = null;
                });
            }
        },
        computed: {
            todos() {
                return this.$store.getters.todos;
            }
        },
        mounted() {
            this.$store.getters.profile.id
                ? this.$store.dispatch('todos')
                : this.$store.dispatch('profile').then(
                    () => this.$store.dispatch('todos'));
        }
    }
</script>

<style scoped>
    .line-througt {
        text-decoration: line-through;
    }
</style>
