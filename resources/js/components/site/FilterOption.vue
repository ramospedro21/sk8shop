<template>
    <div>
        <input v-model="ids" @change="filter()" class="form-check-input" type="checkbox" :value="id"/>
    </div>
</template>

<script>
export default {

    props: ['id'],

    data(){
        return{
            selected_option: [],
            ids: [],
        }
    },

    methods: {
        filter: function(){

            if(this.ids.length > 0){

                if (this.ids.indexOf(Number.parseInt(this.id)) > -1) {

                    var self = this;
                    this.ids.forEach(function(id_array, index){
                        if(id_array == self.id){
                            self.ids.splice(index, 1);
                        }
                    });


                }

                var url = "?ids=" + JSON.stringify(this.ids);
            }else{
                var url = location.origin + location.pathname;
            }

            window.location.href = url
        },

        timeout: function (fn, ms) {
            setTimeout(() =>{ fn() } , ms)
        }
    },

    mounted: function() {

    }

}
</script>
