<template>
    <div>
        <h2 class="text-center">Scrip List </h2>
        <div class="col-12">
            <div class="col-3"> Sector
                <select name="sector" @change="getIndustriesForSector($event)" class="form-control" v-model="sector">       
                <option value="-1">All</option>
                <option v-for="sector in sectors" :key="sector.id" :value='sector.id'>{{sector.name}}</option>
            
                </select>
            </div>
            <div class="col-3"> Industry
                <select name="industry" @change="getScrips($event)" class="form-control" v-model="industry">       
                <option value="-1">All</option>
                <option v-for="industry in industries" :key="industry.id" :value='industry.id'>{{industry.name}}</option>
            
                </select>
            </div>
       
        </div>
        <div>Total count {{scrips.length }}</div>
        <table class="table">
            <thead>
            <tr>
                <th>Symbol</th>
                <th>Name</th>               
                <th>ISIN No</th>
                <th>Face Value</th>
                <th>Sector</th>
                <th>Industry</th>
                
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="scrip in scrips" :key="scrip.id">
                <td>{{ scrip.symbol }}</td>
                <td>{{ scrip.name }}</td>
              
                <td>{{ scrip.isin_no }}</td>
                 <td>{{ scrip.faceValue }}</td>
                  <td>{{ scrip.industry_sector.sector.name }}</td>
                <td>{{ scrip.industry_sector.industry.name }}</td>
                <td>
                    <div class="btn-group" role="group">                        
                        <router-link target="_blank" class="btn btn-success" :to="{ path: 'scrip', query: { id: scrip.id }}">View</router-link>                        
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
 
<script>
    export default {
        data: function () {
            return {
                scrips: [],
                industries: [],
                sectors: [],
                industry: '-1',
                sector: '-1',
                
            }
        },
        created() {
            this.getIndustry();
            this.getSectors();
            this.getScrips();
        },
        methods: {
            getIndustriesForSector($e){
                if(this.sector != -1) {
                    this.axios
                    .get('http://localhost:8000/api/industriesForSector/'+this.sector)
                    .then(response => {
                        this.industries = response.data;
                        this.industry = -1
                        this.getScrips()
                    });

                } else {
                    this.getScrips()
                    this.getIndustry()
                }
                
            },
            getSectors(){
                this.axios
                .get('http://localhost:8000/api/sectors')
                .then(response => {
                    this.sectors = response.data;
                });
            },
            getIndustry() {
                this.axios
                .get('http://localhost:8000/api/industries')
                .then(response => {
                    this.industries = response.data;
                    this.industry = -1
                });
            },
            getScrips:function(){
                 this.axios
                .post('http://localhost:8000/api/scrips/', {
                    'industry' : this.industry,
                     'sector' : this.sector
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