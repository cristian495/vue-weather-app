<template>
    <div class="text-white mb-8">

        <div class="text-balck mb-8">
            <div v-if="errorStr" clas>
            Sorry, but the following error
            occurred: {{errorStr}}
            </div>
        </div>
         


        <div class="places-input text-gray-800">
            <input  id="address"  class="form-control" placeholder="ej. miraflores, Lima, Perú" />
            <!--<p>Selected: <strong id="address-value">none</strong></p>-->

   
        </div>
        <div class="weather-container font-sans w-128 
                    max-w-lg rounded-lg overflow-hidden bg-blue-900 
                    shadow-lg mt-4">
            <div class="current-weather flex items-center
                         justify-between px-6 py-8">
                <div class="flex items-center">
                    <div>
                        <div class="text-6xl font-semibold">{{currentTemperature.actual}}C°</div>    
                        <div>Humedad {{currentTemperature.humidity }}%</div>
                    </div>
                    <div class="mx-5">
                        <div class="font-semibold">{{currentTemperature.summary}}</div>
                        <div > {{location.name.district}}, {{location.name.administrative}}, {{location.name.country}}</div>
                    </div>
                </div>
                <div>
                    <canvas ref="iconCurrent" id="iconCurrent" width="96" height="96">
                    </canvas>
                </div>
            </div> <!-- end current-weather -->
            <div class="future-weather text-sm 
                        bg-blue-800 px-6 py-8
                        overlflow-hidden">

                <div 
                    v-for="(day, index) in dailyFiveDays" 
                    :key="day.time" 
                    class="flex items-center"
                    :class="{'mt-8' : index > 0}"
                >
                    <div class="w-1/6 text-gray-200">{{toDayOfWeek(day.time)}}</div>
                    <div class="w-4/6 flex items-center">
                        <div>
                            <canvas :id="`icon${index+1}`" :data-icon="toKebabCase(day.icon)"
                             width="24" height="24">
                            </canvas>
                        </div>
                        <div class="ml-3">{{day.summary}}</div>
                    </div>
                    <div class="w-1/6 text-right">
                        <div>{{ Math.round(day.temperatureHigh) }} °C</div>
                        <div>{{ Math.round(day.temperatureLow)}} °C</div>
                    </div>
                </div>
                

               
            </div><!-- end future-weather-->
        </div>   
    </div>
</template>

<script>

    export default {
        mounted() {
            this.checkGeolocation();
            this.algoliaInit();

        },
        watch:{            
            location : {
                handler(newValue, oldValue){
                    this.fetchData();
                },deep:true
            }

        },
        computed:{
            dailyFiveDays(){
                return this.daily.filter((day,index) => index < 5);
            },

        },
        data(){
            return {
                currentTemperature:{
                    actual: '',
                    feels: '',
                    summary: '',
                    icon: '',
                    humidity: '',
                },
                daily: [],
                location:{
                    name:{country:'',administrative:'',district:''},
                    coords:{
                        latitude:null,
                        longitude:null,
                    }
                },
                errorStr:null,
                availibleApiGeolocation:false,
            }
        },


        methods:{

            algoliaInit(){

                let placesAutocomplete = places({
                appId: 'pl15HH9S78RC',
                apiKey: 'f0d29197423092b9953f360f408e6a12',
                container: document.querySelector('#address')
                }).configure({
                    type:'city',
                    hitsPerPage: 1,
                    aroundLatLngViaIP: true
                });               
                
                
                placesAutocomplete.search().then((suggestions) => {
                    if (!suggestions[0]) {
                        return;
                    }

                    let nameLocation = {
                        district :  suggestions[0].name,
                        administrative : suggestions[0].administrative ? suggestions[0].administrative  : '',
                        country : suggestions[0].country,
                    } 
                        
                    
                    this.setLocation(suggestions[0].latlng.lat,suggestions[0].latlng.lng,nameLocation);

                });             


                placesAutocomplete.on('change', (e) => {

                    let nameLocation = {
                        district :   e.suggestion.name,
                        administrative : e.suggestion.administrative ? e.suggestion.administrative : e.suggestion.hit.administrative[0],
                        country : e.suggestion.country,
                    } 

                    this.setLocation(e.suggestion.latlng.lat,e.suggestion.latlng.lng,nameLocation);


                });
            },
        

//            setLocation(formattedCity)
            setLocation(lat=null,lng=null,nameLocation)
            {            

                if(this.availibleApiGeolocation)
                {     
                    console.log('se usó el api de geolocalizacion para obtener las coordenadas');

                    /*
                    this.gettingLocation = true;

                    navigator.geolocation.getCurrentPosition((pos)  => {

                        this.gettingLocation = false;

                        this.location.name = formattedCity;

                        this.location.coords.latitude = pos.coords.latitude;

                        this.location.coords.longitude = pos.coords.longitude;

                        console.log('se usó el api de geolocalizacion para obtener las coordenadas');
                    }, err => {
                        this.gettingLocation = false;
                        this.errorStr = err.message;
                    }); */
                }
                else
                {                    
                    console.log('se usó la ip publica para obtener las coordenadas');
                }

                     this.location.coords.latitude = lat;

                    this.location.coords.longitude = lng;

                    this.location.name = nameLocation;
            },

            fetchData(){

                var skycons = new Skycons({'color':'white'});

                
                //fetch(`/api/weather?lat=${this.location.coords.latitude}&lng=${this.location.coords.longitude}`)

                fetch(`/api/weather/${this.location.coords.latitude}/${this.location.coords.longitude}`)
                    .then(response=>response.json())
                    .then(data=>{
                        this.currentTemperature.actual = Math.round(data.currently.temperature);

                        this.currentTemperature.feels = Math.round(data.currently.apparentTemperature);

                        this.currentTemperature.humidity = data.currently.humidity*100;

                        this.currentTemperature.summary = data.currently.summary;

                        this.currentTemperature.icon = this.toKebabCase(data.currently.icon);

                        this.daily = data.daily.data;

                        skycons.add('iconCurrent', this.currentTemperature.icon);
                        skycons.play();

                        this.$nextTick(()=>{
                            skycons.add('icon1', document.getElementById('icon1').getAttribute('data-icon'));
                            skycons.add('icon2', document.getElementById('icon2').getAttribute('data-icon'));
                            skycons.add('icon3', document.getElementById('icon3').getAttribute('data-icon'));
                            skycons.add('icon4', document.getElementById('icon4').getAttribute('data-icon'));
                            skycons.add('icon5', document.getElementById('icon5').getAttribute('data-icon'));
                            skycons.play();                       

                        })
                    })
               
            },
            toKebabCase(stringToConvert){
                return stringToConvert.split(' ').join('-');
            },
            toDayOfWeek(timestamp){
                const newDate =  new Date(timestamp*1000);
                const days = ['DOM', 'LUN', 'MAR', 'MIE','JUE', 'VIE','SAB'];

                return days[newDate.getDay()];

            },
            checkGeolocation()
            {
                if(!"geolocation" in navigator)
                {
                    this.errorStr = 'Geolocalizacion no disponible';
                    this.availibleApiGeolocation = false;
                    return;
                }

                navigator.geolocation.getCurrentPosition((pos)  => {
                    this.availibleApiGeolocation = true;
                }, err => {
                    this.availibleApiGeolocation = false;
                });   
            }

        },
        
    }
</script>

