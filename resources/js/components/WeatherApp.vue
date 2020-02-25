<template>
    <div class="text-white mb-8">
        <div class="places-input text-gray-800">
            <input type="search" id="address" class="form-control" placeholder="Where are we going?" />
            <p>Selected: <strong id="address-value">none</strong></p>

        </div>
        <div class="weather-container font-sans w-128 
                    max-w-lg rounded-lg overflow-hidden bg-blue-900 
                    shadow-lg mt-4">
            <div class="current-weather flex items-center
                         justify-between px-6 py-8">
                <div class="flex items-center">
                    <div>
                        <div class="text-6xl font-semibold">{{currentTemperature.actual}}C°</div>    
                        <div>Feels like{{ currentTemperature.feels}}°C</div>
                    </div>
                    <div class="mx-5">
                        <div class="font-semibold">{{currentTemperature.summary}}</div>
                        <div >{{location.name}}</div>
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
                        <div>-{{ Math.round(day.temperatureLow)}} °C</div>
                    </div>
                </div>

               
            </div><!-- end future-weather-->
        </div>   
    </div>
</template>

<script>/*
export default {
  mounted() {
    this.fetchData()
    
    var placesAutocomplete = places({
        appId: 'pl15HH9S78RC',
        apiKey: 'f0d29197423092b9953f360f408e6a12',
        container: document.querySelector('#address')
      });
    var $address = document.querySelector('#address-value')
      placesAutocomplete.on('change', (e) => {
        $address.textContent = e.suggestion.value
        this.location.name = `${e.suggestion.name}, ${e.suggestion.country}`
        this.location.lat = e.suggestion.latlng.lat
        this.location.lng = e.suggestion.latlng.lng
      });
      placesAutocomplete.on('clear', function () {
        $address.textContent = 'none';
      });
  },
  data() {
    return {
      currentTemperature: {
        actual: '',
        feels: '',
        summary: '',
        icon: '',
      },
      daily: [],
      location: {
        name: 'Toronto, Canada',
        lat: 43.6532,
        lng: -79.38323,
      }
    }
  },
  methods: {
    fetchData() {
      var skycons = new Skycons({'color': 'white'});
      fetch(`/api/weather?lat=${this.location.lat}&lng=${this.location.lng}`)
        .then(response => response.json())
        .then(data => {
          this.currentTemperature.actual = Math.round(data.currently.temperature)
          this.currentTemperature.feels = Math.round(data.currently.apparentTemperature)
          this.currentTemperature.summary = data.currently.summary
          this.currentTemperature.icon = this.toKebabCase(data.currently.icon)
          this.daily = data.daily.data
          skycons.add('iconCurrent', this.currentTemperature.icon)
          skycons.play()
          this.$nextTick(() => {
            skycons.add('icon1', document.getElementById('icon1').getAttribute('data-icon'))
            skycons.add('icon2', document.getElementById('icon2').getAttribute('data-icon'))
            skycons.add('icon3', document.getElementById('icon3').getAttribute('data-icon'))
            skycons.add('icon4', document.getElementById('icon4').getAttribute('data-icon'))
            skycons.add('icon5', document.getElementById('icon5').getAttribute('data-icon'))
            skycons.play()
          })
        })
    },
    toKebabCase(stringToConvert) {
      return stringToConvert.split(' ').join('-')
    },
    toDayOfWeek(timestamp) {
      const newDate = new Date(timestamp*1000)
      const days = ['SUN','MON','TUE','WED','THU','FRI','SAT']
      return days[newDate.getDay()]
    }
  },
  watch: {
    locationc(){
        this.fetchData()
        alert('hoasd');
      }
    },
  computed: {
    dailyFiveDays() {
      return this.daily.filter((day, index) => index < 5 )
    },
    locationc(){
        return this.location.name;
    }
  }
}*/


    export default {
        mounted() {
            this.fetchData()


            var placesAutocomplete = places({
                appId: 'pl15HH9S78RC',
                apiKey: 'f0d29197423092b9953f360f408e6a12',
                container: document.querySelector('#address')
            }).configure({
                type:'city',
                aroundLatLngViaIP: false,
            });

            var $address = document.querySelector('#address-value')

            placesAutocomplete.on('change', (e) => {
                $address.textContent = e.suggestion.value

                this.location.name = `${e.suggestion.name},${e.suggestion.country}`;
                this.location.lat = e.suggestion.latlng.lat;
                this.location.lng = e.suggestion.latlng.lng;
            });

            placesAutocomplete.on('clear', function() {
                $address.textContent = 'none';
            });

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
            }
        },
        data(){
            return {
                currentTemperature:{
                    actual: '',
                    feels: '',
                    summary: '',
                    icon: '',
                },
                daily: [],
                location:{
                    name:'Toronto, Canada',
                    lat: 37.8267,
                    lng: -122.4233,
                }
            }
        },
        methods:{
            fetchData(){

                var skycons = new Skycons({'color':'white'});
                

                fetch(`/api/weather?lat=${this.location.lat}&lng=${this.location.lng}`)
                    .then(response=>response.json())
                    .then(data=>{
                        this.currentTemperature.actual = Math.round(data.currently.temperature)
                        this.currentTemperature.feels = Math.round(data.currently.apparentTemperature)
                        this.currentTemperature.summary = data.currently.summary
                        this.currentTemperature.icon = this.toKebabCase(data.currently.icon)

                        this.daily = data.daily.data;

                        skycons.add('iconCurrent', this.currentTemperature.icon)
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
        }
    }
</script>
