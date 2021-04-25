<template>
    <div class="container"> 
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse">
                <div class="navbar-nav">
                     <router-link to="/setMasterFile" class="nav-item nav-link">Create Master</router-link>
                     <router-link to="/sectors" class="nav-item nav-link">Sectors</router-link>
                     <router-link to="/industries" class="nav-item nav-link">Industries</router-link>
                    <router-link to="/" class="nav-item nav-link">Scrip List</router-link>
                    <router-link to="/setup" class="nav-item nav-link">Setup</router-link>
                    <router-link to="/setupIndustry" class="nav-item nav-link">Setup Industry</router-link>
                    <router-link to="/getbhavcopy" class="nav-item nav-link">Get Bhavcopy</router-link>
                    <!-- <router-link to="/create" class="nav-item nav-link">Create Product</router-link> -->
                </div>
            </div>
        </nav>

        <router-view> </router-view>
    </div>
</template>
 
<script>
    export default {}
</script>