<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h2>{{scrip.name}}</h2><br>
                        <b>Sector:</b> {{sector}} 
                        <b style="margin-left:50px">Industry:</b> {{industry}}<br>
                    </div>
                    <div class="card-body">
                       Details
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
                id: '',
                scrip: '',
                sector: '',
                industry: ''
            }
        },
        mounted() {
            this.id = this.$route.query.id        
            this.getScrip()   
        },
        methods: {
            getScrip() {
                this.axios
                .get('http://localhost:8000/api/scrip/'+ this.id)
                .then(response => {
                    this.scrip = response.data;
                    this.sector = response.data.industry_sector.sector.name
                    this.industry = response.data.industry_sector.industry.name
                });
            }
        }
    }
</script>
