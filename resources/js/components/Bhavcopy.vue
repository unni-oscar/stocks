<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Getting Bhavcopy</div>

                    <div class="card-body">
                        Loading master table for stocks .... 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Bhavcopy Component mounted.')
        },
        created() {
            this.axios
                .get('http://localhost:8000/api/getbhavcopy/23-04-2021')
                .then(response => {
                    console.log(response.data);
                    // this.scrips = response.data;
                });
        },

    }
</script>
