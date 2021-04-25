<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h2>{{scrip.name}}</h2><br>
                        <b>Sector:</b> {{scrip.industry_sector.sector.name}} <b style="margin-left:50px">Industry:</b> {{scrip.industry_sector.industry.name}}<br>
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
                scrip: ''
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
                    console.log(response.data);
                    this.scrip = response.data;
                });
            }
        }
    }
</script>
