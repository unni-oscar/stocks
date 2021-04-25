<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Setting Industry data {{symbols.length}}</div>

                    <div class="card-body">
                         <div v-for="symbol in symbols" :key="symbol"> 
                            
                             <a v-bind:href="http+symbol" target="_blank" rel="noopener noreferrer"> {{symbol}}</a>.
                        </div>
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
                symbols: [],
                http: 'https://www.nseindia.com/api/quote-equity?symbol='
            }
        },
        mounted() {
            console.log('Component mounted.')
        },
        methods: {
            getLink(l){
                console.log(l)
            }   
        },

        created() {
            this.axios
                .get('http://localhost:8000/api/setupIndustry')
                .then(response => {
                    console.log(response.data);
                    let symbols = response.data.filter((c, index) => {
                        return response.data.indexOf(c) === index;
                    });
                    this.symbols = response.data;
                });
        },

    }
</script>
