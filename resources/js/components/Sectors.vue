<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Setting Sector data</div>
                    <button @click="setSectors()">Generate Sectos</button>
                    <div class="card-body">
                         <table class="table">
                            <thead>
                            <tr>
                                <th>Sector Name</th>                                
                                
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="sector in sectors" :key="sector.id">
                                <td>{{ sector.name }}</td>
                               
                                <td>
                                    <div class="btn-group" role="group">
                                        <!-- <router-link :to="{name: 'edit', params: { id: product.id }}" class="btn btn-success">Edit</router-link> -->
                                        <!-- <button class="btn btn-danger" @click="deleteProduct(product.id)">Delete</button> -->
                                    </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {               
                sectors: []
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.getSectors();
        },
        methods: {
            setSectors() {
                this.axios
                .get('http://localhost:8000/api/setSectors')
                .then(response => {
                    this.getSectors()
                });
            },
            getSectors() {
                this.axios
                .get('http://localhost:8000/api/sectors')
                .then(response => {
                    this.sectors = response.data;
                });
            }
        }

    }
</script>
