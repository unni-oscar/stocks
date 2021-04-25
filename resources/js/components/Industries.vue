<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Setting Industry data ({{industries.length}})</div>
                    <button @click="setIndustries()">Generate Industries</button>
                    <div class="card-body">
                         <table class="table">
                            <thead>
                            <tr>
                                <th>Industry Name</th>                                
                                <!-- <th>Sector Name</th>   -->
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="industry in industries" :key="industry.id">
                                <td>{{ industry.name }}</td>
                                 <!-- <td>{{ industry.sector.name }}</td> -->
                               
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
                industries: []
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.getIndustries();
        },
        methods: {
            setIndustries() {
                this.axios
                .get('http://localhost:8000/api/setIndustries')
                .then(response => {
                    // console.log(response.data);
                    this.getIndustries()
                });
            },
            getIndustries() {
                this.axios
                .get('http://localhost:8000/api/industries')
                .then(response => {
                    this.industries = response.data;
                });
            }
        }

    }
</script>
