<template>
    <div>
        <h2 class="text-center">Scrip List </h2>
        <div class="col-12">
        <div class="col-3"> Industry
            <select name="industry" @change="getScrips($event)" class="form-control" v-model="industry">       
            <option value="-1">All</option>
            <option v-for="industry in industries" :key="industry.id" :value='industry.id'>{{industry.name}}</option>
           
            </select>
        </div>
        <div class="col-3"> Group
            <select name="group" @change="getScrips($event)" class="form-control" v-model="group">       
            <option value="-1">All</option>
            <option v-for="grp in groups" :key="grp.group" :value='grp.group'>{{grp.group}}</option>
           
            </select>
        </div>
        </div>
        <table class="table">
            <thead>
            <tr>
                <th>Symbol</th>
                <th>Name</th>               
                <th>ISIN No</th>
                <th>Group</th>
                <th>Industry</th>
                
                <!-- <th>Actions</th> -->
            </tr>
            </thead>
            <tbody>
            <tr v-for="scrip in scrips" :key="scrip.id">
                <td>{{ scrip.symbol }}</td>
                <td>{{ scrip.name }}</td>
              
                <td>{{ scrip.isin_no }}</td>
                 <td>{{ scrip.group }}</td>
                <td>{{ scrip.industry.name }}</td>
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
</template>
 
<script>
    export default {
        data() {
            return {
                scrips: [],
                groups: [],
                industry: '-1',
                group: '-1',
                industries: []
            }
        },
        created() {
            this.getIndustry();
            this.getGroups();
            this.getScrips();
        },
        methods: {
            getGroups(){
                this.axios
                .get('http://localhost:8000/api/groups')
                .then(response => {
                    this.groups = response.data;
                });
            },
            getIndustry() {
                this.axios
                .get('http://localhost:8000/api/industries')
                .then(response => {
                    console.log(response.data);
                    this.industries = response.data;
                });
            },
            getScrips:function(){
                 this.axios
                .post('http://localhost:8000/api/scrips/', {
                    'industry' : this.industry,
                    'group' : this.group
                })
                .then(response => {
                    console.log(response.data);
                    this.scrips = response.data;
                });
            }   
            // deleteProduct(id) { 
            //     this.axios
            //         .delete(`http://localhost:8000/api/products/${id}`)
            //         .then(response => {
            //             let i = this.products.map(data => data.id).indexOf(id);
            //             this.products.splice(i, 1)
            //         });
            // }
        }
    }
</script>