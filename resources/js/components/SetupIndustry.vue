<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Setting Industry data</div>

                    <div class="card-body">
                        Loading Industry for stocks .... 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        },
        created() {
            this.axios
                .get('http://localhost:8000/api/setupIndustry')
                .then(response => {
                    console.log(response.data);
                    // this.scrips = response.data;
                });
        },

    }
</script>
