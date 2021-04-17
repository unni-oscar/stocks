<template>
    <div>
        <h2 class="text-center">Scrip List</h2>
 
        <table class="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Symbol</th>
                <th>Name</th>               
                <th>ISIN No</th>
                <th>Listing Date</th>
                <!-- <th>Actions</th> -->
            </tr>
            </thead>
            <tbody>
            <tr v-for="scrip in scrips" :key="scrip.id">
                <td>{{ scrip.id }}</td>
                <td>{{ scrip.symbol }}</td>
                <td>{{ scrip.name }}</td>
               
                <td>{{ scrip.isin_no }}</td>
                <td>{{ scrip.listing_date }}</td>
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
                scrips: []
            }
        },
        created() {
            this.axios
                .get('http://localhost:8000/api/scrips/')
                .then(response => {
                    console.log(response.data);
                    this.scrips = response.data;
                });
        },
        methods: {
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